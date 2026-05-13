GOOGLE FORMS BUILDER CLONE - SIAP PAKAI

Isi folder:
1. BUKA_LANGSUNG_google_forms_builder.html
   - Cara paling mudah.
   - Double click file ini untuk melihat aplikasi langsung di browser.
   - Tidak perlu npm install dan tidak perlu Vite.

2. static-dist/
   - Versi build production.
   - Bisa di-hosting ke Netlify, Vercel, GitHub Pages, atau server biasa.
   - Kalau mau jalankan lokal dengan server:
     cd static-dist
     py -m http.server 5173
     lalu buka http://localhost:5173

3. source-vite-clean/
   - Source React/Vite yang bersih.
   - Sudah tidak ada package-lock.json dari environment internal.
   - Sudah ada .npmrc agar memakai registry publik npm.
   - Cara menjalankan development:
     cd source-vite-clean
     npm install
     npm run dev

4. screenshots/
   - Contoh tampilan aplikasi.

Catatan:
- Tombol Send, Import, Add image, Add video, dan Add section masih placeholder.
- Fitur utama builder, tambah pertanyaan, ubah tipe pertanyaan, preview, submit, dan export JSON sudah berjalan.
