# Catatan Refactoring: Penggabungan Tabel Admins dan Users

## Perubahan yang Dilakukan

### 1. Database Structure
- **Dihapus**: Tabel `admins` (tidak diperlukan lagi)
- **Digunakan**: Tabel `users` dengan kolom `role` untuk membedakan user dan admin
- **Kolom role**: `'user'`, `'admin'`, atau `'super_admin'`

### 2. Foreign Keys
- **articles.user_id**: Reference ke `users.id` (sebelumnya `admin_id` → `admins.id`)
- **tips.user_id**: Reference ke `users.id` (sebelumnya `admin_id` → `admins.id`)

### 3. Models
- **Dihapus**: Model `Admin`
- **Updated**: Model `User` - sekarang digunakan untuk semua user (user biasa dan admin)
- **Updated**: Model `Article` - relationship `admin()` → `user()`
- **Updated**: Model `Tip` - relationship `admin()` → `user()`

### 4. Controllers
- **Admin/AuthController**: Sekarang menggunakan `User` model dengan filter `role`
- **Admin Controllers**: Semua menggunakan `Auth::id()` instead of `auth('admin')->id()`

### 5. Authentication
- **Dihapus**: Guard `admin` dari `config/auth.php`
- **Digunakan**: Guard `web` untuk semua user
- **Middleware**: 
  - `EnsureUserIsAdmin` - memastikan user memiliki role admin/super_admin
  - `EnsureUserIsNotAdmin` - memastikan admin tidak mengakses route user

### 6. Routes
- **Admin routes**: Menggunakan middleware `['auth', 'admin']`
- **User routes**: Menggunakan middleware `['auth', 'user']`

## Keuntungan

1. **Lebih Sederhana**: Satu tabel, satu model untuk semua user
2. **Lebih Fleksibel**: Mudah menambah role baru tanpa membuat tabel baru
3. **Lebih Efisien**: Query lebih mudah, tidak perlu join antar tabel
4. **Konsisten**: Semua user menggunakan sistem autentikasi yang sama

## Cara Membuat Admin

Setelah migration, buat admin dengan:

```php
\App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@example.com',
    'password' => \Hash::make('password'),
    'role' => 'admin', // atau 'super_admin'
    'total_points' => 0,
]);
```

## Catatan Penting

- Semua user (baik user biasa maupun admin) sekarang disimpan di tabel `users`
- Role ditentukan oleh kolom `role` di tabel `users`
- Middleware `admin` memastikan hanya user dengan role `admin` atau `super_admin` yang bisa akses admin panel
- Middleware `user` memastikan admin tidak bisa akses route user

