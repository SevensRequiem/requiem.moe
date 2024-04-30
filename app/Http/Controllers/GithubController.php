<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Spatie\ImageOptimizer\OptimizerChainFactory;


class GithubController extends Controller
{
    public function getallstats()
    {
        $githubUsername = 'SevensRequiem';
        $accessToken = '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';

        // Initialize Guzzle HTTP client
        $client = new GuzzleHttp\Client();

        // Fetch follower count
        $response = $client->get("https://api.github.com/users/$githubUsername", [
            'headers' => [
                'Authorization' => "Bearer $accessToken",
                'Accept' => 'application/vnd.github.v3+json',
            ],
        ]);
        // json encode the response body
        $userData = json_decode($response->getBody(), true);

        // Fetch repository count
        $repoCount = $userData['public_repos'];

        // Fetch repository languages
        $response = $client->get("https://api.github.com/users/$githubUsername/repos", [
            'headers' => [
                'Authorization' => "Bearer $accessToken",
                'Accept' => 'application/vnd.github.v3+json',
            ],
        ]);
        $reposData = json_decode($response->getBody(), true);
        $languagesToDisplay = ['PHP', 'HTML', 'CSS', 'JavaScript', 'Python', 'Shell'];
        
        $languages = [];
        foreach ($reposData as $repo) {
            $repoLanguages = $client->get($repo['languages_url'], [
                'headers' => [
                    'Authorization' => "Bearer $accessToken",
                    'Accept' => 'application/vnd.github.v3+json',
                ],
            ]);
            $repoLanguagesData = json_decode($repoLanguages->getBody(), true);
            foreach ($repoLanguagesData as $language => $bytes) {
                // Check if the language is in the $languagesToDisplay array
                if (in_array($language, $languagesToDisplay)) {
                    // Modify the language name
                    if ($language == 'PHP') {
                        $language = '.php';
                    } elseif ($language == 'HTML') {
                        $language = '.html';
                    } elseif ($language == 'CSS') {
                        $language = '.css';
                    } elseif ($language == 'JavaScript') {
                        $language = '.js';
                    } elseif ($language == 'TypeScript') {
                        $language = '.ts';
                    } elseif ($language == 'Python') {
                        $language = '.py';
                    } elseif ($language == 'C++') {
                        $language = '.cpp';
                    } elseif ($language == 'C') {
                        $language = '.c';
                    } elseif ($language == 'C#') {
                        $language = '.cs';
                    } elseif ($language == 'Java') {
                        $language = '.java';
                    } elseif ($language == 'Batchfile') {
                        $language = '.bat';
                    } elseif ($language == 'Shell') {
                        $language = '.sh';
                    } elseif ($language == 'PowerShell') {
                        $language = '.ps1';
                    } elseif ($language == 'VBScript') {
                        $language = '.vbs';
                    } elseif ($language == 'Lua') {
                        $language = '.lua';
                    }
        
                    // Store the modified language name in the $languages array
                    if (isset($languages[$language])) {
                        $languages[$language] += $bytes;
                    } else {
                        $languages[$language] = $bytes;
                    }
                    $totalBytes = array_sum($languages);
                    $languagePercentages = [];
                    foreach ($languages as $language => $bytes) {
                        $percentage = ($bytes / $totalBytes) * 100;
                        $roundedPercentage = ceil($percentage);
                        $languagePercentages[$language] = $roundedPercentage;
                    }
                    
                }
            }
        }
        $githubData = [
            'followers' => $userData['followers'],
            'public_repos' => $userData['public_repos'],
            'language_percentages' => $languagePercentages

            
        ];
        Storage::put('github.json', json_encode($githubData));
    }

    public function getwebring()
    {
        $githubToken = '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';
        $repoOwner = 'SevensRequiem';
        $repoName = 'requiem.moe';
        $folderName = 'webring';

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://api.github.com/repos/$repoOwner/$repoName/contents/$folderName");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "User-Agent: GitHub-Repo-Content-Fetcher",
            "Authorization: token $githubToken"
        ));

        $repoContent = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        $repoContent = json_decode($repoContent, true);

        $localWebringPath = public_path('webring/webring.json');
        $localWebring = json_decode(file_get_contents($localWebringPath), true);

        if (json_encode($repoContent) !== json_encode($localWebring)) {
            file_put_contents($localWebringPath, json_encode($repoContent));

            foreach ($repoContent as $item) {
                $imagePath = public_path('webring/' . $item['name']);
                $imageData = file_get_contents($item['download_url']);
                file_put_contents($imagePath, $imageData);

                // Optimize image
                $optimizerChain = OptimizerChainFactory::create();
                $optimizerChain->optimize($imagePath);
            }
        }
}
}