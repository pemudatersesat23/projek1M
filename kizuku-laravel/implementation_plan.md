# FINAL IMPLEMENTATION PLAN — Program Management System LPK Kizuku

## Status Dokumen

Dokumen ini adalah versi final dari `implementation_plan.md` untuk implementasi fitur:

1. **Tampilan Program**
2. **Manajemen Program**

Dokumen ini sudah disesuaikan dengan kondisi project Laravel existing dan kebutuhan revisi client. Fokus utama adalah membuat sistem program yang **dynamic**, **admin-manageable**, **scalable**, dan siap menjadi fondasi untuk fitur berikutnya seperti Dynamic Form Builder.

---

## 0. Scope Implementasi Saat Ini

### Dikerjakan pada phase ini

- Program Management System
- Program horizontal slider di frontend
- Program detail page
- Batch Management System
- Status pendaftaran program
- Optional Program Schema System
- Admin CRUD Program
- Admin CRUD Batch
- Admin CRUD Program Schema
- Compatibility awal dengan registration flow melalui `schema_id`

### Tidak dikerjakan pada phase ini

- Dynamic Form Builder penuh
- Field upload dinamis
- Form field builder dari admin
- Partnership CMS
- Alur Pendaftaran CMS
- Contact CMS
- Payment menu
- Embedded maps
- Validasi beasiswa kampus mitra secara penuh

Jika saat implementasi muncul kebutuhan ke fitur di atas, cukup buat catatan untuk phase berikutnya. Jangan melebar dari scope utama.

---

## 1. Tujuan Sistem

Program Management System dibuat agar seluruh program di website bisa dikelola dari dashboard admin tanpa perubahan kode.

Admin harus dapat:

- Menambah program
- Mengubah informasi program
- Menghapus atau mengarsipkan program
- Mengatur status program
- Mengatur urutan tampil program
- Mengatur program unggulan
- Mengelola batch pendaftaran
- Mengelola skema program jika program memiliki skema
- Mengelola konten multilingual sesuai sistem existing

Frontend harus dapat:

- Menampilkan program dalam horizontal slider
- Menampilkan program dari database, bukan hardcoded
- Menampilkan status pendaftaran berdasarkan batch
- Menampilkan detail dan persyaratan program
- Menampilkan CTA sesuai status batch
- Menampilkan pilihan skema jika program memiliki skema aktif

---

## 2. Prinsip Implementasi Wajib

AI agent wajib mengikuti prinsip berikut:

1. Jangan hardcode nama program.
2. Jangan hardcode slug program.
3. Jangan query database di Blade.
4. Jangan lazy loading di dalam loop.
5. Gunakan eager loading untuk relasi.
6. Gunakan SoftDeletes untuk data penting.
7. Gunakan FormRequest untuk validasi.
8. Gunakan service/helper/accessor untuk business logic.
9. Jangan hard delete data penting.
10. Jangan implement Dynamic Form Builder penuh pada phase ini.
11. Jangan merusak fitur Multi-Bahasa dan Hero Section yang sudah selesai.
12. Jangan mengubah sistem multilingual existing jika sudah berjalan.
13. Jangan menghapus data existing.
14. Migration harus aman terhadap database existing.

---

## 3. Slug Strategy

Gunakan pendekatan:

```text
Immutable Slug
```

### Aturan saat create program

- Jika admin mengisi slug manual, gunakan slug tersebut.
- Jika admin tidak mengisi slug, generate otomatis dari `nama_program`.
- Slug wajib unique di tabel `programs`.

### Aturan saat update program

- Jika admin mengubah `nama_program`, slug tidak ikut berubah.
- Slug hanya berubah jika admin mengedit field slug secara manual.
- Jika slug diedit manual, validasi unique wajib dilakukan.
- Jangan auto-regenerate slug pada update nama program.

### Alasan

- Menjaga SEO.
- Mencegah URL lama menjadi 404.
- Menjaga link yang sudah dibagikan.
- Menghindari redirect problem yang tidak perlu.

### Contoh

Nama awal:

```text
Program Engineering
```

Slug otomatis:

```text
program-engineering
```

Admin mengubah nama menjadi:

```text
Engineering Jepang
```

Slug tetap:

```text
program-engineering
```

Slug hanya berubah jika admin sengaja mengedit field slug.

---

## 4. Database Design Final

### 4.1 Tabel `programs`

Tabel ini sudah ada. Jangan buat ulang tabel jika sudah ada. Gunakan migration tambahan.

