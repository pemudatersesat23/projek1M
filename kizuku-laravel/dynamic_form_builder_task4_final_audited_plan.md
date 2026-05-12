# FINAL PLAN — Dynamic Form Builder LPK Kizuku

# FINAL AUDIT PATCH — Sebelum Implementasi Task 4

## Status Audit

Dokumen ini sudah diaudit ulang sebelum masuk implementasi.

Verdict:

```text
READY FOR STAGED IMPLEMENTATION
```

Namun implementasi **tidak boleh langsung Task 4B–4E sekaligus**.

Urutan wajib:

```text
1. Task 4B Implementation — Migration & Models
2. Audit Task 4B
3. Task 4C Implementation — Admin Form Builder CRUD
4. Audit Task 4C
5. Task 4D Implementation — Frontend Dynamic Form Renderer
6. Audit Task 4D
7. Task 4E Implementation — Submit Handling, Validation, Upload, Download, Admin Applicant Detail
8. Audit Task 4E
9. End-to-End QA
```

---

## Koreksi dan Penegasan Final

### 1. Task 4 tetap menggunakan staged implementation

Walaupun dokumen ini adalah plan lengkap Task 4A–4E, implementasi pertama yang boleh dijalankan adalah:

```text
Task 4B — Migration & Models
```

Jangan langsung mengerjakan:

```text
Task 4C
Task 4D
Task 4E
```

sebelum Task 4B selesai dan diaudit.

---

### 2. Dynamic Form hanya untuk custom/additional fields

Fixed field existing tetap dipertahankan.

Fixed field seperti:

```text
nama
email
phone
tanggal_lahir
alamat
pendidikan
program_id
batch_id
schema_id
```

tetap dikelola oleh flow pendaftaran existing.

Dynamic Form Builder hanya mengelola:

```text
additional/custom fields
field khusus program
field khusus schema
field upload dokumen tambahan
```

---

### 3. Integrasi Beasiswa dan Kampus Mitra

Requirement client menyebut bahwa untuk skema Beasiswa, user wajib memilih kampus mitra.

Namun karena Partnership/Kampus Mitra CMS belum menjadi bagian Task 4, maka aturan finalnya:

```text
Task 4 tidak membuat Partnership CMS.
Task 4 hanya menyiapkan hook/compatibility agar validasi kampus mitra bisa ditambahkan.
```

Jika di project sudah ada tabel/model kampus mitra, maka pada Task 4E boleh ditambahkan validasi minimal:

```text
IF schema.tipe == beasiswa:
    field kampus_mitra_id wajib ada
    kampus_mitra_id harus valid di tabel kampus mitra
```

Jika belum ada tabel/model kampus mitra, jangan hardcode daftar kampus. Buat catatan untuk phase Partnership CMS.

---

### 4. Private storage harus menggunakan path relatif Storage

Simpan `file_path` sebagai path relatif, contoh:

```text
registrations/{applicant_id}/dynamic_{field_id}_{hash}.pdf
```

Gunakan:

```php
Storage::disk('local')->putFileAs(...)
Storage::disk('local')->download($file->file_path)
Storage::disk('local')->exists($file->file_path)
```

Jangan simpan full path seperti:

```text
storage/app/private/...
```

di database.

Jika ingin folder private, gunakan path relatif:

```text
private/registrations/{applicant_id}/...
```

tetapi tetap lewat `Storage::disk('local')`.

---

### 5. Foreign key strategy untuk jawaban dan file

Karena `form_fields` memakai SoftDeletes, data answer/file lama harus tetap aman.

Rekomendasi:

```text
applicant_form_answers.form_field_id -> constrained form_fields
applicant_dynamic_files.form_field_id -> constrained form_fields
```

Jangan gunakan cascade delete dari `form_fields` ke answers/files.

Jika perlu, gunakan:

```text
restrictOnDelete
```

atau biarkan tanpa cascade berbahaya.

Untuk applicant, boleh cascade jika memang applicant dihapus permanen. Namun karena applicant biasanya histori penting, lebih aman sistem applicant juga tidak di-hard-delete.

---

### 6. Field name uniqueness wajib di FormFieldRequest

Karena `schema_id` nullable, jangan hanya mengandalkan unique constraint database.

FormFieldRequest wajib melakukan validasi:

```text
Field umum:
program_id + schema_id null + field_name harus unique

Field schema:
program_id + schema_id + field_name harus unique
DAN tidak boleh bentrok dengan field umum program
```

---

### 7. Options multilingual harus memakai value stabil

Untuk select/radio/checkbox, simpan format:

```json
[
  {
    "value": "n4",
    "label": {
      "id": "N4",
      "jp": "N4"
    }
  }
]
```

Server validation memakai `value`.

Frontend menampilkan `label` sesuai locale aktif.

Pastikan key locale mengikuti project existing:

```text
id/jp
atau
id/ja
```

Jangan campur `jp` dan `ja` dalam satu project.

---

### 8. Upload validation berlapis

Task 4E wajib melakukan validasi file berlapis:

```text
1. Laravel file validation
2. allowed extension dari field config
3. blocked extension global
4. MIME type check
5. max file size
6. private storage only
```

Ekstensi berikut wajib diblokir:

```text
php
phtml
exe
sh
bat
js
html
svg
```

---

### 9. Admin Applicant Detail wajib masuk Task 4E

Dynamic form belum dianggap selesai jika admin belum bisa melihat hasilnya.

Task 4E wajib memastikan admin bisa melihat:

```text
fixed applicant data
additional_data lama jika ada
dynamic answers
dynamic files
snapshot label
download file protected
```

---

### 10. Tidak boleh query database di Blade

Aturan ini berlaku untuk:

```text
admin form builder
frontend form renderer
admin applicant detail
```

Semua data harus disiapkan controller/service.

---

# Final Decision

Dokumen ini **sudah layak untuk menjadi dasar implementasi Task 4**, dengan catatan implementasi dilakukan bertahap.

Langkah pertama setelah dokumen ini:

```text
Jalankan Task 4B Implementation — Migration & Models
```


---

## Status Dokumen

Dokumen ini menggabungkan:

1. **Task 4A — System Design Dynamic Form Builder**
2. **Task 4B — Planning Migration & Model Architecture**
3. **Task 4C — Planning Admin Form Builder CRUD**
4. **Task 4D — Planning Frontend Dynamic Form Renderer**
5. **Task 4E — Planning Dynamic Submit Handling, Validation, Upload, Storage, Download, and Admin Applicant Detail**

Dokumen ini **belum masuk ke tahap coding**. Tujuannya adalah menjadi cetak biru teknis sebelum AI agent melakukan implementasi database, model, admin UI, frontend renderer, dynamic validation, submit handling, upload handler, protected download route, dan admin applicant detail.

---

# 0. Konteks Project Saat Ini

Task sebelumnya sudah selesai:

- Task 1: Database, Model, FormRequest untuk Program, Batch, ProgramSchema
- Task 2: Admin CRUD Program, Batch, ProgramSchema
- Task 3: Frontend Program Slider, Detail Program, Batch CTA, Schema Selector
- Fix tambahan: `schema_id` pada pendaftaran sudah divalidasi agar hanya menerima schema yang:
  - milik program terkait
  - berstatus aktif
  - belum soft delete

Setelah fondasi Program Management selesai, fitur berikutnya adalah **Dynamic Form Builder**, yaitu sistem agar admin bisa mengatur field formulir dari dashboard tanpa perubahan kode.

---

# 1. Scope Dynamic Form Builder

## Dikerjakan dalam fase Dynamic Form Builder

- Admin dapat membuat field formulir berdasarkan program
- Admin dapat membuat field formulir berdasarkan program + schema
- Admin dapat mengatur label, placeholder, deskripsi, tipe field, required, status, dan urutan field
- Admin dapat membuat field upload dokumen
- Admin dapat mengatur format file dan ukuran maksimal upload
- User melihat form dynamic berdasarkan program/schema yang dipilih
- Jawaban dynamic tersimpan ke database relasional
- File upload dynamic tersimpan di private storage
- Admin dapat melihat jawaban dynamic di detail applicant
- Admin dapat mengunduh file dynamic melalui route protected

## Tidak dikerjakan dulu sebelum design ini disetujui

- Implementasi migration
- Implementasi model
- Implementasi admin CRUD Form Builder
- Implementasi frontend renderer
- Implementasi dynamic validation
- Implementasi upload handler
- Implementasi download controller
- Implementasi Partnership CMS
- Implementasi Alur Pendaftaran CMS
- Implementasi Contact CMS

---

# 2. Task 4A — System Design Dynamic Form Builder

## 2.1 Tujuan Task 4A

Task 4A adalah tahap perancangan sistem. Outputnya adalah rancangan database, relasi, flow admin, flow frontend, validasi, upload security, risk analysis, dan acceptance criteria.

Task 4A **bukan tahap coding**.

---

## 2.2 Database Design Final

Dynamic Form Builder membutuhkan tiga tabel utama:

1. `form_fields`
2. `applicant_form_answers`
3. `applicant_dynamic_files`

---

## 2.3 Tabel `form_fields`

### Fungsi

Menyimpan definisi field form yang dibuat oleh admin.

### Struktur final

| Field | Keterangan |
|---|---|
| `id` | Primary key |
| `program_id` | FK ke `programs.id`, wajib |
| `schema_id` | FK nullable ke `program_schemas.id` |
| `label` | JSON, translatable |
| `field_name` | Slug internal input, contoh `asal_sekolah` |
| `type` | text, textarea, number, email, phone, date, select, radio, checkbox, file |
| `placeholder` | JSON nullable, translatable |
| `description` | JSON nullable, translatable |
| `options` | JSON nullable untuk select/radio/checkbox |
| `accepted_file_types` | JSON nullable untuk file upload |
| `max_file_size` | Integer nullable, ukuran KB |
| `is_required` | Boolean default false |
| `status` | aktif / nonaktif |
| `sort_order` | Integer default 0 |
| `created_at` | Timestamp |
| `updated_at` | Timestamp |
| `deleted_at` | Soft delete |

### Catatan penting

- `program_id` wajib.
- `schema_id` boleh kosong.
- Jika `schema_id` kosong, field berlaku sebagai field umum program.
- Jika `schema_id` diisi, field berlaku sebagai field khusus schema.
- `label`, `placeholder`, dan `description` mengikuti sistem multilingual existing.

---

## 2.4 Tabel `applicant_form_answers`

### Fungsi

Menyimpan jawaban field dynamic selain file.

### Struktur final

| Field | Keterangan |
|---|---|
| `id` | Primary key |
| `applicant_id` | FK ke `applicants.id` |
| `form_field_id` | FK ke `form_fields.id` |
| `value` | JSON nullable, bisa string atau array checkbox |
| `field_label_snapshot` | JSON nullable, snapshot label saat submit |
| `field_type_snapshot` | String nullable, snapshot type saat submit |
| `created_at` | Timestamp |
| `updated_at` | Timestamp |

### Kenapa perlu snapshot?

Agar jawaban applicant lama tetap dapat dibaca sesuai label field saat user submit, meskipun admin mengubah label field di masa depan.

Contoh:

- Saat submit: label = “Upload CV”
- Setelah itu admin mengubah label menjadi “Upload Paspor”
- Data applicant lama tetap menampilkan label snapshot “Upload CV”

---

## 2.5 Tabel `applicant_dynamic_files`

### Fungsi

Menyimpan metadata file upload dynamic.

### Struktur final

| Field | Keterangan |
|---|---|
| `id` | Primary key |
| `applicant_id` | FK ke `applicants.id` |
| `form_field_id` | FK ke `form_fields.id` |
| `file_path` | Path file di private storage |
| `original_name` | Nama asli file yang sudah disanitasi |
| `mime_type` | MIME type file |
| `size` | Ukuran file dalam KB |
| `field_label_snapshot` | JSON nullable, snapshot label saat submit |
| `field_type_snapshot` | String nullable, biasanya `file` |
| `created_at` | Timestamp |
| `updated_at` | Timestamp |

