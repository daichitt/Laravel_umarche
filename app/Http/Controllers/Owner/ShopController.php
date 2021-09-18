<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; 

class ShopController extends Controller
{


    public function __construct()
    {
        // Authが認証していたら各Methodが使用できる
        $this->middleware('auth:owners');

        // ログインしているオーナーのShopかチェックするミドルウェア
        $this->middleware(function ($request, $next) {

            $id = $request->route()->parameter('shop');
            if (is_null(!$id)) { // null判定
                $shopsOwnerId = Shop::findOrFail($id)->owner->id;
                $shopId = (int) $shopsOwnerId;
                $ownerId = Auth::id();
                if ($shopId !== $ownerId) { //同じでなかったら
                    abort(404);
                }
            }
            // dd($request->route()->parameter('shop'));  //文字列が取得できる
            // dd(Auth::id()); //数字
            return $next($request);
        });
    }


    //
    public function index()
    {

        // 現在認証されているユーザーのID取得
        // dd($ownerId);
        $shops = Shop::where('owner_id', Auth::id())->get();
        // dd($shops);

        return view('owner.shops.index', compact('shops'));
    }


    public function edit($id)
    {
        $shop = Shop::findOrFail($id);
        //Eloquent
        // dd($shop);
        return view('owner.shops.edit', compact('shop'));
    }


    public function update(Request $request, $id)
    {
        // Update images
        $imageFile = $request->image; //一時保存
        if (!is_null($imageFile) && $imageFile->isValid()) { //nullでないかつアップロードに成功
            Storage::putFile('public/shops', $imageFile);
        }


        return redirect()
            ->route('owner.shops.index');
            // ->with(['message' => 'オーナー情報を更新しました。', 'status' => 'info']);
    }
}
