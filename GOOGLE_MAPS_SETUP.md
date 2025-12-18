# Panduan Setup Google Maps API

## Cara Mendapatkan Google Maps API Key

### Langkah 1: Buat Project di Google Cloud Console

1. Buka [Google Cloud Console](https://console.cloud.google.com/)
2. Login dengan akun Google Anda
3. Klik dropdown project di bagian atas, lalu pilih **"New Project"**
4. Isi nama project (contoh: "EcoWaste Maps")
5. Klik **"Create"**

### Langkah 2: Aktifkan Google Maps JavaScript API

1. Di Google Cloud Console, buka menu **"APIs & Services"** > **"Library"**
2. Cari **"Maps JavaScript API"**
3. Klik **"Enable"** untuk mengaktifkan API

### Langkah 3: Buat API Key

1. Buka menu **"APIs & Services"** > **"Credentials"**
2. Klik **"+ CREATE CREDENTIALS"** > **"API Key"**
3. Copy API key yang muncul (contoh: `AIzaSyBxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx`)

### Langkah 4: Restrict API Key (Opsional tapi Disarankan)

Untuk keamanan, batasi penggunaan API key:

1. Klik pada API key yang baru dibuat
2. Di bagian **"API restrictions"**, pilih **"Restrict key"**
3. Pilih **"Maps JavaScript API"** dan **"Places API"** (jika diperlukan)
4. Di bagian **"Application restrictions"**, pilih **"HTTP referrers"**
5. Tambahkan domain/URL yang diizinkan:
   - `http://localhost/*` (untuk development)
   - `https://yourdomain.com/*` (untuk production)
6. Klik **"Save"**

### Langkah 5: Tambahkan API Key ke File .env

1. Buka file `.env` di root project
2. Tambahkan baris berikut:
   ```
   GOOGLE_MAPS_API_KEY=AIzaSyBxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
   ```
   (Ganti dengan API key Anda yang sebenarnya)

3. Simpan file `.env`

### Langkah 6: Clear Cache (Jika Perlu)

Jalankan perintah berikut di terminal:
```bash
php artisan config:clear
php artisan cache:clear
```

### Langkah 7: Verifikasi

1. Refresh halaman Detail Bank Sampah
2. Google Maps seharusnya sudah muncul dengan benar

## Troubleshooting

### Error: "API Key Google Maps tidak dikonfigurasi"
- Pastikan file `.env` memiliki `GOOGLE_MAPS_API_KEY`
- Pastikan tidak ada spasi sebelum/sesudah tanda `=`
- Clear cache dengan `php artisan config:clear`

### Error: "Autentikasi Google Maps gagal"
- Pastikan API key valid dan tidak expired
- Pastikan **Maps JavaScript API** sudah diaktifkan
- Periksa apakah API key memiliki restrictions yang terlalu ketat

### Error: "Gagal memuat Google Maps"
- Periksa koneksi internet
- Periksa console browser untuk error detail
- Pastikan tidak ada ad blocker yang memblokir Google Maps

### Maps tidak muncul tapi tidak ada error
- Periksa console browser (F12) untuk error JavaScript
- Pastikan elemen `<div id="map">` ada di halaman
- Pastikan koordinat latitude dan longitude valid

## Catatan Penting

1. **Billing**: Google Maps API memiliki free tier, tapi setelah itu akan dikenakan biaya. Pastikan untuk:
   - Monitor penggunaan di Google Cloud Console
   - Set billing alerts
   - Restrict API key untuk mencegah abuse

2. **Quota**: Free tier biasanya:
   - Maps JavaScript API: $200 credit per bulan (cukup untuk ~28,000 map loads)
   - Places API: $200 credit per bulan

3. **Security**: Selalu restrict API key untuk production!

## Link Berguna

- [Google Maps Platform](https://developers.google.com/maps)
- [Maps JavaScript API Documentation](https://developers.google.com/maps/documentation/javascript)
- [Google Cloud Console](https://console.cloud.google.com/)
- [API Key Best Practices](https://developers.google.com/maps/api-security-best-practices)