### Catatan keamanan

File dokumen pendaftaran tidak boleh disimpan di public disk. File harus disimpan di private/local storage dan hanya dapat diunduh oleh admin melalui route protected.

---

# 3. Field Type Design

Sistem mendukung tipe field berikut:

| Type | Fungsi |
|---|---|
| `text` | Input teks pendek |
| `textarea` | Input teks panjang |
| `number` | Input angka |
| `email` | Input email |
| `phone` | Input nomor telepon |
| `date` | Input tanggal |
| `select` | Pilihan tunggal dropdown |
| `radio` | Pilihan tunggal radio |
| `checkbox` | Pilihan ganda |
| `file` | Upload file |

---

# 4. Multilingual Options Format

Untuk `select`, `radio`, dan `checkbox`, options menggunakan format `value + label multilingual`.

Contoh:

```json
[
  {
    "value": "n4",
    "label": {
      "id": "N4",
      "jp": "N4"
    }
  },
  {
    "value": "belum_pernah",
    "label": {
      "id": "Belum Pernah",
      "jp": "未経験"
    }
  }
]
```

## Catatan locale

Pastikan key bahasa mengikuti locale project existing. Jika project memakai `id` dan `jp`, gunakan `jp`. Jika project memakai `id` dan `ja`, gunakan `ja`.

## Prinsip validasi

- Database menyimpan `value`.
- Frontend menampilkan `label` sesuai locale aktif.
- Server-side validation hanya menerima `value` yang terdaftar di JSON options.

---

# 5. Form Resolution Logic

Dynamic form menggunakan sistem inheritance:

## Skenario 1 — Program tanpa schema

Ambil field:

- `program_id = selected program`
- `schema_id = null`
- `status = aktif`
- belum soft delete
- urut berdasarkan `sort_order`

## Skenario 2 — Program dengan schema

Ambil gabungan:

1. Field umum program:
   - `program_id = selected program`
   - `schema_id = null`
   - `status = aktif`

2. Field khusus schema:
   - `program_id = selected program`
   - `schema_id = selected schema`
   - `status = aktif`

Kemudian gabungkan dan urutkan berdasarkan `sort_order`.

## Alasan menggunakan inheritance

Admin tidak perlu membuat ulang field dasar seperti nama, email, dan WhatsApp di setiap schema. Admin cukup membuat field umum program, lalu menambahkan field khusus untuk schema tertentu.

---

# 6. Field Name Uniqueness

Agar input tidak bertabrakan, `field_name` harus diatur ketat.

## Aturan final

### Field umum program

`field_name` tidak boleh duplikat pada:

- program yang sama
- `schema_id = null`

### Field khusus schema

`field_name` tidak boleh duplikat pada:

- program yang sama
- schema yang sama

### Aturan tambahan

Field khusus schema juga **tidak boleh** menggunakan `field_name` yang sama dengan field umum program dalam program yang sama.

## Alasan

Karena form schema adalah gabungan field umum + field schema. Jika ada `field_name` sama, input HTML dan validasi akan bertabrakan.

---

# 7. Admin Flow Design

## Menu

Tambahkan menu admin:

```text
Form Builder
```

## Flow admin

1. Admin membuka Form Builder
2. Admin memilih Program
3. Admin memilih Schema optional
4. Sistem menampilkan daftar field
5. Admin dapat membuat field baru
6. Admin dapat mengedit field
7. Admin dapat menonaktifkan field
8. Admin dapat soft delete field
9. Admin dapat mengatur urutan field
10. Admin dapat preview form

## Form field admin harus mendukung

- Program select
- Schema select optional berdasarkan program
- Label multilingual
- Field name
- Type
- Placeholder multilingual
- Description multilingual
- Options editor multilingual untuk select/radio/checkbox
- Accepted file types untuk file
- Max file size untuk file
- Required toggle
- Status aktif/nonaktif
- Sort order

## Validasi schema di admin

Jika admin memilih `schema_id`, maka schema harus:

- milik program yang sama
- berstatus aktif
- belum soft delete

---

# 8. Frontend Flow Design

## Flow user

1. User membuka detail program
2. User memilih batch
3. Jika program memiliki schema, user memilih schema
4. User klik daftar
5. Sistem menampilkan fixed form existing + dynamic fields
6. User mengisi form
7. User upload dokumen jika ada field file
8. User submit
9. Data utama masuk ke `applicants`
10. Dynamic answers masuk ke `applicant_form_answers`
11. Dynamic files masuk ke private storage dan `applicant_dynamic_files`

## Fixed fields tetap dipertahankan

Field bawaan seperti nama, email, nomor HP, alamat, tanggal lahir, dan field inti existing tetap dipertahankan sebagai fixed fields.

Dynamic Form Builder dipakai untuk additional/custom fields.

---

# 9. Dynamic Validation Design

Validation dibuat secara dynamic berdasarkan data `form_fields`.

## Prinsip

- Field yang tidak terdaftar tidak boleh diterima.
- Field palsu hasil manipulasi inspect element harus ditolak.
- Options select/radio/checkbox harus divalidasi berdasarkan `value`.
- File harus divalidasi berdasarkan konfigurasi admin dan daftar blokir sistem.

## Rules umum

| Kondisi | Rule |
|---|---|
| `is_required = true` | required |
| `is_required = false` | nullable |
| type email | email |
| type number | numeric |
| type date | date |
| type select/radio | value harus ada di options |
| type checkbox | array, setiap item harus ada di options |
| type file | file, mimes, max |

## Validasi keamanan relasi

- `program_id` harus valid.
- `batch_id` harus milik program tersebut.
- `schema_id` jika ada harus milik program tersebut, aktif, dan belum soft delete.
- `form_field_id` yang diproses harus termasuk field aktif untuk program/schema tersebut.

---

# 10. File Upload Security Design

## Prinsip keamanan

- Gunakan Storage API.
- Jangan menggunakan `public_path()` dan `unlink()` manual.
- Jangan simpan file dokumen di public disk.
- Gunakan private/local storage.
- Gunakan nama file hash/unique.
- Simpan original name secara sanitized.
- Blokir ekstensi berbahaya secara sistem.

## Path file

Contoh path:

```text
storage/app/private/registrations/{applicant_id}/dynamic_{field_id}_{hash}
```

## Blocked extensions

Minimal blokir:

```text
php, phtml, exe, sh, bat, js, html, svg
```

## Protected download route

Rancang route:

```text
GET /dashboard-admin/applicants/{applicant}/dynamic-files/{file}/download
```

Middleware:

```text
auth
admin
```

## Download controller harus memastikan

- User adalah admin.
- File benar-benar milik applicant tersebut.
- File ada di storage.
- Tidak ada akses langsung via public URL.
- Tidak terjadi IDOR.

---

# 11. Model Relation Design

## Program

- hasMany FormField

## ProgramSchema

- hasMany FormField

## Applicant

- hasMany ApplicantFormAnswer
- hasMany ApplicantDynamicFile

## FormField

- belongsTo Program
- belongsTo ProgramSchema nullable
- hasMany ApplicantFormAnswer
- hasMany ApplicantDynamicFile

## ApplicantFormAnswer

- belongsTo Applicant
- belongsTo FormField

## ApplicantDynamicFile

- belongsTo Applicant
- belongsTo FormField

---

# 12. Service Architecture

## DynamicFormService

Tanggung jawab:

- Mengambil field berdasarkan program/schema
- Menggabungkan field umum dan field schema
- Menyiapkan field untuk render frontend
- Mengecek ownership field terhadap program/schema

Method yang dirancang:

```text
getFieldsFor(programId, schemaId = null)
prepareForFrontend(fields)
validateFieldOwnership(fields, programId, schemaId)
```

## DynamicValidationService

Tanggung jawab:

- Membuat rules Laravel dari konfigurasi field
- Menolak field palsu
- Memvalidasi choice value
- Memvalidasi checkbox array
- Memvalidasi file berdasarkan konfigurasi field

Method yang dirancang:

```text
buildRules(fields)
sanitizeAndValidate(request, fields)
validateUnknownFields(request, fields)
```

## DynamicFileUploadService

Tanggung jawab:

- Menyimpan file ke private storage
- Membuat nama file hash/unique
- Menyimpan metadata file
- Menghapus file jika transaksi gagal

Method yang dirancang:

```text
processUpload(file, applicant, formField)
storeFile(file, applicant, formField)
deletePhysicalFile(path)
```

---

# 13. Backward Compatibility

Project sudah punya data applicant lama. Karena itu:

- Jangan hapus `additional_data` lama.
- Jangan ubah fixed fields existing secara drastis.
- Applicant lama yang tidak punya dynamic answers tetap aman.
- Admin applicant detail harus punya empty state jika tidak ada dynamic answers.
- Fixed form existing tetap berjalan selama transisi.

---

# 14. Admin Applicant Detail Requirement

Setelah dynamic form berjalan, admin harus bisa melihat:

- Fixed applicant data
- Dynamic answers
- Dynamic uploaded files
- Field label snapshot
- Field type snapshot
- Tombol download file dynamic

Jika applicant lama tidak punya dynamic data:

- halaman tidak error
- tampilkan fallback seperti “Belum ada jawaban tambahan”

---

# 15. Risk Analysis

| Risiko | Level | Mitigasi |
|---|---|---|
| Field dihapus setelah applicant submit | High | Gunakan SoftDeletes dan snapshot label/type |
| Admin mengubah label field | High | Gunakan `field_label_snapshot` |
| User submit field palsu | High | DynamicValidationService menolak key tidak dikenal |
| User submit option palsu | High | Validasi value berdasarkan JSON options |
| File upload berbahaya | Critical | Private storage, MIME validation, blocked extensions |
| IDOR download file | Critical | Protected route dan validasi file milik applicant |
| Field name bentrok | High | Unique rule dan larangan bentrok field umum/schema |
| Data applicant lama error | Medium | Backward compatibility dan empty state |
| Query berat di applicant detail | Medium | Eager loading relasi dynamic answers/files |
| Locale option tidak sesuai | Medium | Pastikan key locale mengikuti project existing |

---

# 16. Task 4B — Planning Migration & Model Architecture

## 16.1 Status Task 4B

Task 4B pada dokumen ini **masih tahap planning**, belum coding.

Tujuannya adalah memecah pekerjaan implementasi migration dan model agar saat nanti masuk coding, AI agent tidak melebar ke admin UI, frontend, atau upload handler.

---

## 16.2 Tujuan Task 4B

Task 4B nanti akan menjadi tahap awal implementasi Dynamic Form Builder.

Fokus coding Task 4B nanti adalah:

- migration
- model
- relation
- casts
- scopes
- helper dasar
- config dasar dynamic form

Namun pada dokumen ini, semua itu masih berupa rencana teknis.

---

## 16.3 Batasan Task 4B Saat Implementasi Nanti

Saat Task 4B benar-benar dijalankan, AI agent **tidak boleh** mengerjakan:

- Admin Form Builder CRUD
- Frontend Dynamic Form Renderer
- Submit handling dynamic answers
- Dynamic file upload service penuh
- Download controller
- Admin applicant detail update
- Partnership CMS
- Contact CMS
- Alur Pendaftaran CMS

---

## 16.4 Rencana Migration Task 4B

Migration yang akan dibuat nanti:

1. `create_form_fields_table`
2. `create_applicant_form_answers_table`
3. `create_applicant_dynamic_files_table`

Migration harus:

- dibuat sebagai migration baru
- tidak mengubah migration lama
- tidak menghapus data existing
- tidak drop tabel existing
- aman terhadap applicant lama

---

## 16.5 Rencana Migration `form_fields`

### Field yang harus dibuat

