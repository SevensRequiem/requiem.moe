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
		    foreach ($data['data']['Page']['activities'] as $activity) {
		        $media = $activity['media'];
		        $image = $media['coverImage']['medium'];
		        $title = $media['title']['romaji'];
		        $status = $activity['status'];
		        $progress = $activity['progress'];
		        $createdAt = $activity['createdAt'];
		        $createdAtStr = date('Y-m-d H:i:s', $createdAt);
		        echo '<li>';
		        echo '<img src="' . $image . '" alt="' . $title . '">';
		        echo '<div>';
		        echo '<h2>' . $title . '</h2>';
		        echo '<p>Status: ' . $status . '</p>';
		        echo '<p>Progress: ' . $progress . '</p>';
		        echo '<p>' . $createdAtStr . '</p>';
		        echo '</div>';
		        echo '</li>';
		    }
		}

		// Close the cURL session
		curl_close($curl);
		

	?>