<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationCreated extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Reservation $reservation)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $r = $this->reservation;

        return (new MailMessage)
            ->subject('Konfirmasi Reservasi Studio')
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line('Reservasi studio kamu berhasil dibuat.')
            ->line('Studio: ' . $r->studio->name)
            ->line('Waktu: ' . $r->start_time->format('d-m-Y H:i') . ' s/d ' . $r->end_time->format('H:i'))
            ->line('Status: ' . $r->status)
            ->line('Kode Check-in: ' . $r->checkin_code)
            ->line('Terima kasih telah menggunakan layanan StudioRent.');
    }
}
