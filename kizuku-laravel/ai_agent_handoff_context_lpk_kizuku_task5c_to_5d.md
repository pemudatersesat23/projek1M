# HANDOFF CONTEXT — LPK Kizuku Laravel Project
## Google Forms-like 100% Dynamic Form Builder

Dokumen ini digunakan untuk membuka conversation baru di AI Agent agar agent baru memahami status project saat ini tanpa harus membaca seluruh chat lama.

---

# 1. Project Context

Project ini adalah website Laravel untuk LPK Bahasa Jepang.

Fitur revisi client yang sedang dikerjakan berfokus pada:

```text
- Program Management
- Batch Management
- Program Schema
- Dynamic Form Builder
- Google Forms-like 100% Dynamic Form Builder
```

Target terbaru:

```text
Mengubah sistem pendaftaran dari Fixed Form + Dynamic Fields Tambahan
menjadi 100% Dynamic Form seperti Google Forms.
```

Artinya:

```text
Semua input user pada formulir pendaftaran dibuat dan diatur dari dashboard admin.
Tidak ada lagi input user hardcoded di Blade public registration form.
```

Namun data sistem tetap hidden:

```text
program_id
batch_id
schema_id
form_id
status pendaftaran
created_at
updated_at
```

---

# 2. Task yang Sudah Selesai

## Task 1–3 — Program System

Sudah selesai:

```text
✅ Program Management
✅ Batch Management
✅ Program Schema
✅ Frontend Program Slider
✅ Program Detail Dynamic
✅ Batch CTA
✅ Schema Selector
```

Catatan penting:

```text
schema_id sudah divalidasi:
- nullable
- harus milik program_id terkait
- status schema aktif
- tidak soft-deleted
```

---

# 3. Task 4 — Dynamic Form Builder

Task 4 sudah selesai dan sudah diaudit final.

Fitur yang sudah selesai:

```text
✅ form_fields table
✅ applicant_form_answers table
✅ applicant_dynamic_files table
✅ Admin Form Builder CRUD
✅ Frontend Dynamic Form Renderer
✅ Dynamic Submit Handling
✅ DynamicValidationService
✅ DynamicFileUploadService
✅ Private storage upload
✅ Protected download route
✅ Admin Applicant Detail dynamic answers/files
```

## Status Task 4

```text
READY TO PUSH / sudah selesai
```

## Catatan Task 4

Task 4 awalnya masih memakai konsep:

```text
Fixed Form + Dynamic Fields Tambahan
```

Contoh fixed fields lama:

```text
nama
email
phone
tanggal_lahir
alamat
pendidikan
```

Dynamic fields pada Task 4 hanya menjadi field tambahan.

---

# 4. Perubahan Konsep Setelah Task 4

Setelah Task 4, konsep diubah menjadi:

```text
100% Dynamic Form Builder seperti Google Forms
```

Artinya field seperti:

```text
Nama Lengkap
Email
Nomor WhatsApp
Alamat
Tanggal Lahir
Pendidikan
Upload CV
Upload KTP
Upload Ijazah
```

juga harus dibuat melalui Form Builder, bukan hardcoded di Blade.

Kolom lama di tabel `applicants` tidak dihapus. Kolom lama menjadi legacy/mirror columns.

---

# 5. Field Role Strategy

Karena semua field menjadi dynamic, sistem perlu tahu field mana yang menjadi identitas applicant.

Tambahan konsep:

```text
field_role
```

pada `form_fields`.

Daftar role:

```text
none
applicant_name
applicant_email
applicant_phone
applicant_birth_date
applicant_address
applicant_education
```

Mapping:

```text
applicant_name       -> applicants.nama
applicant_email      -> applicants.email
applicant_phone      -> applicants.phone
applicant_birth_date -> applicants.tanggal_lahir
applicant_address    -> applicants.alamat
applicant_education  -> applicants.pendidikan
```

Ini dipakai nanti saat Task 5D mengubah submit handler agar semua input user 100% dynamic tetapi kolom lama tetap terisi sebagai mirror.

---

# 6. Task 5A — Final Architecture Review

Task 5A sudah selesai.

Hasil review awal menemukan risiko penting:

```text
Jika fixed input dihapus dari UI, lalu backfill hanya memindahkan form_fields existing,
maka form lama bisa kehilangan Nama, Email, WhatsApp, Alamat, Pendidikan, dan dokumen wajib.
```

Solusi yang disetujui:

```text
Backfill wajib meng-inject base fields dan default document fields.
```

---

# 7. Task 5B — Database Upgrade & Backfill

Task 5B sudah selesai dan sudah diaudit.

## Migration yang dibuat

```text
1. create_forms_table
2. add_form_columns_to_form_fields_table
3. add_form_columns_to_applicants_table
```

## Tabel forms

