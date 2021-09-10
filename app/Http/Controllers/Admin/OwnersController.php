<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Owner; //Eloquent
use Illuminate\Support\Facades\DB; // Query Bulid
use Carbon\Carbon; // for date
use Illuminate\Support\Facades\Hash; // for store action
use Illuminate\Validation\Rules;
use SoftDeletes;
use Throwable;
use Illuminate\Support\Facades\Log;
use App\Models\Shop;


class OwnersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        // Authが認証していたら各Methodが使用できる
        $this->middleware('auth:admin');
    }



    public function index()
    {

        // 
        // $date_now = Carbon::now();
        // $date_parse = Carbon::parse(now());
        // echo $date_now;
        // echo $date_parse;
        // Eloquent方式
        $e_all = Owner::all();

        // QueryBuild方式
        $q_get = DB::table('owners')->select('name', 'created_at')->get();
        // $q_first = DB::table('owners')->select('name')->first();

        //Collection method
        // $c_test = collect([
        //     'name' => 'てすと'
        // ]);
        // var_dump($q_first);
        // dd($e_all, $q_get, $q_first, $c_test);

        // Get data with Eloquent method
        $owners = Owner::select('id', 'name', 'email', 'created_at')->paginate(3);
        return view('admin.owners.index', compact('owners'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.owners.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // i can get data form form use request
        // for instance $request->name = date from forms name="name" it like params in Ruby

        // Do validate
        $request->validate([
            'name' => 'required|string|max:225',
            'email' => 'required|string|email|max:225|unique:owners',
            'password' => 'required|string|confirmed|min:8',
            // confirmed = Password and password_confirmation
        ]);
        // $request->validate([
        //     'name' => 'required|string|max:255',
        //     'email' => 'required|string|email|max:255|unique:admins',
        //     'password' => ['required', 'confirmed', Rules\Password::defaults()],
        // ]);

        // If erroe ocared $e = error
        try {


            DB::transaction(function () use ($request) {

                // $request->name;
                $owner = Owner::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);

                // オーナーとShopは1 to 1の関係のためOwnerを作成しそのIDでShopを作成
                Shop::create([
                    'owner_id' => $owner->id,
                    'name' => '店名を入力',
                    'information' => '',
                    'filename' => '',
                    'is_selling' => true,

                ]);
            }, 2);
        } catch (Throwable $e) {
            Log::error($e);
            throw $e;
        }


        // If done redirect with route
        return redirect()->route('admin.owners.index')->with(['message' => 'オーナー登録を実施しました', 'status' => 'info']);
        // return redirect('/')->with('flash_message', '投稿が完了しました');

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
        //Eloquent
        // findOrFail = 存在しない$idを取得した時は404を返す
        $owner = Owner::findOrFail($id);

        // dd($owner);
        return view('admin.owners.edit', compact('owner'));
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
        $owner = Owner::findOrfail($id);
        $owner->name = $request->name;
        $owner->email = $request->email;
        $owner->password = Hash::make($request->password);
        $owner->save();

        return redirect()
            ->route('admin.owners.index')
            ->with(['message' => 'オーナー情報を更新しました。', 'status' => 'info']);
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
        // dd('削除処理');
        Owner::findOrfail($id)->delete();
        return redirect()
            ->route('admin.owners.index')
            ->with(['message' => 'オーナー情報を削除しました。', 'status' => 'alert']);
    }

    // expiredOwnerIndex
    public function expiredOwnerIndex()
    {
        // onlyTrashedメソッドによりソフトデリート済みのモデルのみを取得できます。
        // dd('hhh');
        $expiredOwners = Owner::onlyTrashed()->get();
        return view('admin.expired-owners', compact('expiredOwners'));
    }


    public function expiredOwnerDestroy($id)
    {
        // onlyTrashedメソッドによりソフトデリート済みのモデルのみを取得できます。
        Owner::onlyTrashed()->findOrfail($id)->forceDelete();
        return redirect()->route('admin.expired-owners.index');
    }
}
