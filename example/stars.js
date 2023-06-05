const canvas = document.getElementById('canvas');
const ctx = canvas.getContext('2d');

const STAR_COUNT = 1000;
const STAR_RADIUS = 1.5;
const STAR_COLOR = '#ffffff';

const stars = [];

for (let i = 0; i < STAR_COUNT; i++) {
  const x = Math.random() * canvas.width;
  const y = Math.random() * canvas.height;
  stars.push({ x, y });
}

function drawStars() {
  ctx.fillStyle = STAR_COLOR;
  stars.forEach(star => {
    ctx.beginPath();
    ctx.arc(star.x, star.y, STAR_RADIUS, 0, 2 * Math.PI);
    ctx.fill();
  });
}

function animate() {
  ctx.clearRect(0, 0, canvas.width, canvas.height);
  drawStars();
  requestAnimationFrame(animate);
}

animate();