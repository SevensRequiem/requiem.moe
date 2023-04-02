<?php
 require('getanime.php');
?>

<style>
  body {
  font-family: Arial, sans-serif;
}

section {
  margin-bottom: 20px;
}

section h2 {
  font-size: 24px;
  font-weight: bold;
  margin-bottom: 10px;
}

section p {
  font-size: 16px;
  margin-bottom: 10px;
}

section#anime {
  display: flex;
  flex-wrap: wrap;
}

section#anime li {
  margin-right: 10px;
}

footer {
  margin-top: 50px;
  text-align: center;
}

footer p {
  font-size: 14px;
  color: #999;
}

</style>
<section>
		<h2>About Me</h2>
		<p>My name is Cameron, I go by requiem or SevensRequiem online. I've been coding for fun since I was a kid, welcome to my website. I host gameservers for fun and also run a gaming community which said servers are hosted for.</p>
	</section>
  <section id="anime"><?php
    foreach ($decodedData["data"] as $item) { 
      $title = $item["node"]["title"];
        echo '<li><a>'.$title.'</a></li>';
      }
?>
</section>
	<section>
		<h2>Github Stats</h2>
		<p>Profile: <a href="https://github.com/sevensrequiem">sevensrequiem</a></p>
		<p><a href="https://www.buymeacoffee.com/sevensrequiem"> <img align="left" src="https://cdn.buymeacoffee.com/buttons/v2/default-yellow.png" height="50" width="210" alt="sevensrequiem" /></a></p><br><br>

<p><img align="left" src="https://github-readme-stats.vercel.app/api/top-langs?username=sevensrequiem&show_icons=true&theme=merko&locale=en&layout=compact" alt="sevensrequiem" /></p>

<p>&nbsp;<img align="center" src="https://github-readme-stats.vercel.app/api?username=sevensrequiem&show_icons=true&theme=merko&locale=en" alt="sevensrequiem" /></p>
	</section>

	<footer>
		<p>&copy; 2023 Requiem</p>
	</footer>