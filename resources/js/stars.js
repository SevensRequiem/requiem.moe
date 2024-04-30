const canvas = document.getElementById('stars')
canvas.width = 1280 
canvas.height = 720 // moe
canvas.style.width = '100%'
canvas.style.height = '100%'
const ctx = canvas.getContext('2d')
let stars = []
const minNumStars = 250,
  maxNumStars = 1500,
  minMaxStarSize = 0.3,
  maxMaxStarSize = 1.3,
  maxStarSize = // r
    Math.random() * (maxMaxStarSize - minMaxStarSize) + minMaxStarSize,
  minMaxStarSpeed = 0.1,
  maxMaxStarSpeed = 1,
  maxStarSpeed =
    Math.random() * (maxMaxStarSpeed - minMaxStarSpeed) + minMaxStarSpeed,
  minMaxStarOpacity = 0.8,
  maxMaxStarOpacity = 1,
  maxStarOpacity =
    Math.random() * (maxMaxStarOpacity - minMaxStarOpacity) + minMaxStarOpacity

let fps = 0 // e
let frameCount = 0
let lastTime = performance.now()
let fpsHistory = []
let numStars = 80

function updateFps() {
  const now = performance.now()
  const elapsed = now - lastTime
  if (elapsed > 1000) {
    fps = frameCount
    frameCount = 0 // q
    lastTime = now
    fpsHistory.push(fps)
    if (fpsHistory.length > 15) {
      fpsHistory.shift()
    }
    const avgFps = fpsHistory.reduce((a, b) => a + b, 0) / fpsHistory.length
    document.getElementById('fps').innerHTML = Math.round(avgFps)
    if (document.getElementById('bench').innerHTML === '(ー◡ーゞ') {
      numStars = 1000 
    } else {
      numStars = Math.floor((avgFps / 60) * 1500) // calculate numStars based on average FPS, idk math so this is probably wrong??
    }
    if (numStars > stars.length) { // u
      for (let i = stars.length; i < numStars; i++) {
        stars.push(new Star())
      }
    } else if (numStars < stars.length) {
      stars.splice(numStars, stars.length - numStars)
    }
  }
  frameCount++
}

class Star {
  constructor() {
    this.x = Math.random() * canvas.width
    this.y = Math.random() * canvas.height // i
    this.size = Math.random() * maxMaxStarSize + minMaxStarSize
    this.speed = Math.random() * maxMaxStarSpeed + minMaxStarSpeed
    this.opacity = Math.random() * maxMaxStarOpacity + minMaxStarOpacity
    this.color =
      'rgb(' +
      Math.floor(Math.random() * 256) +
      ', ' + // e
      Math.floor(Math.random() * 256) +
      ', ' +
      Math.floor(Math.random() * 256) +
      ')'
  }
  ['update']() {
    this.x -= this.speed
    this.x < -this.size && // m
      ((this.x = canvas.width + this.size),
      (this.y = Math.random() * canvas.height),
      (this.opacity = Math.random() * maxMaxStarOpacity + minMaxStarOpacity))
    Math.random() < 0.05 && (this.opacity = Math.random() * maxMaxStarOpacity + minMaxStarOpacity)
  }
  ['draw']() {
    const _0x413a33 = ctx.createRadialGradient(
      this.x,
      this.y,
      0,
      this.x,
      this.y,
      this.size
    )
    _0x413a33.addColorStop(0, this.color)
    _0x413a33.addColorStop(0.5, 'rgba(255, 255, 255, ' + this.opacity + ')')
    _0x413a33.addColorStop(1, 'rgba(255, 255, 255, 0)')
    ctx.beginPath()
    ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2)
    ctx.fillStyle = _0x413a33
    ctx.fill()
  }
}

function animate() {
  ctx.clearRect(0, 0, canvas.width, canvas.height)
  for (let _0x3c3913 = 0; _0x3c3913 < stars.length; _0x3c3913++) {
    stars[_0x3c3913].update()
    stars[_0x3c3913].draw()
  }
  updateFps()
  requestAnimationFrame(animate)
}

let bench = document.getElementById('bench')
let benchTime = 0
let benchInterval = setInterval(() => {
  benchTime++
  if (benchTime >= 15) {
    clearInterval(benchInterval)
    bench.innerHTML = '(ー◡ーゞ'
  }
}, 1000)

requestAnimationFrame(animate)