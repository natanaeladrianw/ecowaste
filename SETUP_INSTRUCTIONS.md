# Instruksi Setup Aplikasi Bank Sampah

## Langkah-langkah Setup

### 1. Konfigurasi Database
Pastikan file `.env` sudah dikonfigurasi dengan benar:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database_anda
DB_USERNAME=username_database
DB_PASSWORD=password_database
```

### 2. Jalankan Migrations
```bash
php artisan migrate
```
Ini akan membuat semua tabel yang diperlukan di database.

### 3. Buat Storage Link (untuk upload file)
```bash
php artisan storage:link
```
Ini diperlukan agar file yang diupload (foto sampah, foto bank sampah, dll) bisa diakses via URL.

### 4. Buat Admin Pertama (Opsional tapi Disarankan)
Anda bisa membuat admin pertama melalui Laravel Tinker:
```bash
php artisan tinker
```

Kemudian jalankan:
```php
\App\Models\Admin::create([
    'name' => 'Admin',
    'email' => 'admin@example.com',
    'password' => \Hash::make('password'),
    'role' => 'admin'
]);
```

Atau bisa langsung via SQL:
```sql
INSERT INTO admins (name, email, password, role, created_at, updated_at) 
VALUES ('Admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NOW(), NOW());
-- Password default: password
```

### 5. Buat Kategori Sampah Dasar (Opsional)
Jika ingin membuat kategori sampah dasar, bisa via Tinker:
```bash
php artisan tinker
```

```php
\App\Models\WasteCategory::create(['name' => 'Organik', 'points_per_kg' => 10, 'color' => '#2E7D32', 'is_active' => true]);
\App\Models\WasteCategory::create(['name' => 'Anorganik', 'points_per_kg' => 15, 'color' => '#2196F3', 'is_active' => true]);
\App\Models\WasteCategory::create(['name' => 'B3', 'points_per_kg' => 20, 'color' => '#F44336', 'is_active' => true]);
\App\Models\WasteCategory::create(['name' => 'Recycle', 'points_per_kg' => 25, 'color' => '#FF9800', 'is_active' => true]);
```

### 6. Jalankan Server
```bash
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

## Catatan Penting

1. **Tidak perlu Seeder**: Sesuai permintaan, tidak ada seeder yang dibuat. Data bisa ditambahkan manual melalui aplikasi atau Tinker.

2. **Admin Guard**: Pastikan middleware untuk admin menggunakan guard yang benar. Jika diperlukan, bisa tambahkan middleware khusus untuk admin.

3. **File Upload**: Pastikan folder `storage/app/public` memiliki permission yang benar untuk upload file.

4. **Route Names**: Beberapa views menggunakan route name tanpa prefix `user.`, sudah ditambahkan alias routes untuk kompatibilitas.

## Struktur Aplikasi

### User Side
- Dashboard: `/user/dashboard` atau `/dashboard`
- Input Sampah: `/user/waste/create` atau `/waste/create`
- Statistik: `/user/statistics/daily`, `/weekly`, `/monthly`
- Bank Sampah: `/user/bank-sampah`
- Education: `/user/education/tips`, `/articles`, `/challenges`
- Points: `/user/points`
- Community: `/user/community/forum`, `/achievements`

### Admin Side
- Login: `/admin/login`
- Dashboard: `/admin/dashboard`
- Users: `/admin/users`
- Waste Reports: `/admin/waste/reports`
- Categories: `/admin/waste/categories`
- Bank Sampah: `/admin/bank-sampah`
- Statistics: `/admin/statistics`
- Tips & Articles: `/admin/education/tips`, `/articles`

## Troubleshooting

1. **Error "Class not found"**: Jalankan `composer dump-autoload`
2. **Error "Route not found"**: Pastikan sudah menjalankan `php artisan route:clear`
3. **Error upload file**: Pastikan `storage/app/public` ada dan `php artisan storage:link` sudah dijalankan
4. **Error migration**: Pastikan database sudah dibuat dan konfigurasi `.env` benar

