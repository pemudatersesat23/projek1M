# PROJECT_CONTEXT.md
# LPK Kizuku International Academy — Laravel Project

> **Terakhir diperbarui:** 22 Juni 2026  
> **Versi Laravel:** 12.x | **PHP:** 8.2+ | **Database:** MySQL (SQLite untuk dev fallback)

---

## 1. Gambaran Umum Project

**LPK Kizuku International Academy** adalah website resmi lembaga pelatihan kerja yang mempersiapkan peserta Indonesia untuk berkarir di Jepang. Website ini mencakup:

- **Landing page** publik (beranda, program, berita, FAQ, alur pendaftaran, partner kampus)
- **Formulir pendaftaran dinamis** berbasis Google Forms-like builder
- **Admin panel** untuk mengelola semua konten, program, batch, pendaftar, dan form
- **Sistem multibahasa** (Indonesia & Jepang)
- **Manajemen batch/gelombang** pendaftaran dengan status lifecycle

---

## 2. Teknologi yang Digunakan

| Kategori         | Teknologi                                          |
|------------------|----------------------------------------------------|
| Backend          | PHP 8.2+, Laravel 12.x                            |
| Frontend         | Blade templates, Vanilla CSS, Vanilla JS           |
| CSS Build        | Vite + PostCSS + Tailwind (untuk admin)            |
| Slider/UI        | Swiper.js 11 (CDN)                                 |
| Icon             | Material Symbols Outlined (Google Fonts CDN)       |
| Fonts            | Sora, Noto Sans JP (Google Fonts CDN)              |
| Database         | MySQL (prod) / SQLite (dev fallback)               |
| ORM              | Eloquent + SoftDeletes                             |
| Auth             | Laravel Breeze (email/password)                    |
| Translasi Model  | `spatie/laravel-translatable` ^6.11               |
| Auto-Translate   | `stichoza/google-translate-php` ^5.3              |
| Package Manager  | Composer (PHP) + npm (JS/CSS)                      |
| Lokalisasi       | Laravel `lang/` files (id, jp)                    |
| Queue/Job        | Database driver                                    |
| Cache            | Database driver                                    |
| Session          | Database driver                                    |

---

## 3. Struktur Folder

```
kizuku-laravel/
│
├── app/
│   ├── Console/           — Artisan commands (jika ada)
│   ├── Helpers/
│   │   └── helpers.php    — Global helper functions (autoload via composer)
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/     — Semua controller admin (16 controller)
│   │   │   ├── Api/       — API controllers (jika ada)
│   │   │   ├── Auth/      — Auth controllers (Breeze)
│   │   │   ├── BeritaController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── ExportController.php
│   │   │   ├── HomeController.php     ← Entry utama frontend publik
│   │   │   ├── PartnerCampusController.php
│   │   │   ├── ProfileController.php
│   │   │   └── SiswaController.php
│   │   ├── Middleware/
│   │   │   ├── IsAdmin.php     — Cek role === 'admin', redirect jika bukan
│   │   │   └── SetLocale.php   — Set locale dari session; paksa 'id' di admin
│   │   ├── Requests/
│   │   │   ├── Admin/
│   │   │   ├── PendaftaranRequest.php   ← Validasi form pendaftaran publik
│   │   │   ├── BeritaRequest.php
│   │   │   ├── PartnerCampusRequest.php
│   │   │   └── ProfileUpdateRequest.php
│   │   └── Resources/     — API Resources (jika ada)
│   ├── Models/            — 22 Eloquent models
│   ├── Providers/
│   │   └── AppServiceProvider.php  ← Share $appSettings ke semua views, View Composer untuk keunggulan
│   ├── Services/
│   │   ├── DashboardService.php       — Statistik dashboard admin
│   │   ├── DynamicFormService.php     — Resolve form aktif & ambil fields
│   │   ├── FileUploadService.php      — Upload file umum
│   │   ├── RegistrationService.php    — Proses pendaftaran (DB transaction)
│   │   └── DynamicForm/
│   │       ├── ApplicantIdentityMapper.php  — Mapping field ke kolom identitas applicant
│   │       ├── DynamicFileUploadService.php — Upload file form dinamis
│   │       └── DynamicValidationService.php — Validasi field-level form dinamis
│   ├── Traits/
│   │   └── AutoTranslate.php   — Auto-translate via Google Translate saat model disimpan
│   └── View/              — View composers (jika ada tambahan)
│
├── config/                — Konfigurasi Laravel (app, database, programs, dynamic_forms, dll.)
├── database/
│   ├── migrations/        — 45 migration files (urutan timestamp)
│   ├── seeders/           — 14 seeder files
│   └── database.sqlite    — SQLite fallback (dev)
│
├── lang/
│   ├── id/                — Terjemahan Bahasa Indonesia
│   └── jp/                — Terjemahan Bahasa Jepang
│
├── public/
│   ├── assets/css/        — CSS khusus per halaman (program-detail.css, dll.)
│   ├── assets/js/         — JS khusus per halaman
│   ├── css/               — CSS global (variables.css, navbar.css, hero.css, dll.)
│   ├── js/                — JS global (navbar.js, lang-toggle.js)
│   └── image/             — Gambar statis (logo, dll.)
│
├── resources/
│   ├── css/               — Source CSS (dikompilasi via Vite)
│   ├── js/                — Source JS (dikompilasi via Vite)
│   └── views/
│       ├── admin/         — Views panel admin (dashboard, forms, programs, dll.)
│       ├── auth/          — Views auth (Breeze: login, register, dll.)
│       ├── components/    — Blade components reusable
│       │   ├── app-alerts.blade.php
│       │   ├── dynamic-form/         — Komponen dynamic form builder
│       │   └── program/detail/       — Komponen halaman detail program
│       ├── layouts/
│       │   └── app.blade.php         ← Layout utama (navbar, footer, JS global)
│       ├── sections/                 — Bagian-bagian halaman home (hero, berita, dll.)
│       ├── home.blade.php            ← Halaman home (include sections)
│       ├── program-detail.blade.php  ← Halaman detail program
│       ├── faq.blade.php
│       ├── alur-pendaftaran.blade.php
│       └── dashboard.blade.php       ← Dashboard user (non-admin)
│
├── routes/
│   ├── web.php            ← Semua route web (publik + admin + auth)
│   ├── auth.php           ← Route auth Breeze
│   ├── api.php            ← Route API (minimal)
│   └── console.php
│
├── .env                   ← Konfigurasi environment
└── composer.json
```

