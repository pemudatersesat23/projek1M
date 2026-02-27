// ═══════════════ NAVBAR & UI ═══════════════

// Navbar scroll effect
const nav = document.getElementById('navbar');
window.addEventListener('scroll', () => {
    nav.classList.toggle('scrolled', window.scrollY > 20);
});

// Active nav link
const sections = document.querySelectorAll('section[id]');
const navLinks = document.querySelectorAll('.nav-links a');
function updateActive() {
    let current = '';
    sections.forEach(s => { if (window.scrollY >= s.offsetTop - 100) current = s.id; });
    navLinks.forEach(a => { a.classList.toggle('active', a.getAttribute('href') === '#' + current); });
}
window.addEventListener('scroll', updateActive);

// Hamburger
const hambtn = document.getElementById('hambtn');
const mobmenu = document.getElementById('mobmenu');
hambtn.addEventListener('click', () => { hambtn.classList.toggle('open'); mobmenu.classList.toggle('open'); });
mobmenu.querySelectorAll('a').forEach(a => { a.addEventListener('click', () => { hambtn.classList.remove('open'); mobmenu.classList.remove('open'); }); });

// Scroll reveal
const reveals = document.querySelectorAll('.reveal');
const obs = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
}, { threshold: 0.12 });
reveals.forEach(r => obs.observe(r));
