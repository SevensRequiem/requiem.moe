// made by https://requiem.moe/
const hoverSound = new Audio('./assets/sfx/scph10000_00022.wav');
hoverSound.volume = 0;
const clickSound = new Audio('./assets/sfx/scph10000_00023.wav');
clickSound.volume = 0.2;
const links = document.querySelectorAll('a');
links.forEach(link => {
  link.addEventListener('mouseenter', () => {
    hoverSound.currentTime = 0;
    hoverSound.play();
  });
  link.addEventListener('click', (event) => {
    if (link.hasAttribute('href')) {
      clickSound.currentTime = 0;
      clickSound.play();
    }
  });
});
// made by https://requiem.moe/