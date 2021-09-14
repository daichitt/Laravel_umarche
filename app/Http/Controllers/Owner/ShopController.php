<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

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
                if($shopId !== $ownerId) { //同じでなかったら
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
        $ownerId = Auth::id();
        // dd($ownerId);
        $shops = Shop::where('owner_id', $ownerId)->get();
        return view('owner.shops.index', compact('shops'));
    }


    public function edit($id)
    {
        dd(Shop::findOrFail($id));
        //Eloquent
        // findOrFail = 存在しない$idを取得した時は404を返す
        $owner = Owner::findOrFail($id);
        // dd($owner);
        return view('admin.owners.edit', compact('owner'));
    }


    public function update(Request $request, $id)
    {
        //
        $owner = Owner::findOrfail($id);
        $owner->name = $request->name;
        $owner->email = $request->email;
        $owner->password = Hash::make($request->password);
        $owner->save();

        return redirect()
            ->route('admin.owners.index')
            ->with(['message' => 'オーナー情報を更新しました。', 'status' => 'info']);
    }
}
