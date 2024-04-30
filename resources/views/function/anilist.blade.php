<?php
		// Set the GraphQL API endpoint URL
		$url = 'https://graphql.anilist.co';

		// Define the GraphQL query and its variables
		$query = 'query ($types: [ActivityType], $userid: Int) {
		  Page {
		    activities(type_in: $types, userId: $userid, sort: ID_DESC) {
		      ... on ListActivity {
		        media {
		          id
		          title {
		            romaji
		          }
		          coverImage {
		            medium
		          }
				  format
		        }
		        status
		        progress
		        createdAt
		      }
		    }
		  }
		}';

		$variables = json_encode([
		    'types' => ['ANIME_LIST', 'MANGA_LIST'],
		    'userid' => 533005 // SET TO YOUR USER_ID, you can get this in the discord by using "!u Username"
		]);

		// Set the cURL options
		$options = array(
		    CURLOPT_POST => true,
		    CURLOPT_POSTFIELDS => json_encode(array(
		        'query' => $query,
		        'variables' => $variables,
		    )),
		    CURLOPT_HTTPHEADER => array(
		        'Content-Type: application/json',
		        'Accept: application/json'
		    ),
		    CURLOPT_RETURNTRANSFER => true,
		    CURLOPT_SSL_VERIFYHOST => false,
		    CURLOPT_SSL_VERIFYPEER => false,
		    CURLOPT_CONNECTTIMEOUT => 10,
		    CURLOPT_TIMEOUT => 30,
		    CURLOPT_URL => $url
		);

		// Initialize cURL and set the options
		$curl = curl_init();
		curl_setopt_array($curl, $options);

		// Execute the request and get the response
		$response = curl_exec($curl);

		// Check for errors
		if (curl_errno($curl)) {
		    echo 'Error: ' . curl_error($curl);
		} else {
		    // Decode the response
		    $data = json_decode($response, true);
		    // Print the response as a list
        ?>
<ul class="horizontal-list">
    <?php foreach (array_slice($data['data']['Page']['activities'], 0, 10) as $activity) {
        $media = $activity['media'];
        $image = $media['coverImage']['medium'];
		$title = $media['title']['romaji'];
		$format = $media['format'];
		if (strlen($title) > 13) {
			$title = substr($title, 0, 13) . '...';
		}
		$animeId = $media['id'];
		if (in_array($format, ['MANGA', 'NOVEL', 'ONE_SHOT', 'MANGA_ONE_SHOT', 'NOVEL_ONE_SHOT']))  {
			$titleLink = 'https://anilist.co/manga/' . $animeId;
		} else {
			$titleLink = 'https://anilist.co/anime/' . $animeId;
		}
			
        $status = $activity['status'];
        $progress = $activity['progress'];
        $createdAt = $activity['createdAt'];
        $createdAtStr = date('Y-m-d', $createdAt);
        ?>
        <li>
            <div class="media">
                <img src="<?php echo $image; ?>" alt="<?php echo $title; ?>">
                <div class="info">
                    <span class="glowlightpink">&gt;<a href="<?php echo $titleLink; ?>" target="_blank"><?php echo $title; ?></a>&lt;</span>
                    <span>[<span class="glowlightblue"><?php echo $status; ?></span>]</span>
                    <?php if ($progress !== null) { ?>
                        <span>[<span class="glowlightgreen"><?php echo $progress; ?></span>]</span>
                    <?php } ?>
                    <span>[<span class="glowlightyellow"><?php echo $createdAtStr; ?></span>]</span>
                </div>
            </div>
        </li>
    <?php } ?>
</ul>
        <?php
		}

		// Close the cURL session
		curl_close($curl);
		

	?>