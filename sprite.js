const canvas = document.createElement("canvas");
const ctx = canvas.getContext("2d");
document.body.appendChild(canvas);

// Set canvas dimensions to match window size
canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

// Create ghost sprite
const ghost = {
  x: Math.random() * canvas.width,
  y: Math.random() * canvas.height,
  vx: Math.random() * 2 - 1,
  vy: Math.random() * 2 - 1,
  size: 20,
  auraSize: 0,
  auraOpacity: 0
};

// Create twinkling trail
const trail = [];
for (let i = 0; i < 20; i++) {
  trail.push({
    x: ghost.x,
    y: ghost.y,
    size: ghost.size * (1 - i / 20),
    opacity: 1 - i / 20
  });
}

// Draw function
function draw() {
  // Update ghost position
  ghost.x += ghost.vx;
  ghost.y += ghost.vy;
  
  // Bounce off walls
  if (ghost.x < ghost.size || ghost.x > canvas.width - ghost.size) {
    ghost.vx *= -1;
  }
  if (ghost.y < ghost.size || ghost.y > canvas.height - ghost.size) {
    ghost.vy *= -1;
  }
  
  // Update aura size and opacity
  ghost.auraSize += 0.5;
  ghost.auraOpacity -= 0.02;
  if (ghost.auraOpacity < 0) {
    ghost.auraOpacity = 0;
  }
  
  // Draw ghost aura
  ctx.fillStyle = `rgba(255, 255, 255, ${ghost.auraOpacity})`;
  ctx.beginPath();
  ctx.arc(ghost.x, ghost.y, ghost.auraSize, 0, Math.PI * 2);
  ctx.fill();
  
  // Draw ghost sprite
  ctx.fillStyle = "white";
  ctx.beginPath();
  ctx.arc(ghost.x, ghost.y, ghost.size, 0, Math.PI * 2);
  ctx.fill();
  
  // Draw twinkling trail
  for (let i = 0; i < trail.length; i++) {
    const star = trail[i];
    ctx.fillStyle = `rgba(255, 255, 255, ${star.opacity})`;
    ctx.beginPath();
    ctx.arc(star.x, star.y, star.size, 0, Math.PI * 2);
    ctx.fill();
  }
  
  // Add new star to trail
  trail.unshift({
    x: ghost.x,
    y: ghost.y,
    size: ghost.size,
    opacity: 1
  });
  
  // Remove old star from trail
  if (trail.length > 20) {
    trail.pop();
  }
  
// Check for collisions with elements in header
const header = document.querySelector("header");
const elements = header.querySelectorAll("*");
for (let i = 0; i < elements.length; i++) {
const element = elements[i];
const rect = element.getBoundingClientRect();
const distance = Math.sqrt(Math.pow(ghost.x - rect.left - rect.width / 2, 2) + Math.pow(ghost.y - rect.top - rect.height / 2, 2));
if (distance < ghost.size + Math.max(rect.width, rect.height) / 2) {
ghost.auraSize = 0;
ghost.auraOpacity = 1;
break;
}
}

// Clear canvas
ctx.clearRect(0, 0, canvas.width, canvas.height);

// Request next frame
requestAnimationFrame(draw);
}

// Start animation loop
draw();