---

## 4. Alur Utama Aplikasi

### 4.1 Alur Halaman Home

1. User akses `/`
2. `HomeController::index()` dipanggil
3. Query: Berita, PartnerCampus, HeroSection, Testimonial, Fasilitas, Gallery, FeaturedPrograms, Settings (stats)
4. Data dikirim ke `view('home')` yang include sections: `hero`, `program`, `keunggulan`, `fasilitas`, `gallery`, `berita`, `testimonial`, `partner`, `kontak`
5. `AppServiceProvider` share `$appSettings` (WA number, email, alamat) ke semua view

### 4.2 Alur Halaman Program Detail

1. User akses `/program/{slug}` atau alias (`/tokutei-ginou`, `/engineer-jepang`, dll.)
2. `HomeController::showProgram($slug)` dipanggil
3. Ambil Program dengan relasi: `batches`, `activeBatches`, `activeSchemas`, `activeSections`
4. Resolve form aktif via `DynamicFormService::resolveForm(program_id, schema_id, batch_id)`
5. Ambil fields via `DynamicFormService::getFieldsForForm($form)`
6. Render `program-detail.blade.php` yang include:
   - `components.program.detail.hero` — Banner hero program
   - `components.program.detail.content` — Konten detail (sections dinamis atau fallback static)
   - `components.program.detail.registration-form` — Form pendaftaran (jika batch aktif & bukan WA)
   - `sections.kontak` — Section kontak

### 4.3 Alur Pendaftaran

1. User mengisi form pendaftaran di `/program/{slug}#registration-section`
2. Form POST ke `/pendaftaran`
3. `PendaftaranRequest` melakukan validasi: program_id, batch_id, schema_id, form_id (termasuk cross-validation via `withValidator`)
4. `HomeController::storePendaftaran()` memanggil `RegistrationService::register($request)`
5. `RegistrationService`:
   - Ambil `Form` by `form_id`
   - Validasi field-level via `DynamicValidationService`
   - DB Transaction:
     - `ApplicantIdentityMapper::map()` → mapping field ke kolom identitas (nama, email, phone, dll.)
     - Buat `Applicant`
     - Simpan jawaban ke `ApplicantFormAnswer` (per non-file field)
     - Upload & simpan file ke `ApplicantDynamicFile` (per file field)
6. Redirect back dengan success message dari form atau fallback ke lang key

### 4.4 Alur Admin