- `id`
- `program_id`
- `schema_id nullable`
- `label json`
- `field_name string`
- `type string`
- `placeholder json nullable`
- `description json nullable`
- `options json nullable`
- `accepted_file_types json nullable`
- `max_file_size integer nullable`
- `is_required boolean default false`
- `status string default aktif`
- `sort_order integer default 0`
- `created_at`
- `updated_at`
- `deleted_at`

### Index yang direncanakan

- `program_id`
- `schema_id`
- `type`
- `status`
- `sort_order`
- kombinasi `program_id`, `schema_id`, `field_name` jika aman diterapkan

### Catatan unique nullable

Karena `schema_id` nullable, unique constraint database bisa tricky di MySQL. Karena itu:

- database boleh memakai index biasa terlebih dahulu
- validasi unique detail wajib dilakukan di FormFieldRequest pada Task 4C
- jika database mendukung pendekatan aman, boleh tambahkan unique composite dengan pertimbangan null handling

---

## 16.6 Rencana Migration `applicant_form_answers`

### Field yang harus dibuat

- `id`
- `applicant_id`
- `form_field_id`
- `value json nullable`
- `field_label_snapshot json nullable`
- `field_type_snapshot string nullable`
- `created_at`
- `updated_at`

### Index yang direncanakan

- `applicant_id`
- `form_field_id`

### Catatan foreign key

- Jangan cascade delete jawaban lama hanya karena form field di-soft-delete.
- `form_fields` memakai SoftDeletes, jadi jawaban lama harus tetap aman.

---

## 16.7 Rencana Migration `applicant_dynamic_files`

### Field yang harus dibuat

- `id`
- `applicant_id`
- `form_field_id`
- `file_path string`
- `original_name string`
- `mime_type string`
- `size integer`
- `field_label_snapshot json nullable`
- `field_type_snapshot string nullable`
- `created_at`
- `updated_at`

### Index yang direncanakan

- `applicant_id`
- `form_field_id`

### Catatan

File fisik tidak disimpan di public storage. File path mengarah ke private storage.

---

# 17. Rencana Model Task 4B

## 17.1 Model `FormField`

File yang akan dibuat nanti:

```text
app/Models/FormField.php
```

### Trait

- SoftDeletes
- HasTranslations jika project memakai Spatie

### Translatable fields

- label
- placeholder
- description

### Casts

- options → array
- accepted_file_types → array
- is_required → boolean

### Relations

- program()
- schema()
- answers()
- dynamicFiles()

### Scopes

- scopeActive()
- scopeOrdered()
- scopeForProgram(programId)
- scopeForSchema(schemaId)

### Helpers

- isFile()
- isChoiceField()
- isRequired()
- getOptionValues()
- getLabelForLocale(locale = null)

---

## 17.2 Model `ApplicantFormAnswer`

File yang akan dibuat nanti:

```text
app/Models/ApplicantFormAnswer.php
```

### Casts

- value → array
- field_label_snapshot → array

### Relations

- applicant()
- formField()

---

## 17.3 Model `ApplicantDynamicFile`

File yang akan dibuat nanti:

```text
app/Models/ApplicantDynamicFile.php
```

### Casts

- field_label_snapshot → array

### Relations

- applicant()
- formField()

### Helpers

- readableSize()
- isOwnedByApplicant(applicantId)

---

## 17.4 Update Existing Models

### Program

Tambahkan relation:

- formFields()

### ProgramSchema

Tambahkan relation:

- formFields()

### Applicant

Tambahkan relation:

- dynamicAnswers()
- dynamicFiles()

---

# 18. Rencana Config Dynamic Form

Jika project menggunakan config untuk constant, rancang file:

```text
config/dynamic_forms.php
```

Isi yang direncanakan:

- allowed_field_types
- choice_field_types
- file_field_type
- default_max_file_size
- blocked_file_extensions
- default_allowed_file_extensions

## allowed_field_types

```text
text, textarea, number, email, phone, date, select, radio, checkbox, file
```

## choice_field_types

```text
select, radio, checkbox
```

## file_field_type

```text
file
```

## default_max_file_size

```text
2048 KB
```

## blocked_file_extensions

```text
php, phtml, exe, sh, bat, js, html, svg
```

## default_allowed_file_extensions

```text
pdf, jpg, jpeg, png, doc, docx
```

---

# 19. Task 4B Acceptance Criteria Planning

Task 4B nanti dianggap selesai jika:

- migration `form_fields` dibuat
- migration `applicant_form_answers` dibuat
- migration `applicant_dynamic_files` dibuat
- model `FormField` dibuat
- model `ApplicantFormAnswer` dibuat
- model `ApplicantDynamicFile` dibuat
- Program memiliki relation `formFields()`
- ProgramSchema memiliki relation `formFields()`
- Applicant memiliki relation `dynamicAnswers()` dan `dynamicFiles()`
- config dynamic form dibuat jika diperlukan
- `php artisan migrate` berhasil
- `php artisan optimize:clear` berhasil
- belum ada admin UI yang dikerjakan
- belum ada frontend renderer yang dikerjakan
- belum ada submit handling dynamic form yang dikerjakan

---

# 20. Prompt Task 4B Planning untuk AI Agent

Gunakan prompt ini jika ingin meminta AI agent menyusun breakdown implementasi Task 4B lebih teknis tanpa coding:

```text
Buat breakdown teknis untuk Task 4B — Migration & Models Dynamic Form Builder.

KONTEKS:
Task 4A System Design Dynamic Form Builder sudah approved.
Namun saat ini belum boleh coding.

FOKUS:
Rancang detail implementasi migration dan model untuk:
- form_fields
- applicant_form_answers
- applicant_dynamic_files
- FormField model
- ApplicantFormAnswer model
- ApplicantDynamicFile model
- update relation Program, ProgramSchema, Applicant
- config dynamic_forms.php

JANGAN coding dulu.
JANGAN membuat migration file dulu.
JANGAN membuat model file dulu.
JANGAN mengubah project dulu.

OUTPUT:
1. daftar migration yang nanti akan dibuat
2. struktur field final tiap migration
3. foreign key strategy
4. index strategy
5. model relation final
6. casts final
7. scopes final
8. helper final
9. config dynamic form final
10. risiko implementasi migration/model
11. checklist sebelum coding Task 4B
```

---

# 21. Prompt Implementasi Task 4B Nanti

Prompt ini **belum dijalankan sekarang**. Gunakan hanya ketika sudah siap masuk coding.

```text
Kerjakan TASK 4B — Migration & Models Dynamic Form Builder.

KONTEKS:
Task 4A dan planning Task 4B sudah approved.

FOKUS CODING TASK 4B:
- Migration
- Model
- Relation
- Casts
- Scope
- Helper dasar
- Config dynamic form

JANGAN mengerjakan:
- Admin Form Builder CRUD
- Frontend Dynamic Form Renderer
- Submit Handling
- Dynamic Validation Service penuh
- Dynamic File Upload Service penuh
- Download Controller
- Admin Applicant Detail Update

[Masukkan detail migration dan model dari dokumen ini]
```

---

# 22. Next Step Setelah Dokumen Ini

Jika dokumen ini sudah disetujui, langkah berikutnya adalah:

```text
Task 4B Implementation — Migration & Models
```

Namun sebelum itu, pastikan:

- key locale options sudah sesuai project (`id/jp` atau `id/ja`)
- istilah “encrypted file” tidak dipakai jika implementasinya hanya private storage + hashed filename
- admin memahami bahwa fixed fields lama tetap dipertahankan
- dynamic form hanya untuk custom/additional fields

---

# 23. Final Verdict Dokumen

Status dokumen:

```text
READY AS COMBINED TASK 4A + TASK 4B PLANNING DOCUMENT (lihat tambahan Task 4C pada bagian akhir)
```

Dokumen ini sudah bisa dijadikan satu file acuan sebelum masuk ke coding Task 4B.

---

# 24. Task 4C — Planning Admin Form Builder CRUD

## 24.1 Status Task 4C

Task 4C pada dokumen ini **masih tahap planning**, belum coding.

Tujuan Task 4C adalah merancang dashboard admin untuk mengelola `form_fields` setelah Task 4B migration dan model selesai. Pada tahap implementasi nanti, Task 4C hanya boleh fokus pada **Admin Form Builder CRUD**, belum menyentuh frontend dynamic renderer, submit handling, upload service penuh, atau download controller.

---

## 24.2 Tujuan Task 4C

Task 4C bertujuan membuat perencanaan agar admin dapat mengelola field formulir secara dynamic melalui dashboard.

Admin nantinya harus bisa:

- melihat daftar field berdasarkan program
- melihat daftar field berdasarkan program + schema
- membuat field umum program
- membuat field khusus schema
- mengedit field
- menonaktifkan field
- melakukan soft delete field
- mengatur urutan field
- mengatur required / optional
- mengatur tipe field
- mengatur options untuk select/radio/checkbox
- mengatur accepted file types untuk upload file
- mengatur max file size
- melakukan preview form gabungan field umum + field schema

---

## 24.3 Batasan Task 4C Saat Implementasi Nanti

Saat Task 4C benar-benar dijalankan, AI agent **tidak boleh** mengerjakan:

- Frontend Dynamic Form Renderer
- Submit handling dynamic answers
- DynamicValidationService penuh untuk user submit
- DynamicFileUploadService penuh untuk user upload
- Download Controller untuk file applicant
- Admin Applicant Detail dynamic answers
- Partnership CMS
- Contact CMS
- Alur Pendaftaran CMS

Task 4C hanya fokus pada:

- Admin Form Builder menu
- Admin FormField CRUD
- FormFieldRequest
- Admin blade Form Builder
- Preview admin sederhana
- Reorder planning/endpoint jika diperlukan

---

## 24.4 Rencana Route Admin Task 4C

Gunakan route admin sesuai struktur project existing.

Contoh route planning:

```php
Route::prefix('dashboard-admin')
    ->middleware(['auth', 'admin'])
    ->name('admin.')
    ->group(function () {
        Route::resource('form-fields', Admin\FormFieldController::class);
        Route::post('form-fields/reorder', [Admin\FormFieldController::class, 'reorder'])
            ->name('form-fields.reorder');
        Route::get('form-fields/preview', [Admin\FormFieldController::class, 'preview'])
            ->name('form-fields.preview');
    });
```

Catatan:

- Sesuaikan prefix dan naming dengan route admin existing.
- Semua route wajib berada di middleware `auth` dan `admin`.
- Route `reorder` dan `preview` boleh dibuat jika diperlukan, tetapi jangan membuat fitur terlalu kompleks di awal.

---

## 24.5 Rencana Controller Task 4C

Buat controller:

```text
app/Http/Controllers/Admin/FormFieldController.php
```

Method yang direncanakan:

| Method | Fungsi |
|---|---|
| `index()` | Menampilkan daftar field berdasarkan filter program/schema |
| `create()` | Menampilkan form tambah field |
| `store()` | Menyimpan field baru |
| `edit()` | Menampilkan form edit field |
| `update()` | Mengupdate field |
| `destroy()` | Soft delete field |
| `reorder()` | Mengubah urutan field, optional |
| `preview()` | Preview form berdasarkan program/schema, optional |

Controller harus tipis dan hanya bertugas:

- menerima request
- memanggil FormFieldRequest
- memanggil service jika logic panjang
- mengirim data ke view
- redirect dengan flash message

Controller tidak boleh berisi:

- query kompleks berulang
- validasi panjang manual
- business logic field resolution terlalu besar
- parsing options yang berantakan

---

## 24.6 Rencana FormFieldRequest

Buat request:

```text
app/Http/Requests/FormFieldRequest.php
```

Validasi minimal:

```text
program_id required exists:programs,id
schema_id nullable exists:program_schemas,id
label required
field_name required string
field_name format slug/snake_case
 type required in:text,textarea,number,email,phone,date,select,radio,checkbox,file
placeholder nullable
description nullable
options nullable array/json
accepted_file_types nullable array/json
max_file_size nullable integer min:1
is_required boolean
status required in:aktif,nonaktif
sort_order nullable integer
```

