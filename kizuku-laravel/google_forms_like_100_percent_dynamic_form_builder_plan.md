# PLAN LENGKAP — Google Forms-like 100% Dynamic Form Builder

## Status Dokumen

Dokumen ini adalah plan lanjutan setelah **Task 4 — Dynamic Form Builder** selesai.

Target baru dari plan ini adalah mengubah sistem dari:

```text
Fixed Form + Dynamic Fields Tambahan
```

menjadi:

```text
100% Dynamic Form Builder seperti Google Forms
```

Artinya, semua field yang diisi user pada formulir pendaftaran harus dibuat dan diatur dari dashboard admin.

Dokumen ini **belum masuk tahap coding**. Dokumen ini adalah rencana teknis sebelum implementasi.

---

# 1. Konteks Project Saat Ini

Fitur yang sudah selesai:

```text
✅ Program Management
✅ Batch Management
✅ Program Schema
✅ Dynamic Form Builder basic
✅ Admin Form Builder CRUD
✅ Frontend Dynamic Form Renderer
✅ Dynamic Submit Handling
✅ Dynamic File Upload private storage
✅ Admin Applicant Detail dynamic answers/files
✅ Protected download dynamic files
```

Namun sistem saat ini masih memakai konsep:

```text
Fixed applicant fields + Dynamic additional fields
```

Contoh fixed fields existing:

```text
nama
email
phone
tanggal_lahir
alamat
pendidikan
```

Target baru:

```text
Tidak ada input user yang hardcoded di Blade form pendaftaran.
Semua input user dibuat melalui dashboard Form Builder.
```

---

# 2. Prinsip Utama Arsitektur Baru

## 2.1 Semua input user berasal dari Form Builder

Input seperti:

```text
Nama Lengkap
Email
Nomor WhatsApp
Alamat
Tanggal Lahir
Pendidikan
Upload CV
Upload KTP
Level Bahasa Jepang
Pilihan Bidang
```

harus dibuat sebagai field dari dashboard admin.

Tidak boleh ada lagi input hardcoded untuk data user di Blade public registration form.

---

## 2.2 Data sistem tetap hidden

Walaupun semua input user menjadi dynamic, sistem tetap membutuhkan data internal:

```text
program_id
batch_id
schema_id
form_id
status pendaftaran
created_at
updated_at
```

Data ini bukan field yang dibuat user atau admin sebagai pertanyaan, tetapi data sistem untuk menghubungkan submission ke program, batch, schema, dan form.

---

## 2.3 Kolom fixed lama jangan langsung dihapus

Kolom lama di tabel `applicants` jangan langsung di-drop.

Alasan:

```text
- applicant lama masih memakai kolom tersebut
- admin applicant list mungkin masih memakai nama/email/phone
- export existing mungkin masih memakai kolom lama
- pencarian applicant mungkin masih memakai kolom lama
```

Strategi aman:

```text
1. Hapus fixed input dari UI public form
2. Semua input user masuk ke dynamic answers/files
3. Kolom lama menjadi legacy/mirror columns
4. Isi kolom lama dari field_role mapping
5. Setelah sistem stabil, baru evaluasi apakah kolom lama tetap dipakai atau dihapus
```

---

# 3. Konsep Identity Field Mapping

Karena semua field menjadi dynamic, sistem harus tahu field mana yang mewakili identitas utama applicant.

Tambahkan konsep:

```text
field_role
```

pada `form_fields`.

## 3.1 Contoh field_role

```text
Field Label: Nama Lengkap
field_name: nama_lengkap
type: text
field_role: applicant_name

Field Label: Email
field_name: email
type: email
field_role: applicant_email

Field Label: Nomor WhatsApp
field_name: nomor_whatsapp
type: phone
field_role: applicant_phone
```

## 3.2 Daftar field_role

```text
none
applicant_name
applicant_email
applicant_phone
applicant_birth_date
applicant_address
applicant_education
```

## 3.3 Fungsi field_role

Saat user submit:

