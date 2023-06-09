// made by https://requiem.moe/
const btcAddress = document.querySelector('#tip1 p');
const dogeAddress = document.querySelector('#tip2 p');
const ethAddress = document.querySelector('#tip3 p');

function copyToClipboard(text) {
  const textarea = document.createElement('textarea');
  textarea.value = text;
  document.body.appendChild(textarea);
  textarea.select();
  document.execCommand('copy');
  document.body.removeChild(textarea);
}

let timeoutId;

function showMessage(message) {
  const messageElement = document.createElement('div');
  messageElement.textContent = message;
  messageElement.style.position = 'relative';
  messageElement.style.padding = '10px';
  messageElement.style.backgroundColor = '#333';
  messageElement.style.color = '#fff';
  messageElement.style.borderRadius = '5px';
  messageElement.style.zIndex = '9999';
  messageElement.setAttribute('id', 'message');
  const tipsElement = document.querySelector('#tips');
  const oldMessageElement = document.querySelector('#message');
  if (oldMessageElement) {
    oldMessageElement.remove();
    clearTimeout(timeoutId);
  }
  if (tipsElement.contains(document.querySelector('ul'))) {
    tipsElement.insertAdjacentElement('beforebegin', messageElement);
  } else {
    document.body.appendChild(messageElement);
  }
  timeoutId = setTimeout(() => {
    const messageElement = document.querySelector('#message');
    if (messageElement) {
      messageElement.remove();
    }
  }, 3000);
}
btcAddress.addEventListener('click', () => {
  copyToClipboard(btcAddress.textContent);
  showMessage('Copied BTC wallet!');
});

dogeAddress.addEventListener('click', () => {
  copyToClipboard(dogeAddress.textContent);
  showMessage('Copied DOGE wallet!');
});

ethAddress.addEventListener('click', () => {
  copyToClipboard(ethAddress.textContent);
  showMessage('Copied ETH wallet!');
});
// made by https://requiem.moe/