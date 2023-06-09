// made by https://requiem.moe/
function loadBlog() {
    document.getElementById("content").innerHTML = "";
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4) {
        document.getElementById("content").innerHTML = xhr.responseText;
        var title = document.createElement("legend");
        title.innerHTML = ">cat blog.txt";
        document.getElementById("content").insertBefore(title, document.getElementById("content").firstChild);
      }
    };
    xhr.open("GET", "home.html", true);
    xhr.send();
  }
  function loadHome() {
    document.getElementById("content").innerHTML = "";
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4) {
        document.getElementById("content").innerHTML = xhr.responseText;
        var title = document.createElement("legend");
        title.innerHTML = ">cat home.txt";
        document.getElementById("content").insertBefore(title, document.getElementById("content").firstChild);
      }
    };
    xhr.open("GET", "home.html", true);
    xhr.send();
  }
  window.addEventListener('load', loadHome);
  function loadVersions() {
    document.getElementById("content").innerHTML = "";
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4) {
        document.getElementById("content").innerHTML = xhr.responseText;
        var title = document.createElement("legend");
        title.innerHTML = ">curl oldversions.php";
        document.getElementById("content").insertBefore(title, document.getElementById("content").firstChild);
      }
    };
    xhr.open("GET", "home.html", true);
    xhr.send();
  }
  function loadProjects() {
    document.getElementById("content").innerHTML = "";
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4) {
        document.getElementById("content").innerHTML = xhr.responseText;
        var title = document.createElement("legend");
        title.innerHTML = ">ls /projects/";
        document.getElementById("content").insertBefore(title, document.getElementById("content").firstChild);
      }
    };
    xhr.open("GET", "home.html", true);
    xhr.send();
  }
  // made by https://requiem.moe/