```text
dynamic_answers[nama_lengkap] → applicants.nama
dynamic_answers[email] → applicants.email
dynamic_answers[nomor_whatsapp] → applicants.phone
dynamic_answers[tanggal_lahir] → applicants.tanggal_lahir
dynamic_answers[alamat] → applicants.alamat
dynamic_answers[pendidikan] → applicants.pendidikan
```

Dengan strategi ini, form tetap 100% dynamic, tetapi sistem tetap bisa menampilkan applicant list dengan data penting seperti nama/email/phone.

---

# 4. Tabel `forms`

## 4.1 Rekomendasi

Tambahkan tabel baru:

```text
forms
```

## 4.2 Alasan perlu tabel forms

Agar sistem benar-benar mirip Google Forms, form perlu menjadi entity sendiri.

Jika hanya memakai `form_fields`, sistem masih bisa berjalan, tetapi sulit untuk fitur:

```text
- form title
- form description
- form draft/published
- form preview
- duplicate form
- responses tab
- success message
- response management per form
- export response per form
```

---

# 5. Database Design Baru

## 5.1 Tabel Baru: `forms`

### Fungsi

Mewakili satu formulir pendaftaran.

### Struktur

```text
id
program_id foreign key
schema_id nullable foreign key
batch_id nullable foreign key
title json
description json nullable
success_message json nullable
status string default draft
is_active boolean default true
accepts_responses boolean default true
version integer default 1
published_at nullable timestamp
created_at
updated_at
deleted_at
```

### Status form

```text
draft
published
archived
```

### Catatan

```text
program_id wajib
schema_id nullable
batch_id nullable
title translatable
description translatable
success_message translatable
```

Jika `schema_id` kosong, form berlaku umum untuk program.

Jika `schema_id` diisi, form berlaku untuk schema tertentu.

Jika `batch_id` diisi, form bisa berlaku spesifik untuk batch tertentu.

Untuk implementasi awal, batch-specific form boleh belum dipakai, tetapi kolom disiapkan.

---

## 5.2 Update Tabel `form_fields`

Tambahkan kolom:

```text
form_id nullable foreign key ke forms.id
field_role string default none
is_locked boolean default false
settings json nullable
```

### Penjelasan

#### `form_id`

Menghubungkan field ke form tertentu.

#### `field_role`

Untuk mapping identitas applicant.

#### `is_locked`

Opsional. Dipakai jika ada field penting yang tidak boleh sembarangan dihapus.

#### `settings`

JSON fleksibel untuk konfigurasi tambahan seperti:

```text
display_width
admin_notes
custom_validation_message
layout_group
```

Untuk fase awal, `settings` boleh belum dipakai banyak.

---

## 5.3 Update Tabel `applicants`

Tambahkan:

```text
form_id nullable foreign key ke forms.id
```

### Fungsi

Agar setiap applicant tahu submit melalui form yang mana.

Ini penting untuk response management dan export.

---

## 5.4 Tabel Existing yang Tetap Dipakai

Tetap gunakan:

```text
applicant_form_answers
applicant_dynamic_files
```

Tidak perlu membuat tabel baru untuk jawaban karena struktur yang sekarang sudah benar.

---

# 6. Form Resolution Baru

## 6.1 Urutan pencarian form

Saat user daftar ke program/batch/schema:

```text
1. Cari published form yang cocok dengan program_id + schema_id + batch_id
2. Jika tidak ada, cari published form program_id + schema_id
3. Jika tidak ada, cari published form program_id + schema_id null
4. Jika tidak ada, tampilkan pesan bahwa form belum tersedia
```

## 6.2 Field yang dirender

Setelah `form_id` ditemukan:

```text
ambil form_fields where form_id = selected form
status = aktif
deleted_at null
order by sort_order
```

## 6.3 Transisi dari sistem lama

Karena sebelumnya form_fields belum punya form_id, perlu backfill:

```text
1. Ambil form_fields existing yang belum punya form_id
2. Group berdasarkan program_id dan schema_id
3. Buat forms default untuk setiap group
4. Set form_fields.form_id ke forms default
```

