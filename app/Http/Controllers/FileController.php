<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileRequest;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Image;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function upload_image(FileRequest $request)
    {
        $img = $request->user_image;
        $id = $request->user()->id;
        $image = Image::make($img);
        $image->backup();
        $res = $image->resize(400, null, function ($constraint) {
            $constraint->aspectRatio();
        })->encode();
        //$res->reset()->save('C:/xampp/htdocs/backup.png');
        $extension = $img->getClientOriginalExtension();

        $timestamp = now()->timestamp;
        //$result = $img->move('C:/xampp/htdocs/', $img->getCLientOriginalName());
        Storage::disk('huy')->makeDirectory('IMAGE_STORAGE');
        $result = Storage::disk('huy')->put('/' . 'IMAGE_STORAGE' . '/' . $timestamp . $id . '.' . $extension, $res);

        return response()->json([
            'result' => $result
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function get_image(Request $request)
    {
        //
        return Storage::disk('huy')->download('42tpwq.png');
    }

    public function get_avatar(Request $request)
    {
        //

    }
}
