<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function __construct()
    {
        // Authが認証していたら各Methodが使用できる
        $this->middleware('auth:owners');

        $this->middleware(function ($request, $next) {

            $id = $request->route()->parameter('image');
            if (is_null(!$id)) { // null判定
                $imagesOwnerId = Image::findOrFail($id)->owner->id;
                $shopId = (int) $imagesOwnerId;
                if ($shopId !== Auth::id()) { 
                    abort(404);
                }
            }
            return $next($request);
        });
    }


    public function index()
    {
        //
         // 現在認証されているユーザーのID取得
        $images = Image::where('owner_id', Auth::id())
        ->orderBy('updated_at', 'desc')->paginate(10);
        // ->pagenate(20)->get();
        

        return view('owner.images.index', compact('images'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