---

# 7. Google Forms-like Admin Builder UI

## 7.1 Halaman Builder

Route:

```text
/dashboard-admin/forms
/dashboard-admin/forms/{form}/builder
```

Tampilan utama:

```text
Form Builder
------------------------------------------------
Program: [Pilih Program]
Schema: [Pilih Schema / Umum Program]
Batch: [Opsional]

[Preview] [Publish] [Save]
------------------------------------------------

Form Title
[____________________________]

Form Description
[____________________________]

+ Tambah Pertanyaan

------------------------------------------------
[Question Card 1]
Label: Nama Lengkap
Type: Short Answer
Role: Applicant Name
Required: ON
[Duplicate] [Delete]

[Question Card 2]
Label: Email
Type: Email
Role: Applicant Email
Required: ON
[Duplicate] [Delete]

[Question Card 3]
Label: Upload CV
Type: File Upload
Allowed: PDF, JPG, PNG
Max Size: 2048 KB
Required: ON
[Duplicate] [Delete]
```

---

## 7.2 Question Card

Setiap field tampil dalam bentuk card.

Isi card:

```text
label
field_name
type
field_role
placeholder
description
required toggle
status
options editor
file config editor
duplicate button
delete button
drag handle
```

---

## 7.3 Add Question

Admin klik:

```text
+ Tambah Pertanyaan
```

Muncul field baru:

```text
label kosong
type text
required false
status aktif
sort_order terakhir
field_role none
```

---

## 7.4 Type Mapping ala Google Forms

| UI Label        | Internal Type |
| --------------- | ------------- |
| Short Answer    | text          |
| Paragraph       | textarea      |
| Number          | number        |
| Email           | email         |
| Phone           | phone         |
| Date            | date          |
| Dropdown        | select        |
| Multiple Choice | radio         |
| Checkboxes      | checkbox      |
| File Upload     | file          |

---

## 7.5 Options Editor

Untuk:

```text
select
radio
checkbox
```

Admin bisa:

```text
- tambah option
- hapus option
- edit option value
- edit label Indonesia
- edit label Jepang
- reorder option
```

Format tetap:

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

---

## 7.6 File Upload Config

Untuk type file:

```text
Allowed file types:
[ ] PDF
[ ] JPG
[ ] JPEG
[ ] PNG
[ ] DOC
[ ] DOCX

Max file size:
[ 2048 ] KB

Required:
ON/OFF
```

Blocked extension tetap ditolak:

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

## 7.7 Duplicate Question

Admin bisa duplicate field.

Aturan:

```text
label: [label asli] Copy
field_name: [field_name asli]_copy
sort_order: setelah field asli
status: aktif
```

Jika field_name copy sudah ada:

```text
upload_cv_copy_2
upload_cv_copy_3
```

---

## 7.8 Delete Question

Delete tetap memakai soft delete.

Jangan hard delete field.

---

## 7.9 Drag and Drop Reorder

Gunakan drag handle pada card.

Endpoint:

```text
POST /dashboard-admin/forms/{form}/fields/reorder
```

Payload:

```json
[
    { "id": 10, "sort_order": 1 },
    { "id": 11, "sort_order": 2 }
]
```

Backend harus validasi semua field milik form tersebut.

---

## 7.10 Preview Form

Admin klik:

```text
Preview
```

Route:

```text
GET /dashboard-admin/forms/{form}/preview
```

Preview menampilkan form seperti user melihatnya.

Preview tidak submit data.

---

## 7.11 Publish / Draft

Form bisa:

```text
draft
published
archived
```

Aturan publish:

```text
1. Form harus punya minimal 1 field aktif
2. Form harus punya field_role applicant_name
3. Form harus punya minimal salah satu:
   - applicant_email
   - applicant_phone
4. Semua field choice harus punya options valid
5. Semua field file harus punya accepted_file_types aman
6. Tidak boleh ada field_name duplicate dalam form
```

