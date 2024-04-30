<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AnimeController extends Controller
{
    public function fetchAnime()
    {
        $url = 'https://graphql.anilist.co';
        $query = 'query ($userid: Int, $types: [ActivityType]) {
          Page {
            activities(userId: $userid, type_in: $types, sort: ID_DESC) {
              ... on ListActivity {
                id
                status
                progress
                createdAt
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
            // print response as json
            echo json_encode($data);
        }

        // Close cURL
        curl_close($curl);
    }

    public function fetchFavs()
    {
        $url = 'https://graphql.anilist.co';
        $query = 'query UserFavourites($userid: Int, $animePage: Int, $mangaPage: Int) {
  User(id: $userid) {
    favourites {
      characters {
        nodes {
          id
          name {
            first
            last
          }
          image {
            medium
          }
        }
      }
      anime(page: $animePage) {
        nodes {
          id
          title
          image {
            medium
          }
        }
      }
      manga(page: $mangaPage) {
        nodes {
          id
          title
          image {
            medium
          }
        }
      }
    }
  }
}';

        $variables = json_encode([
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
            echo json_encode($data);
        }

        // Close cURL
        curl_close($curl);
    }

    public function fetchWaifus()
    {
        $url = 'https://graphql.anilist.co';
        $query = 'query UserFavourites($userid: Int) {
            User(id: $userid) {
              favourites {
                characters {
                  nodes {
                    id
                    name {
                      first
                      last
                    }
                    image {
                      medium
                    }
                  }
                }
              }
            }
          }';

        $variables = json_encode([
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
            echo json_encode($data);
        }

        // Close cURL
        curl_close($curl);
    }
}