Tabel `forms` dibuat sebagai entity utama seperti Google Forms.

Fields penting:

```text
id
program_id
schema_id nullable
batch_id nullable
title json
description json nullable
success_message json nullable
status draft/published/archived
is_active
accepts_responses
version
published_at
timestamps
softDeletes
```

## Update form_fields

Ditambahkan:

```text
form_id
field_role
is_locked
settings
```

Catatan:

```text
program_id dan schema_id lama di form_fields tetap dipertahankan untuk backward compatibility.
```

## Update applicants

Ditambahkan:

```text
form_id
form_version_snapshot
form_title_snapshot
```

Kolom lama tetap ada:

```text
nama
email
phone
tanggal_lahir
alamat
pendidikan
```

## Model

Model `Form` sudah dibuat.

Model existing yang diperbarui:

```text
Program
ProgramSchema
Batch
FormField
Applicant
```

## Backfill command

Command:

```bash
php artisan forms:backfill-defaults
```

Fungsi:

```text
- membuat default forms
- menghubungkan form_fields lama ke form_id
- inject base fields
- inject default document fields dari config('programs.docs_per_slug')
- mencegah duplikasi
- idempotent
```

## Hasil backfill

```text
Forms created: 8
FormFields linked: 9
Base fields injected: 56
Document fields injected: 42
FormFields remaining with null form_id: 0
No warnings
```

## Audit Task 5B

Status:

```text
READY FOR TASK 5C
```

---

# 8. Task 5C — Visual Google Forms-like Builder UI

Task 5C sudah dikerjakan.

Fitur yang dibuat:

```text
✅ Forms index
✅ Builder page
✅ Question cards
✅ Add question
✅ Edit question
✅ Soft delete question
✅ Duplicate question
✅ Drag and drop reorder
✅ Options editor
✅ File config editor
✅ Preview form
✅ Publish/Draft/Archive
✅ Sidebar update
✅ Form Builder lama tetap sebagai Advanced Fields
```

Controller yang dibuat:

```text
app/Http/Controllers/Admin/FormController.php
app/Http/Controllers/Admin/FormBuilderFieldController.php
```

Request/validator yang dibuat:

```text
FormPublishRequest
FormRequest / Form field validation existing updated
```

UI:

```text
Vanilla JS
AJAX/fetch
SortableJS untuk drag and drop
```

## Bug yang ditemukan dan sudah diperbaiki

### 1. Preview Error 500

Error:

```text
Call to a member function getTranslation() on array
```

Penyebab:

```text
Component dynamic-form.field menerima array, padahal butuh Eloquent model FormField.
```

Fix:

```text
preview.blade.php sekarang mengirim instance model Eloquent $field langsung ke component.
```

### 2. Save/Delete 405 Method Not Allowed

Penyebab:

```text
fetch tidak mengirim Accept: application/json.
Laravel validation redirect 302 ke referer, browser mempertahankan method PATCH/DELETE, lalu route GET menghasilkan 405.
```

Fix:

```text
Tambahkan Accept: application/json pada semua fetch request di builder.blade.php.
```

### 3. Update/Duplicate Field 422

Penyebab:

```text
FormFieldRequest membaca route param {form_field}, sedangkan builder baru memakai {field}.
Unique except self tidak mengenali ID field yang sedang diedit.
```

Fix:

```text
FormFieldRequest sekarang membaca baik route param form_field maupun field.
```

## Status Task 5C

Laporan terakhir mengatakan:

```text
Task 5C selesai dan lulus audit fungsional.
```

Namun sebelum lanjut Task 5D, disarankan melakukan quick regression audit.

---

# 9. Quick Regression Audit Task 5C yang Disarankan

Sebelum lanjut Task 5D, cek ulang:

```text
1. Preview form tidak error 500
2. Save question card tidak error 405
3. Delete question card tidak error 405
4. Duplicate question tidak error 422
5. Drag/drop reorder berhasil
6. Publish validation berhasil
7. Advanced Fields lama masih aman
8. route:list, optimize:clear, migrate:status aman
```

Jika semua PASS:

```text
READY FOR TASK 5D
```

---

# 10. Task Berikutnya: Task 5D

Task berikutnya adalah:

```text
Task 5D — Convert Public Registration to 100% Dynamic
```

Task ini sensitif karena akan mengubah public registration form.

## Tujuan Task 5D

```text
- Hapus fixed input hardcoded dari public registration form
- Public form render berdasarkan selected published form_id
- Semua user input berasal dari form_fields
- Submit handler menggunakan dynamic fields sepenuhnya
- field_role mapping mengisi kolom legacy applicants
- applicant_form_answers tetap menyimpan semua jawaban
- applicant_dynamic_files tetap menyimpan semua file
```

## Yang belum boleh dikerjakan sebelum Task 5D dimulai

