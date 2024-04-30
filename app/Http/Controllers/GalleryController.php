<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;


class GalleryController extends Controller
{
    public function index()
    {
        $file = Storage::get('app/gallery/gallery.json');
        $json = json_decode($file, true);
        $json = $json ?? [];

        return view('gallery')->with('json', $json);
    }
    

    public function uploadPost()
    {
        $request = request();
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => 'required|image',
        ]);

        $file = $request->file('image');
        $filename = $file->getClientOriginalName();
        $title = $request->input('title');
        $description = $request->input('description');
        $date = Carbon::now()->toDateTimeString();
        $dimensions = getimagesize($file);
        $size = $file->getSize();
        $uuid = bin2hex(random_bytes(16));
        $author = Auth::user()->username;
        $authorid = Auth::user()->id;


        $json = json_decode(Storage::get('app/gallery/gallery.json'));
        $json = $json ?? [];
        $json[] = [
            'filename' => $filename,
            'title' => $title,
            'description' => $description,
            'created' => $date,
            'height' => $dimensions[0],
            'width' => $dimensions[1],
            'size' => $size,
            'uuid' => $uuid,
            'author'=> $author,
            'authorid'=> $authorid,
        ];
        Storage::put('app/gallery/gallery.json', json_encode($json));
        Storage::put('app/gallery/' . $filename, file_get_contents($file));

        response()->json(['success' => 'You have successfully uploaded an image.']);
        return redirect()->route('gallery');
    }

    public function delete($filename)
    {
        $json = json_decode(Storage::get('app/gallery/gallery.json'));
        $json = $json ?? [];
        $newJson = [];
        foreach ($json as $item) {
            if ($item->filename != $filename) {
                $newJson[] = $item;
            }
        }
        Storage::put('app/gallery/gallery.json', json_encode($newJson));
        Storage::delete('app/gallery/' . $filename);
        return redirect()->route('gallery');
    }

}