Catatan: hilangkan spasi tidak sengaja pada key `type` saat implementasi.

---

## 24.7 Validasi Khusus Schema Ownership

Jika `schema_id` diisi, maka schema wajib:

- milik `program_id` yang sama
- berstatus `aktif`
- belum soft delete

Jika tidak sesuai, request harus gagal dengan validation error.

Contoh logic planning:

```php
Rule::exists('program_schemas', 'id')->where(function ($query) {
    return $query
        ->where('program_id', $this->program_id)
        ->where('status', 'aktif')
        ->whereNull('deleted_at');
})
```

---

## 24.8 Validasi Khusus Field Name

`field_name` harus aman karena akan menjadi nama input HTML.

Aturan format:

- lowercase
- snake_case
- tidak boleh mengandung spasi
- tidak boleh mengandung karakter aneh
- hanya huruf, angka, dan underscore
- tidak boleh diawali angka jika memungkinkan

Contoh valid:

```text
asal_sekolah
upload_cv
level_bahasa_jepang
```

Contoh tidak valid:

```text
Asal Sekolah
upload-cv
123field
<script>
```

---

## 24.9 Validasi Uniqueness Field Name

Karena form schema menggunakan gabungan field umum + field schema, aturan uniqueness harus ketat.

### A. Field umum program

Jika `schema_id = null`, maka `field_name` tidak boleh duplikat dengan field umum lain dalam `program_id` yang sama.

### B. Field khusus schema

Jika `schema_id` diisi, maka:

- `field_name` tidak boleh duplikat dalam `program_id + schema_id` yang sama
- `field_name` juga tidak boleh sama dengan field umum program (`schema_id = null`) pada program yang sama

Tujuan:

- mencegah bentrok input HTML
- mencegah dynamic validation salah membaca field
- mencegah field schema menimpa field umum secara diam-diam

---

## 24.10 Validasi Type-Specific Admin

### Untuk type biasa

Jika type adalah:

```text
text, textarea, number, email, phone, date
```

Maka:

- `options` harus kosong/null
- `accepted_file_types` harus kosong/null
- `max_file_size` boleh kosong/null

### Untuk choice field

Jika type adalah:

```text
select, radio, checkbox
```

Maka:

- `options` wajib ada
- `options` harus array
- setiap option wajib memiliki `value`
- setiap option wajib memiliki `label`
- label harus mengikuti locale project, misalnya `id` dan `jp`
- value tidak boleh duplikat dalam field yang sama

Format options:

```json
[
  {
    "value": "n4",
    "label": {
      "id": "N4",
      "jp": "N4"
    }
  },
  {
    "value": "belum_pernah",
    "label": {
      "id": "Belum Pernah",
      "jp": "未経験"
    }
  }
]
```

### Untuk file field

Jika type adalah:

```text
file
```

Maka:

- `accepted_file_types` wajib ada atau gunakan default config
- `accepted_file_types` tidak boleh mengandung blocked extension
- `max_file_size` wajib ada atau gunakan default config
- `options` harus kosong/null

Blocked extension minimal:

```text
php, phtml, exe, sh, bat, js, html, svg
```

---

## 24.11 Rencana Admin Index Page

Halaman index Form Builder harus mendukung filter:

- program
- schema optional
- type optional
- status optional

Tampilan tabel minimal:

| Kolom | Keterangan |
|---|---|
| Label | Label field sesuai locale aktif |
| Field Name | Nama internal input |
| Program | Nama program |
| Schema | Nama schema atau “Umum Program” |
| Type | Jenis field |
| Required | Ya/Tidak |
| Status | Aktif/Nonaktif |
| Sort Order | Urutan |
| Action | Edit/Delete |

Query harus menggunakan eager loading:

```php
FormField::with(['program', 'schema'])
    ->ordered()
    ->paginate(10);
```

Tidak boleh ada query database di Blade.

---

## 24.12 Rencana Admin Create/Edit Form

Form create/edit harus memiliki field:

- Program select
- Schema select optional
- Label multilingual
- Field name
- Type select
- Placeholder multilingual
- Description multilingual
- Options editor untuk select/radio/checkbox
- Accepted file types editor untuk file
- Max file size untuk file
- Required toggle
- Status select
- Sort order

UX behavior yang direncanakan:

- Jika admin memilih program, daftar schema di-filter berdasarkan program tersebut.
- Jika type adalah select/radio/checkbox, tampilkan options editor.
- Jika type adalah file, tampilkan accepted file types dan max file size.
- Jika type bukan choice, sembunyikan options editor.
- Jika type bukan file, sembunyikan file config.

---

## 24.13 Rencana Options Editor

Options editor tidak boleh hanya textarea bebas tanpa struktur jika memungkinkan.

Rencana ideal:

- Admin klik “Tambah Option”
- Input `value`
- Input label Indonesia
- Input label Jepang
- Bisa hapus option
- Bisa reorder option sederhana jika dibutuhkan

Jika implementasi awal ingin sederhana, boleh memakai textarea JSON, tetapi harus:

- menyediakan contoh format
- melakukan validasi JSON
- menolak option tanpa `value`
- menolak option tanpa `label`
- menolak value duplikat

Rekomendasi:

```text
Gunakan UI row-based sederhana, bukan textarea JSON mentah, jika waktu memungkinkan.
```

---

## 24.14 Rencana Accepted File Types Editor

Accepted file types sebaiknya ditampilkan sebagai checkbox, bukan input bebas.

Default pilihan:

```text
pdf, jpg, jpeg, png, doc, docx
```

Blocked extension tidak boleh muncul sebagai pilihan.

Jika admin mengisi manual, sistem tetap harus menolak:

```text
php, phtml, exe, sh, bat, js, html, svg
```

---

## 24.15 Rencana Preview Form Admin

Preview form bersifat opsional pada implementasi awal.

Jika dibuat, preview harus:

- berdasarkan program yang dipilih
- berdasarkan schema optional yang dipilih
- menggabungkan field umum + field schema
- menampilkan urutan sesuai sort_order
- menampilkan required marker
- menampilkan label sesuai locale admin
- tidak melakukan submit

Preview tidak boleh dianggap sebagai frontend renderer final. Ini hanya alat bantu admin.

---

## 24.16 Rencana Reorder Field

Reorder field bisa dibuat dengan dua opsi.

### Opsi A — Simple Sort Order Input

Admin mengisi angka `sort_order` secara manual.

Kelebihan:

- mudah dibuat
- risiko rendah
- cocok untuk implementasi awal

### Opsi B — Drag & Drop

Admin bisa drag & drop field, lalu sistem menyimpan urutan via endpoint `reorder`.

Kelebihan:

- UX lebih baik

Risiko:

- butuh JS tambahan
- perlu endpoint khusus
- perlu validasi ownership field

Rekomendasi implementasi awal:

```text
Mulai dengan Opsi A terlebih dahulu.
Drag & Drop boleh ditambahkan setelah fitur utama stabil.
```

---

## 24.17 Rencana Service Pendukung Admin

Jika logic di controller mulai panjang, buat service:

```text
FormFieldAdminService
```

Tanggung jawab:

- normalize field_name
- normalize options
- normalize accepted_file_types
- validate blocked extensions
- prepare data sebelum disimpan
- handle sort order default

Namun jika implementasi masih sederhana, logic ini boleh berada di FormFieldRequest + controller tipis.

---

## 24.18 Rencana Blade Admin

Blade yang direncanakan:

```text
resources/views/admin/form_fields/index.blade.php
resources/views/admin/form_fields/create.blade.php
resources/views/admin/form_fields/edit.blade.php
resources/views/admin/form_fields/_form.blade.php
resources/views/admin/form_fields/_options_editor.blade.php
resources/views/admin/form_fields/_file_config.blade.php
resources/views/admin/form_fields/preview.blade.php optional
```

Prinsip blade:

- tidak ada query database
- tidak ada `Model::where()`
- hanya render data dari controller
- tampilkan validation error
- old input tetap muncul saat validasi gagal
- komponen options editor tetap mempertahankan data lama saat error

---

## 24.19 Rencana Sidebar/Menu Admin

Tambahkan menu:

```text
Form Builder
```

Letakkan di area dashboard admin yang relevan, misalnya setelah menu Program/Batch/Program Schema.

Menu ini mengarah ke:

```text
/dashboard-admin/form-fields
```

Nama menu boleh:

```text
Form Builder
Kelola Formulir
```

Jika dashboard memakai multi-bahasa admin, gunakan label yang mengikuti pola existing.

---

## 24.20 Query Strategy Task 4C

Admin index harus menggunakan eager loading.

Contoh:

```php
FormField::query()
    ->with(['program', 'schema'])
    ->when($programId, fn ($q) => $q->where('program_id', $programId))
    ->when($schemaId, fn ($q) => $q->where('schema_id', $schemaId))
    ->ordered()
    ->paginate(10);
```

Data untuk select program:

```php
Program::active()->ordered()->select(['id', 'nama_program'])->get();
```

Data untuk select schema:

```php
ProgramSchema::active()
    ->where('program_id', $selectedProgramId)
    ->ordered()
    ->select(['id', 'program_id', 'nama_skema'])
    ->get();
```

Tidak boleh mengambil semua schema dari semua program tanpa filter jika data mulai banyak.

---

## 24.21 Security Task 4C

Task 4C harus memastikan:

- route hanya admin
- FormFieldRequest digunakan
- tidak ada hard delete field
- field dihapus menggunakan SoftDeletes
- field nonaktif tidak muncul di frontend nanti
- schema_id harus milik program_id
- field_name tidak bentrok
- blocked file extensions tidak bisa disimpan
- options tidak bisa menyimpan value duplikat
- tidak ada query di Blade

---

## 24.22 Task 4C Acceptance Criteria Planning

Task 4C nanti dianggap selesai jika:

- route admin Form Builder dibuat dan protected
- FormFieldController dibuat
- FormFieldRequest dibuat
- admin bisa melihat daftar form fields
- admin bisa filter field berdasarkan program
- admin bisa filter field berdasarkan schema
- admin bisa create field umum program
- admin bisa create field khusus schema
- admin bisa edit field
- admin bisa soft delete field
- admin bisa set required
- admin bisa set status aktif/nonaktif
- admin bisa set sort_order
- admin bisa set type
- admin bisa mengatur options multilingual untuk select/radio/checkbox
- admin bisa mengatur accepted file types dan max file size untuk file
- validasi schema_id sesuai program berjalan
- validasi field_name uniqueness berjalan
- validasi blocked file extensions berjalan
- tidak ada query database di Blade
- belum ada frontend renderer yang dikerjakan
- belum ada submit handling yang dikerjakan

---

## 24.23 Prompt Task 4C Planning untuk AI Agent

Gunakan prompt ini jika ingin meminta AI agent menyusun breakdown lebih teknis untuk Task 4C tanpa coding:

```text
Buat breakdown teknis untuk Task 4C — Admin Form Builder CRUD.

KONTEKS:
Task 4A System Design dan Task 4B Planning sudah approved.
Namun saat ini belum boleh coding.

FOKUS:
Rancang detail implementasi admin Form Builder untuk mengelola form_fields.

JANGAN coding dulu.
JANGAN membuat controller file dulu.
JANGAN membuat blade file dulu.
JANGAN membuat route dulu.
JANGAN mengubah project dulu.

OUTPUT:
1. route admin yang nanti akan dibuat
2. struktur FormFieldController
3. struktur FormFieldRequest
4. validasi program_id dan schema_id
5. validasi field_name uniqueness
6. validasi options multilingual
7. validasi accepted file types
8. rencana index page
9. rencana create/edit page
10. rencana options editor
11. rencana file config editor
12. rencana preview form admin
13. rencana reorder field
14. query strategy
15. security checklist
16. acceptance criteria
17. risiko implementasi admin Form Builder
```