#### Struktur final

```text
id
nama_program
slug
deskripsi
focus
output
target_peserta
durasi
benefit
alur_seleksi
biaya
faq
materi
brosur
thumbnail_path
video_url
status
is_featured
sort_order
has_schema
created_at
updated_at
deleted_at
```

#### Field yang perlu dipastikan ada

```text
sort_order integer default 0
has_schema boolean default false
deleted_at nullable
```

#### Status program

```text
aktif
nonaktif
```

#### Arti status

```text
aktif    -> tampil di frontend
nonaktif -> tidak tampil di frontend
```

#### Multilingual

Jika project menggunakan Spatie `HasTranslations`, field berikut mengikuti sistem multilingual existing:

```text
nama_program
deskripsi
focus
output
target_peserta
durasi
benefit
alur_seleksi
biaya
faq
materi
```

Jangan mengubah sistem multilingual yang sudah berjalan.

---

### 4.2 Tabel `batches`

Tabel ini sudah ada. Jangan buat ulang tabel jika sudah ada. Gunakan migration tambahan.

#### Struktur final

```text
id
program_id
nama_batch
tanggal_buka
tanggal_tutup
tanggal_mulai
tanggal_selesai
tanggal_estimasi_selesai
kuota
status
cta_type
whatsapp_link
created_at
updated_at
deleted_at
```

#### Relasi

```text
Batch belongsTo Program
Program hasMany Batch
```

#### Status batch internal

Gunakan status internal berikut:

```text
dibuka
diperpanjang
akan_dibuka
ditutup
berjalan
selesai
```

#### Mapping status ke frontend

| Internal Status | Label Frontend | CTA |
|---|---|---|
| `dibuka` | Aktif | Tombol daftar aktif |
| `diperpanjang` | Diperpanjang | Tombol daftar aktif |
| `akan_dibuka` | Segera Dibuka | Tombol disabled |
| `ditutup` | Ditutup | Tombol disabled |
| `berjalan` | Sedang Berjalan | Tombol disabled |
| `selesai` | Selesai | Tombol disabled |

#### CTA type

Gunakan:

```text
internal_form
whatsapp
disabled
```

#### Aturan CTA

```text
IF batch.status == dibuka:
    tombol daftar aktif

IF batch.status == diperpanjang:
    tombol daftar aktif

IF batch.status == akan_dibuka:
    tombol disabled

IF batch.status == ditutup:
    tombol disabled

IF batch.status == berjalan:
    tombol disabled

IF batch.status == selesai:
    tombol disabled
```

#### Aturan WhatsApp CTA

```text
IF cta_type == whatsapp
AND whatsapp_link tidak kosong
AND status memungkinkan pendaftaran:
    tampilkan tombol WhatsApp

ELSE:
    ikuti status batch normal
```

Jangan otomatis mengarahkan status `akan_dibuka` ke WhatsApp kecuali admin memang mengatur `cta_type = whatsapp`.

---

### 4.3 Tabel Baru `program_schemas`

Gunakan nama tabel:

```text
program_schemas
```

Gunakan model:

```text
ProgramSchema
```

Jangan gunakan model bernama `Schema` agar tidak membingungkan dengan Laravel `Schema` facade.

#### Struktur final

```text
id
program_id
batch_id nullable
nama_skema
slug
tipe
deskripsi
persyaratan
harga
status
sort_order
created_at
updated_at
deleted_at
```

#### Detail field

```text
program_id    -> foreign key ke programs.id
batch_id      -> nullable foreign key ke batches.id
nama_skema    -> nama skema
slug          -> slug skema
tipe          -> tipe skema
deskripsi     -> penjelasan skema
persyaratan   -> persyaratan khusus skema
harga         -> harga khusus skema
status        -> aktif / nonaktif
sort_order    -> urutan tampil
deleted_at    -> soft delete
```

#### Tipe skema

```text
beasiswa
scholar_partnership
reguler
```

#### Status skema

```text
aktif
nonaktif
```

#### Multilingual Program Schema

Jika project memakai Spatie `HasTranslations`, field berikut harus translatable:

```text
nama_skema
deskripsi
persyaratan
```

#### Unique slug skema

Slug skema jangan unique global.

Gunakan unique per program:

```text
unique(program_id, slug)
```

Alasan:

```text
Program A bisa punya skema reguler
Program B juga bisa punya skema reguler
```

