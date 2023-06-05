const stars = require('./example/stars.js');

const ZOOM_MULTIPLIER = 0.00015;

(async () => {
    await stars.init();
    setupZoom();
    let t = 0;
    const loop = () => {
        window.requestAnimationFrame((now) => {
            const dx = 100; // 100;
            const dy = 0; // -.1
            const dt = (now - t) / 1000;
            t = now;
            stars.move(dx, dy).inc(dt).draw(now);
            loop();
        });
    };
    loop();
})();

function setupZoom() {
    window.addEventListener('wheel', (event) => {
        stars.zoom(event.deltaY * ZOOM_MULTIPLIER);
    });
}

setupZoom();