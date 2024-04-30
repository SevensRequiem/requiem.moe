<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\HitCounterController;
use App\Http\Controllers\LoggingController;
use App\Http\Controllers\DiscordNotificationController;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\MimeType\ExtensionGuesser;
use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesser;
use Spatie\ImageOptimizer\OptimizerChainFactory;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::feeds();
Route::get('/feeds/main', 'FeedController@main')->name('feeds.main');
Route::get('/rss', function () {
    return view('rss');
});

Route::get('/', function () {
    $hit = new HitCounterController();
    $hit->addhit();

    $log = new LoggingController();
    $log->log(request(), '/');
    return view('base');
});
Route::get('/hits', function () {
    $hit = new HitCounterController();
    $hits = $hit->gethits();
    return response()->json($hits);
});
Route::get('/{page}', function ($page) {
    $hit = new HitCounterController();
    $hit->addhit();

    $log = new LoggingController();
    $log->log(request(), $page);

    return view('base');
})->where('page', 'home|blog|projects|version|tools|gallery|store|about|donate');
use App\Http\Controllers\ChatController;

Route::post('/send-message', [ChatController::class, 'sendMessage']);


Route::get('/get-messages', function () {
    $userId = auth()->id();
    $adminIds = explode(',', env('ADMIN_IDS'));

    if (in_array($userId, $adminIds)) {
        $messages = DB::connection('chat')->table('messages')->get();
        return response()->json(['messages' => $messages]);
    } else {
        $messages = DB::connection('chat')->table('messages')->select('id', 'message', 'timestamp', 'trueuser', 'username')->get();
        return response()->json(['messages' => $messages]);
    }
});

Route::delete('/messages/{uuid}', [ChatController::class, 'deleteMessage']);

Route::get('/admin', function () {
    $userId = auth()->id();
    $adminIds = explode(',', env('ADMIN_IDS'));

    if (in_array($userId, $adminIds)) {
        return view('base', compact('userId', 'adminIds'));
    } else {
        abort(404);
    }
});
Route::get('/admin/post', function () {
    $userId = auth()->id();
    $adminIds = explode(',', env('ADMIN_IDS'));

    if (in_array($userId, $adminIds)) {
        return view('base', compact('userId', 'adminIds'));
    } else {
        abort(404);
    }
});
Route::get('/admin/logs', function () {
    $userId = auth()->id();
    $adminIds = explode(',', env('ADMIN_IDS'));

    if (in_array($userId, $adminIds)) {
        return view('base', compact('userId', 'adminIds'));
    } else {
        abort(404);
    }
});
Route::get('/admin/analytics', function () {
    $userId = auth()->id();
    $adminIds = explode(',', env('ADMIN_IDS'));

    if (in_array($userId, $adminIds)) {
        return view('base', compact('userId', 'adminIds'));
    } else {
        abort(404);
    }
});

Route::get('/download/{file}', function ($file) {
    $path = storage_path("app/public/tools/$file");
    if (file_exists($path)) {
        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . basename($path) . '"',
        ];
        return response()->download($path, basename($path), $headers);
    } else {
        abort(404);
    }
})->name('download');
Route::get('/logout', function () {
    Auth::logout();
    return redirect('/home');
});

use Illuminate\Support\Facades\File;

Route::get('/static/gallery/{uuid}', function ($uuid) {
    $allowedExtensions = ['jpg', 'png', 'webm', 'jpeg', 'gif', 'mp4']; // specify allowed extensions here
    $files = glob(storage_path('app/gallery/' . $uuid . '.*'));

    if (!empty($files)) {
        $path = $files[0]; // take the first matching file
        $extension = File::extension($path);

        if (in_array($extension, $allowedExtensions)) {
            $mimeType = File::mimeType($path);
            return response()->file($path, ['Content-Type' => $mimeType]);
        } else {
            return response("File type not allowed", 403);
        }
    } else {
        return response("File not found", 404);
    }
});
Route::get('/static/banner/{uuid}', function ($uuid) {
    $allowedExtensions = ['jpg', 'png', 'jpeg', 'gif']; // specify allowed extensions here
    $files = glob(storage_path('app/banner/' . $uuid . '.*'));

    if (!empty($files)) {
        $path = $files[0]; // take the first matching file
        $extension = File::extension($path);

        if (in_array($extension, $allowedExtensions)) {
            $mimeType = File::mimeType($path);
            return response()->file($path, ['Content-Type' => $mimeType]);
        } else {
            return response("File type not allowed", 403);
        }
    } else {
        return response("File not found", 404);
    }
});


use App\Http\Controllers\BlogController;

Route::post('/admin/bpost', [BlogController::class, 'store']);

Route::get('/static/blog/{uuid}', function ($uuid) {
    $postDirectory = storage_path('app/blog/' . $uuid);

    if (File::isDirectory($postDirectory)) {
        $files = File::allFiles($postDirectory);
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $fileCount = 0;

        foreach ($files as $file) {
            $extension = strtolower($file->getExtension());

            if (in_array($extension, $imageExtensions)) {
                $fileCount++;
                $headers = [
                    'Content-Type' => File::mimeType($file),
                    'Content-Disposition' => 'inline; filename="' . $file->getFilename() . '"',
                ];
                return response()->file($file->getPathname(), $headers);
            }
        }

        if ($fileCount === 0) {
            abort(404);
        }
    } else {
        abort(404);
    }
});

Route::get('/blog/{uuid}', [BlogController::class, 'showpost']);

Route::post('/blog-comment', [BlogController::class, 'postcomment']);
Route::delete('/blog-comment/{uuid}/{commentid}', [BlogController::class, 'deletecomment']);

use Illuminate\Support\Facades\Cache;
Route::get('/stats', function () {
    $moeusers = DB::connection('main')->table('users')->count();
    $file_size = Cache::remember('file_size', 336 * 60, function () {
        $size = 0;
        foreach (File::allFiles(base_path()) as $file) {
          $size += $file->getSize();
        }
        return $size;
      });
    return response()->json(['moeUsers' => $moeusers , 'fileSize' => number_format($file_size / 1073741824, 2) . ' GB']);
});

use App\Http\Controllers\AnimeController;

Route::get('/fetchanime', [AnimeController::class, 'fetchAnime']);
Route::get('/fetchfavs', [AnimeController::class, 'fetchFavs']);
Route::get('/fetchwaifus', [AnimeController::class, 'fetchWaifus']);


use App\Http\Controllers\GalleryController;

use App\Http\Controllers\ContactController;

Route::post('/contact', [ContactController::class, 'NewContact']);
Route::post('/comment', [DiscordNotificationController::class, 'NewComment']);
Route::post('/message', [DiscordNotificationController::class, 'NewMessage']);
use Illuminate\Support\Facades\Log;
use App\Models\Donation;

Route::post('/charge', 'App\Http\Controllers\DonationController@charge');
Route::post('/create-checkout-session', 'App\Http\Controllers\DonationController@createCheckoutSession');
Route::get('/?donate&session_id={CHECKOUT_SESSION_ID}&success=true', function () {
    $session_id = request()->query('session_id');
    $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
    $session = $stripe->checkout->sessions->retrieve($session_id, []);
    $amount = $session->amount_total / 100;
    $donation = new Donation();
    $donation->amount = $amount;
    $donation->save();

    return redirect(env('APP_URL'));
});