Jadi slug `reguler` boleh sama selama `program_id` berbeda.

---

### 4.4 Update Tabel `applicants`

Tambahkan field nullable:

```text
schema_id nullable
```

Relasi:

```text
Applicant belongsTo ProgramSchema
ProgramSchema hasMany Applicants
```

Pada phase ini, `schema_id` hanya untuk kompatibilitas awal. Jika user memilih skema, simpan `schema_id`. Jika tidak ada skema, `schema_id = null`.

Jangan mengubah seluruh sistem form menjadi Dynamic Form Builder pada phase ini.

---

## 5. Migration Strategy

Karena project sudah memiliki database existing, migration harus aman.

AI agent wajib:

- Mengecek kolom sebelum menambahkan kolom.
- Tidak menghapus data existing.
- Tidak drop tabel existing.
- Tidak hard delete data.
- Tidak mengubah nama kolom existing tanpa alasan kuat.
- Menggunakan migration tambahan, bukan rewrite migration lama.

### Prinsip migration aman

Contoh:

```php
if (!Schema::hasColumn('programs', 'sort_order')) {
    $table->integer('sort_order')->default(0);
}
```

### Migration yang dibutuhkan

```text
1. add_sort_order_has_schema_soft_deletes_to_programs_table
2. add_soft_deletes_and_status_diperpanjang_to_batches_table
3. create_program_schemas_table
4. add_schema_id_to_applicants_table
```

### Foreign key strategy

- `program_schemas.program_id` mengarah ke `programs.id`.
- `program_schemas.batch_id` nullable mengarah ke `batches.id`.
- `applicants.schema_id` nullable mengarah ke `program_schemas.id`.

Karena data memakai SoftDeletes, jangan gunakan hard cascade yang berisiko menghapus data histori. Gunakan nullable atau restrict sesuai kondisi project.

---

## 6. Delete Strategy

Gunakan:

```text
Soft Deletes
```

Untuk:

```text
programs
batches
program_schemas
```

### Alasan

Data program, batch, dan schema kemungkinan terhubung dengan:

```text
applicants
applicant_documents
laporan admin
export data
history pendaftaran
```

Jadi jangan hapus permanen.

### Aturan

```text
Admin delete = soft delete / archive
Restore boleh disediakan jika pola admin existing mendukung
Hard delete tidak perlu dibuat pada phase ini
```

---

## 7. Model Design

### 7.1 Model `Program`

Update model `Program`.

Gunakan:

```php
use Illuminate\Database\Eloquent\SoftDeletes;
```

Relasi wajib:

```text
batches()
activeBatches()
programSchemas()
activeSchemas()
applicants()
```

Scope wajib:

```text
scopeActive()
scopeFeatured()
scopeOrdered()
```

Accessor/helper yang disarankan:

```text
currentOpenBatch()
latestAvailableBatch()
hasActiveSchemas()
registrationStatusLabel()
registrationStatusClass()
isRegistrationOpen()
```

#### Logic `hasActiveSchemas`

```text
IF has_schema == true AND activeSchemas count > 0:
    true
ELSE:
    false
```

---

### 7.2 Model `Batch`

Gunakan:

```php
use Illuminate\Database\Eloquent\SoftDeletes;
```

Relasi:

```text
program()
programSchemas()
applicants()
```

Helper:

```text
isOpen()
isExtended()
isUpcoming()
isClosed()
isRegistrationEnabled()
frontendStatusLabel()
frontendStatusClass()
ctaLabel()
ctaUrl()
```

#### Registration enabled logic

```text
dibuka       -> true
diperpanjang -> true
akan_dibuka  -> false
ditutup      -> false
berjalan     -> false
selesai      -> false
```

---

### 7.3 Model `ProgramSchema`

Buat model baru:

```text
app/Models/ProgramSchema.php
```

Gunakan:

```php
use Illuminate\Database\Eloquent\SoftDeletes;
```

Jika project memakai Spatie:

```php
use Spatie\Translatable\HasTranslations;
```

Relasi:

```text
program()
batch()
applicants()
```

Scope:

```text
scopeActive()
scopeOrdered()
scopeByType($type)
```

Helper:

```text
isActive()
isScholarship()
formattedPrice()
```

---

### 7.4 Model `Applicant`

Update model:

```text
fillable tambah schema_id
casts tetap existing
```

Relasi:

```text
programSchema()
```

---

## 8. Service Layer

Buat atau update service agar controller tetap tipis.