---

# 8. Public Registration Form Baru

## 8.1 Hapus fixed input dari UI

Hapus input hardcoded dari Blade public form:

```text
nama
email
phone
tanggal_lahir
alamat
pendidikan
```

## 8.2 Hidden system data tetap ada

```text
program_id
batch_id
schema_id
form_id
```

## 8.3 Render field

Public form hanya render:

```text
form_fields dari form_id published
```

Form wajib:

```html
enctype="multipart/form-data"
```

---

# 9. Submit Flow Baru

```text
1. User buka form published
2. Sistem render form_fields berdasarkan form_id
3. User submit dynamic_answers dan dynamic_files
4. Validate form_id, program_id, batch_id, schema_id
5. Validate all fields berdasarkan form_fields aktif
6. Create Applicant
7. Save dynamic answers
8. Save dynamic files
9. Apply field_role mapping ke applicant columns
10. Save applicant
11. Redirect success
```

---

# 10. Field Role Mapping Flow

Pseudocode:

```php
$identityMap = [
    'applicant_name' => 'nama',
    'applicant_email' => 'email',
    'applicant_phone' => 'phone',
    'applicant_birth_date' => 'tanggal_lahir',
    'applicant_address' => 'alamat',
    'applicant_education' => 'pendidikan',
];

foreach ($fields as $field) {
    if ($field->field_role !== 'none') {
        $column = $identityMap[$field->field_role] ?? null;

        if ($column) {
            $applicant->{$column} = $dynamicValue;
        }
    }
}
```

---

# 11. Response Management seperti Google Forms

Tambahkan tab:

```text
Questions
Responses
```

## 11.1 Questions Tab

Berisi builder field.

## 11.2 Responses Tab

Menampilkan submissions.

Tabel:

```text
Submitted At | Applicant Name | Email/Phone | Program | Schema | Action
```

Action:

```text
View Response
Download Files
```

---

## 11.3 Response Detail

Tampilkan semua jawaban berdasarkan snapshot:

```text
Nama Lengkap: Umar
Email: xxx@gmail.com
Level Jepang: N4
Upload CV: Download
```

---

# 12. Export Responses

Tambahkan export:

```text
Export CSV
Export Excel optional
```

Minimal mulai dari CSV dulu.

Kolom export:

```text
submitted_at
program
batch
schema
field_1
field_2
field_3
file_field_original_name
```

Untuk file, export bisa berisi:

```text
original_name
```

atau protected download URL admin jika aman.

---

# 13. Migration Strategy

## 13.1 Task 5B migrations

```text
create_forms_table
add_form_id_field_role_is_locked_settings_to_form_fields
add_form_id_to_applicants
```

## 13.2 Backfill Strategy

1. Cari `form_fields` existing yang belum punya `form_id`.
2. Group berdasarkan `program_id` dan `schema_id`.
3. Buat `forms` default untuk setiap group (status: `published`).
4. Set `form_fields.form_id` untuk dynamic fields existing.
5. **Inject Base Fields:** Karena fixed input dihapus dari public form, backfill wajib meng-inject base fields berikut ke setiap default form (jika belum ada):
    - Nama Lengkap (text, field_role: applicant_name)
    - Email (email, field_role: applicant_email)
    - Nomor WhatsApp (phone, field_role: applicant_phone)
    - Tempat Lahir (text, field_role: none)
    - Tanggal Lahir (date, field_role: applicant_birth_date)
    - Alamat (textarea, field_role: applicant_address)
    - Pendidikan (text/select, field_role: applicant_education)
6. **Inject Default Document Fields:** Baca `config('programs.docs_per_slug')`. Konversi setiap dokumen (seperti CV, KTP, Ijazah, dll) menjadi `form_fields` bertipe `file`.
    - Aturan: `type = file`, `accepted_file_types = ["pdf", "jpg", "jpeg", "png", "doc", "docx"]`, `max_file_size = 2048`, `field_role = none`.
    - `is_required` mengikuti config (atau default true untuk dokumen wajib).
