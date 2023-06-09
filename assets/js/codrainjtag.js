// made by https://requiem.moe/
const span = document.getElementById("rain");
const colors = ["red", "orange", "yellow", "green", "blue", "indigo", "violet"];
function animateRainbow() {
  let colorIndex = 0;
  setInterval(() => {
    span.style.color = colors[colorIndex];
    colorIndex = (colorIndex + 1) % colors.length;
  }, 100);
}
animateRainbow();
// made by https://requiem.moe/