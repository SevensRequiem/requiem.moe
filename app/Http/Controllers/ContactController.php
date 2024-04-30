<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;


class ContactController extends Controller
{
    public function filtermsg($message)
    {
        $timestamp = time();
        $date = date("Y-m-d H:i:s", $timestamp);

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
        if (strlen($message) > 1000) {
            $response = response()->json(['error' => 'Error: Message exceeds the limit of 1000 characters.']);
            $response->send();
            exit();
        }
        return $message;
    }
    public function filterusr($name)
    {
        $name = strip_tags($name);
        $name = htmlentities($name);
        $name = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $name);
        $name = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', "", $name);
        $name = preg_replace('/(select|insert|delete|drop table|show tables|\*|--|\\\\)/i', '', $name);
        $name = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
            return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
        }, $name);
        if (preg_match("/[^\x20-\xAD\x7F]/", $name)) {
            $response = response()->json(['error' => 'Error: Username contains invalid characters.']);
            $response->send();
            exit();
        }
        if (strlen($name) > 15) {
            $response = response()->json(['error' => 'Error: Username exceeds the limit of 15 characters.']);
            $response->send();
            exit();
        }
        return $name;
    }
    public function NewContact(Request $request)
    {
        $message = $request->input('message');
        $message = $this->filtermsg($message);
        $name = $request->input('name');
        $name = $this->filterusr($name);
        $trueuser = Auth::user()->username ?? 'Guest';
        $email = $request->input('email');
        $timestamp = time();
        $date = date("Y-m-d H:i:s", $timestamp);
        $csrf = $request->input('_token');

        // save to database

        DB::table('contact')->insert(
            ['message' => $message, 'name' => $name, 'email' => $email, 'trueuser' => $trueuser, 'timestamp' => $date]
        );

        $webhook = 'https://discord.com/api/webhooks/1204784618255945728/p_zbWbJxyOmorVeOB0mns6XBrWm7qc2TXCDfTlaUHiOUt-ls1hxsiG74y4lwEDJD34e6';

        $data = [
            'embeds' => [
                [
                    'title' => 'New Contact Form Submission',
                    'description' => "Message: $message\nFrom: $name | $email\nTimestamp: $date",
                    'color' => hexdec('3366ff')
                ]
            ]
        ];

        $options = [
            'http' => [
                'method' => 'POST',
                'header' => 'Content-Type: application/json',
                'content' => json_encode($data),
            ]
        ];

        $context = stream_context_create($options);
        $result = file_get_contents($webhook, false, $context);
        if ($result === FALSE) {
            die('Error');
        }
        return response()->json(['message' => 'Success']);
        

    }
}