7. **Pencegahan Duplikasi:** Cek apakah base field atau document field sudah ada di `form_fields` sebelum membuat baru.
8. **Hasil Akhir:** Default form langsung layak dipublish karena sudah memiliki identitas utama dan dokumen wajib.

---

# 14. Task Breakdown

## Task 5A — Final Architecture & Migration Plan

### Tujuan

Menyetujui konsep 100% dynamic form dan Google Forms-like UX sebelum coding.

### File/Tabel yang Dikaji

```text
forms
form_fields
applicants
applicant_form_answers
applicant_dynamic_files
public registration form
admin form builder
```

### Detail Pekerjaan

```text
- review kebutuhan forms table
- review field_role strategy
- review fixed form removal
- review data lama applicants
- review response management
- review export strategy
```

### Batasan

```text
- jangan coding
- jangan drop kolom lama
- jangan ubah UI dulu
```

### Acceptance Criteria

```text
- konsep 100% dynamic disetujui
- forms table disetujui
- field_role disetujui
- fixed form removal strategy jelas
- data lama aman
```

### Risiko

```text
- salah strategi bisa merusak flow submit
- data lama applicant bisa sulit dibaca
```

---

## Task 5B — Database Upgrade & Backfill

### Tujuan

Menambahkan `forms` sebagai entity utama.

### File/Tabel yang Berubah

```text
forms table
form_fields table
applicants table
Form model
FormField model
Applicant model
Program model
ProgramSchema model
```

### Detail Pekerjaan

```text
- create forms table
- add form_id to form_fields
- add field_role to form_fields
- add is_locked to form_fields
- add settings to form_fields
- add form_id to applicants
- create Form model
- update relations
- create backfill command
```

### Batasan

```text
- jangan ubah UI
- jangan ubah public form
- jangan ubah submit handler
- jangan hapus kolom lama applicants
```

### Acceptance Criteria

```text
- migration berhasil
- existing form_fields punya form_id setelah backfill
- Form model berhasil
- relation berjalan
- applicant lama aman
```

### Risiko & Mitigasi

```text
Risiko: Jika base fields tidak di-inject, form published hasil backfill bisa kehilangan nama/email/phone/dokumen.
Mitigasi: Inject base fields dan document fields saat backfill untuk memastikan form langsung layak publish.
```

---

## Task 5C — Visual Google Forms-like Builder UI

### Tujuan

Membuat admin builder satu halaman seperti Google Forms.

### File/Tabel yang Berubah

```text
Admin FormController / FormBuilderController
Blade admin forms
Blade question cards
JS builder
routes admin forms
```

### Detail Pekerjaan

```text
- forms index
- builder page
- question card
- add question
- edit question inline
- duplicate question
- soft delete question
- reorder question
- preview form
- publish/draft
```

### Batasan

```text
- jangan ubah public form submit
- jangan ubah upload security
- jangan buat conditional logic
- jangan buat multi-page form
```

### Acceptance Criteria

```text
- admin bisa buat form
- admin bisa tambah pertanyaan
- admin bisa edit pertanyaan
- admin bisa duplicate
- admin bisa delete
- admin bisa reorder
- admin bisa preview
- admin bisa publish
```

### Risiko

```text
- JS builder terlalu kompleks
- inline editing bisa memicu validasi longgar
```

---

## Task 5D — Convert Public Registration to 100% Dynamic

### Tujuan

Menghapus fixed form dari public registration UI.

### File/Tabel yang Berubah

```text
public registration Blade
HomeController storePendaftaran
DynamicFormService / FormResolutionService
DynamicValidationService
```

### Detail Pekerjaan

```text
- hapus input fixed hardcoded dari Blade
- public form render by form_id
- hidden program_id, batch_id, schema_id, form_id
- submit handler dynamic only
- field_role mapping ke applicants columns
```

### Batasan

```text
- jangan drop kolom lama applicants
- jangan ubah admin builder besar-besaran
- jangan ubah file upload security
```

