// made by https://requiem.moe/
const sampleRate = 44100;
const frequencyLeft = 106.13;
const frequencyRight = 113.96;
const volumeLeft = 0.0013;
const volumeRight = 0.0013;
const audioContext = new AudioContext({ sampleRate });
const bufferSize = sampleRate;
const audioBuffer = audioContext.createBuffer(2, bufferSize, sampleRate);
const leftChannel = audioBuffer.getChannelData(0);
const rightChannel = audioBuffer.getChannelData(1);
for (let i = 0; i < bufferSize; i++) {
  const t = i / sampleRate;
  leftChannel[i] = volumeLeft * Math.sin(2 * Math.PI * frequencyLeft * t);
  rightChannel[i] = volumeRight * Math.sin(2 * Math.PI * frequencyRight * t);
}
const audioSource = audioContext.createBufferSource();
audioSource.buffer = audioBuffer;
audioSource.loop = true;
audioSource.connect(audioContext.destination);
audioSource.start();
const audioElement = document.getElementById("resonance");
audioElement.src = audioContext.createMediaElementSource(audioSource).connect(audioContext.destination);
// made by https://requiem.moe/