---

## 24.24 Prompt Implementasi Task 4C Nanti

Prompt ini **belum dijalankan sekarang**. Gunakan hanya setelah Task 4B implementation selesai dan diaudit.

```text
Kerjakan TASK 4C — Admin Form Builder CRUD.

KONTEKS:
Task 4A design, Task 4B migration/model implementation, dan audit Task 4B sudah selesai.

FOKUS CODING TASK 4C:
- Admin route Form Builder
- FormFieldController
- FormFieldRequest
- Blade admin index/create/edit/form
- Options editor
- File config editor
- Soft delete FormField
- Admin filter program/schema

JANGAN mengerjakan:
- Frontend Dynamic Form Renderer
- Submit Handling Dynamic Answers
- Dynamic File Upload Service penuh
- Download Controller
- Admin Applicant Detail dynamic answers

[Masukkan detail Task 4C dari dokumen ini]
```

---

# 25. Update Next Step Setelah Dokumen Ini

Jika dokumen gabungan Task 4A + Task 4B Planning + Task 4C Planning ini sudah disetujui, urutan berikutnya tetap:

```text
1. Task 4B Implementation — Migration & Models
2. Audit Task 4B
3. Task 4C Implementation — Admin Form Builder CRUD
4. Audit Task 4C
5. Task 4D Implementation — Frontend Dynamic Form Renderer
6. Audit Task 4D
7. Task 4E Planning — Dynamic Submit Handling, Validation, Upload, and Storage
```

Jangan loncat langsung ke Task 4C implementation sebelum Task 4B migration dan model selesai.

---

# 26. Final Verdict Dokumen Gabungan

Status dokumen sebelum penambahan Task 4E:

```text
READY AS COMBINED TASK 4A + TASK 4B PLANNING + TASK 4C PLANNING + TASK 4D PLANNING DOCUMENT
```

Dokumen ini dilanjutkan dengan Task 4E agar menjadi dokumen plan lengkap Dynamic Form Builder.



---

# 27. Task 4D — Planning Frontend Dynamic Form Renderer

## 27.1 Status Task 4D

Task 4D pada dokumen ini **masih tahap planning**, belum coding.

Tujuan Task 4D adalah merancang bagaimana field dynamic yang sudah dibuat admin pada Task 4C nanti akan tampil di halaman pendaftaran user.

Task 4D berfokus pada **frontend renderer**, yaitu cara sistem menampilkan form dynamic berdasarkan:

- `program_id`
- `batch_id`
- `schema_id` jika ada
- daftar `form_fields` aktif
- field umum program
- field khusus schema

Task 4D **belum** menangani penyimpanan jawaban, dynamic validation penuh, upload service, private download, atau admin applicant detail. Bagian itu masuk Task 4E dan seterusnya.

---

## 27.2 Tujuan Task 4D

Task 4D bertujuan membuat rencana frontend agar user dapat melihat dan mengisi field dynamic yang sudah dikelola admin.

Frontend nantinya harus bisa:

- menampilkan fixed form existing
- menampilkan dynamic fields tambahan di bawah fixed form
- menampilkan field berdasarkan program
- menampilkan field berdasarkan program + schema
- menampilkan label sesuai locale aktif
- menampilkan placeholder dan description sesuai locale aktif
- menampilkan required marker
- menampilkan pilihan select/radio/checkbox dari JSON options
- menampilkan file input sesuai konfigurasi admin
- menjaga struktur name input agar siap diproses oleh Task 4E
- tetap menjaga compatibility dengan form existing

---

## 27.3 Batasan Task 4D Saat Implementasi Nanti

Saat Task 4D benar-benar dijalankan, AI agent **tidak boleh** mengerjakan:

- submit handling dynamic answers
- penyimpanan ke `applicant_form_answers`
- penyimpanan ke `applicant_dynamic_files`
- DynamicValidationService penuh
- DynamicFileUploadService penuh
- Download Controller
- Admin Applicant Detail dynamic answers
- Partnership CMS
- Contact CMS
- Alur Pendaftaran CMS

Task 4D hanya fokus pada:

- rendering field dynamic di halaman form pendaftaran
- struktur komponen Blade dynamic form
- input name convention
- tampilan error validation jika nanti ada
- memastikan tidak ada query database di Blade
- memastikan form siap untuk Task 4E

---

## 27.4 Flow Frontend Dynamic Form

Flow user setelah Task 4D nanti:

1. User membuka detail program.
2. User memilih batch.
3. Jika program punya schema aktif, user memilih schema.
4. User klik tombol daftar.
5. Sistem membuka form pendaftaran existing.
6. Fixed fields existing tetap tampil seperti biasa.
7. Dynamic fields ditampilkan di bawah fixed fields.
8. User mengisi dynamic fields.
9. User menekan submit.
10. Pada Task 4D, payload sudah tersusun dengan benar, tetapi penyimpanan penuh akan dikerjakan pada Task 4E.

---

## 27.5 Data yang Harus Disiapkan Controller / Service

Blade frontend tidak boleh mengambil data sendiri dari database.

Controller harus mengirim data siap render, misalnya:

```php
$dynamicFields = $dynamicFormService->getFieldsFor($programId, $schemaId);
```

Data yang harus tersedia untuk view:

- `$program`
- `$batch`
- `$schema` nullable
- `$dynamicFields`
- locale aktif
- fixed form data existing jika ada

Service yang digunakan:

```text
DynamicFormService
```

Method yang dipakai:

```text
getFieldsFor(programId, schemaId = null)
prepareForFrontend(fields)
```

---

## 27.6 Form Resolution di Frontend

Task 4D harus mengikuti form resolution logic yang sudah ditetapkan:

### Program tanpa schema

Render field:

```text
program_id = selected program
schema_id = null
status = aktif
```

### Program dengan schema

Render gabungan:

```text
1. Field umum program:
   program_id = selected program
   schema_id = null
   status = aktif

2. Field khusus schema:
   program_id = selected program
   schema_id = selected schema
   status = aktif
```

Lalu urutkan berdasarkan `sort_order`.

---

## 27.7 Input Name Convention

Agar Task 4E mudah memproses data, input name harus konsisten.

### Non-file fields

Gunakan:

```text
dynamic_answers[field_name]
```

Contoh:

```html
<input name="dynamic_answers[asal_sekolah]">
```

### Checkbox multiple

Gunakan:

```text
dynamic_answers[field_name][]
```

Contoh:

```html
<input type="checkbox" name="dynamic_answers[bidang_tg][]" value="perawat">
```

### File fields

Gunakan:

```text
dynamic_files[field_name]
```

Contoh:

```html
<input type="file" name="dynamic_files[upload_cv]">
```

### Hidden context fields

Form tetap harus membawa:

```text
program_id
batch_id
schema_id nullable
```

Jika form memiliki file dynamic, form wajib menggunakan:

```html
enctype="multipart/form-data"
```

---

## 27.8 Field Renderer Design

Buat komponen renderer berdasarkan type.

Component yang direncanakan:

```text
resources/views/components/dynamic-form/field.blade.php
resources/views/components/dynamic-form/text.blade.php
resources/views/components/dynamic-form/textarea.blade.php
resources/views/components/dynamic-form/number.blade.php
resources/views/components/dynamic-form/email.blade.php
resources/views/components/dynamic-form/phone.blade.php
resources/views/components/dynamic-form/date.blade.php
resources/views/components/dynamic-form/select.blade.php
resources/views/components/dynamic-form/radio.blade.php
resources/views/components/dynamic-form/checkbox.blade.php
resources/views/components/dynamic-form/file.blade.php
```

Jika ingin lebih sederhana pada implementasi awal, boleh memakai satu component `field.blade.php` dengan switch type, tetapi logic harus tetap rapi dan tidak melakukan query database.

---

## 27.9 Field Rendering Rules

### Common rules semua field

Setiap field harus menampilkan:

- label sesuai locale aktif
- tanda required jika `is_required = true`
- placeholder jika ada
- description/petunjuk jika ada
- error validation jika ada
- old input jika form gagal submit

### Text / Email / Phone / Number / Date

Render sebagai input biasa.

Contoh name:

```text
dynamic_answers[field_name]
```

### Textarea

Render sebagai textarea.

### Select

Render options dari JSON.

- value memakai `option.value`
- label memakai `option.label[locale]`
- fallback ke `option.label.id` jika locale tidak tersedia

### Radio

Render options sebagai radio group.

### Checkbox

Render options sebagai checkbox group.

- name menggunakan `dynamic_answers[field_name][]`
- old input harus mendukung array

### File

Render file input.

Tampilkan informasi:

- accepted file types
- max file size
- required marker jika wajib

Jangan tampilkan file lama pada Task 4D, karena penyimpanan file belum dikerjakan.

---

## 27.10 Multilingual Rendering

Dynamic form harus mengikuti locale aktif.

Field translatable:

- label
- placeholder
- description

Options multilingual:

```json
{
  "value": "belum_pernah",
  "label": {
    "id": "Belum Pernah",
    "jp": "未経験"
  }
}
```

Render label option:

```text
option.label[current_locale]
```

Fallback:

```text
option.label.id
```

Catatan:
Pastikan key locale mengikuti project existing, misalnya `id/jp` atau `id/ja`.

---

## 27.11 Error Handling Design

Task 4D harus menyiapkan tempat error agar nanti Task 4E mudah memakai validation error.

Error key untuk dynamic answer:

```text
dynamic_answers.field_name
```

Error key untuk dynamic file:

```text
dynamic_files.field_name
```

Contoh render error:

```php
@error('dynamic_answers.' . $field->field_name)
    <p>{{ $message }}</p>
@enderror
```

Untuk checkbox array:

```text
dynamic_answers.field_name
```

atau item-level:

```text
dynamic_answers.field_name.*
```

---

## 27.12 UX Requirement Frontend

Dynamic form harus:

- responsive mobile/tablet/desktop
- mudah dibaca
- tidak merusak form existing
- tidak membuat form terlalu panjang tanpa spacing
- menampilkan grouping “Informasi Tambahan” atau “Dokumen Tambahan” jika diperlukan
- menampilkan required marker yang jelas
- menampilkan petunjuk upload file
- menampilkan empty state jika belum ada dynamic fields

Jika belum ada dynamic fields:

```text
Jangan tampilkan section kosong.
Form fixed existing tetap berjalan normal.
```

---

## 27.13 Dynamic Form Section Placement

Dynamic form sebaiknya ditempatkan:

```text
Setelah fixed applicant fields
Sebelum tombol submit
```

Contoh struktur:

```text
1. Data Pribadi / Fixed Form
2. Pilihan Program / Batch / Schema hidden context
3. Informasi Tambahan Dynamic
4. Dokumen Tambahan Dynamic
5. Tombol Submit
```

File fields boleh dipisahkan secara visual dalam section “Dokumen Tambahan” agar user lebih paham.

---

## 27.14 No Query in Blade Rule

Blade frontend tidak boleh berisi:

```text
FormField::query()
FormField::where()
Program::where()
ProgramSchema::where()
DB::table()
```

Blade hanya boleh menerima `$dynamicFields` dari controller/service.

Jika perlu memisahkan file fields dan non-file fields, lakukan di service atau controller.

Contoh:

```text
$textFields
$fileFields
```

Bukan memfilter berat di Blade.

---

## 27.15 Frontend Renderer Security

Frontend renderer bukan lapisan keamanan utama, tetapi tetap harus aman.

Pastikan:

- tidak render HTML mentah dari label/description tanpa escaping
- options label di-escape
- field_name sudah divalidasi snake_case dari admin
- tidak ada script injection dari description
- file accept attribute hanya bersifat UX, bukan keamanan utama
- server-side validation tetap wajib di Task 4E