### Acceptance Criteria

```text
- tidak ada fixed user input hardcoded
- nama/email/phone dibuat dari Form Builder
- submit berhasil
- dynamic answers tersimpan
- applicant mirror columns terisi dari field_role
```

### Risiko

```text
- admin lupa membuat field name/email/phone
- applicant list kosong jika field_role tidak ada
```

---

## Task 5E — Responses Management

### Tujuan

Membuat halaman responses seperti Google Forms.

### File/Tabel yang Berubah

```text
Admin Form responses page
ApplicantController / FormResponseController
Blade response detail
```

### Detail Pekerjaan

```text
- tab Questions / Responses
- list responses per form
- view response detail
- dynamic answers display
- dynamic files display
- download file
```

### Batasan

```text
- jangan buat export dulu jika Task 5F terpisah
- jangan ubah upload storage
```

### Acceptance Criteria

```text
- admin bisa lihat response per form
- response lama aman
- file bisa didownload
- applicant lama tidak error
```

### Risiko

```text
- response query berat jika data banyak
```

---

## Task 5F — Export Responses

### Tujuan

Export jawaban dynamic.

### File/Tabel yang Berubah

```text
Export controller
CSV export route
optional Excel export
```

### Detail Pekerjaan

```text
- export CSV
- optional Excel
- dynamic columns dari form fields
- include submitted_at, program, batch, schema
- file column berisi original_name atau protected link
```

### Batasan

```text
- mulai dari CSV dulu
- Excel optional
```

### Acceptance Criteria

```text
- admin bisa export responses
- kolom sesuai form fields
- checkbox tampil rapi
- file tampil sebagai nama/link
```

### Risiko

```text
- export dynamic columns bisa kompleks
- file download link harus tetap protected
```

---

## Task 5G — Final QA

### Tujuan

Memastikan flow Google Forms-like berjalan end-to-end.

### Checklist

```text
- create form
- add questions
- duplicate question
- reorder question
- preview form
- publish form
- submit public form
- validate required
- upload file
- admin view response
- export response
- applicant legacy aman
```

### Acceptance Criteria

```text
- semua fitur PASS
- tidak ada Critical/High bug
- siap push
```

---

# 15. Risiko dan Mitigasi

| Risiko                                    | Level    | Mitigasi                                                         |
| ----------------------------------------- | -------- | ---------------------------------------------------------------- |
| Data applicant lama rusak                 | Critical | Jangan drop kolom lama, pakai legacy/mirror                      |
| Admin lupa membuat name/email/phone field | High     | Publish validation wajib field_role applicant_name + email/phone |
| Form tidak punya published version        | High     | Public page tampil pesan form belum tersedia                     |
| field_role duplikat dalam form            | High     | Validasi satu role per form                                      |
| Submit tanpa form_id valid                | High     | Validasi form published                                          |
| Export dynamic terlalu kompleks           | Medium   | Mulai dari CSV dulu                                              |
| Drag/drop reorder bug                     | Medium   | Validasi field milik form                                        |
| Duplicate field_name                      | High     | Generate unique field_name                                       |
| File upload security regress              | Critical | Reuse DynamicFileUploadService existing                          |
| Fixed form dihapus terlalu cepat          | High     | UI saja yang dihapus, kolom DB tetap                             |
| Response query berat                      | Medium   | Gunakan eager loading dan pagination                             |

---

# 16. Acceptance Criteria Final

Sistem dianggap berhasil jika:

```text
- Semua input user dibuat dari dashboard admin
- Public form tidak punya fixed input hardcoded
- Admin bisa membuat form seperti Google Forms
- Admin bisa tambah pertanyaan
- Admin bisa ubah tipe pertanyaan
- Admin bisa tambah options
- Admin bisa membuat field upload file
- Admin bisa set required
- Admin bisa duplicate field
- Admin bisa reorder field
- Admin bisa preview form
- Admin bisa publish form
- User bisa submit form
- Jawaban tersimpan dynamic
- File tersimpan private
- Applicant tetap punya nama/email/phone via field_role mapping
- Admin bisa lihat responses
- Admin bisa export responses
- Data lama aman
```

