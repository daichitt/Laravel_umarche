<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LifeCycleTestController extends Controller
{
    //
    public function showServiceProviderTest(){


        $encrypter = app()->make('encrypter');
        // encryptメソッドで暗号化をする
        $password = $encrypter->encrypt('password');


        $sample = app()->make('ServiceProviderTest');
        // decryptメソッドで暗号化を復元
        dd($password, $sample, $encrypter->decrypt($password));
    }




    public function showServiceContainerTest()
    {

        app()->bind('LifeCycleTest', function(){
            return 'ライフサイクルテスト';
        });

        $a = app()->make('LifeCycleTest');

        // サービスコンテナなしのパターン
        // $message = new Message();
        // $sample = new Sample($message);
        // $sample->run();


        // サービスコンテナapp()ありのパターン
        app()->bind('sample', Sample::class);
        $sample = app()->make('sample');
        $sample->run();


        dd($a, app());

    }
}



class Sample {
    public $message;
    public function  __construct(Message $message){
        $this->message = $message;
    }

    public function  run(){
        $this->message->send();
    }
    
}



class Message {
    public function send(){
        echo('メッセージ表示');
    }
}

