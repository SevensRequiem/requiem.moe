<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Pusher\Pusher;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\DiscordNotificationController;
use Spatie\ResponseCache\Facades\ResponseCache;

class ChatController extends Controller
{
    private $usernameLimit = 15;
    private $msgLimit = 200;

    public function index()
    {
        $messages = DB::connection('chat')->table('messages')->get();
        return view('chat', ['messages' => $messages]);
    }

    private function filterMessage($message)
    {
        $spamWords = array('https', 'http', '.gg', 'invite');
        $bwpath = File::get(storage_path('badwords.txt'));
        $badwords = explode("\n", $bwpath);
        foreach ($badwords as $badword) {
            $badword = trim($badword);
            $pattern = '/\b\w*' . preg_quote($badword, '/') . '\w*\b/i';
            $message = preg_replace_callback($pattern, function ($matches) {
                return str_repeat('*', strlen($matches[0]));
            }, $message);
        }
        foreach ($spamWords as $spamWord) {
            if (strpos($message, $spamWord) !== false) {
                $response = response()->json(['error' => 'Error: Message contains spam.']);
                $response->send();
                exit();
            }
        }

        $message = strip_tags($message);
        $message = htmlentities($message);
        $message = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $message);
        $message = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', "", $message);
        $message = preg_replace('/(select|insert|delete|drop table|show tables|\*|--|\\\\)/i', '', $message);
        $message = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
            return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
        }, $message);
        if (preg_match("/[^\x20-\xAD\x7F]/", $message)) {
            $response = response()->json(['error' => 'Error: Message contains invalid characters.']);
            $response->send();
            exit();
        }
        if (strlen($message) > $this->msgLimit) {
            $response = response()->json(['error' => 'Error: Message exceeds the limit of ' . $this->msgLimit . ' characters.']);
            $response->send();
            exit();
        }
        return $message;
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
            $response = response()->json(['error' => 'Error: Username contains invalid characters.']);
            $response->send();
            exit();
        }
        if (strlen($username) > $this->usernameLimit) {
            $response = response()->json(['error' => 'Error: Username exceeds the limit of ' . $this->usernameLimit . ' characters.']);
            $response->send();
            exit();
        }
        return $username;
    }

    public function sendMessage(Request $request)
    {
        if (! $request->session()->token() === $request->input('_token')) {
            die('Invalid request');
        }
        if ($request->input('message') == null || $request->input('username') == null) {
            $response = response()->json(['error' => 'Error: Message or username is empty.']);
            $response->send();
            exit();
        }

        $ip_address = $request->ip();
        $timestamp = time();
        $last_message = DB::connection('chat')->table('messages')
        ->where('ip_address', $ip_address)
        ->orderBy('timestamp', 'desc')
        ->first();
    
    // Check for time interval between messages
    if ($last_message && $timestamp - $last_message->timestamp < 5) {
        $response = response()->json(['error' => 'Error: You are sending messages too quickly.']);
        $response->send();
        exit();
    }
    
    // Check for duplicate messages in the last 20 messages
    $recent_messages = DB::connection('chat')->table('messages')
        ->where('ip_address', $ip_address)
        ->orderBy('timestamp', 'desc')
        ->take(20)
        ->get();
    
    foreach ($recent_messages as $recent) {
        // Compare the content of the current message with each recent message
        if ($recent->message === $request->input('message')) {
            $response = response()->json(['error' => 'Error: Duplicate message detected.']);
            $response->send();
            exit();
            
        }
    }
    
        $username = $request->input('username');
        $message = $request->input('message');
        $message = $this->filterMessage($message);
        $username = $this->filterUsername($username);
        $ip_address = $request->ip();
        $timestamp = time();
        $trueuser = auth()->check() ? auth()->user()->username : 'none';
        $uid =  'msg_' . bin2hex(openssl_random_pseudo_bytes(6));

        $id = DB::connection('chat')->table('messages')->insertGetId([
            'uuid' => $uid,
            'ip_address' => $ip_address,
            'message' => $message,
            'timestamp' => $timestamp,
            'trueuser' => $trueuser,
            'username' => $username,
        ]);

        // Send the message to Discord
        $discord = new DiscordNotificationController();
        $discord->NewMessage($message);

        // Send the message to Pusher
        $options = array(
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'useTLS' => true
        );
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options
        );
        $data['id'] = $id;
        $data['message'] = $message;
        $data['timestamp'] = $timestamp;
        $data['trueuser'] = $trueuser;
        $data['username'] = $username;
        $data['uuid'] = $uid;
        $pusher->trigger('dev-channel', 'dev-event', $data);
        ResponseCache::forget('/get-messages');

        // Return a success message
        $response = response()->json(['status' => 'Message sent successfully.']);
        $response->send();

        exit();
    }

    public function getMessages()
    {
        $messages = DB::connection('chat')->table('messages')->get();
        $response = response()->json(['messages' => $messages]);
        $response->send();
        exit();
    }

    public function deleteMessage($uuid)
    {
        $userId = auth()->id();
        $adminIds = explode(',', env('ADMIN_IDS'));
        $authorizedUserId = in_array($userId, $adminIds) ? $userId : null;

        if (auth()->id() == $authorizedUserId) {
            DB::connection('chat')->table('messages')->where('uuid', $uuid)->delete();
            ResponseCache::forget('/get-messages');
            $response = response()->json(['status' => 'Message deleted successfully.']);
        } else {
            $response = response()->json(['error' => 'Error: You are not authorized to delete this message.']);
            $response->send();
            exit();
        }
    }
}