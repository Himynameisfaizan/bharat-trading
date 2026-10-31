/* BHARAT TRADING — main.js (no dependencies) */
(() => {
  'use strict';
  const $  = (s, c = document) => c.querySelector(s);
  const $$ = (s, c = document) => Array.from(c.querySelectorAll(s));
  const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ---------- sticky header + back-to-top ---------- */
  const header = $('#siteHeader'), toTop = $('#toTop'), heroEl = $('.hero');
  const onScroll = () => {
    if (heroEl && !reduce && window.scrollY < 900) heroEl.style.setProperty('--py', (window.scrollY * 0.18).toFixed(1) + 'px');
    header && header.classList.toggle('is-stuck', window.scrollY > 40);
    toTop && toTop.classList.toggle('show', window.scrollY > 700);
  };
  onScroll();
  window.addEventListener('scroll', onScroll, { passive: true });
  toTop && toTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: reduce ? 'auto' : 'smooth' }));

  /* ---------- mobile menu ---------- */
  const burger = $('#burger'), scrim = $('#navScrim');
  const setNav = open => {
    document.body.classList.toggle('nav-open', open);
    burger.setAttribute('aria-expanded', String(open));
    burger.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
  };
  if (burger) {
    burger.addEventListener('click', () => setNav(!document.body.classList.contains('nav-open')));
    scrim && scrim.addEventListener('click', () => setNav(false));
    $$('#mainNav a').forEach(a => a.addEventListener('click', () => setNav(false)));
    document.addEventListener('keydown', e => { if (e.key === 'Escape') setNav(false); });
  }

  /* ---------- hero slider ---------- */
  const hero = $('.hero');
  if (hero) {
    const slides = $$('.hero-slide', hero), dots = $$('.hero-dots button', hero), cur = $('#heroCur');
    const DUR = 6500;
    let i = 0, timer = null;
    hero.style.setProperty('--dur', DUR + 'ms');

    const queue = () => {
      clearTimeout(timer);
      if (slides.length > 1 && !reduce) timer = setTimeout(() => show(i + 1), DUR);
    };
    const show = n => {
      i = (n + slides.length) % slides.length;
      slides.forEach((s, k) => { s.classList.toggle('is-active', k === i); s.setAttribute('aria-hidden', String(k !== i)); });
      dots.forEach((d, k) => {
        d.classList.toggle('is-active', k === i);
        const bar = d.firstElementChild;
        if (bar) { bar.style.animation = 'none'; void bar.offsetWidth; bar.style.animation = ''; }
      });
      if (cur) cur.textContent = String(i + 1).padStart(2, '0');
      queue();
    };
    $('#heroPrev') && $('#heroPrev').addEventListener('click', () => show(i - 1));
    $('#heroNext') && $('#heroNext').addEventListener('click', () => show(i + 1));
    dots.forEach((d, k) => d.addEventListener('click', () => show(k)));

    let x0 = null;
    hero.addEventListener('touchstart', e => { x0 = e.touches[0].clientX; }, { passive: true });
    hero.addEventListener('touchend', e => {
      if (x0 === null) return;
      const dx = e.changedTouches[0].clientX - x0;
      if (Math.abs(dx) > 50) show(i + (dx < 0 ? 1 : -1));
      x0 = null;
    }, { passive: true });
    document.addEventListener('visibilitychange', () => { document.hidden ? clearTimeout(timer) : show(i); });
    queue();
  }

  /* ---------- scroll reveal + counters ---------- */
  const countUp = el => {
    const to = parseInt(el.dataset.to, 10) || 0;
    if (reduce || to < 2) { el.textContent = to.toLocaleString('en-IN'); return; }
    const t0 = performance.now(), dur = 1400;
    const step = t => {
      const p = Math.min(1, (t - t0) / dur), e = 1 - Math.pow(1 - p, 3);
      el.textContent = Math.round(to * e).toLocaleString('en-IN');
      if (p < 1) requestAnimationFrame(step);
    };
    requestAnimationFrame(step);
  };
  const revealEls = $$('.reveal'), counters = $$('.count');
  if ('IntersectionObserver' in window) {
    const io = new IntersectionObserver(entries => entries.forEach(en => {
      if (!en.isIntersecting) return;
      en.target.classList.add('in');
      $$('.count', en.target).forEach(countUp);
      io.unobserve(en.target);
    }), { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
    revealEls.forEach((el, k) => {
      // light stagger for items that sit next to each other in a grid
      const sib = el.parentElement ? Array.from(el.parentElement.children).filter(c => c.classList.contains('reveal')).indexOf(el) : 0;
      if (sib > 0) el.style.animationDelay = Math.min(sib, 5) * 70 + 'ms';
      io.observe(el);
    });
  } else {
    revealEls.forEach(el => el.classList.add('in'));
    counters.forEach(countUp);
  }

  /* ---------- product filter ---------- */
  const tabs = $$('.ptab'), cards = $$('.pcard');
  tabs.forEach(tab => tab.addEventListener('click', () => {
    tabs.forEach(t => t.classList.toggle('is-active', t === tab));
    const f = tab.dataset.filter;
    cards.forEach(c => { c.hidden = !(f === 'all' || c.dataset.cat === f); c.classList.add('in'); });
  }));

  /* ---------- "Get quote" pre-selects the product in the form ---------- */
  const sel = $('#inqProduct');
  $$('[data-quote]').forEach(a => a.addEventListener('click', () => {
    if (!sel) return;
    const name = a.dataset.quote;
    let opt = Array.from(sel.options).find(o => o.value === name || o.text === name);
    if (!opt) { opt = new Option(name, name); sel.add(opt, sel.options[1]); }
    sel.value = opt.value;
  }));

  /* ---------- reviews scroller ---------- */
  const track = $('#rvTrack');
  if (track) {
    const by = dir => track.scrollBy({ left: dir * Math.max(300, track.clientWidth * 0.8), behavior: reduce ? 'auto' : 'smooth' });
    $('#rvPrev') && $('#rvPrev').addEventListener('click', () => by(-1));
    $('#rvNext') && $('#rvNext').addEventListener('click', () => by(1));
  }

  /* ---------- gallery lightbox ---------- */
  const items = $$('[data-lightbox]');
  if (items.length) {
    const lb = document.createElement('div');
    lb.className = 'lb';
    lb.innerHTML = '<button type="button" aria-label="Close">&times;</button><div><img alt=""><p></p></div>';
    document.body.appendChild(lb);
    const img = $('img', lb), cap = $('p', lb);
    const close = () => lb.classList.remove('open');
    items.forEach(a => a.addEventListener('click', e => {
      e.preventDefault();
      img.src = a.href; img.alt = a.dataset.lightbox; cap.textContent = a.dataset.lightbox;
      lb.classList.add('open');
    }));
    lb.addEventListener('click', e => { if (e.target !== img) close(); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') close(); });
  }

  /* ---------- scroll-spy for the nav ---------- */
  const links = $$('#mainNav li a[href^="#"]');
  if (links.length && 'IntersectionObserver' in window) {
    const map = new Map();
    links.forEach(l => { const s = $(l.getAttribute('href')); if (s) map.set(s, l); });
    const spy = new IntersectionObserver(entries => entries.forEach(en => {
      if (en.isIntersecting) { links.forEach(l => l.classList.remove('active')); map.get(en.target).classList.add('active'); }
    }), { rootMargin: '-45% 0px -50% 0px' });
    map.forEach((_, s) => spy.observe(s));
  }

  /* ---------- enquiry form (AJAX) ---------- */
  const form = $('#inqForm'), status = $('#inqStatus');
  if (form) {
    const say = (msg, ok) => { status.textContent = msg; status.className = 'f-status ' + (ok ? 'ok' : 'err'); };
    form.addEventListener('submit', async e => {
      e.preventDefault();
      let bad = null;
      $$('input[required],textarea[required]', form).forEach(f => {
        const invalid = !f.value.trim() || (f.type === 'email' && !/^\S+@\S+\.\S+$/.test(f.value));
        f.classList.toggle('is-invalid', invalid);
        if (invalid && !bad) bad = f;
      });
      if (bad) { say('Please fill in the highlighted fields.', false); bad.focus(); return; }

      const btn = $('button[type="submit"]', form), label = btn.textContent;
      btn.disabled = true; btn.textContent = 'Sending…'; say('', true);
      try {
        const res = await fetch(form.action, { method: 'POST', body: new FormData(form), headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const data = await res.json();
        say(data.message || 'Something went wrong.', !!data.ok);
        if (data.ok) form.reset();
      } catch (err) {
        say('Network error. Please try again or call us directly.', false);
      } finally {
        btn.disabled = false; btn.textContent = label;
      }
    });
    form.addEventListener('input', e => e.target.classList.remove('is-invalid'));
  }
})();
