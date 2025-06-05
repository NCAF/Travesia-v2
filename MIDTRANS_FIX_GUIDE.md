# Panduan Memperbaiki Error Midtrans 401 Unauthorized

## Masalah Yang Teridentifikasi

Dari screenshot `.env` Anda, terdapat **konflik konfigurasi** antara jenis API key dan environment setting:

```env
MIDTRANS_SERVER_KEY=SB-Mid-server-xxxxxxxxxx  # <- SANDBOX KEY
MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxxxxxxxx         # <- SANDBOX KEY
MIDTRANS_IS_PRODUCTION=true                                   # <- PRODUCTION SETTING ❌
```

**Masalah:** Anda menggunakan Sandbox Keys (prefix `SB-`) tetapi mengatur `MIDTRANS_IS_PRODUCTION=true`

## Solusi 1: Gunakan Sandbox Mode (Recommended untuk Testing)

Ubah file `.env` Anda menjadi:

```env
MIDTRANS_SERVER_KEY=SB-Mid-server-xxxxxxxxxx
MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxxxxxxxx
MIDTRANS_IS_PRODUCTION=false  # <- UBAH DARI true KE false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

## Solusi 2: Gunakan Production Mode (Untuk Live Environment)

Jika ingin menggunakan production:
1. Login ke [Midtrans Production Dashboard](https://dashboard.midtrans.com/)
2. Ambil Production Keys (tanpa prefix `SB-`)
3. Ubah `.env`:

```env
MIDTRANS_SERVER_KEY=Mid-server-xxxxxxxxxx     # <- Production key (tanpa SB-)
MIDTRANS_CLIENT_KEY=Mid-client-xxxxxxxxxx     # <- Production key (tanpa SB-)
MIDTRANS_IS_PRODUCTION=true
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

## Langkah Setelah Mengubah .env

1. **Clear Cache:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

2. **Test Ulang** pembayaran di aplikasi Anda

## Validasi Tambahan

Saya telah menambahkan validasi di `app/Traits/MidtransConfigTrait.php` yang akan:
- ✅ Mendeteksi konflik konfigurasi
- ✅ Memberikan error message yang jelas
- ✅ Log konfigurasi untuk debugging

## Cara Mengecek Log

Jika masih ada masalah, cek log Laravel:
```bash
tail -f storage/logs/laravel.log
```

Atau lihat di `storage/logs/laravel-{tanggal}.log`

## Tips Keamanan

⚠️ **Penting:** Jangan commit file `.env` ke Git! Pastikan `.env` ada di `.gitignore`

## URL yang Benar

- **Sandbox:** `https://app.sandbox.midtrans.com/snap/snap.js`
- **Production:** `https://app.midtrans.com/snap/snap.js`

Kode Anda sudah benar menggunakan sandbox URL.

---

**Solusi yang Direkomendasikan:** Gunakan **Solusi 1** (Sandbox Mode) untuk testing dan development. 