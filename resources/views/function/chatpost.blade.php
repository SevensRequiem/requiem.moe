<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('chat');
});

Route::post('/send-message', function (Request $request) {
    // Get the username and message from the form data
    $username = $request->input('username');
    $message = $request->input('message');

    // Insert the message into the database
    DB::connection('chat')->table('messages')->insert([
        'username' => $username,
        'message' => $message,
    ]);

    // Send the message to Pusher
    $options = array(
        'cluster' => env('PUSHER_APP_CLUSTER'),
        'useTLS' => true
    );
    $pusher = new Pusher\Pusher(
        env('PUSHER_APP_KEY'),
        env('PUSHER_APP_SECRET'),
        env('PUSHER_APP_ID'),
        $options
    );
    $data['message'] = $message;
    $data['username'] = $username;
    $pusher->trigger('chat-channel', 'chat-event', $data);

    // Return a success message
    return response()->json(['status' => 'Message sent successfully.']);
});

?>