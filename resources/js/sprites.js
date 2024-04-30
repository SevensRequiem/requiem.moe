function animateGhost1(ghost, ghcontainer) {
    let x = Math.random() * (ghcontainer.clientWidth - 55);
    let y = Math.random() * (ghcontainer.clientHeight - 55);
    let dx = (Math.random() - 0.5) * 5;
    let dy = (Math.random() - 0.5) * 5;
    let lastChange = Date.now();
    let direction = dx > 0 ? 'right' : 'left'; // Initialize direction based on initial dx value
    let zIndex = 0;
    let size = 30;
    let opacity = Math.random() * 0.4 + 0.6; // Random opacity between 0.6 and 1

    // Set initial position, size, and opacity randomly
    ghost.style.left = `${x}px`;
    ghost.style.top = `${y}px`;
    ghost.style.width = `${size}px`;
    ghost.style.height = `${size}px`;
    ghost.style.opacity = opacity;

    // Add a class to set z-index and initial flip
    ghost.classList.add('ghost');

    // Handle flipping when moving left/right
    ghost.addEventListener('animationiteration', () => {
        ghost.classList.toggle('flip');
    });

    // Adjust ghcontainer size
    ghcontainer.style.width = `${ghcontainer.clientWidth}px`;
    ghcontainer.style.height = `${ghcontainer.clientHeight}px`;

    setInterval(() => {
        zIndex += Math.floor(Math.random() * 5) - 2; // Change z-index by -2 to 2
        zIndex = Math.min(Math.max(zIndex, -99), 99); // Clamp z-index between -99 and 99
        ghost.style.zIndex = zIndex;

        size += Math.floor(Math.random() * 5) - 2; // Change size by -2 to 2
        size = Math.min(Math.max(size, 30), 55); // Clamp size between 30 and 55
        ghost.style.width = `${size}px`;
        ghost.style.height = `${size}px`;
    }, 1000);

    function animate() {
        requestAnimationFrame(animate);

        const now = Date.now();
        const elapsed = now - lastChange;

        if (elapsed > (Math.random() * 10000 + 3000)) {
            dx = (Math.random() - 0.5) * 5;
            dy = (Math.random() - 0.5) * 5;
            lastChange = now;
            if (dx > 0 && direction === 'left') {
                direction = 'right';
                ghost.style.transform = 'scaleX(-1)';
            } else if (dx < 0 && direction === 'right') {
                direction = 'left';
                ghost.style.transform = 'scaleX(1)';
            }
        }

        x += dx;
        y += dy;

        if (x < 0 || x > ghcontainer.clientWidth - size) {
            dx = -dx;
            x += dx * 2;
            if (dx > 0 && direction === 'left') {
                direction = 'right';
                ghost.style.transform = 'scaleX(-1)';
            } else if (dx < 0 && direction === 'right') {
                direction = 'left';
                ghost.style.transform = 'scaleX(1)';
            }
        }

        if (y < 0 || y > ghcontainer.clientHeight - size) {
            dy = -dy;
            y += dy * 2;
        }

        ghost.style.left = `${x}px`;
        ghost.style.top = `${y}px`;
        ghost.style.opacity = `${opacity + (Math.random() - 0.5) * 0.05}`;
    }

    animate();
}

const ghost1 = document.getElementById('ghost1');
const ghcontainer = document.getElementById('sprite-container');

animateGhost1(ghost1, ghcontainer);