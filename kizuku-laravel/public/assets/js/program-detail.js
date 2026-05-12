/**
 * Program Detail Page — JavaScript
 * Dipindahkan dari @push('scripts') di program-detail.blade.php
 */
document.addEventListener('DOMContentLoaded', function () {

  // ── Scroll Reveal ──────────────────────────────────────────────────
  const revealObserver = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        entry.target.classList.add('active');
      }
    });
  }, { threshold: 0.1 });

  document.querySelectorAll('.reveal').forEach(function (el) {
    revealObserver.observe(el);
  });

  // ── Smooth Scroll untuk anchor links ──────────────────────────────
  document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
    anchor.addEventListener('click', function (e) {
      const target = document.querySelector(this.getAttribute('href'));
      if (target) {
        e.preventDefault();
        target.scrollIntoView({ behavior: 'smooth' });
      }
    });
  });

});

// ── File Upload Preview ──────────────────────────────────────────────
// Fungsi ini dipanggil inline dari onchange attribute upload zone,
// sehingga harus berada di scope global (bukan di dalam DOMContentLoaded).
function updateFileName(input, zoneId) {
  var zone = document.getElementById(zoneId);
  if (!zone) return;

  var fileNameDisplay = zone.querySelector('.file-name-display');
  var uploadText      = zone.querySelector('.upload-text');

  // Teks localised disuntikkan dari blade ke dataset pada wrapper zone
  var selectedText    = zone.dataset.selectedText    || 'File dipilih';
  var placeholderText = zone.dataset.placeholderText || 'Klik untuk upload';

  if (input.files && input.files[0]) {
    if (fileNameDisplay) fileNameDisplay.textContent = input.files[0].name;
    zone.classList.add('file-selected');
    if (uploadText) uploadText.textContent = selectedText;
  } else {
    zone.classList.remove('file-selected');
    if (uploadText) uploadText.textContent = placeholderText;
    if (fileNameDisplay) fileNameDisplay.textContent = '';
  }
}
