/* BHARAT TRADING — pages.js (inner pages, no dependencies) */
(() => {
  'use strict';
  const $  = (s, c = document) => c.querySelector(s);
  const $$ = (s, c = document) => Array.from(c.querySelectorAll(s));

  /* product gallery: thumbnails swap the main image (the lightbox reads the link at click time) */
  const main = $('#galMain'), img = $('#galImg');
  if (main && img) {
    $$('.gal-thumb').forEach(btn => btn.addEventListener('click', () => {
      $$('.gal-thumb').forEach(b => b.classList.toggle('is-active', b === btn));
      main.href = btn.dataset.src;
      img.src = btn.dataset.src;
    }));
  }

  /* sort dropdown submits by itself */
  $$('select[data-autosubmit]').forEach(sel => sel.addEventListener('change', () => sel.form && sel.form.submit()));

  /* FAQ: keep one answer open at a time inside a list */
  $$('.faq').forEach(list => {
    const items = $$('details', list);
    items.forEach(d => d.addEventListener('toggle', () => {
      if (d.open) items.forEach(o => { if (o !== d) o.open = false; });
    }));
  });

  /* product page sub-navigation highlights the section being read */
  const links = $$('.subnav a[href^="#"]:not(.subnav-cta)');
  if (links.length && 'IntersectionObserver' in window) {
    const map = new Map();
    links.forEach(l => { const s = $(l.getAttribute('href')); if (s) map.set(s, l); });
    const spy = new IntersectionObserver(entries => entries.forEach(en => {
      if (!en.isIntersecting) return;
      links.forEach(l => l.classList.remove('active'));
      map.get(en.target).classList.add('active');
    }), { rootMargin: '-35% 0px -55% 0px' });
    map.forEach((_, s) => spy.observe(s));
  }
})();
