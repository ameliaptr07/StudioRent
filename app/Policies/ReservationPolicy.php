<?php

namespace App\Policies;

use App\Models\Reservation;
use App\Models\User;

class ReservationPolicy
{
    /**
     * Cek apakah user adalah admin/manager.
     * Mendukung dua skenario:
     * - Pakai Spatie (hasRole)
     * - Pakai relasi role (role->name)
     */
    protected function isAdminOrManager(User $user): bool
    {
        // Kalau pakai Spatie Permission
        if (method_exists($user, 'hasRole')) {
            return $user->hasRole('admin') || $user->hasRole('manager');
        }

        // Kalau pakai relasi role biasa: users.role_id -> roles.name
        if (property_exists($user, 'role') && $user->role) {
            return in_array($user->role->name, ['Admin', 'Manager']);
        }

        // Fallback: pakai role_id (misal 1 = admin, 2 = manager)
        if (property_exists($user, 'role_id')) {
            return in_array($user->role_id, [1, 2]);
        }

        return false;
    }

    /**
     * Menentukan apakah user boleh meng-update (edit) reservasi.
     */
    public function update(User $user, Reservation $reservation): bool
    {
        if ($this->isAdminOrManager($user)) {
            return true;
        }

        return $reservation->user_id === $user->id;
    }

    /**
     * Menentukan apakah user boleh membatalkan reservasi.
     */
    public function cancel(User $user, Reservation $reservation): bool
    {
        return $this->update($user, $reservation);
    }

    /**
     * Menentukan apakah user boleh melakukan check-in.
     */
    public function checkin(User $user, Reservation $reservation): bool
    {
        if ($this->isAdminOrManager($user)) {
            return true;
        }

        return $reservation->user_id === $user->id;
    }

    /**
     * Menentukan apakah user boleh menghapus reservasi (hard delete).
     */
    public function delete(User $user, Reservation $reservation): bool
    {
        return $this->update($user, $reservation);
    }
}