---

## 27.16 Relationship dengan Task 4E

Task 4D hanya menyiapkan payload.

Task 4E nanti akan mengerjakan:

- DynamicValidationService penuh
- validasi dynamic_answers
- validasi dynamic_files
- penyimpanan applicant_form_answers
- penyimpanan applicant_dynamic_files
- private storage upload
- DB transaction
- rollback file jika gagal

Karena itu Task 4D harus memastikan input name dan payload sudah final.

---

## 27.17 Rencana Blade yang Diubah

Kemungkinan file yang akan disentuh saat implementasi Task 4D:

```text
resources/views/program-detail.blade.php
resources/views/components/program/detail/batch-section.blade.php
resources/views/pendaftaran.blade.php
resources/views/components/dynamic-form/field.blade.php
resources/views/components/dynamic-form/*.blade.php
```

Sesuaikan dengan struktur form existing di project.

Jika form pendaftaran berada di file lain, gunakan file existing dan jangan membuat flow baru yang merusak route lama.

---

## 27.18 Query Strategy Task 4D

Controller/service harus mengambil dynamic fields dengan eager loading minimal.

Contoh planning:

```php
$dynamicFields = $dynamicFormService->getFieldsFor(
    programId: $program->id,
    schemaId: $schema?->id
);
```

`DynamicFormService` harus mengembalikan collection yang sudah:

- aktif
- belum soft delete
- sesuai program
- sesuai schema jika ada
- sudah terurut
- sudah siap render

---

## 27.19 Task 4D Acceptance Criteria Planning

Task 4D nanti dianggap selesai jika:

- form pendaftaran existing tetap berjalan
- dynamic fields tampil berdasarkan program
- dynamic fields tampil berdasarkan program + schema
- field umum + field schema tergabung
- field nonaktif tidak tampil
- field soft-deleted tidak tampil
- label tampil sesuai locale aktif
- options tampil sesuai locale aktif
- required marker tampil
- description tampil
- old input tetap tampil saat validation error
- file field tampil dengan accepted file types dan max size
- input name mengikuti convention final
- `program_id`, `batch_id`, dan `schema_id` tetap terkirim
- tidak ada query database di Blade
- tidak ada hardcoded program/schema
- belum ada submit handling dynamic answers
- belum ada upload service penuh

---

## 27.20 Risiko Task 4D

| Risiko | Level | Mitigasi |
|---|---|---|
| Input name tidak konsisten | High | Tetapkan convention `dynamic_answers[field_name]` dan `dynamic_files[field_name]` |
| Field file tidak memakai multipart | High | Form wajib `enctype="multipart/form-data"` jika dynamic file ada |
| Query di Blade | High | Semua field dikirim dari service/controller |
| Options locale tidak sesuai | Medium | Gunakan fallback ke locale default |
| Description mengandung HTML berbahaya | Medium | Escape output, jangan render raw HTML |
| Form existing rusak | High | Jangan ubah fixed fields secara drastis |
| Dynamic field kosong membuat UI aneh | Low | Jangan tampilkan section dynamic jika tidak ada field |

---

## 27.21 Prompt Task 4D Planning untuk AI Agent

Gunakan prompt ini jika ingin meminta AI agent menyusun breakdown lebih teknis untuk Task 4D tanpa coding:

```text
Buat breakdown teknis untuk Task 4D — Frontend Dynamic Form Renderer.

KONTEKS:
Task 4A System Design, Task 4B Planning, dan Task 4C Planning sudah approved.
Namun saat ini belum boleh coding.

FOKUS:
Rancang detail implementasi frontend renderer untuk dynamic form berdasarkan form_fields.

JANGAN coding dulu.
JANGAN membuat blade file dulu.
JANGAN mengubah route dulu.
JANGAN mengubah controller dulu.
JANGAN mengubah project dulu.

OUTPUT:
1. struktur frontend renderer
2. input name convention final
3. component blade yang nanti dibuat
4. field rendering rules per type
5. cara render multilingual label/options
6. cara menampilkan required marker, description, dan error
7. cara menampilkan file field
8. posisi dynamic section dalam form existing
9. data yang harus dikirim controller/service
10. no-query-in-blade checklist
11. compatibility dengan fixed form existing
12. acceptance criteria Task 4D
13. risiko implementasi frontend renderer
```

---

## 27.22 Prompt Implementasi Task 4D Nanti

Prompt ini **belum dijalankan sekarang**. Gunakan hanya setelah Task 4B dan Task 4C implementation selesai serta sudah diaudit.

```text
Kerjakan TASK 4D — Frontend Dynamic Form Renderer.

KONTEKS:
Task 4A design, Task 4B migration/model implementation, Task 4C Admin Form Builder implementation, dan audit Task 4C sudah selesai.

FOKUS CODING TASK 4D:
- Render dynamic fields di form pendaftaran
- Gunakan field dari form_fields berdasarkan program/schema
- Tampilkan label, placeholder, description, required, options, dan file config
- Gunakan input name convention final
- Pastikan form existing tetap berjalan

JANGAN mengerjakan:
- Submit Handling Dynamic Answers
- DynamicValidationService penuh
- DynamicFileUploadService penuh
- Download Controller
- Admin Applicant Detail dynamic answers

[Masukkan detail Task 4D dari dokumen ini]
```

---

# 28. Update Next Step Setelah Dokumen Ini

Jika dokumen gabungan Task 4A + Task 4B Planning + Task 4C Planning + Task 4D Planning ini sudah disetujui, urutan berikutnya adalah:

```text
1. Task 4B Implementation — Migration & Models
2. Audit Task 4B
3. Task 4C Implementation — Admin Form Builder CRUD
4. Audit Task 4C
5. Task 4D Implementation — Frontend Dynamic Form Renderer
6. Audit Task 4D
7. Task 4E Planning — Dynamic Submit Handling, Validation, Upload, and Storage
```

Jangan loncat langsung ke Task 4D implementation sebelum Task 4B dan Task 4C selesai.

---

# 29. Final Verdict Dokumen Gabungan

Status dokumen sebelum penambahan Task 4E:

```text
READY AS COMBINED TASK 4A + TASK 4B PLANNING + TASK 4C PLANNING + TASK 4D PLANNING DOCUMENT
```

Dokumen ini dilanjutkan dengan Task 4E agar menjadi dokumen plan lengkap Dynamic Form Builder.
---

# 30. Task 4E — Planning Dynamic Submit Handling, Validation, Upload, Storage, Download, and Admin Applicant Detail

## 30.1 Status Task 4E

Task 4E pada dokumen ini **masih tahap planning**, belum coding.

Task 4E adalah tahap akhir dari perencanaan Dynamic Form Builder. Fokusnya adalah merancang bagaimana data dari dynamic form yang sudah dirender pada Task 4D akan:

- divalidasi secara server-side,
- disimpan ke tabel `applicant_form_answers`,
- file upload disimpan ke private storage,
- metadata file disimpan ke `applicant_dynamic_files`,
- proses submit dibungkus dalam database transaction,
- file yang gagal disimpan bisa dibersihkan,
- file bisa diunduh admin melalui protected route,
- jawaban dynamic tampil di admin applicant detail.

Task 4E **tidak boleh dikerjakan sebelum Task 4B, Task 4C, dan Task 4D selesai serta diaudit**.

---

## 30.2 Tujuan Task 4E

Tujuan Task 4E adalah merancang tahap submit dan penyimpanan dynamic form secara aman.

Setelah Task 4E diimplementasikan nanti, sistem harus mampu:

- menerima payload `dynamic_answers`
- menerima payload `dynamic_files`
- mengambil daftar field aktif berdasarkan `program_id` dan `schema_id`
- menolak field palsu yang tidak terdaftar di `form_fields`
- memvalidasi required field
- memvalidasi type field
- memvalidasi options untuk select/radio/checkbox
- memvalidasi upload file berdasarkan konfigurasi field
- memblokir ekstensi file berbahaya
- membuat applicant utama menggunakan flow existing
- menyimpan jawaban dynamic ke `applicant_form_answers`
- menyimpan file dynamic ke private storage
- menyimpan metadata file ke `applicant_dynamic_files`
- menyimpan snapshot label dan type saat submit
- menampilkan dynamic answers dan dynamic files di admin applicant detail
- menyediakan protected download route untuk file dynamic

---

## 30.3 Batasan Task 4E Saat Implementasi Nanti

Saat Task 4E benar-benar dijalankan, AI agent hanya boleh fokus pada:

- `DynamicValidationService`
- `DynamicFileUploadService`
- update `PendaftaranRequest` / request pendaftaran existing
- update controller submit pendaftaran existing
- penyimpanan `applicant_form_answers`
- penyimpanan `applicant_dynamic_files`
- private storage upload
- protected download route
- admin applicant detail dynamic section
- audit flow submit dynamic form

AI agent **tidak boleh** mengerjakan:

- mengubah ulang Program Management System
- mengubah ulang Admin Program CRUD
- mengubah ulang Admin Batch CRUD
- mengubah ulang Admin ProgramSchema CRUD
- mengubah ulang Admin Form Builder CRUD secara besar
- mengubah ulang Frontend Dynamic Renderer secara besar
- Partnership CMS
- Contact CMS
- Alur Pendaftaran CMS
- Payment
- Maps

Jika butuh penyesuaian kecil pada Task 4C atau Task 4D, lakukan hanya jika wajib untuk membuat submit berjalan.

---

## 30.4 Payload Submit Final

Task 4E harus mengikuti input name convention dari Task 4D.

### Context fields

Form pendaftaran wajib membawa:

```text
program_id
batch_id
schema_id nullable
```

### Dynamic non-file fields

Gunakan:

```text
dynamic_answers[field_name]
```

Contoh:

```html
<input name="dynamic_answers[asal_sekolah]">
```

### Dynamic checkbox fields

Gunakan:

```text
dynamic_answers[field_name][]
```

Contoh:

```html
<input type="checkbox" name="dynamic_answers[bidang_tg][]" value="perawat">
```

### Dynamic file fields

Gunakan:

```text
dynamic_files[field_name]
```

Contoh:

```html
<input type="file" name="dynamic_files[upload_cv]">
```

### Multipart form

Jika dynamic form memiliki minimal satu field `type = file`, form harus menggunakan:

```html
enctype="multipart/form-data"
```

---

## 30.5 Submit Flow Final

Flow submit yang direncanakan:

1. User submit form pendaftaran.
2. `PendaftaranRequest` memvalidasi fixed fields existing.
3. Request memvalidasi `program_id`, `batch_id`, dan `schema_id`.
4. `DynamicFormService` mengambil field aktif berdasarkan `program_id` dan `schema_id`.
5. `DynamicValidationService` membangun rule dari `form_fields`.
6. Sistem menolak field palsu yang tidak terdaftar.
7. Sistem memvalidasi `dynamic_answers`.
8. Sistem memvalidasi `dynamic_files`.
9. Sistem membuka `DB::transaction`.
10. Applicant utama dibuat menggunakan flow existing.
11. Dynamic answers non-file disimpan ke `applicant_form_answers`.
12. Dynamic files diupload ke private storage.
13. Metadata file disimpan ke `applicant_dynamic_files`.
14. Snapshot label dan type disimpan.
15. Jika semua berhasil, transaction commit.
16. Jika ada error, transaction rollback dan file yang terlanjur diupload dihapus.

---

## 30.6 DynamicValidationService Planning

Buat service:

```text
app/Services/DynamicForm/DynamicValidationService.php
```

Tanggung jawab:

- membuat rules validasi dari `form_fields`
- memvalidasi dynamic answers
- memvalidasi dynamic files
- menolak unknown field
- memastikan field yang diproses sesuai program/schema
- memastikan options value valid
- memastikan file sesuai aturan field

### Method yang direncanakan