---

# 17. Urutan Implementasi Wajib

```text
1. Task 5A — Final Architecture Review
2. Task 5B — Database Upgrade & Backfill
3. Audit Task 5B
4. Task 5C — Visual Builder UI
5. Audit Task 5C
6. Task 5D — Convert Public Registration to 100% Dynamic
7. Audit Task 5D
8. Task 5E — Responses Management
9. Audit Task 5E
10. Task 5F — Export Responses
11. Audit Task 5F
12. Task 5G — Final QA
```

Jangan langsung mengerjakan semua Task 5 sekaligus.

---

# 18. Prompt Awal untuk AI Agent — Task 5A Planning Review

Gunakan prompt ini sebelum coding:

```text
Baca plan Google Forms-like 100% Dynamic Form Builder.

Tugas Anda sekarang hanya melakukan review Task 5A:
- validasi konsep
- cari risiko
- cek apakah forms table dibutuhkan
- cek apakah field_role strategy aman
- cek dampak penghapusan fixed form dari UI
- cek strategi menjaga data applicant lama
- jangan coding dulu

Berikan output:
1. apakah plan layak
2. risiko tambahan
3. bagian yang perlu diperbaiki
4. rekomendasi sebelum Task 5B implementation
```

---

# 19. Prompt Implementasi Pertama — Task 5B

Gunakan hanya setelah Task 5A disetujui.

```text
Kerjakan Task 5B — Database Upgrade & Backfill untuk Google Forms-like 100% Dynamic Form Builder.

FOKUS:
- create forms table
- add form_id, field_role, is_locked, settings to form_fields
- add form_id to applicants
- create Form model
- update relations
- create backfill command untuk form_fields existing
- jangan ubah UI
- jangan ubah submit handler dulu
- jangan hapus fixed columns applicants

JANGAN mengerjakan:
- Visual Builder UI
- Public dynamic-only form
- Responses tab
- Export
- Conditional logic
- Multi-page form

Output akhir:
- migration yang dibuat
- model yang dibuat/diubah
- command backfill yang dibuat
- hasil migrate
- hasil backfill
- risiko sebelum Task 5C
```

---

# 20. Kesimpulan

Plan ini mengubah sistem dari:

```text
Dynamic additional fields
```

menjadi:

```text
100% dynamic Google Forms-like registration system
```

Dengan tetap menjaga keamanan:

```text
- tidak drop data lama
- tidak hapus kolom lama
- tetap pakai private upload
- tetap pakai protected download
- tetap pakai field snapshots
- tetap pakai applicant sebagai parent submission
```

# Tambahan Final Sebelum Implementasi

1. Hanya boleh ada satu active published form untuk kombinasi program_id + schema_id + batch_id.

2. Public form resolution harus memilih forms terlebih dahulu, lalu mengambil form_fields berdasarkan form_id.

3. Setelah forms table digunakan, frontend tidak boleh mengambil form_fields langsung berdasarkan program_id/schema_id.

4. Backfill form existing sebaiknya dibuat published agar flow existing tidak rusak.

5. applicants perlu menyimpan:
    - form_id
    - form_version_snapshot
    - form_title_snapshot nullable json

6. Publish validation wajib memastikan:
    - minimal 1 field aktif
    - field_role applicant_name ada dan hanya satu
    - minimal applicant_email atau applicant_phone ada
    - field_role tidak duplikat dalam satu form
    - field_name tidak duplikat dalam satu form
    - choice options valid
    - file config aman

7. Form Builder lama tidak langsung dihapus.
   Untuk transisi, jadikan fallback/advanced mode atau redirect ke builder baru.

8. Task 5C tidak membuat autosave dulu.
   Gunakan tombol Save manual.

9. Responses tab harus berbasis form_id.

10. Data applicant lama tetap aman dan tetap bisa dibuka walaupun belum punya form_id.
