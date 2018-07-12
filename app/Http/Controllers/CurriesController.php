<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Curry;
use App\Shop;
use App\Photo;
use Image;

class CurriesController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth', ['except' => ['show', 'search']]);
  }

  public function show($id1, $id2)
  {
      $curry = Curry::where('shop_id', $id1)->find($id2);
      return view('curries.show')->with('curry', $curry);
  }

  public function search(Request $request)
  {
      // 検索フォームのキーワードあいまい検索
      $word = $request->keyword;
      // ジャンルで検索(カレー種類)
      $type = $request->curry_type;
      if(!empty($type)){
        $curries = Curry::where('curry_type', $type)->paginate(15);
        switch ($type){
          case 1:
          $word = "洋風カレー";break;
          case 2:
          $word = "スープカレー";break;
          case 3:
          $word = "インドカレー";break;
          case 4:
          $word = "ご当地カレー";break;
          case 5:
          $word = "その他";break;
        }
        $word = 'カレー種類：'.$word;
      }
      // ジャンルで検索(メイン具材)
      $type = $request->main_type;
      if(!empty($type)){
        $curries = Curry::where('main_ingredien', $type)->paginate(15);
        switch ($type){
          case 1:
          $word = "チキン";break;
          case 2:
          $word = "ビーフ";break;
          case 3:
          $word = "ポーク";break;
          case 4:
          $word = "マトン";break;
          case 5:
          $word = "シーフード";break;
          case 6:
          $word = "野菜";break;
          case 7:
          $word = "その他";break;
        }
        $word = 'メイン具材：'.$word;
      }
      // ジャンルで検索(ライスorナン)
      $type = $request->ricenaan_type;
      if(!empty($type)){
        $curries = Curry::where('naan_rice', $type)->paginate(15);
        switch ($type){
          case 1:
          $word = "ライス";break;
          case 2:
          $word = "ナン";break;
          case 3:
          $word = "その他";break;
        }
        $word = 'ライス/ナン：'.$word;
      }
      else{
        $curries = Curry::where('curry_name', 'LIKE', "%$word%")->paginate(15);
      }
      return view('curries.search')->with(array('curries' => $curries, 'word' => $word));
  }

  public function create($id1)
  {
      $shop = Shop::find($id1);
      return view('curries.create')->with('shop', $shop);
  }

  public function store(Request $request, $id1)
  {
      // 写真を保存
      $fileName = $request->picture->getClientOriginalName();
      Image::make($request->picture)->save(public_path() . '/images/curries/' . $fileName);

      $recipe = new Curry();
      //カレーDBに入力
      $recipe->curry_name = $request->name;
      $recipe->price = $request->price;
      $pr_url = url()->previous();
      $recipe->shop_id = $id1;
      $recipe->save();

      //写真DBに入力
      $photo = new Photo();
      $photo->image = $fileName;
      $photo->curry_id = $recipe->id;
      $photo->save();
      return redirect('/shops/'.$id1.'/curries/'.$recipe->id);
  }
}
