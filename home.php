<?php
 require('getanime.php');
?>

<style>
body {
  font-family: Arial, sans-serif;
  margin: 0;
}

header {
  background-color: #333;
  color: #fff;
  display: flex;
  justify-content: center;
  padding: 10px;
}

header h1 {
  margin: 0;
}

nav {
  background-color: #ddd;
  display: flex;
  justify-content: space-between;
  padding: 10px;
}

nav ul {
  list-style: none;
  margin: 0;
  padding: 0;
  display: flex;
}

nav ul li {
  margin: 0 10px;
}

nav ul li a {
  color: #333;
  text-decoration: none;
}

nav ul li a:hover {
  color: #555;
}

section {
  padding: 20px;
}

section h2 {
  margin-top: 0;
  text-align: center;
}

#anime {
  background-color: #eee;
}

#anime ul {
  list-style: none;
  margin: 0;
  padding: 0;
}

#anime ul li {
  margin: 10px 0;
}

#anime ul li a {
  color: #333;
  text-decoration: none;
}

#anime ul li a:hover {
  color: #555;
}

#github {
  background-color: #eee;
}

#github p {
  margin: 10px 0;
}

#github img {
  margin: 10px 0;
}

footer {
  background-color: #333;
  color: #fff;
  padding: 10px;
  text-align: center;
}
.ani-img {
  width: auto;
  height: 50px;
}

</style>
<section>
		<h2>About Me</h2>
		<p>My name is Cameron, I go by requiem or SevensRequiem online. I've been coding for fun since I was a kid, welcome to my website. I host gameservers for fun and also run a gaming community which said servers are hosted for.</p>
	</section>
  <section id="anime">
    <h2>Recent Anime/Manga Activity</h2>
    <ol>
    <?php
    foreach ($decodedData["data"] as $item) { 
      $title = $item["node"]["title"];
      $pictureUrl = $item["node"]["main_picture"]["large"];
      $url = $pictureUrl;
      $lastSlashPos = strrpos($url, "/");
      $numberStr = substr($url, $lastSlashPos + 1); // get substring starting from last slash
      $aniid = str_replace(["l.jpg", ".jpg"], "", $numberStr); // remove non-numeric characters

        echo '<li><img src="'. $pictureUrl .'" class="ani-img"><a>'.$title.'</a></li>';
      }
?>
    </ol>
</section>
	<section id="github">
		<h2>Github Stats</h2>
		<p>Profile: <a href="https://github.com/sevensrequiem" target="_blank">sevensrequiem</a></p>
    <ul id="languages"></ul>
    <script>
    // Replace YOUR_GITHUB_USERNAME with your actual GitHub username
    const url = "https://api.github.com/users/sevensrequiem/repos?per_page=1000";
    
    // Fetch the repositories from the GitHub API
    fetch(url)
      .then(response => response.json())
      .then(repositories => {
        // Count the total bytes of code for each language
        const languageBytes = {};
        repositories.forEach(repository => {
          const language = repository.language;
          const size = repository.size;
          if (language) {
            if (language in languageBytes) {
              languageBytes[language] += size;
            } else {
              languageBytes[language] = size;
            }
          }
        });
        
        // Sort the languages by total bytes of code
        const sortedLanguages = Object.keys(languageBytes).sort((a, b) => {
          return languageBytes[b] - languageBytes[a];
        });
        
        // Display the top 5 languages
        const languagesList = document.getElementById("languages");
        for (let i = 0; i < 5 && i < sortedLanguages.length; i++) {
          const language = sortedLanguages[i];
          const bytes = languageBytes[language];
          const listItem = document.createElement("li");
          listItem.innerHTML = `${language}: ${bytes} bytes`;
          languagesList.appendChild(listItem);
        }
      })
      .catch(error => {
        console.error(error);
        const errorListItem = document.createElement("li");
        errorListItem.innerHTML = "Error retrieving GitHub repositories";
        languagesList.appendChild(errorListItem);
      });
  </script>

  </section>

	<footer>
		<p>&copy; 2023 Requiem</p>
	</footer>