# Dokumentasi API StudioRent v1

## 1. Autentikasi

Sebagian besar endpoint menggunakan autentikasi Laravel bawaan (`auth` / session).

Alur penggunaan (untuk frontend / Orang 3):

1. User login melalui halaman web (form `/login`).
2. Setelah login, browser menyimpan cookie session.
3. Frontend memanggil endpoint `/api/...` dalam kondisi user sudah login (session aktif).

> Untuk keperluan UAS, fokus utamanya: struktur endpoint & logika reservasi, bukan mekanisme token khusus.

---

## 2. Endpoint Studio

### 2.1. GET `/api/studios`

Mengambil daftar studio (paginated).

**Query parameter (opsional):**

- `capacity` → minimal kapasitas studio
- `status` → status studio (sesuai nilai di kolom `status` pada tabel `studios`)

**Contoh Response 200:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Studio Podcast A",
      "description": "Ruang podcast kedap suara",
      "capacity": 4,
      "price_per_hour": 150000,
      "status": 1
    }
  ],
  "links": {
    "first": "http://localhost/api/studios?page=1",
    "last": "http://localhost/api/studios?page=1",
    "prev": null,
    "next": null
  },
  "meta": {
    "current_page": 1,
    "last_page": 1,
    "per_page": 10,
    "total": 1
  }
}

2.2. GET /api/studios/{id}

Mengambil detail satu studio berdasarkan ID.

Contoh Response 200:

{
  "id": 1,
  "name": "Studio Podcast A",
  "description": "Ruang podcast kedap suara",
  "capacity": 4,
  "price_per_hour": 150000,
  "status": 1
}

3. Endpoint Reservasi (Member / Admin)
3.1. GET /api/me/reservations

Mengambil daftar semua reservasi milik user yang sedang login.

Query parameter (opsional):

status → filter berdasarkan status, misalnya:

pending

confirmed

cancelled

completed

Contoh Response 200:

{
  "data": [
    {
      "id": 10,
      "studio_id": 1,
      "user_id": 5,
      "start_time": "2025-11-25T10:00:00",
      "end_time": "2025-11-25T11:30:00",
      "status": "confirmed",
      "total_price": 225000
    }
  ],
  "links": {
    "first": "http://localhost/api/me/reservations?page=1",
    "last": "http://localhost/api/me/reservations?page=1",
    "prev": null,
    "next": null
  },
  "meta": {
    "current_page": 1,
    "last_page": 1,
    "per_page": 10,
    "total": 1
  }
}

3.2. GET /api/reservations

Mengambil daftar semua reservasi (biasanya untuk admin/manager).

Query parameter (opsional):

status → filter berdasarkan status reservasi

studio_id → filter berdasarkan studio tertentu

date_from → tanggal mulai (format YYYY-MM-DD)

date_to → tanggal akhir (format YYYY-MM-DD)

Contoh pemanggilan:

GET /api/reservations?status=confirmed&studio_id=1&date_from=2025-11-01&date_to=2025-11-30

3.3. POST /api/reservations

Membuat reservasi baru.

Body (JSON):

{
  "studio_id": 1,
  "start_time": "2025-11-25 10:00",
  "end_time": "2025-11-25 11:30",
  "addons": {
    "1": 2,
    "3": 1
  }
}


Keterangan field:

studio_id (wajib) → ID studio

start_time (wajib) → waktu mulai (datetime)

end_time (wajib) → waktu selesai (datetime)

addons (opsional) → objek {addon_id: quantity} untuk tambahan fasilitas

Aturan bisnis utama:

Durasi pemakaian minimal 90 menit, maksimal 120 menit.

Tidak boleh ada bentrok jadwal dengan reservasi lain di studio yang sama (termasuk buffer).

Ada kuota maksimal reservasi per minggu per user (misalnya 3x per minggu).

Jika salah satu aturan dilanggar, API mengembalikan status 422 (validation error).

Contoh Response 201:

