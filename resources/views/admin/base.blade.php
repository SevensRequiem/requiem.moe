@if (in_array($userId, $adminIds))
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<div id="admin">
    <div id="user">
        <span>Logged in as: {{ Auth::user()->username }}</span>
    </div>
        <div id="buttons">
                    <span>[<a href="/admin">Home</a>]</span>
                    <span>[<a href="/admin/post">Make Blog Post</a>]</span>
                    <span>[<a href="/admin/logs">Logs</a>]</span>
                    <span>[<a href="/admin/analytics">Analytics</a>]</span>
            </div>
            <fieldset id="console">
                <legend>console</legend>
                <!--Console will just be a div that is updated with the content of buttons, ajax-->
                <div id="console-content">
                    <span><a href="#">Clear Cache</a></span>
                    
                    <hr>
                    env('APP_NAME') = {{ env('APP_NAME') }}
                    <br>
                    env('APP_ENV') = {{ env('APP_ENV') }}
                    <br>
                    env('APP_DEBUG') = {{ env('APP_DEBUG') }}
                    <br>
                    env('APP_URL') = {{ env('APP_URL') }}
                    <br>
                    env('APP_TIMEZONE') = {{ env('APP_TIMEZONE') }}
                    <br>
                    env('APP_LOCALE') = {{ env('APP_LOCALE') }}
                    <br>
                    env('LOG_CHANNEL') = {{ env('LOG_CHANNEL') }}
                    <br>
                    env('LOG_LEVEL') = {{ env('LOG_LEVEL') }}
                    <br>
                    env('MAIL_MAILER') = {{ env('MAIL_MAILER') }}
                    <br>
                    env('MAIL_HOST') = {{ env('MAIL_HOST') }}
                    <br>
                    env('MAIL_PORT') = {{ env('MAIL_PORT') }}
                    <br>
                    env('MAIL_USERNAME') = {{ env('MAIL_USERNAME') }}
                    <br>
                    env('MAIL_FROM_ADDRESS') = {{ env('MAIL_FROM_ADDRESS') }}
                    <br>
                    env('PUSHER_APP_ID') = {{ env('PUSHER_APP_ID') }}
                    <br>
                    env('PUSHER_APP_KEY') = {{ env('PUSHER_APP_KEY') }}
                    <br>
                    env('PUSHER_APP_CLUSTER') = {{ env('PUSHER_APP_CLUSTER') }}
                    <br>
                    env('LARASCORD_CLIENT_ID') = {{ env('LARASCORD_CLIENT_ID') }}
                    <br>
                    <hr>
                    <?php
                    $session = session()->all();
                    echo "<pre>";
                    print_r($session);
                    echo "</pre>";

                    
                    ?>

                </div>
            </fieldset>
</div>
    <style>
content {
    grid-area: content; /* assign the content area to the .content element */
    align-items: center;
    justify-content: center;
    display: grid;
    grid-template-areas: 
      "user buttons console"
      "user buttons console";
    padding: 1px;
    margin: 5px;
    grid-gap: 10px;
  }
    #user {
        grid-area: user;
        max-height: calc(70vh - 50px); /* 70% of viewport height minus header height */
  max-width: 30vh;
  height: 100%;
  text-align: center;
    }
    #buttons {
        grid-area: buttons;
        max-height: calc(70vh - 50px); /* 50% of viewport height minus header height */
  height: 100%;
  text-align: center;
    }
    #console {
        grid-area: console;
        max-height: calc(70vh - 50px); /* 50% of viewport height minus header height */
  height: 100%;
  text-align: left;
    }
    </style>
@endif