1. Admin login via `/login` (Breeze)
2. Redirect ke `/dashboard-admin` via `DashboardController`
3. Semua route admin di bawah prefix `dashboard-admin` dengan middleware `['auth', 'admin']`
4. `IsAdmin` middleware memvalidasi `role === 'admin'`
5. `SetLocale` middleware memaksa locale `id` di area admin

### 4.5 Alur Form Builder (Google Forms-like)

1. Admin buka `Forms` → Create form baru
2. Pilih program, schema (opsional), batch (opsional), isi metadata
3. Buka `Builder` — tambah/edit/reorder fields via AJAX
4. `FormBuilderFieldController` handle CRUD fields
5. Admin klik Publish → `FormController::publish()` validasi field wajib (applicant_name, email/phone, no duplikasi field_name) → ubah status ke `published`
6. Form aktif ter-resolve oleh `DynamicFormService` saat user membuka halaman program

---

## 5. Modul / Fitur Utama

| Modul               | Controller                        | Model(s)                              |
|---------------------|-----------------------------------|---------------------------------------|
| Halaman Home        | HomeController                    | Berita, HeroSection, Testimonial, dll.|
| Program Publik      | HomeController                    | Program, Batch, ProgramSchema         |
| Pendaftaran         | HomeController (storePendaftaran) | Applicant, ApplicantFormAnswer, ApplicantDynamicFile |
| Berita              | BeritaController (admin)          | Berita                                |
| Partner Kampus      | PartnerCampusController           | PartnerCampus                         |
| Program CRUD        | Admin\ProgramController           | Program, ProgramSection               |
| Batch CRUD          | Admin\BatchController             | Batch                                 |
| Program Schema      | Admin\ProgramSchemaController     | ProgramSchema                         |
| Form Builder        | Admin\FormController              | Form                                  |
| Form Fields         | Admin\FormBuilderFieldController  | FormField                             |
| Form Responses      | Admin\FormResponseController      | Applicant, ApplicantFormAnswer        |
| Applicant Mgmt      | Admin\ApplicantController         | Applicant                             |
| FAQ                 | Admin\FaqController               | Faq                                   |
| Fasilitas           | Admin\FasilitasController         | Fasilitas                             |
| Gallery             | Admin\GalleryController           | Gallery                               |
| Hero Section        | Admin\HeroSectionController       | HeroSection                           |
| Testimonial         | Admin\TestimonialController       | Testimonial                           |
| Keunggulan          | Admin\KeunggulanController        | Keunggulan                            |
| Settings            | Admin\SettingController           | Setting                               |
| Export              | ExportController                  | Applicant (export CSV)                |
| Dashboard Stats     | DashboardController               | DashboardService                      |

---

## 6. Hubungan File Penting

### Fitur: Pendaftaran Program

```
Route:      POST /pendaftaran  →  HomeController::storePendaftaran
Request:    app/Http/Requests/PendaftaranRequest.php
Controller: app/Http/Controllers/HomeController.php (storePendaftaran)
Service:    app/Services/RegistrationService.php
            app/Services/DynamicFormService.php
            app/Services/DynamicForm/DynamicValidationService.php
            app/Services/DynamicForm/DynamicFileUploadService.php
            app/Services/DynamicForm/ApplicantIdentityMapper.php
Models:     Applicant, ApplicantFormAnswer, ApplicantDynamicFile, Form, FormField
View:       resources/views/components/program/detail/registration-form.blade.php
            resources/views/components/program/detail/forms/ (field renderers)
```

### Fitur: Program Detail

```
Route:      GET /program/{slug}  →  HomeController::showProgram
Controller: app/Http/Controllers/HomeController.php (showProgram)
Service:    app/Services/DynamicFormService.php (resolveForm, getFieldsForForm)
Models:     Program, Batch, ProgramSchema, ProgramSection, Form, FormField
Views:      resources/views/program-detail.blade.php
            resources/views/components/program/detail/hero.blade.php
            resources/views/components/program/detail/content.blade.php
            resources/views/components/program/detail/section.blade.php
            resources/views/components/program/detail/batch-section.blade.php
CSS:        public/assets/css/program-detail.css
JS:         public/assets/js/program-detail.js
```

### Fitur: Form Builder Admin

```
Routes:     admin.forms.* (CRUD), admin.forms.fields.* (CRUD fields)
Controllers: Admin\FormController, Admin\FormBuilderFieldController
Models:     Form, FormField
Views:      resources/views/admin/forms/ (index, create, builder, preview, responses/)
```