### 8.1 `ProgramService`

Tanggung jawab:

- Mengambil featured programs untuk homepage.
- Mengambil semua program aktif.
- Mengambil program detail berdasarkan slug.
- Menentukan current/open batch.
- Menentukan active schemas.
- Menentukan CTA frontend.
- Menyiapkan data siap render untuk Blade.

Method disarankan:

```php
getFeaturedPrograms()
getAllActivePrograms()
getProgramDetailBySlug(string $slug)
prepareProgramCardData($program)
prepareProgramDetailData($program)
```

---

### 8.2 `BatchStatusService`

Tanggung jawab:

- Mapping status batch ke label frontend.
- Mapping status batch ke class badge.
- Menentukan apakah tombol daftar aktif.
- Menentukan CTA type.

Method disarankan:

```php
getFrontendLabel(string $status)
getBadgeClass(string $status)
isRegistrationEnabled(string $status)
getCtaConfig(Batch $batch)
```

---

### 8.3 `ProgramSchemaService`

Tanggung jawab:

- Mengelola schema aktif.
- Menyiapkan pilihan schema untuk frontend.
- Menentukan apakah flow schema atau flow normal yang digunakan.

Method disarankan:

```php
getActiveSchemasByProgram(Program $program)
shouldShowSchemaSelector(Program $program)
prepareSchemaOptions(Program $program)
```

---

## 9. Admin Management Design

### 9.1 Admin Program

Admin dapat:

- Create program
- Edit program
- Soft delete program
- Restore program jika pola existing mendukung
- Toggle aktif/nonaktif
- Toggle featured
- Atur `sort_order`
- Upload thumbnail
- Upload brosur
- Atur `has_schema`
- Kelola multilingual content

Form Program wajib mendukung:

```text
nama_program
slug
deskripsi
focus
output
target_peserta
durasi
benefit
alur_seleksi
biaya
faq
materi
thumbnail_path
brosur
video_url
status
is_featured
sort_order
has_schema
```

#### Slug behavior di admin

Saat create:

```text
IF slug kosong:
    generate dari nama program
ELSE:
    gunakan slug input admin
```

Saat update:

```text
IF slug tidak diubah:
    pertahankan slug lama
ELSE:
    validasi unique lalu update slug
```

---

### 9.2 Admin Batch

Admin dapat:

- Create batch
- Edit batch
- Soft delete batch
- Restore batch jika pola existing mendukung
- Ubah status batch
- Atur tanggal buka
- Atur tanggal tutup
- Atur tanggal mulai
- Atur tanggal selesai
- Atur kuota
- Atur CTA type
- Atur WhatsApp link

Status yang harus tersedia di form:

```text
dibuka
diperpanjang
akan_dibuka
ditutup
berjalan
selesai
```

CTA type yang tersedia:

```text
internal_form
whatsapp
disabled
```

---

### 9.3 Admin Program Schema

Buat CRUD baru untuk Program Schema.

Admin dapat:

- Create schema
- Edit schema
- Soft delete schema
- Restore schema jika pola existing mendukung
- Toggle aktif/nonaktif
- Set tipe schema
- Set harga
- Set `sort_order`
- Assign ke program
- Assign ke batch optional

Field form Program Schema:

```text
program_id
batch_id nullable
nama_skema
slug
tipe
deskripsi
persyaratan
harga
status
sort_order
```

#### Aturan batch_id

```text
batch_id boleh kosong
```

Jika kosong:

```text
Schema berlaku umum untuk program tersebut
```

Jika diisi:

```text
Schema hanya berlaku untuk batch tertentu
```

---

## 10. Frontend Design

### 10.1 Homepage Program Slider

Requirement client:

```text
Program ditampilkan dalam bentuk horizontal slider / scroll
Geser per item program
```

Implementasi:

- Horizontal slider
- Swipe mobile
- Arrow navigation
- Responsive desktop/tablet/mobile
- Lazy image loading
- Render dari database
- Tidak ada query di Blade

Program card menampilkan:

```text
thumbnail
nama program
ringkasan deskripsi
badge status pendaftaran
CTA Detail
```

#### Badge status pendaftaran

Badge diambil dari batch paling relevan.

Prioritas batch:

```text
1. batch dibuka
2. batch diperpanjang
3. batch akan_dibuka
4. batch berjalan
5. batch selesai
6. batch ditutup
```

Jika tidak ada batch:

```text
Pendaftaran Ditutup
```

---

