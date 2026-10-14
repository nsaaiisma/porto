(() => {
  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const $ = (s, c = document) => c.querySelector(s);
  const $$ = (s, c = document) => [...c.querySelectorAll(s)];

  /* ---------- Navigasi ---------- */
  const nav = $('#nav'), toggle = $('#navToggle'), links = $('#navLinks');
  const onScroll = () => nav.classList.toggle('is-scrolled', window.scrollY > 24);
  onScroll();
  window.addEventListener('scroll', onScroll, { passive: true });

  toggle.addEventListener('click', () => {
    const open = links.classList.toggle('is-open');
    toggle.setAttribute('aria-expanded', open);
    toggle.setAttribute('aria-label', open ? 'Tutup menu' : 'Buka menu');
  });
  $$('a', links).forEach(a => a.addEventListener('click', () => {
    links.classList.remove('is-open');
    toggle.setAttribute('aria-expanded', 'false');
  }));

  // Tandai menu sesuai bagian yang sedang dilihat
  const navMap = new Map($$('a[href^="#"]', links).map(a => [a.getAttribute('href').slice(1), a]));
  const spy = new IntersectionObserver(entries => {
    entries.forEach(e => {
      if (!e.isIntersecting) return;
      navMap.forEach(a => a.classList.remove('is-current'));
      navMap.get(e.target.id)?.classList.add('is-current');
    });
  }, { rootMargin: '-45% 0px -50% 0px' });
  navMap.forEach((_, id) => { const s = document.getElementById(id); if (s) spy.observe(s); });

  /* ---------- Blob mengikuti kursor ---------- */
  const hero = $('#beranda');
  if (!reduceMotion && hero && window.matchMedia('(hover:hover)').matches) {
    hero.addEventListener('pointermove', e => {
      const r = hero.getBoundingClientRect();
      const x = (e.clientX - r.left) / r.width - .5;
      const y = (e.clientY - r.top) / r.height - .5;
      $$('.blob', hero).forEach(b => {
        const d = +b.dataset.depth;
        b.style.transform = `translate(${x * d}px, ${y * d}px)`;
      });
    });
  }

  /* ---------- Efek mengetik: peran ---------- */
  const roleEl = $('#role');
  if (roleEl) {
    let roles = [];
    try { roles = JSON.parse(roleEl.dataset.roles); } catch (e) {}
    if (!reduceMotion && roles.length > 1) {
      let ri = 0, ci = 0, del = false;
      const tick = () => {
        const word = roles[ri];
        ci += del ? -1 : 1;
        roleEl.textContent = word.slice(0, ci);
        let wait = del ? 28 : 65;
        if (!del && ci === word.length) { del = true; wait = 1600; }
        else if (del && ci === 0) { del = false; ri = (ri + 1) % roles.length; wait = 350; }
        setTimeout(tick, wait);
      };
      roleEl.textContent = '';
      setTimeout(tick, 900);
    }
  }

  /* ---------- Jendela kode: diketik sekali saat halaman dibuka ---------- */
  const codeEl = $('#code');
  if (codeEl) {
    const text = codeEl.dataset.code;
    const esc = s => s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    const re = /("[^"\n]*"?)|\b(const|true|false)\b|(^[ \t]+)(\w+)(?=:)/gm;
    const paint = s => esc(s).replace(re, (m, str, kw, sp, key) =>
      str ? `<span class="t-s">${str}</span>` :
      kw ? `<span class="t-k">${kw}</span>` :
      `${sp}<span class="t-key">${key}</span>`);

    if (reduceMotion) {
      codeEl.innerHTML = paint(text);
    } else {
      let i = 0;
      const type = () => {
        i += 2;
        codeEl.innerHTML = paint(text.slice(0, i)) + '<span class="caret"></span>';
        if (i < text.length) setTimeout(type, 22);
        else codeEl.innerHTML = paint(text);
      };
      codeEl.textContent = '';
      setTimeout(type, 400);
    }
  }

  /* ---------- Bar keahlian: terisi saat terlihat ---------- */
  const fills = $$('.bar-fill');
  fills.forEach(f => f.style.setProperty('--w', f.dataset.level + '%'));
  const barObs = new IntersectionObserver(entries => {
    entries.forEach(e => {
      if (e.isIntersecting) {
        e.target.style.width = e.target.dataset.level + '%';
        barObs.unobserve(e.target);
      }
    });
  }, { threshold: .4 });
  fills.forEach(f => barObs.observe(f));

  /* ---------- Filter proyek ---------- */
  const chips = $$('.filters .chip');
  const cards = $$('.project');
  chips.forEach(chip => chip.addEventListener('click', () => {
    chips.forEach(c => { c.classList.remove('is-active'); c.setAttribute('aria-pressed', 'false'); });
    chip.classList.add('is-active');
    chip.setAttribute('aria-pressed', 'true');
    const f = chip.dataset.filter;
    cards.forEach(card => { card.hidden = !(f === 'all' || card.dataset.category === f); });
  }));

  /* ---------- Kartu miring mengikuti kursor ---------- */
  if (!reduceMotion && window.matchMedia('(hover:hover)').matches) {
    cards.forEach(card => {
      card.addEventListener('pointermove', e => {
        const r = card.getBoundingClientRect();
        const px = (e.clientX - r.left) / r.width - .5;
        const py = (e.clientY - r.top) / r.height - .5;
        card.style.setProperty('--ry', (px * 6) + 'deg');
        card.style.setProperty('--rx', (-py * 6) + 'deg');
      });
      card.addEventListener('pointerleave', () => {
        card.style.setProperty('--ry', '0deg');
        card.style.setProperty('--rx', '0deg');
      });
    });
  }

  /* ---------- Detail proyek (modal) ---------- */
  const modal = $('#projectModal');
  const data = window.PROJECTS || [];
  const el = (tag, attrs = {}, text = '') => {
    const n = document.createElement(tag);
    Object.entries(attrs).forEach(([k, v]) => n.setAttribute(k, v));
    if (text) n.textContent = text;
    return n;
  };

  cards.forEach(card => card.addEventListener('click', () => {
    const p = data.find(x => x.id === +card.dataset.id);
    if (!p || !modal) return;

    const cover = $('#mCover'); cover.replaceChildren();
    if (p.image) cover.append(el('img', { src: p.image, alt: '' }));
    else cover.append(el('span', { class: 'cover-fallback', 'aria-hidden': 'true' }, p.title.slice(0, 2).toUpperCase()));

    $('#mCat').textContent = p.category;
    $('#mTitle').textContent = p.title;
    $('#mDesc').textContent = p.description;
    $('#mTech').replaceChildren(...p.tech.map(t => el('span', {}, t)));

    const actions = $('#mLinks'); actions.replaceChildren();
    if (p.demo) actions.append(el('a', { class: 'btn btn-primary', href: p.demo, target: '_blank', rel: 'noopener' }, 'Lihat demo'));
    if (p.repo) actions.append(el('a', { class: 'btn btn-soft', href: p.repo, target: '_blank', rel: 'noopener' }, 'Lihat kode'));

    modal.showModal();
  }));
  if (modal) {
    $('#modalClose').addEventListener('click', () => modal.close());
    modal.addEventListener('click', e => { if (e.target === modal) modal.close(); });
  }

  /* ---------- Formulir kontak ---------- */
  const form = $('#contactForm'), status = $('#formStatus');
  if (form) {
    form.addEventListener('submit', async e => {
      e.preventDefault();
      $$('.is-invalid', form).forEach(i => i.classList.remove('is-invalid'));
      status.className = 'form-status'; status.textContent = '';

      const btn = $('button[type=submit]', form);
      btn.disabled = true; const label = btn.textContent; btn.textContent = 'Mengirim…';

      try {
        const res = await fetch(form.action, {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
          },
          body: new FormData(form),
        });
        const json = await res.json();
        if (res.ok) {
          status.classList.add('ok'); status.textContent = json.message;
          form.reset();
        } else if (res.status === 422) {
          Object.keys(json.errors).forEach(k => form.elements[k]?.classList.add('is-invalid'));
          status.classList.add('err'); status.textContent = Object.values(json.errors)[0][0];
        } else if (res.status === 429) {
          status.classList.add('err'); status.textContent = 'Terlalu banyak percobaan. Coba lagi sebentar lagi.';
        } else {
          throw new Error();
        }
      } catch (err) {
        status.classList.add('err'); status.textContent = 'Pesan belum terkirim. Periksa koneksi Anda lalu coba lagi.';
      } finally {
        btn.disabled = false; btn.textContent = label;
      }
    });
  }
})();