### Global: Layout & Settings

```
Layout:     resources/views/layouts/app.blade.php
Provider:   app/Providers/AppServiceProvider.php  ($appSettings shared ke semua views)
Middleware: app/Http/Middleware/SetLocale.php
            app/Http/Middleware/IsAdmin.php
Settings:   app/Models/Setting.php (cached via Cache::rememberForever)
```

---

## 7. Pola Coding yang Harus Diikuti

### 7.1 Naming Convention

- **Controller**: PascalCase, suffix `Controller` — `HomeController`, `FormController`
- **Model**: PascalCase singular — `Program`, `Batch`, `Applicant`
- **Request**: PascalCase suffix `Request` — `PendaftaranRequest`, `FormRequest`
- **Service**: PascalCase suffix `Service` — `RegistrationService`, `DashboardService`
- **View**: kebab-case atau snake_case folder, PascalCase tidak digunakan — `program-detail.blade.php`, `admin/forms/index.blade.php`
- **Route name**: dot notation — `admin.forms.index`, `programs.show`, `pendaftaran.store`
- **CSS class**: kebab-case — `pd-content-section`, `nav-brand`, `btn-primary`

### 7.2 Multibahasa (Translatable)

- Semua teks konten model yang perlu diterjemahkan menggunakan `Spatie\Translatable\HasTranslations`
- Locale yang didukung: `id` (Indonesia) dan `jp` (Jepang — PERHATIAN: kode `jp` bukan `ja`!)
- Pattern akses translasi: `$model->getTranslation('field', app()->getLocale())`
- AutoTranslate Trait (`app/Traits/AutoTranslate.php`) otomatis menerjemahkan via Google Translate saat save jika salah satu locale kosong
- Di admin, locale selalu di-force ke `id` oleh `SetLocale` middleware
- String UI (bukan konten DB) menggunakan `__('messages.key')` dari `lang/` files

### 7.3 Form Validation

- Form validation menggunakan `FormRequest` terpisah
- Field-level validation form dinamis ditangani oleh `DynamicValidationService` (bukan di FormRequest utama)
- `PendaftaranRequest` hanya validasi struktur dasar (program_id, batch_id, form_id, schema_id)

### 7.4 Database / Query

- Selalu gunakan eager loading (`with()`) untuk cegah N+1, terutama di list views
- Model menggunakan `SoftDeletes` untuk Program, Batch, Form, FormField (jangan hard delete tanpa alasan)
- Settings di-cache permanent via `Cache::rememberForever`, di-clear otomatis saat model disimpan

### 7.5 File Upload

- Storage disk: `public` (symlink ke `storage/app/public`)
- Direktori upload: `programs/thumbnails`, `programs/brosur`, `applicant-files/{applicant_id}/`
- File upload form dinamis ditangani `DynamicFileUploadService`

### 7.6 CSS/UI

- CSS global ada di `public/css/` (langsung, tidak dikompilasi)
- CSS per-halaman di `public/assets/css/`
- Variable CSS ada di `public/css/variables.css` (gunakan `var(--variable-name)`)
- Layout utama: `resources/views/layouts/app.blade.php`
- Untuk halaman yang perlu CSS/JS tambahan, gunakan `@push('styles')` dan `@push('scripts')`

### 7.7 Admin Panel

- Semua admin route prefix: `dashboard-admin`, nama: `admin.*`
- Admin views di `resources/views/admin/`
- Admin selalu menggunakan locale `id` (dipaksa oleh middleware)

---

## 8. Catatan Penting untuk Pengembangan

### ⚠️ Perhatian Locale
- Kode bahasa Jepang di sistem ini adalah `jp` (bukan `ja` standar ISO)
- Tapi Google Translate API menggunakan `ja` sebagai target
- `SetLocale` middleware sudah meng-handle ini di level app
- Cek kondisi locale Jepang selalu: `app()->getLocale() === 'jp'` atau `in_array(app()->getLocale(), ['jp', 'ja'])`

### ⚠️ Form Resolve Logic
- `DynamicFormService::resolveForm()` memiliki 3-level fallback:
  1. program_id + schema_id + batch_id (exact match)
  2. program_id + schema_id + null batch_id
  3. program_id + null schema_id + null batch_id
- Form HARUS status `published`, `is_active=true`, `accepts_responses=true`

