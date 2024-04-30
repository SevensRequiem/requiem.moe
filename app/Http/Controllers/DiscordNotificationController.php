<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class DiscordNotificationController extends Controller
{
    public function NewMessage()
    {  
        //get $message post data
        $message = request()->input('message');
        $username = request()->input('username');
        $timestamp = time();
        $date = date("Y-m-d H:i:s", $timestamp);

        $webhook = 'https://discord.com/api/webhooks/1204784618255945728/p_zbWbJxyOmorVeOB0mns6XBrWm7qc2TXCDfTlaUHiOUt-ls1hxsiG74y4lwEDJD34e6';

        $data = [
            'embeds' => [
                [
                    'title' => 'New Message',
                    'description' => "Message: $message\nFrom: $username\nTimestamp: $date",
                    'color' => hexdec( '3366ff' )
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
        if ($result === FALSE) 
        {
            die('Error');
        }
        return $result;
    }

    public function NewComment()
    {
        $comment = request()->input('comment');
        $uuid = request()->input('uuid');
        $title = request()->input('title');
        $username = request()->input('username');
        $timestamp = time();
        $date = date("Y-m-d H:i:s", $timestamp);

        $webhook = 'https://discord.com/api/webhooks/1204784618255945728/p_zbWbJxyOmorVeOB0mns6XBrWm7qc2TXCDfTlaUHiOUt-ls1hxsiG74y4lwEDJD34e6';

        $data = [
            'embeds' => [
                [
                    'title' => 'New Comment on ' . $title . '',
                    'description' => "Comment: $comment\nFrom: $username\nTimestamp: $date",
                    'color' => hexdec( 'f54242' ),
                    'footer' => [
                        'text' => $uuid
                    ]
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
        if ($result === FALSE) 
        {
            die('Error');
        }
        return $result;
    }
}
?>