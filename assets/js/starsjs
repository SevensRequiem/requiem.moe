const canvas = document.getElementById('stars');
const ctx = canvas.getContext('2d');

// Set canvas dimensions
canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

// Create stars
const stars = [];
for (let i = 0; i < 1300; i++) {
    stars.push({
        x: Math.random() * canvas.width,
        y: Math.random() * canvas.height,
        z: Math.random() * canvas.width,
        size: Math.random() * 3,
        color: `rgb(${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 90)}, ${Math.floor(Math.random() * 90)})`, // Limit RGB values to 0-100
        flicker: Math.random() < 0.5
    });
}

// Draw stars
function draw() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.fillStyle = '#000';
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    for (let i = 0; i < stars.length; i++) {
        const star = stars[i];
        const x = (star.x - canvas.width / 2) * (canvas.width / star.z) + canvas.width / 2;
        const y = (star.y - canvas.height / 2) * (canvas.width / star.z) + canvas.height / 2;
        const size = star.size * (canvas.width / star.z);
        ctx.fillStyle = star.color;
        if (star.flicker && Math.random() < 0.05) {
            ctx.beginPath();
            ctx.arc(x, y, size / 2, 0, 2 * Math.PI); // Draw circular shape
            ctx.fill();
        } else {
            ctx.beginPath();
            ctx.arc(x, y, size / 2, 0, 2 * Math.PI); // Draw circular shape
            ctx.fill();
        }
    }
}

// Animate stars
function animate() {
    requestAnimationFrame(animate);
    for (let i = 0; i < stars.length; i++) {
        const star = stars[i];
        star.z -= 0.1;
        if (star.z <= 0) {
            star.z = canvas.width;
        }
    }
    draw();
}

animate();

// Apply pixelated filter
ctx.imageSmoothingEnabled = false;
ctx.webkitImageSmoothingEnabled = false;
ctx.mozImageSmoothingEnabled = false;

// Adjust colors to match PS1 palette
ctx.fillStyle = '#000000';
ctx.fillRect(0, 0, canvas.width, canvas.height);
const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
const data = imageData.data;
for (let i = 0; i < data.length; i += 4) {
    const r = data[i];
    const g = data[i + 1];
    const b = data[i + 2];
    const gray = 0.2989 * r + 0.5870 * g + 0.1140 * b;
    data[i] = gray;
    data[i + 1] = gray;
    data[i + 2] = gray;
}
ctx.putImageData(imageData, 0, 0);

// Resize canvas
window.addEventListener('resize', () => {
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
});