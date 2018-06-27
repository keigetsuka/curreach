<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Shop;

class ShopsController extends Controller
{
  /*public function __construct()
  {
    $this->middleware('auth', ['except' => ['show', 'mapsearch']]);
  }*/

  public function show()
  {
      return view('shops.index');
  }

  public function mapsearch()
  {
      return view('shops.mapsearch');
  }

  public function create()
  {
      return view('shops.create');
  }

  public function store()
  {
      //return redirect('/');
  }
}