{
  "message": "Reservasi berhasil dibuat.",
  "reservation": {
    "id": 10,
    "studio_id": 1,
    "user_id": 5,
    "start_time": "2025-11-25T10:00:00",
    "end_time": "2025-11-25T11:30:00",
    "status": "confirmed",
    "checkin_code": "CHK-ABC123",
    "total_price": 225000,
    "studio": {
      "id": 1,
      "name": "Studio Podcast A",
      "price_per_hour": 150000
    },
    "addons": [
      {
        "id": 1,
        "name": "Mic Tambahan",
        "pivot": {
          "quantity": 2
        }
      }
    ]
  }
}


Contoh Response 422 (gagal karena aturan):

{
  "message": "The given data was invalid.",
  "errors": {
    "duration": [
      "Durasi minimal adalah 90 menit."
    ]
  }
}

3.4. PATCH /api/reservations/{id}

Mengubah jadwal dan/atau addons sebuah reservasi.

Body (JSON) contoh:

{
  "start_time": "2025-11-25 12:00",
  "end_time": "2025-11-25 13:30",
  "addons": {
    "1": 1
  }
}


Aturan:

Hanya pemilik reservasi atau admin/manager yang boleh mengupdate.

Aturan durasi, bentrok, dan kuota tetap berlaku.

Contoh Response 200:

{
  "message": "Reservasi berhasil diupdate.",
  "reservation": {
    "id": 10,
    "studio_id": 1,
    "user_id": 5,
    "start_time": "2025-11-25T12:00:00",
    "end_time": "2025-11-25T13:30:00",
    "status": "confirmed"
  }
}

3.5. PATCH /api/reservations/{id}/cancel

Membatalkan reservasi (soft cancel, hanya mengubah status → tidak menghapus data).

Aturan:

Hanya pemilik reservasi atau admin/manager (diatur di ReservationPolicy@cancel).

Saat cancel, sistem juga mengirim email notifikasi pembatalan ke pemilik.

Contoh Response 200:

{
  "message": "Reservasi berhasil dibatalkan.",
  "reservation": {
    "id": 10,
    "status": "cancelled"
  }
}

3.6. POST /api/reservations/{id}/checkin

Melakukan check-in untuk reservasi dengan kode (biasanya dipindai dari QR code).

Body (JSON):

{
  "code": "CHK-ABC123"
}


Aturan:

code harus sesuai dengan checkin_code di tabel reservations.

Waktu check-in harus berada pada jendela waktu tertentu (misalnya beberapa menit sebelum/sesudah start_time).

Hanya pemilik reservasi atau admin/manager yang boleh melakukan check-in (diatur di ReservationPolicy@checkin).

Contoh Response 200:

{
  "message": "Check-in berhasil.",
  "reservation": {
    "id": 10,
    "status": "completed",
    "checked_in_at": "2025-11-25T10:05:00"
  }
}


Contoh Response 422 (kode salah):

{
  "message": "The given data was invalid.",
  "errors": {
    "code": [
      "Kode check-in tidak valid."
    ]
  }
}

3.7. DELETE /api/reservations/{id}

Menghapus reservasi (hard delete) dari database.

Aturan:

Diatur oleh ReservationPolicy@delete:

pemilik reservasi boleh menghapus, atau

admin/manager boleh menghapus.

Contoh Response 200:

{
  "message": "Reservasi berhasil dihapus."
}

4. Endpoint Admin (Master Data)

Endpoint ini biasanya hanya bisa diakses oleh user dengan role admin/manager dan berada di prefix /api/admin.

4.1. Studio: /api/admin/studios

POST /api/admin/studios
Membuat studio baru.

Body contoh:

{
  "name": "Studio Musik B",
  "description": "Ruang latihan band",
  "capacity": 5,
  "price_per_hour": 200000,
  "status": 1
}


PUT /api/admin/studios/{id} atau PATCH /api/admin/studios/{id}
Mengubah data studio.

DELETE /api/admin/studios/{id}
Menghapus studio.

4.2. Addon: /api/admin/addons

CRUD untuk data addon (misal: mic tambahan, kamera, mixer, dsb).

Contoh endpoint:

GET /api/admin/addons

POST /api/admin/addons

PATCH /api/admin/addons/{id}

DELETE /api/admin/addons/{id}

4.3. Features: /api/admin/features

CRUD untuk data fitur studio (misal: AC, soundproof, mixer, dll).

Contoh endpoint:

GET /api/admin/features

POST /api/admin/features

PATCH /api/admin/features/{id}

DELETE /api/admin/features/{id}