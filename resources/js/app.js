document.addEventListener('DOMContentLoaded', () => {
    const root = document.querySelector('#wc-carousel');
    if (!root) return;

    const arc = document.getElementById('wc-arc');
    const ballImg = document.getElementById('wc-ball');
    const prevBtn = root.querySelector('[data-action="prev"]');
    const nextBtn = root.querySelector('[data-action="next"]');

    // Toma los datos de los elementos generados por Blade
    const thumbs = Array.from(arc.querySelectorAll('.wc-thumb'));
    if (!thumbs.length) return;

    const items = thumbs.map(btn => ({
        year: Number(btn.dataset.year),
        cover: btn.dataset.cover,
        ball: btn.dataset.ball
    }));

    // Parámetros del arco (ajústalos)
    const ARC_SPAN_DEG = 120;
    const ROT_FACTOR = 0.85;
    const RADIUS_MIN = 240;
    const RADIUS_MAX = 360;
    const CENTER_SHIFT_Y = 40;

    let current = 0;
    const fallbackBall = items[0].ball;

    function deg2rad(d) { return d * Math.PI / 180; }

    function computeGeometry() {
        const W = arc.clientWidth || 1024;
        const R = Math.max(RADIUS_MIN, Math.min(RADIUS_MAX, W / 2.2));
        const cx = W / 2;
        const cy = R + CENTER_SHIFT_Y;
        const step = items.length > 1 ? ARC_SPAN_DEG / (items.length - 1) : 0;
        return { R, cx, cy, step };
    }

    function layout() {
        const W = arc.clientWidth || 1024;
        const cx = W / 2;
        const baseY = (arc.clientHeight || 320) / 2 + 10; // altura de la fila
        const spacing = 140; // separación horizontal entre tarjetas

        thumbs.forEach((t, k) => {
            let rel = k - current;
            if (rel > items.length / 2) rel -= items.length;
            if (rel < -items.length / 2) rel += items.length;

            const x = cx + rel * spacing;
            const y = baseY;

            t.style.left = `${x}px`;
            t.style.top = `${y}px`;
            t.style.transform = `translate(-50%, -50%) scale(${k === current ? 1.12 : 1})`;
            t.style.zIndex = String(1000 - Math.abs(rel));
            t.classList.toggle('ring-2', k === current);
            t.classList.toggle('ring-white/80', k === current);
        });
    }


    function setCurrent(idx) {
        current = (idx + items.length) % items.length;
        ballImg.src = items[current].ball;
        ballImg.alt = `Balón del Mundial ${items[current].year}`;
        ballImg.onerror = () => (ballImg.src = fallbackBall);
        layout();
    }

    function go(delta) { setCurrent(current + delta); }

    // Eventos
    thumbs.forEach((btn, idx) => btn.addEventListener('click', () => setCurrent(idx)));
    prevBtn.addEventListener('click', () => go(-1));
    nextBtn.addEventListener('click', () => go(1));
    window.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowLeft') go(-1);
        if (e.key === 'ArrowRight') go(1);
    });
    window.addEventListener('resize', layout);

    // Init
    setCurrent(0);
});
