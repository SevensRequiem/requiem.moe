<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use App\Http\Controllers\DiscordNotificationController;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;
use Illuminate\Support\Facades\Http;
use DateTime;
use DateTimeZone;
use Spatie\ResponseCache\Facades\ResponseCache;

class BlogController extends Controller
{
    public function store(Request $request)
    {
        $title = $request->input('title');
        $content = $request->input('content');
        $quote = $request->input('quote');
        $hex = $request->input('hex');

        $author = Auth::user()->username;
        $uuid = bin2hex(openssl_random_pseudo_bytes(16));
        $date = Carbon::now('America/Chicago')->format('m-d-Y');
        $datefull = Carbon::now('America/Chicago')->format('m-d-Y h:i a');
        $slug = strtolower(str_replace(' ', '-', $title));
        $folderName = $uuid;

        if (!in_array(Auth::id(), explode(',', env('ADMIN_IDS')))) {
            return response('Unauthorized', 403);
        }

        $folderPath = Storage::disk('public')->path('blog/' . $folderName);
        Storage::disk('public')->makeDirectory('blog/' . $folderName);
        $commentinit = json_encode([
            'comments' => []
        ], JSON_PRETTY_PRINT);
        Storage::put('blog/' . $folderName . '/comments.json', $commentinit);
        Storage::put('blog/' . $folderName . '/post.md', $content);

        // Store post data as JSON
        $postData = [
            'title' => $title,
            'content' => $content,
            'date' => $datefull,
            'author' => $author,
            'quote' => $quote,
            'hex' => $hex,
            'uuid' => $uuid
        ];
        Storage::put('blog/' . $folderName . '/post.json', json_encode($postData));

        if ($request->hasFile('image')) {
            
            $image = $request->file('image');
            $imageName = 'post.' . $image->getClientOriginalExtension();
            Storage::put('blog/' . $folderName . '/' . $imageName, file_get_contents($image));
            $imagepath = Storage::disk('public')->path('blog/' . $folderName . '/' . $imageName);
            $optimizerChain = OptimizerChainFactory::create();
            $optimizerChain->optimize($imagepath);
        }

        if ($request->hasFile('video')) {
            $video = $request->file('video');
            $videoName = 'post.' . $video->getClientOriginalExtension();
            Storage::put('blog/' . $folderName . '/' . $videoName, file_get_contents($video));
            $videopath = Storage::disk('public')->path('blog/' . $folderName . '/' . $videoName);
            $optimizerChain = OptimizerChainFactory::create();
            $optimizerChain->optimize($videopath);
        }

        // Send data to Discord webhook
        //$webhookUrls = [
        //    "https://discord.com/api/webhooks/1089017409496690728/Xbg58WiqetyETBvTTTe3dV8b8J9g4KlNiULZRbvPciyrvZPobuLO3_RW4Ow6Oms4p06x",
        //    "https://discord.com/api/webhooks/1156077225083408394/GyX4iVlHDaFT39hNczes1lKi93daTqCYDI_xOSXy9P7E6vGpz0-vkVdCeXYb9VMl-JAl"
        //];
        
// Get current Unix timestamp
$unixTimestamp = time();

// Convert Unix timestamp to ISO 8601 format
$iso8601 = date('c', $unixTimestamp);
        $hookObject = json_encode([
            "content" => "", // Your message here
            "username" => ".moe webhook",
            "avatar_url" => "https://cdn.discordapp.com/avatars/228343232520519680/529babab679bcf6d8e43a2753b7864e6?size=1024",
            "tts" => false,
            "embeds" => [
                [
                    "title" => $title,
                    "type" => "rich",
                    "description" => "",
                    "url" => "https://requiem.moe/blog?fromdiscord",
                    "timestamp" => $iso8601,
                    "color" => hexdec($hex ?? 'FFFFFF'), // Convert hex color to decimal, use FFFFFF if $hex is not set
                    "footer" => [
                        "text" => "New blog post by $author",
                        "icon_url" => "https://cdn.discordapp.com/avatars/228343232520519680/529babab679bcf6d8e43a2753b7864e6?size=1024"
                    ],
                    "image" => [
                        "url" => "https://dev8839.requiem.moe/static/blog/$uuid"
                    ],
                    "thumbnail" => [
                        "url" => "https://requiem.moe/banner.gif"
                    ],
                    "author" => [
                        "name" => "requiem.moe",
                        "url" => "https://requiem.moe/"
                    ],
                    "fields" => [
                        [
                            "name" => $quote ?? '',
                            "value" => "$content",
                            "inline" => false
                        ]
                    ]
                ]
            ]
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        //foreach ($webhookUrls as $webhookUrl) {
        //    $ch = curl_init();
        //    curl_setopt_array($ch, [
        //        CURLOPT_URL => $webhookUrl,
        //        CURLOPT_POST => true,
        //        CURLOPT_POSTFIELDS => $hookObject,
        //        CURLOPT_HTTPHEADER => [
         //           "Content-Type: application/json"
        //        ]
        //    ]);
        //    curl_exec($ch);
        //    curl_close($ch);
        //}


        //rss feed
        FeedItem::create()
            ->id($uuid)
            ->title($title)
            ->updated($datefull)
            ->link('https://requiem.moe/blog')
            ->author($author);

        //create laravel cache for post
        

        return response('Post created successfully!_' . $folderPath . '_' . $folderName, 200);
    }
    public function edit(Request $request)
    {}


    private function filterMessage($content)
    {
        $spamWords = array('https', 'http', '.gg', 'invite');
        $bwpath = File::get(storage_path('badwords.txt'));
        $badwords = explode("\n", $bwpath);
        foreach ($badwords as $badword) {
            $badword = trim($badword);
            $pattern = '/\b\w*' . preg_quote($badword, '/') . '\w*\b/i';
            $content = preg_replace_callback($pattern, function ($matches) {
                return str_repeat('*', strlen($matches[0]));
            }, $content);
        }
        foreach ($spamWords as $spamWord) {
            if (strpos($content, $spamWord) !== false) {
                return response()->json(['error' => 'Error: Message contains spam.']);
            }
        }

        $content = strip_tags($content);
        $content = htmlentities($content);
        $content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $content);
        $content = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', "", $content);
        $content = preg_replace('/(select|insert|delete|drop table|show tables|\*|--|\\\\)/i', '', $content);
        $content = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
            return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
        }, $content);
        if (preg_match("/[^\x20-\xAD\x7F]/", $content)) {
            return response()->json(['error' => 'Error: Message contains invalid characters.']);
        }
        return $content;
    }
    private function filterUsername($username)
    {
        $bwpath = File::get(storage_path('badwords.txt'));
        $badwords = explode("\n", $bwpath);
        foreach ($badwords as $badword) {
            $badword = trim($badword);
            $pattern = '/\b\w*' . preg_quote($badword, '/') . '\w*\b/i';
            $username = preg_replace_callback($pattern, function ($matches) {
                return str_repeat('*', strlen($matches[0]));
            }, $username);
        }

        $username = strip_tags($username);
        $username = htmlentities($username);
        $username = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $username);
        $username = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', "", $username);
        $username = preg_replace('/(from|select|insert|delete|where|drop table|show tables|#|\*|--|\\\\)/i', '', $username);
        $username = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
            return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
        }, $username);
        if (preg_match("/[^\x20-\xAD\x7F]/", $username)) {
            return response()->json(['error' => 'Error: Username contains invalid characters.']);
        }
        return $username;
    }
    public function postcomment(Request $request)
    {
        // Validate the request data here if needed

        $content = $request->input('comment');
        $content = $this->filterMessage($content);
        $author = $request->input('username') ?? 'Anonymous' ?? Auth::user()->username;
        $author = $this->filterUsername($author);
        $trueuser = Auth::user()->username ?? 'none';
        $commentIP = $request->ip();
        $commentID = 'com_' . bin2hex(openssl_random_pseudo_bytes(6));
        $uuid = $request->input('postuuid');
        $date = Carbon::now('America/Chicago')->format('m-d-Y h:i a');
        $title = $request->input('title') ?? 'n/a';

        // Store comment data as JSON
        $commentData = [
            'content' => $content,
            'author' => $author,
            'date' => $date,
            'ip' => $commentIP,
            'id' => $commentID,
            'trueuser' => $trueuser
        ];

        $discord = new DiscordNotificationController();
        $discord->NewComment($commentData, $uuid, $title);
        

        // Get existing comments
        $existingComments = json_decode(Storage::get('blog/' . $uuid . '/comments.json'), true);

        // Add new comment to existing comments
        $existingComments['comments'][] = $commentData;

        // Save back to storage
        Storage::put('blog/' . $uuid . '/comments.json', json_encode($existingComments));
        ResponseCache::forget('/blog');


        return response('Comment created successfully!', 200);
    }
    public function deletecomment(Request $request)
    {
        $uuid = $request->input('uuid');
        $commentID = $request->input('commentid');
        $trueuser = Auth::user()->username ?? 'none';

        // Get existing comments
        $existingComments = json_decode(Storage::get('blog/' . $uuid . '/comments.json'), true);

        // Remove comment from existing comments
        foreach ($existingComments['comments'] as $key => $comment) {
            if ($comment['id'] === $commentID && $comment['trueuser'] === $trueuser) {
                unset($existingComments['comments'][$key]);
            }
        }

        // Save back to storage
        Storage::put('blog/' . $uuid . '/comments.json', json_encode($existingComments));

        return response('Comment deleted successfully!', 200);
    }
    public function getcomments(Request $request)
    {
        $uuid = $request->input('postuuid');
        $comments = json_decode(Storage::get('blog/' . $uuid . '/comments.json'), true);

        return response()->json(['comments' => $comments]);
    }
    public function showpost(Request $request)
    {
        $uuid = $request->input('postuuid');
        $post = json_decode(Storage::get('blog/' . $uuid . '/post.json'), true);
        $post['content'] = Storage::get('blog/' . $uuid . '/post.md');
        $post['comments'] = json_decode(Storage::get('blog/' . $uuid . '/comments.json'), true);

        return response()->json(['post' => $post]);
    }
}