```text
buildAnswerRules(Collection $fields): array
buildFileRules(Collection $fields): array
validateUnknownFields(Request $request, Collection $fields): void
validateChoiceValues(array $input, Collection $fields): void
validateDynamicPayload(Request $request, Collection $fields): array
```

---

## 30.7 Rule Generation untuk Dynamic Answers

### Text, textarea, phone

Jika `is_required = true`:

```text
required|string|max:255
```

Jika optional:

```text
nullable|string|max:255
```

Untuk textarea, max boleh lebih besar, misalnya:

```text
nullable|string|max:2000
```

### Email

```text
required|email|max:255
```

atau:

```text
nullable|email|max:255
```

### Number

```text
required|numeric
```

atau:

```text
nullable|numeric
```

### Date

```text
required|date
```

atau:

```text
nullable|date
```

### Select / radio

Value harus termasuk dalam `options.value`.

Contoh:

```text
required|in:n4,n5,belum_pernah
```

atau:

```text
nullable|in:n4,n5,belum_pernah
```

### Checkbox

Input harus array.

```text
required|array
dynamic_answers.field_name.* in:option_1,option_2
```

Jika optional:

```text
nullable|array
dynamic_answers.field_name.* in:option_1,option_2
```

### File

File tidak diproses di `dynamic_answers`, tetapi di `dynamic_files`.

---

## 30.8 Rule Generation untuk Dynamic Files

File field menggunakan payload:

```text
dynamic_files[field_name]
```

Rules:

Jika required:

```text
required|file|mimes:pdf,jpg,png|max:2048
```

Jika optional:

```text
nullable|file|mimes:pdf,jpg,png|max:2048
```

Nilai `mimes` diambil dari `accepted_file_types`.

Nilai `max` diambil dari `max_file_size`.

Jika `accepted_file_types` kosong, gunakan default config:

```text
pdf, jpg, jpeg, png, doc, docx
```

Jika `max_file_size` kosong, gunakan default config:

```text
2048 KB
```

---

## 30.9 Unknown Field Protection

Task 4E wajib menolak field palsu.

Contoh serangan:

```text
dynamic_answers[is_admin] = true
dynamic_answers[status] = accepted
dynamic_files[malware] = shell.php
```

Sistem harus:

1. Ambil daftar `field_name` valid dari `form_fields` aktif.
2. Bandingkan semua key `dynamic_answers` dan `dynamic_files`.
3. Jika ada key yang tidak terdaftar, lempar `ValidationException`.

Pesan user-friendly yang disarankan:

```text
Terdapat field tidak valid pada formulir.
```

---

## 30.10 Choice Value Protection

Untuk field:

```text
select
radio
checkbox
```

Sistem harus membaca `options.value`, bukan `options.label`.

Contoh options:

```json
[
  {
    "value": "n4",
    "label": {
      "id": "N4",
      "jp": "N4"
    }
  }
]
```

Valid value:

```text
n4
```

Invalid value:

```text
N4
日本語
admin
```

Server-side validation harus menolak value yang tidak ada di daftar `options.value`.

---

## 30.11 File Upload Security Planning

Buat service:

```text
app/Services/DynamicForm/DynamicFileUploadService.php
```

Tanggung jawab:

- validasi extension tambahan selain Laravel `mimes`
- menolak blocked extension
- sanitize original name
- membuat nama file hash/unique
- menyimpan file ke private storage
- mengembalikan metadata file
- menghapus file jika transaction gagal

### Blocked extensions

Minimal:

```text
php
phtml
exe
sh
bat
js
html
svg
```

### Private storage path

Gunakan path:

```text
private/registrations/{applicant_id}/dynamic_{field_id}_{hash}.{ext}
```

Atau path Laravel storage:

```text
storage/app/private/registrations/{applicant_id}/dynamic_{field_id}_{hash}.{ext}
```

### Catatan

Jangan simpan dokumen pendaftaran di public disk.

Jangan memakai `public_path()` dan `unlink()` manual.

Gunakan `Storage` facade.

---

## 30.12 File Metadata yang Disimpan

Saat file berhasil diupload, simpan ke `applicant_dynamic_files`:

```text
applicant_id
form_field_id
file_path
original_name
mime_type
size
field_label_snapshot
field_type_snapshot
created_at
updated_at
```

### Snapshot

Ambil dari `FormField` saat submit:

```text
field_label_snapshot = form_field.label
field_type_snapshot = form_field.type
```

Tujuan:

- Jika admin mengubah label field nanti, file lama tetap punya konteks.
- Jika field di-soft-delete, data file tetap bisa dibaca.

---

## 30.13 Dynamic Answers Saving Planning

Jawaban non-file disimpan ke `applicant_form_answers`.

Untuk setiap field non-file:

```text
applicant_id
form_field_id
value
field_label_snapshot
field_type_snapshot
created_at
updated_at
```

### Value format

Untuk text:

```json
"SMA Negeri 1"
```

Untuk select/radio:

```json
"n4"
```

Untuk checkbox:

```json
["perawat", "pengolahan_makanan"]
```

### Catatan

Jangan simpan file path di `applicant_form_answers`.

File masuk ke `applicant_dynamic_files`.

---

## 30.14 Transaction and Rollback Planning

Gunakan transaction database.

Namun file upload ke storage tidak otomatis ikut rollback DB. Karena itu perlu array tracking:

```text
$uploadedFiles = []
```

Flow:

1. Upload file.
2. Simpan path ke `$uploadedFiles`.
3. Jika ada exception setelah upload, hapus file yang ada di `$uploadedFiles`.
4. Lempar ulang exception.

Pseudocode planning:

```php
$uploadedFiles = [];

try {
    DB::beginTransaction();

    $applicant = Applicant::create($fixedData);

    foreach ($answerFields as $field) {
        ApplicantFormAnswer::create([...]);
    }

    foreach ($fileFields as $field) {
        $metadata = $dynamicFileUploadService->storeFile(...);
        $uploadedFiles[] = $metadata['file_path'];

        ApplicantDynamicFile::create([...]);
    }

    DB::commit();
} catch (\Throwable $e) {
    DB::rollBack();

    foreach ($uploadedFiles as $path) {
        Storage::delete($path);
    }

    throw $e;
}
```

---

## 30.15 PendaftaranRequest Update Planning

Update request pendaftaran existing.

Tetap pertahankan fixed field validation yang sudah ada.

Tambahkan:

```text
dynamic_answers nullable array
dynamic_files nullable array
```

Namun detail validation dynamic sebaiknya dilakukan di `DynamicValidationService`, bukan semua di `PendaftaranRequest`.

`PendaftaranRequest` tetap bertanggung jawab untuk:

- fixed fields
- `program_id`
- `batch_id`
- `schema_id`
- struktur awal `dynamic_answers`
- struktur awal `dynamic_files`

`DynamicValidationService` bertanggung jawab untuk:

- validasi field dynamic berdasarkan database

---

## 30.16 Program / Batch / Schema Ownership Validation

Sebelum menyimpan applicant, pastikan:

- `program_id` valid dan aktif
- `batch_id` valid dan milik `program_id`
- batch status mengizinkan pendaftaran:
  - `dibuka`
  - `diperpanjang`
- `schema_id` nullable
- jika ada, `schema_id` milik `program_id`
- schema aktif
- schema belum soft delete

Validasi ini sudah sebagian ada dari Task 3, tapi Task 4E harus memastikan tidak rusak.

---

## 30.17 Download Controller Planning

Buat controller:

```text
app/Http/Controllers/Admin/ApplicantDynamicFileDownloadController.php
```

Route:

```text
GET /dashboard-admin/applicants/{applicant}/dynamic-files/{file}/download
```

Middleware:

```text
auth
admin
```

Controller harus memastikan:

- `$file->applicant_id === $applicant->id`
- file path ada di storage
- user yang login adalah admin
- tidak ada akses public
- response menggunakan `Storage::download()`

Jika file tidak milik applicant:

```text
abort(403)
```

Jika file tidak ditemukan:

```text
abort(404)
```

---

## 30.18 Admin Applicant Detail Update Planning

Update halaman detail applicant admin agar menampilkan:

1. Fixed applicant data existing
2. Additional data lama jika masih ada
3. Dynamic answers
4. Dynamic files

### Dynamic answers section

Tampilkan:

| Label | Type | Value |
|---|---|---|
| field_label_snapshot | field_type_snapshot | value |

Jika value array, tampilkan sebagai list atau comma-separated.

### Dynamic files section

Tampilkan:

| Label | Original Name | Size | Download |
|---|---|---|---|

Download menggunakan protected route.

### Empty state

Jika applicant tidak punya dynamic answers/files:

```text
Belum ada jawaban tambahan.
Belum ada dokumen tambahan.
```

### Query strategy

Gunakan eager loading:

```php
Applicant::with([
    'dynamicAnswers.formField',
    'dynamicFiles.formField',
])
```

Jangan query di Blade.

---

## 30.19 Service Architecture Final for Task 4E

### DynamicFormService

Dipakai untuk:

- mengambil fields berdasarkan program/schema
- memisahkan file fields dan non-file fields
- memastikan field aktif dan valid

Method tambahan yang direncanakan:

```text
getAnswerFields(programId, schemaId = null)
getFileFields(programId, schemaId = null)
getValidFieldNames(programId, schemaId = null)
```

### DynamicValidationService

Dipakai untuk:

- dynamic rules
- unknown field detection
- choice value validation
- file validation

### DynamicFileUploadService

Dipakai untuk:

- store file private
- sanitize original name
- generate hash filename
- return metadata
- cleanup failed upload

### DynamicAnswerService optional

Jika controller mulai terlalu panjang, buat:

```text
DynamicAnswerService
```

Tanggung jawab:

- menyimpan applicant_form_answers
- menyimpan snapshot label/type
- format value checkbox/select/text

---

## 30.20 Error Handling and User Feedback

Jika dynamic validation gagal:

- user kembali ke form
- fixed input tetap terisi
- dynamic input non-file tetap terisi dengan `old()`
- file input kosong kembali karena alasan keamanan browser
- tampilkan pesan error di field terkait

Contoh error key:

```text
dynamic_answers.asal_sekolah
dynamic_answers.bidang_tg
dynamic_answers.bidang_tg.*
dynamic_files.upload_cv
```

Jika upload gagal:

```text
Dokumen gagal diunggah. Silakan coba lagi.
```

Jika file type tidak valid:

```text
Format file tidak diizinkan.
```

Jika file terlalu besar:

```text
Ukuran file melebihi batas maksimal.
```

---

## 30.21 Security Checklist Task 4E

Task 4E harus memastikan:

- field palsu ditolak
- options palsu ditolak
- schema_id beda program ditolak
- batch_id beda program ditolak
- batch tertutup tidak bisa submit
- schema nonaktif tidak bisa submit
- form field nonaktif tidak diproses
- form field soft-deleted tidak diproses
- file extension berbahaya ditolak
- file disimpan private
- file tidak punya public URL
- download file hanya admin
- file download harus milik applicant terkait
- tidak ada query database di Blade
- tidak ada hard delete file metadata tanpa alasan jelas

---

## 30.22 Task 4E Acceptance Criteria Planning

Task 4E nanti dianggap selesai jika:

- `PendaftaranRequest` mendukung struktur dynamic payload
- `DynamicValidationService` dibuat
- unknown dynamic fields ditolak
- select/radio/checkbox hanya menerima value valid
- file upload divalidasi berdasarkan field config
- blocked extensions ditolak
- applicant tetap tersimpan melalui flow existing
- dynamic answers tersimpan ke `applicant_form_answers`
- dynamic files tersimpan ke private storage
- file metadata tersimpan ke `applicant_dynamic_files`
- snapshot label dan type tersimpan
- semua proses submit memakai transaction
- file yang terupload dihapus jika transaction gagal
- admin applicant detail menampilkan dynamic answers
- admin applicant detail menampilkan dynamic files
- admin bisa download file dynamic lewat protected route
- applicant lama tetap aman dan tidak error
- tidak ada query database di Blade
- `php artisan optimize:clear` berhasil
- `npm run build` berhasil jika ada perubahan asset