### ⚠️ AutoTranslate Side Effects
- `AutoTranslate` trait memanggil Google Translate saat model disimpan
- Jika Google Translate API gagal, error di-log tapi model tetap disimpan (tidak gagal)
- Ini bisa menjadi bottleneck performa saat save model secara massal (seeder)

### ⚠️ SoftDeletes
- Program, Batch, Form, FormField menggunakan SoftDeletes
- Jangan gunakan `delete()` tanpa pertimbangan — data masih bisa di-restore
- `$form->fields()->delete()` di `FormController::destroy()` sudah benar (soft delete fields dulu)

### ⚠️ File Root yang Tidak Standar
- Ada beberapa file debug di root project: `debug_migrate.php`, `fix_admin.php`, `fix_json.php`, `fix_more.php`
- File ini TIDAK boleh ada di production, perlu dihapus atau dipindahkan

### ⚠️ Route Alias Program
- Ada 4 route alias hardcoded di `web.php` yang memanggil `showProgram` dengan slug hardcoded:
  - `/tokutei-ginou` → slug `tokutei-ginou-tg`
  - `/engineer-jepang` → slug `engineer-jepang-gijinkoku`
  - `/ex-internship` → slug `engineer-jepang-ex-internship`
  - `/kursus-bahasa-jepang` → slug `kursus-bahasa-jepang`
- Jika slug program di database berubah, route alias ini akan 404

### ✅ View Sharing
- `$appSettings` (WA number, email, alamat, jam kerja) tersedia di SEMUA views via `AppServiceProvider`
- `$keunggulans` tersedia di view `sections.keunggulan` via View Composer

### ✅ DashboardService
- `DashboardService` sudah fix N+1 menggunakan `withCount()` dan single query aggregate
- Laporan bulanan menggunakan GROUP BY di PHP level (bukan raw SQL GROUP BY)

---

## 9. Temuan Masalah Potensial

### [TINGGI] File Debug di Root
- **File:** `debug_migrate.php`, `fix_admin.php`, `fix_json.php`, `fix_more.php`
- **Risiko:** Security exposure jika server tidak dikonfigurasi dengan benar
- **Saran:** Hapus atau pindahkan ke folder yang tidak accessible publik

### [TINGGI] Route Alias dengan Slug Hardcoded
- **File:** `routes/web.php` (baris 26-40)
- **Risiko:** Jika slug program diubah di admin panel, route alias 404
- **Saran:** Buat relasi antara alias route dan slug di database, atau tambahkan redirect fallback

### [SEDANG] AutoTranslate pada Seeding Massal
- **File:** `app/Traits/AutoTranslate.php`
- **Risiko:** Seeding massal memanggil Google Translate API berkali-kali → lambat/rate limit
- **Saran:** Nonaktifkan trait saat seeding, atau tambahkan conditional flag

### [SEDANG] Laporan Bulanan Tidak Menggunakan SQL GROUP BY
- **File:** `app/Services/DashboardService.php` (method `getMonthlyReport`)
- **Risiko:** Jika data applicant banyak, semua data di-load ke PHP dulu baru di-group
- **Saran:** Gunakan raw SQL `GROUP BY YEAR(created_at), MONTH(created_at)` 

### [SEDANG] ProgramSection `syncSections` Hapus & Buat Ulang
- **File:** `app/Http/Controllers/Admin/ProgramController.php` (method `syncSections`)
- **Risiko:** `$program->sections()->delete()` menghapus semua sections lalu buat ulang — tidak efisien dan SoftDeletes tidak digunakan
- **Saran:** Gunakan upsert atau compare existing vs baru, jaga ID lama

### [RENDAH] Inline Style di Blade Views
- **File:** Beberapa views menggunakan inline style panjang (terutama `content.blade.php`)
- **Risiko:** Susah di-maintain dan override dengan CSS
- **Saran:** Pindahkan ke CSS file terpisah

### [RENDAH] WA Modal Program List Hardcoded
- **File:** `resources/views/layouts/app.blade.php` (baris 490-495)
- **Risiko:** Daftar program di WA modal tidak sinkron dengan database
- **Saran:** Ambil dari database atau setidaknya gunakan config

### [RENDAH] Duplikasi Swiper Init
- **File:** `resources/views/layouts/app.blade.php` (baris 516-603)
- **Risiko:** Swiper init ada di layout global — akan error jika element tidak ditemukan di halaman lain
- **Saran:** Tambahkan null check atau pindahkan init ke halaman yang membutuhkan

---

*File ini dibuat otomatis oleh AI Code Reviewer. Update setiap kali ada perubahan arsitektur besar.*
