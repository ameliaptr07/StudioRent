<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\Studio;
use App\Models\User;
use App\Services\ReservationService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    protected ReservationService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(ReservationService::class);
    }

    public function test_user_can_create_reservation_when_slot_free(): void
    {
        $user   = User::factory()->create();
        $studio = Studio::factory()->create([
            'price_per_hour' => 100000,
        ]);

        $start = now()->addDay()->setTime(10, 0);
        $end   = (clone $start)->addMinutes(90);

        $data = [
            'studio_id'  => $studio->id,
            'start_time' => $start->toDateTimeString(),
            'end_time'   => $end->toDateTimeString(),
            // addons kosong
        ];

        $reservation = $this->service->createReservation($data, $user);

        $this->assertDatabaseHas('reservations', [
            'id'        => $reservation->id,
            'user_id'   => $user->id,
            'studio_id' => $studio->id,
            'status'    => 'confirmed',
        ]);
    }

    public function test_cannot_create_reservation_if_duration_too_short(): void
    {
        $this->expectException(ValidationException::class);

        $user   = User::factory()->create();
        $studio = Studio::factory()->create();

        $start = now()->addDay()->setTime(10, 0);
        $end   = (clone $start)->addMinutes(30); // 30 menit, kurang dari 90

        $data = [
            'studio_id'  => $studio->id,
            'start_time' => $start->toDateTimeString(),
            'end_time'   => $end->toDateTimeString(),
        ];

        $this->service->createReservation($data, $user);
    }

    public function test_cannot_create_reservation_if_conflict(): void
    {
        $this->expectException(ValidationException::class);

        $user   = User::factory()->create();
        $studio = Studio::factory()->create();

        $start1 = now()->addDay()->setTime(10, 0);
        $end1   = (clone $start1)->addMinutes(90);

        // Reservasi pertama (sudah ada, status confirmed)
        Reservation::factory()->create([
            'user_id'    => $user->id,
            'studio_id'  => $studio->id,
            'start_time' => $start1,
            'end_time'   => $end1,
            'status'     => 'confirmed',
        ]);

        // Reservasi kedua mencoba masuk di slot yang bentrok
        $start2 = now()->addDay()->setTime(11, 0);
        $end2   = (clone $start2)->addMinutes(90);

        $data = [
            'studio_id'  => $studio->id,
            'start_time' => $start2->toDateTimeString(),
            'end_time'   => $end2->toDateTimeString(),
        ];

        $this->service->createReservation($data, $user);
    }

    public function test_owner_can_cancel_reservation(): void
    {
        $user   = User::factory()->create();
        $studio = Studio::factory()->create();

        $reservation = Reservation::factory()->create([
            'user_id'    => $user->id,
            'studio_id'  => $studio->id,
            'start_time' => now()->addDay()->setTime(10, 0),
            'end_time'   => now()->addDay()->setTime(11, 30),
            'status'     => 'confirmed',
        ]);

        $updated = $this->service->cancelReservation($reservation);

        $this->assertEquals('cancelled', $updated->status);
        $this->assertEquals('cancelled', $reservation->fresh()->status);
    }

    public function test_weekly_quota_limits_reservations(): void
    {
        $this->expectException(ValidationException::class);

        $user   = User::factory()->create();
        $studio = Studio::factory()->create();

        // Buat 3 reservasi dalam minggu yang sama (quota penuh)
        for ($i = 0; $i < 3; $i++) {
            $start = now()->startOfWeek()->addDays($i)->setTime(10, 0);
            $end   = (clone $start)->addMinutes(90);

            Reservation::factory()->create([
                'user_id'    => $user->id,
                'studio_id'  => $studio->id,
                'start_time' => $start,
                'end_time'   => $end,
                'status'     => 'confirmed',
            ]);
        }

        // Percobaan ke-4 → harus gagal karena quota minggu sudah penuh
        $start4 = now()->startOfWeek()->addDays(4)->setTime(10, 0);
        $end4   = (clone $start4)->addMinutes(90);

        $data = [
            'studio_id'  => $studio->id,
            'start_time' => $start4->toDateTimeString(),
            'end_time'   => $end4->toDateTimeString(),
        ];

        $this->service->createReservation($data, $user);
    }

    public function test_checkin_succeeds_with_valid_code(): void
    {
        $user   = User::factory()->create();
        $studio = Studio::factory()->create();

        $start = now()->addDay()->setTime(10, 0);
        $end   = (clone $start)->addMinutes(90);

        // Bekukan waktu ke jam mulai, supaya masuk window check-in
        Carbon::setTestNow($start);

        $reservation = Reservation::factory()->create([
            'user_id'      => $user->id,
            'studio_id'    => $studio->id,
            'start_time'   => $start,
            'end_time'     => $end,
            'status'       => 'confirmed',
            'checkin_code' => 'CHK-TEST',
        ]);

        $updated = $this->service->checkInReservation($reservation, 'CHK-TEST');

        $this->assertEquals('completed', $updated->status);
        $this->assertNotNull($updated->checked_in_at);

        Carbon::setTestNow(); // reset waktu test
    }

    public function test_auto_cancel_no_show_changes_status(): void
    {
        $reservation = Reservation::factory()->create([
            'status'     => 'confirmed',
            'start_time' => now()->subMinutes(20),
            'end_time'   => now()->subMinutes(5),
        ]);

        $affected = $this->service->autoCancelNoShow();

        $this->assertEquals(1, $affected);
        $this->assertEquals('cancelled', $reservation->fresh()->status);
    }
}