### 10.2 Program Detail Page

Saat user klik program, tampilkan:

```text
judul program
thumbnail / visual
deskripsi
focus
output
target peserta
durasi
benefit
alur seleksi
biaya
materi
faq jika ada
brosur jika ada
video jika ada
daftar batch
status batch
CTA daftar
schema selector jika berlaku
```

---

## 11. Program Schema Frontend Logic

Gunakan field:

```text
has_schema
```

Logic final:

```text
IF program.has_schema == true AND activeSchemas count > 0:
    tampilkan pilihan skema

ELSE:
    tampilkan flow program normal
```

Jangan lakukan:

```text
if program.slug == 'engineering'
if program.nama_program == 'Engineering'
```

Schema selector menampilkan:

```text
nama skema
tipe
deskripsi
persyaratan
harga
batch terkait jika ada
CTA pilih skema
```

Jika program tidak punya schema aktif:

```text
Detail Program -> Pilih Batch -> Daftar
```

---

## 12. Registration Flow di Phase Ini

Phase ini belum membuat Dynamic Form Builder.

### Program tanpa schema

```text
User buka program
User lihat detail
User lihat batch
User klik daftar
User masuk ke form existing
Submit
schema_id = null
```

### Program dengan schema aktif

```text
User buka program
User lihat detail
User pilih schema
User lihat detail schema
User pilih batch
User klik daftar
User masuk ke form existing
Submit
schema_id = id schema yang dipilih
```

Jika form existing belum siap menerima `schema_id`, lakukan minimal:

```text
- tambahkan hidden input schema_id
- validasi nullable
- simpan schema_id jika ada
```

Jangan mengubah seluruh sistem form menjadi Dynamic Form Builder dulu.

---

## 13. Beasiswa Validation

Karena skema memiliki tipe:

```text
beasiswa
```

Maka perlu disiapkan logic minimal.

Pada phase ini cukup:

```text
- ProgramSchema memiliki tipe beasiswa
- Tampilkan badge Beasiswa
- Simpan tipe schema
- Siapkan method isScholarship()
```

Validasi penuh kampus mitra dikerjakan pada phase berikutnya, karena berkaitan dengan Dynamic Form dan Scholarship Validation.

---

## 14. Route Design

### Frontend routes

Gunakan route existing jika sudah ada.

Minimal:

```text
GET /programs
GET /program/{slug}
POST /pendaftaran
```

Jika perlu schema route:

```text
GET /program/{program:slug}/schema/{schema:slug}
```

Namun route schema tidak wajib jika UI bisa handle schema selector di halaman detail.

### Admin routes

Tambahkan resource route:

```php
Route::resource('program-schemas', ProgramSchemaController::class);
```

Jika admin route memakai prefix:

```text
/admin/program-schemas
```

Gunakan middleware:

```text
auth
admin
```

---

## 15. Controller Design

### Frontend controller

Controller sebaiknya hanya memanggil service.

Contoh controller:

```text
HomeController@index
ProgramController@index
ProgramController@show
PendaftaranController@store
```

Controller tidak boleh berisi:

```text
- status mapping panjang
- query kompleks berulang
- business logic schema
- parsing konten
```

### Admin controller

Controller admin:

```text
Admin\ProgramController
Admin\BatchController
Admin\ProgramSchemaController
```

Gunakan:

```text
FormRequest
Service jika logic mulai panjang
```

---

## 16. Form Request Validation

### ProgramRequest

Validasi minimal:

```text
nama_program required
slug nullable unique
status required in:aktif,nonaktif
is_featured boolean
sort_order integer
has_schema boolean
thumbnail image nullable
brosur file nullable
```

### BatchRequest

Validasi minimal:

```text
program_id required exists:programs,id
nama_batch required
tanggal_buka nullable date
tanggal_tutup nullable date after_or_equal:tanggal_buka
tanggal_mulai nullable date
tanggal_selesai nullable date after_or_equal:tanggal_mulai
kuota nullable integer min:0
status required in:dibuka,diperpanjang,akan_dibuka,ditutup,berjalan,selesai
cta_type nullable in:internal_form,whatsapp,disabled
whatsapp_link nullable url
```

### ProgramSchemaRequest

Validasi minimal:

```text
program_id required exists:programs,id
batch_id nullable exists:batches,id
nama_skema required
slug nullable
tipe required in:beasiswa,scholar_partnership,reguler
deskripsi nullable
persyaratan nullable
harga nullable numeric min:0
status required in:aktif,nonaktif
sort_order nullable integer
```