```text
❌ Responses tab
❌ Export responses
❌ Conditional logic
❌ Multi-page form
❌ Auto-save
❌ Refactor besar di luar registration form
```

---

# 11. Important Boundaries

AI agent baru harus memahami batasan ini:

```text
1. Jangan menghapus kolom lama applicants.
2. Jangan drop table lama.
3. Jangan merusak Task 4 dynamic validation/upload security.
4. Jangan menghapus Form Builder lama dulu; tetap sebagai Advanced Fields.
5. Jangan membuat Responses/Export sebelum Task 5E/5F.
6. Jangan membuat conditional logic/multi-page/autosave.
7. Jangan lanjut Task 5D jika quick regression audit Task 5C belum PASS.
```

---

# 12. Files / Concepts Agent Baru Perlu Cek

Agent baru perlu membaca file terkait:

```text
app/Models/Form.php
app/Models/FormField.php
app/Models/Applicant.php
app/Models/Program.php
app/Models/ProgramSchema.php
app/Models/Batch.php

app/Http/Controllers/Admin/FormController.php
app/Http/Controllers/Admin/FormBuilderFieldController.php
app/Http/Controllers/HomeController.php

app/Http/Requests/Admin/FormPublishRequest.php
app/Http/Requests/Admin/FormFieldRequest.php
app/Http/Requests/PendaftaranRequest.php

app/Services/DynamicFormService.php
app/Services/DynamicForm/DynamicValidationService.php
app/Services/DynamicForm/DynamicFileUploadService.php

resources/views/admin/forms/index.blade.php
resources/views/admin/forms/builder.blade.php
resources/views/admin/forms/preview.blade.php
resources/views/components/dynamic-form/field.blade.php
resources/views/components/program/detail/registration-form.blade.php
resources/views/components/program/detail/batch-section.blade.php

routes/web.php
config/dynamic_forms.php
config/programs.php
```

---

# 13. Suggested Prompt for New AI Agent

Copy prompt berikut ke conversation baru di AI agent:

```text
Saya sedang mengerjakan project Laravel LPK Kizuku.

Baca file HANDOFF CONTEXT ini terlebih dahulu sampai selesai.

Tugas Anda:
1. Pahami status project saat ini.
2. Jangan langsung coding.
3. Konfirmasi bahwa Anda memahami:
   - Task 4 sudah selesai
   - Task 5A selesai
   - Task 5B selesai
   - Task 5C sudah dikerjakan dan bug utama sudah diperbaiki
   - sebelum Task 5D perlu quick regression audit Task 5C
4. Setelah memahami, lakukan QUICK REGRESSION AUDIT Task 5C dulu.
5. Jangan mulai Task 5D sebelum audit Task 5C berstatus READY FOR TASK 5D.

Batasan:
- Jangan membuat fitur baru.
- Jangan mengubah public registration form dulu.
- Jangan mengubah submit handler dulu.
- Jangan membuat responses tab.
- Jangan membuat export.
- Jangan membuat conditional logic.
- Jangan membuat multi-page form.
- Jangan menghapus kolom lama applicants.
```

---

# 14. Quick Regression Audit Prompt for New Agent

Gunakan prompt ini setelah agent membaca context:

```text
Lakukan QUICK REGRESSION AUDIT untuk Task 5C — Visual Google Forms-like Builder UI setelah bug fixing.

KONTEKS:
Task 5C sudah selesai, tetapi ada bug yang sudah diperbaiki:
1. Preview error 500 karena komponen menerima array, sudah diperbaiki dengan mengirim Eloquent model FormField langsung.
2. Save/Delete error 405 karena fetch tidak mengirim Accept: application/json, sudah diperbaiki.
3. Update field error 422 karena route parameter berbeda antara form_field dan field, sudah diperbaiki di FormFieldRequest.

TUJUAN:
Pastikan semua bug benar-benar sudah selesai dan tidak menimbulkan regression sebelum lanjut Task 5D.

JANGAN mengerjakan fitur baru.
JANGAN mengubah public registration form.
JANGAN mengubah submit handler.
JANGAN membuat responses tab.
JANGAN membuat export.

Cek:
1. Preview form
2. Save question card
3. Delete question card
4. Duplicate question
5. Reorder drag/drop
6. Publish validation
7. Advanced Fields lama
8. route:list
9. optimize:clear
10. migrate:status

OUTPUT:
- PASS/FAIL setiap poin
- error jika ada
- final verdict:
  READY FOR TASK 5D / NEED MINOR FIX / NOT READY
```

---

# 15. If Regression Audit PASS

Jika quick regression audit PASS, baru lanjut ke Task 5D.

Task 5D belum dibuat prompt finalnya dalam file ini. Prompt Task 5D harus dibuat setelah regression audit PASS.
