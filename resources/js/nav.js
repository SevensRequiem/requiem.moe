const pages = [
  { name: 'home', url: './home' },
  { name: 'blog', url: './blog' },
  { name: 'projects', url: './projects' },
  { name: 'versions', url: './version' },
  { name: 'tools', url: './tools' },
  { name: 'gallery', url: './gallery' },
  { name: 'store', url: './store' },
  { name: 'about', url: './about' },
  { name: 'donate', url: './donate' }
];

function loadPage(pageName) {
  const page = pages.find(p => p.name === pageName);
  if (!page) {
    console.error(`Page ${pageName} not found`);
    return;
  }
  const content = document.getElementById("content");
  const loadingBar = document.createElement("div");
  loadingBar.className = "loading-bar";
  content.appendChild(loadingBar);
  fetch(page.url)
    .then(response => response.text())
    .then(data => {
      const parser = new DOMParser();
      const xmlDoc = parser.parseFromString(data, "text/html");
      const newContent = xmlDoc.querySelector("#content");
      if (newContent) {
        content.parentNode.replaceChild(newContent, content);
        if (pageName === 'home') {
          axios.get('/get-messages')
          .then(function (response) {
            var messages = response.data.messages;
            var chatbox = document.getElementById('chatbox');
            messages.forEach(function (message) {
              var chatmessage = document.createElement('div');
              chatmessage.className = 'chatmessage';
              
              var chatid = document.createElement('span');
              chatid.className = 'chatid';
              chatid.innerHTML = "[" + message.id + "]";
              var chattimestamp = document.createElement('span');
              chattimestamp.className = 'chattimestamp';
              var date = new Date(message.timestamp * 1000);
              chattimestamp.innerHTML = "[" + date.toLocaleString() + "]";
              
              var chatusername = document.createElement('span');
              chatusername.className = 'chatusername';
              if (message.trueuser && message.trueuser !== 'none') {
                chatusername.innerHTML = "*[" + message.username + "]";
              } else if (message.trueuser === 'none' && message.username !== 'Anonymous') {
                chatusername.innerHTML = "~[" + message.username + "]";
              } else {
                chatusername.innerHTML = "[" + message.username + "]";
              }
              chatusername.setAttribute('alt', message.trueuser);
              
              var chatmessagecontent = document.createElement('p');
              chatmessagecontent.className = 'chatmessagecontent';
              chatmessagecontent.innerHTML = message.message;
              
              chatmessage.appendChild(chatid);
              chatmessage.appendChild(chatusername);
              chatmessage.appendChild(chatmessagecontent);
              chatmessage.appendChild(chattimestamp);
              
              chatbox.appendChild(chatmessage);
              
              var hr = document.createElement('hr');
              chatbox.appendChild(hr);
            });
          })
          .catch(error => console.error(error));
        }
      }
    })
    .catch(error => console.error(error))
    .finally(() => { // requiemdotmoe was here
      content.removeChild(loadingBar);
    });
}
function loadForm() {
  var form = document.getElementById("contact-box");
  if (form.style.display === "none" || form.style.display === "") {
    form.style.display = "block";
    form.addEventListener("submit", function(event) {
      event.preventDefault();
      submitForm();
    });
  } else {
    form.style.display = "none";
  }

  var chatform = document.getElementById("chatform");
  if (chatform.style.display === "none" || chatform.style.display === "") {
    chatform.style.display = "block";
    chatform.addEventListener("submit", function(event) {
      event.preventDefault();
      submitChat();
    });
  } else {
    chatform.style.display = "none";
  }
}

function loadDonate() {
  var donateform = document.getElementById("donate-box");
  if (donateform.style.display === "none" || donateform.style.display === "") {
    donateform.style.display = "block";
    var stripe = Stripe(
      'pk_live_51OoEq7FFweABEjfJhD7dqZqhOuvXXSWbh7gdqPziIVSYAjft7AxlRQ4tpvzPLEy9x7wFsuBFIcOMOdQXGWPJ0uMT00ICiiGeNs'
      );
  
  var form = document.getElementById('payment-form');
  form.addEventListener('submit', function(ev) {
      ev.preventDefault();

      var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  
      fetch('/create-checkout-session', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
          amount: form.amount.value,
          recurring: form.recurring.checked
        })
      })
          .then(function(response) {
              return response.json();
          })
          .then(function(session) {
              return stripe.redirectToCheckout({
                  sessionId: session.id
              });
          })
          .then(function(result) {
              if (result.error) {
                  alert(result.error.message);
              }
          })
          .catch(function(error) {
              console.error('Error:', error);
          });
  });
  } else {
    donateform.style.display = "none";
  }
}

function submitForm() {
  var form = document.getElementById("contact-box");
  var name = form.querySelector("input[name='name']").value;
  var email = form.querySelector("input[name='email']").value;
  var message = form.querySelector("textarea[name='message']").value;
  axios.post('/contact', {
    name: name,
    email: email,
    message: message
  })
  .then(function (response) {
    var status = document.getElementById("contact-status");
    if (response.data.message === "Success") {
      status.innerHTML = "Message sent! Closing form in 5 seconds...";
      status.style.color = "limegreen";
      setTimeout(function() {
        form.style.display = "none";
        status.innerHTML = "";
      }, 5000);
    } else {
      status.innerHTML = "Message failed to send.";
      status.style.color = "red";
    }
  })
  .catch(function (error) {
    console.error(error);
    if (error.response) {
      // The request was made and the server responded with a status code
      // that falls out of the range of 2xx
      console.log(error.response.data);
      console.log(error.response.status);
      console.log(error.response.headers);
    } else if (error.request) {
      // The request was made but no response was received
      console.log(error.request);
    } else {
      // Something happened in setting up the request that triggered an Error
      console.log('Error', error.message);
    }
    console.log(error.config);
  });
}