---

## 30.23 Risiko Task 4E

| Risiko | Level | Mitigasi |
|---|---|---|
| Field palsu ikut tersimpan | Critical | Unknown field detection berdasarkan field aktif |
| File berbahaya terupload | Critical | MIME validation, blocked extension, private storage |
| File bocor via URL public | Critical | Simpan private dan akses via protected route |
| IDOR download file | Critical | Pastikan file milik applicant route parameter |
| DB rollback tidak menghapus file | High | Track uploaded files dan cleanup di catch |
| Schema/batch mismatch | High | Validasi ownership sebelum submit |
| Batch tertutup masih bisa submit | High | Validasi status `dibuka` / `diperpanjang` |
| Field label berubah setelah submit | Medium | Gunakan snapshot label/type |
| Applicant lama error | Medium | Empty state dan nullable relasi |
| Checkbox value rusak | Medium | Simpan value JSON array dan validasi item |
| File input tidak old() | Low | Browser behavior normal, tampilkan pesan upload ulang |

---

## 30.24 Prompt Task 4E Planning untuk AI Agent

Gunakan prompt ini jika ingin meminta AI agent menyusun breakdown lebih teknis untuk Task 4E tanpa coding:

```text
Buat breakdown teknis untuk Task 4E — Dynamic Submit Handling, Validation, Upload, Storage, Download, and Admin Applicant Detail.

KONTEKS:
Task 4A System Design, Task 4B Planning, Task 4C Planning, dan Task 4D Planning sudah approved.
Namun saat ini belum boleh coding.

FOKUS:
Rancang detail implementasi submit dynamic form.

JANGAN coding dulu.
JANGAN membuat service file dulu.
JANGAN membuat controller file dulu.
JANGAN membuat route dulu.
JANGAN mengubah request dulu.
JANGAN mengubah project dulu.

OUTPUT:
1. struktur DynamicValidationService
2. struktur DynamicFileUploadService
3. struktur DynamicAnswerService jika diperlukan
4. update yang dibutuhkan pada PendaftaranRequest
5. update yang dibutuhkan pada controller submit pendaftaran
6. flow DB transaction
7. flow cleanup file jika gagal
8. validasi unknown fields
9. validasi choice values
10. validasi file upload
11. validasi program/batch/schema ownership
12. route download protected
13. download controller planning
14. admin applicant detail planning
15. query strategy applicant detail
16. security checklist
17. acceptance criteria Task 4E
18. risiko implementasi
```

---

## 30.25 Prompt Implementasi Task 4E Nanti

Prompt ini **belum dijalankan sekarang**. Gunakan hanya setelah Task 4B, Task 4C, dan Task 4D implementation selesai serta sudah diaudit.

```text
Kerjakan TASK 4E — Dynamic Submit Handling, Validation, Upload, Storage, Download, and Admin Applicant Detail.

KONTEKS:
Task 4A design, Task 4B migration/model implementation, Task 4C Admin Form Builder implementation, Task 4D Frontend Dynamic Form Renderer implementation, dan seluruh audit sebelumnya sudah selesai.

FOKUS CODING TASK 4E:
- DynamicValidationService
- DynamicFileUploadService
- update PendaftaranRequest
- update controller submit pendaftaran
- simpan applicant_form_answers
- simpan applicant_dynamic_files
- private storage upload
- cleanup upload saat gagal
- protected download route
- admin applicant detail dynamic answers/files

JANGAN mengerjakan:
- Partnership CMS
- Contact CMS
- Alur Pendaftaran CMS
- Payment
- Maps
- refactor besar program management
- refactor besar admin form builder
- refactor besar frontend renderer

[Masukkan detail Task 4E dari dokumen ini]
```

---

# 31. Final Implementation Sequence for Task 4

Setelah seluruh planning Task 4A–4E disetujui, urutan implementasi tetap harus bertahap:

```text
1. Task 4B Implementation — Migration & Models
2. Audit Task 4B
3. Task 4C Implementation — Admin Form Builder CRUD
4. Audit Task 4C
5. Task 4D Implementation — Frontend Dynamic Form Renderer
6. Audit Task 4D
7. Task 4E Implementation — Submit Handling, Validation, Upload, Download, Admin Applicant Detail
8. Audit Task 4E
9. End-to-End QA Dynamic Form Builder
```

Jangan menjalankan Task 4B–4E sekaligus.

---

# 32. End-to-End QA Dynamic Form Builder Planning

Setelah Task 4E selesai, jalankan QA menyeluruh.

## Admin QA

- Admin bisa membuat field text.
- Admin bisa membuat field select.
- Admin bisa membuat field checkbox.
- Admin bisa membuat field file.
- Admin bisa membuat field umum program.
- Admin bisa membuat field khusus schema.
- Admin tidak bisa membuat field_name bentrok.
- Admin tidak bisa memilih schema dari program lain.
- Admin tidak bisa menyimpan blocked file extensions.
- Field nonaktif tidak muncul di frontend.
- Field soft-deleted tidak muncul di frontend.

## Frontend QA

- Form fixed existing tetap berjalan.
- Dynamic fields tampil sesuai program.
- Dynamic fields tampil sesuai schema.
- Field umum + field schema tergabung.
- Label tampil sesuai locale.
- Options tampil sesuai locale.
- Required marker tampil.
- User bisa submit dynamic answers.
- User bisa submit dynamic files.
- File terlalu besar ditolak.
- File extension berbahaya ditolak.
- Pilihan option palsu ditolak.
- Field palsu ditolak.

## Data QA

- Applicant tersimpan.
- `schema_id` tersimpan jika ada.
- Dynamic answers tersimpan.
- Dynamic files metadata tersimpan.
- Snapshot label/type tersimpan.
- Applicant lama tetap bisa dibuka.
- Additional data lama tidak rusak.

## Admin Applicant Detail QA

- Dynamic answers tampil.
- Dynamic files tampil.
- File bisa didownload admin.
- User non-admin tidak bisa download.
- IDOR download file ditolak.
- File hilang menampilkan error 404 yang aman.

## Command QA

Jalankan:

```text
php artisan migrate:status
php artisan optimize:clear
php artisan route:list
npm run build
```

---

# 33. Final Verdict Dokumen Task 4

Status dokumen setelah penambahan Task 4E:

```text
READY AS COMPLETE TASK 4 DYNAMIC FORM BUILDER PLANNING DOCUMENT
```

Dokumen ini sekarang mencakup seluruh perencanaan Dynamic Form Builder dari:

```text
Task 4A — System Design
Task 4B — Migration & Models
Task 4C — Admin Form Builder CRUD
Task 4D — Frontend Dynamic Form Renderer
Task 4E — Submit Handling, Validation, Upload, Storage, Download, Admin Applicant Detail
```

Setelah dokumen ini disetujui, langkah berikutnya adalah mulai implementasi bertahap dari **Task 4B**, bukan langsung mengerjakan semua Task 4.

---

# 34. PROMPT IMPLEMENTASI PERTAMA — TASK 4B ONLY

Gunakan prompt ini untuk mulai coding pertama. Jangan jalankan Task 4C, 4D, atau 4E dulu.

```text
Kerjakan TASK 4B — Migration & Models Dynamic Form Builder.

KONTEKS:
Planning Dynamic Form Builder Task 4A–4E sudah approved.
Sekarang hanya implementasi tahap pertama, yaitu Task 4B.

FOKUS CODING TASK 4B:
1. Migration
2. Model
3. Relation
4. Casts
5. Scope
6. Helper dasar
7. Config dynamic form

JANGAN mengerjakan:
- Admin Form Builder CRUD
- Frontend Dynamic Form Renderer
- Submit Handling Dynamic Answers
- DynamicValidationService penuh
- DynamicFileUploadService penuh
- Download Controller
- Admin Applicant Detail dynamic answers
- Partnership CMS
- Contact CMS
- Alur Pendaftaran CMS
- Payment
- Maps

==================================================
A. MIGRATION
==================================================

Buat migration baru, jangan ubah migration lama.

1. create_form_fields_table

Fields:
- id
- program_id foreign key ke programs.id
- schema_id nullable foreign key ke program_schemas.id
- label json
- field_name string
- type string
- placeholder json nullable
- description json nullable
- options json nullable
- accepted_file_types json nullable
- max_file_size integer nullable
- is_required boolean default false
- status string default aktif
- sort_order integer default 0
- timestamps
- softDeletes

Index:
- program_id
- schema_id
- type
- status
- sort_order
- field_name

Catatan:
Karena schema_id nullable, unique field_name detail jangan hanya mengandalkan database.
Nanti validasi uniqueness detail dilakukan di FormFieldRequest pada Task 4C.

2. create_applicant_form_answers_table

Fields:
- id
- applicant_id foreign key ke applicants.id
- form_field_id foreign key ke form_fields.id
- value json nullable
- field_label_snapshot json nullable
- field_type_snapshot string nullable
- timestamps

Index:
- applicant_id
- form_field_id

Jangan cascade delete dari form_fields ke answers.

3. create_applicant_dynamic_files_table

Fields:
- id
- applicant_id foreign key ke applicants.id
- form_field_id foreign key ke form_fields.id
- file_path string
- original_name string
- mime_type string
- size integer
- field_label_snapshot json nullable
- field_type_snapshot string nullable
- timestamps

Index:
- applicant_id
- form_field_id

Jangan cascade delete dari form_fields ke files.

==================================================
B. MODEL
==================================================

Buat model:

1. app/Models/FormField.php

Gunakan:
- SoftDeletes
- HasTranslations jika project memakai Spatie

Translatable fields:
- label
- placeholder
- description

Casts:
- options => array
- accepted_file_types => array
- is_required => boolean

Relations:
- program()
- schema()
- answers()
- dynamicFiles()

Scopes:
- scopeActive()
- scopeOrdered()
- scopeForProgram($programId)
- scopeForSchema($schemaId)

Helpers:
- isFile()
- isChoiceField()
- isRequired()
- getOptionValues()
- getLabelForLocale($locale = null)

2. app/Models/ApplicantFormAnswer.php

Casts:
- value => array
- field_label_snapshot => array

Relations:
- applicant()
- formField()

3. app/Models/ApplicantDynamicFile.php

Casts:
- field_label_snapshot => array

Relations:
- applicant()
- formField()

Helpers:
- readableSize()
- isOwnedByApplicant($applicantId)

==================================================
C. UPDATE EXISTING MODELS
==================================================

Update Program:
- formFields()

Update ProgramSchema:
- formFields()

Update Applicant:
- dynamicAnswers()
- dynamicFiles()

Jangan ubah flow existing applicant secara besar.

==================================================
D. CONFIG
==================================================

Buat config/dynamic_forms.php jika belum ada.

Isi:
- allowed_field_types:
  text, textarea, number, email, phone, date, select, radio, checkbox, file

- choice_field_types:
  select, radio, checkbox

- file_field_type:
  file

- default_max_file_size:
  2048

- blocked_file_extensions:
  php, phtml, exe, sh, bat, js, html, svg

- default_allowed_file_extensions:
  pdf, jpg, jpeg, png, doc, docx

==================================================
E. COMMAND CHECK
==================================================

Jalankan:
- php artisan migrate
- php artisan migrate:status
- php artisan optimize:clear

Pastikan tidak ada fatal error model/autoload.

==================================================
F. OUTPUT AKHIR
==================================================

Berikan laporan:
1. migration yang dibuat
2. model yang dibuat
3. model existing yang diubah
4. relation yang ditambahkan
5. config yang dibuat
6. hasil php artisan migrate
7. hasil php artisan optimize:clear
8. error jika ada
9. catatan risiko sebelum Task 4C
```
