<?php

//membaca lokasi Controller dalam Folder
namespace App\Http\Controllers\Wali;



use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; //menjalankan printah Hash Enkripsi

use Auth;//menjalankan printah auth

class CwaliHome extends Controller
{
	public function index()
	{
		return view('wali/wali_home');
	}

	
}