Unique slug:

```text
unique per program_id + slug
```

---

## 17. Query Strategy

Gunakan eager loading.

### Homepage

```php
Program::active()
    ->featured()
    ->ordered()
    ->with(['activeBatches', 'activeSchemas'])
    ->get();
```

### Program detail

```php
Program::active()
    ->with([
        'batches',
        'activeBatches',
        'activeSchemas',
    ])
    ->where('slug', $slug)
    ->firstOrFail();
```

### Admin program index

```php
Program::withCount(['batches', 'programSchemas'])
    ->ordered()
    ->paginate(10);
```

### Dilarang

```text
- Program::where() di Blade
- Batch::where() di Blade
- ProgramSchema::where() di Blade
- foreach lalu query di dalam loop
```

---

## 18. Component / Blade Design

### Component frontend yang disarankan

```text
components/program/card.blade.php
components/program/status-badge.blade.php
components/program/cta-button.blade.php
components/program/batch-list.blade.php
components/program/schema-selector.blade.php
components/program/schema-card.blade.php
```

### Page yang memakai component

```text
sections/program.blade.php
program-detail.blade.php
programs/index.blade.php
```

### Prinsip Blade

Blade hanya render data.

Tidak boleh:

```text
- query database
- mapping status panjang
- if slug engineering
- parsing field JSON/manual terlalu berat
```

---

## 19. Cache Strategy

Jika sebelumnya project sudah memakai cache settings, lanjutkan pola itu.

Untuk program:

```text
Optional cache
```

Boleh ditambahkan nanti, tapi jangan terlalu kompleks di awal.

Jika memakai cache, invalidate saat:

```text
program created
program updated
program deleted
batch created
batch updated
batch deleted
program schema created
program schema updated
program schema deleted
```

---

## 20. Testing Checklist

AI agent wajib menguji minimal:

### Program

- Create program baru.
- Edit nama program tanpa mengubah slug.
- Edit slug manual.
- Nonaktifkan program.
- Soft delete program.
- Featured program tampil di homepage.
- Sort order mempengaruhi urutan tampil.

### Batch

- Create batch.
- Edit batch.
- Soft delete batch.
- Status `dibuka` membuat tombol daftar aktif.
- Status `diperpanjang` membuat tombol daftar aktif.
- Status `akan_dibuka` membuat tombol disabled.
- Status `ditutup` membuat tombol disabled.
- Status `berjalan` membuat tombol disabled.
- Status `selesai` membuat tombol disabled.

### Program Schema

- Create schema.
- Edit schema.
- Soft delete schema.
- Schema aktif tampil.
- Schema nonaktif tidak tampil.
- Schema `sort_order` bekerja.
- Schema dengan `batch_id` kosong tampil sebagai schema umum.
- Schema dengan `batch_id` tertentu hanya tampil sesuai konteks batch.

### Flow Frontend

- Program tanpa schema -> flow normal.
- Program `has_schema = true` tapi tidak ada schema aktif -> flow normal.
- Program `has_schema = true` dan ada schema aktif -> tampil schema selector.
- User pilih schema -> `schema_id` tersimpan saat pendaftaran.

### Security

- Tidak ada hard delete.
- Tidak ada query di Blade.
- Tidak ada lazy loading dalam loop.
- FormRequest berjalan.
- Upload thumbnail aman.
- Slug unique valid.

---

## 21. Batasan Implementasi

AI agent tidak boleh mengerjakan ini pada phase sekarang:

```text
Dynamic Form Builder penuh
Field upload dynamic
Form field admin builder
Partnership CMS
Alur Pendaftaran CMS
Contact CMS
Payment menu
Maps
```

Jika AI agent menemukan kebutuhan terkait itu, cukup buat catatan untuk phase berikutnya.

---

## 22. Implementation Order Final

Kerjakan berurutan.

### Step 1 — Migration

- Update `programs`.
- Update `batches`.
- Create `program_schemas`.
- Update `applicants` dengan `schema_id`.

### Step 2 — Model

- Update `Program`.
- Update `Batch`.
- Create `ProgramSchema`.
- Update `Applicant`.

### Step 3 — FormRequest

- Update `ProgramRequest`.
- Update `BatchRequest`.
- Create `ProgramSchemaRequest`.

### Step 4 — Admin CRUD

- Update Program CRUD.
- Update Batch CRUD.
- Create ProgramSchema CRUD.

### Step 5 — Service Layer

- ProgramService.
- BatchStatusService.
- ProgramSchemaService.

### Step 6 — Frontend Homepage

- Update program slider.
- Dynamic badge.
- Lazy loading.
- Responsive swipe.

### Step 7 — Frontend Detail

- Update program detail.
- Batch list.
- CTA logic.
- Schema selector.

### Step 8 — Registration Compatibility

- Tambahkan `schema_id` nullable.
- Hidden input `schema_id` jika ada.
- Simpan `schema_id` saat submit.
- Jangan ubah dynamic form dulu.

### Step 9 — QA

- Test program.
- Test batch.
- Test schema.
- Test frontend.
- Test admin.
- Test no query in Blade.

---

## 23. Prompt Implementasi Siap Tempel ke AI Agent

Gunakan prompt berikut untuk implementasi:

```text
Implementasikan Program Management System berdasarkan FINAL IMPLEMENTATION PLAN berikut.

PROJECT:
Laravel + Blade + Tailwind + MySQL

FOKUS:
Bagian 3. Tampilan Program
Bagian 4. Manajemen Program

JANGAN mengerjakan:
- Dynamic Form Builder penuh
- Partnership CMS
- Alur Pendaftaran CMS
- Contact CMS
- Payment
- Maps

TUJUAN:
Membuat sistem program yang fully dynamic, bisa dikelola admin, memiliki batch, status pendaftaran, optional schema, dan tampilan program horizontal slider.

WAJIB IKUTI ATURAN:
1. Jangan hardcode nama program.
2. Jangan hardcode slug program.
3. Jangan query database di Blade.
4. Jangan lazy loading di loop.
5. Gunakan eager loading.
6. Gunakan SoftDeletes.
7. Gunakan FormRequest.
8. Gunakan service/helper/accessor untuk business logic.
9. Jangan hard delete data penting.
10. Jangan implement dynamic form builder dulu.

DATABASE:
1. Update tabel programs:
- pastikan ada sort_order integer default 0
- pastikan ada has_schema boolean default false
- pastikan ada deleted_at nullable
- gunakan migration aman dengan Schema::hasColumn

2. Update tabel batches:
- pastikan mendukung status:
  dibuka, diperpanjang, akan_dibuka, ditutup, berjalan, selesai
- pastikan ada deleted_at nullable
- pastikan field CTA tetap:
  cta_type, whatsapp_link

3. Buat tabel program_schemas:
- id
- program_id foreign key
- batch_id nullable foreign key
- nama_skema json/translatable
- slug string
- tipe enum/string: beasiswa, scholar_partnership, reguler
- deskripsi json/translatable nullable
- persyaratan json/translatable nullable
- harga decimal nullable/default 0
- status enum/string: aktif, nonaktif
- sort_order integer default 0
- created_at
- updated_at
- deleted_at
- unique(program_id, slug)
- index program_id, batch_id, status, tipe, sort_order

4. Update applicants:
- tambah schema_id nullable foreign key ke program_schemas

MODEL:
1. Update Program:
- gunakan SoftDeletes
- casts has_schema dan is_featured ke boolean
- relation batches()
- relation activeBatches()
- relation programSchemas()
- relation activeSchemas()
- relation applicants()
- scopeActive()
- scopeFeatured()
- scopeOrdered()
- helper currentOpenBatch()
- helper hasActiveSchemas()

2. Update Batch:
- gunakan SoftDeletes
- belongsTo Program
- hasMany ProgramSchema jika diperlukan
- helper isRegistrationEnabled()
- helper frontendStatusLabel()
- helper frontendStatusClass()
- helper ctaLabel()
- helper ctaUrl()

3. Buat ProgramSchema:
- gunakan SoftDeletes
- gunakan HasTranslations jika project existing memakai Spatie
- translatable: nama_skema, deskripsi, persyaratan
- belongsTo Program
- belongsTo Batch nullable
- hasMany Applicants
- scopeActive()
- scopeOrdered()
- scopeByType()
- helper isScholarship()
- helper formattedPrice()

4. Update Applicant:
- tambah schema_id ke fillable
- relation programSchema()

ADMIN:
1. Update Program CRUD:
- create/edit/delete soft delete
- restore jika pola existing mendukung
- status aktif/nonaktif
- featured toggle
- sort_order
- has_schema
- upload thumbnail
- immutable slug

Slug rules:
- create: generate slug dari nama jika slug kosong
- update: jangan ubah slug ketika nama berubah
- update slug hanya jika admin edit slug manual
- validasi unique slug

2. Update Batch CRUD:
- status lengkap:
  dibuka, diperpanjang, akan_dibuka, ditutup, berjalan, selesai
- cta_type:
  internal_form, whatsapp, disabled
- whatsapp_link nullable
- tanggal buka/tutup/mulai/selesai
- kuota

3. Buat ProgramSchema CRUD:
- create/edit/soft delete
- status aktif/nonaktif
- tipe beasiswa/scholar_partnership/reguler
- harga
- sort_order
- assign program
- assign batch optional
- unique slug per program

FRONTEND:
1. Homepage Program Slider:
- horizontal slider
- geser per item
- swipe mobile
- arrow navigation
- responsive desktop/tablet/mobile
- lazy image loading
- render dari database
- jangan query di Blade

Program card:
- thumbnail
- nama program
- ringkasan deskripsi
- badge status batch
- tombol Detail

2. Program Detail:
- tampil detail program
- tampil persyaratan
- tampil materi
- tampil biaya
- tampil alur
- tampil batch
- tampil status batch
- tampil CTA daftar sesuai status
- tampil schema selector jika program.has_schema true dan active schema > 0

CTA logic:
- dibuka -> daftar aktif
- diperpanjang -> daftar aktif
- akan_dibuka -> disabled
- ditutup -> disabled
- berjalan -> disabled
- selesai -> disabled

Schema logic:
IF program.has_schema == true AND activeSchemas count > 0:
    tampilkan schema selector
ELSE:
    flow normal

Jangan hardcode:
- slug Engineering
- nama Engineering
- nama schema

REGISTRATION COMPATIBILITY:
- tambahkan schema_id nullable ke request pendaftaran
- jika user memilih schema, simpan schema_id
- jika tidak ada schema, schema_id null
- jangan implement dynamic form builder penuh

QUERY STRATEGY:
Gunakan eager loading:
- activeBatches
- activeSchemas
- currentOpenBatch jika memungkinkan

Dilarang:
- query di Blade
- lazy loading dalam loop
- hardcoded program name
- hardcoded program slug

TESTING:
Lakukan test:
1. Create/edit/delete program
2. Edit nama program tidak mengubah slug
3. Edit slug manual berjalan dan unique
4. Featured program tampil di homepage
5. Sort order berjalan
6. Batch dibuka tombol aktif
7. Batch diperpanjang tombol aktif
8. Batch akan_dibuka disabled
9. Batch ditutup disabled
10. Schema aktif tampil
11. Schema nonaktif tidak tampil
12. Program has_schema false flow normal
13. Program has_schema true tanpa schema aktif flow normal
14. Program has_schema true dengan schema aktif tampil selector
15. schema_id tersimpan saat pendaftaran
16. tidak ada query database di Blade
17. tidak ada hard delete data penting

OUTPUT AKHIR:
Berikan:
- daftar file yang dibuat
- daftar file yang diubah
- daftar migration
- daftar route baru
- hasil testing
- catatan issue jika ada
```

---

## 24. Acceptance Criteria

Implementasi dianggap selesai jika:

1. Program bisa dikelola admin secara dynamic.
2. Program tampil di homepage sebagai horizontal slider.
3. Program card tidak hardcoded.
4. Program detail mengambil data dari database.
5. Batch bisa dikelola admin.
6. Status batch mempengaruhi CTA.
7. Program schema bisa dikelola admin.
8. Schema hanya tampil jika aktif.
9. Program tanpa schema tetap memakai flow normal.
10. Program dengan schema aktif menampilkan schema selector.
11. `schema_id` bisa tersimpan pada pendaftaran.
12. Tidak ada query database di Blade.
13. Tidak ada hard delete data penting.
14. Slug program tidak berubah otomatis saat nama program diedit.
15. Semua perubahan tidak merusak Multi-Bahasa dan Hero Section.

---

## 25. Setelah Implementasi Selesai

Setelah AI agent selesai mengimplementasikan phase ini, jalankan audit lanjutan dengan fokus:

```text
Program Management System QA
Batch Status Logic QA
Program Schema Logic QA
Frontend Slider QA
Admin CRUD QA
No Query in Blade QA
Registration Compatibility QA
```

Jangan masuk ke Dynamic Form Builder sebelum hasil audit phase ini dinyatakan aman.
