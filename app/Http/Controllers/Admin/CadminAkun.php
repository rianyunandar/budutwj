<?php

//membaca lokasi Controller dalam Folder
namespace App\Http\Controllers\Admin;



use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; //menjalankan printah Hash Enkripsi
use Illuminate\Support\Facades\Crypt;

use Auth;//menjalankan printah auth
use App\User_admin;
use App\User_guru;
use App\Master_sekolah;
use App\Master_jabatan;
use App\Setting;

class CadminAkun extends Controller
{
	public function __construct()
  {
    $this->middleware('auth:admin');
	}
 
	public function index()
	{
		$params = [
			'title'	=>'Setting Akun Admin',
			'label'	=>'<b>SETTING AKUN ADMIN </b>',
			'idUser'	=> Crypt::encrypt(Auth::user()->admId),
			
		];
		return view('crew/akun/profile')->with($params);
	}
	function UpdateAdmin(Request $request){
		$idd = Crypt::decrypt($request->id);
		$data = User_admin::find($idd);
		$data->admFirstName	= $request->namadepan;
		$data->admLastName	= $request->namabelakang;
		$data->admUpdated	= date("Y-m-d h:i:s");
		$data->admUpdatedBy	= Auth::user()->admId;

		if(!empty($request->newpassword)){
			$data->admPassword	= Hash::make($request->newpassword);
		}

		if($data->save()){
			$response = ['save'=>'Berhasil Update Data Akun'];
		}
		return response()->json($response,200);
		
	}
	//tampilkan akun admin
	function listAkunAdmin(){
		$params = [
			'title'	=>'Data Akun Admin',
			'label'	=>'<b>DATA AKUN ADMIN </b>',
			'no'=>1,
			'getData' => User_admin::get(),
		];
		return view('crew/akun/view_admin')->with($params);
	}
	function add(){
		$params = [
			'title'	=>'Data Akun Admin',
			'label'	=>'<b>DATA AKUN ADMIN </b>',
			'no'=>1,
			'getSekolah' => Master_sekolah::get(),
			'getGuru' => User_guru::get(),
			'getJabatan' =>Master_jabatan::get(),
		];
		return view('crew/akun/add_admin')->with($params);
	}

	function edit($id){
		$idd = decrypt_url($id);
		$data = User_admin::find($idd);
		$params = [
			'title'	=>'Data Akun Admin',
			'label'	=>'<b>DATA AKUN ADMIN </b>',
			'no'=>1,
			'getSekolah' => Master_sekolah::get(),
			'getGuru' => User_guru::get(),
			'getJabatan' =>Master_jabatan::get(),
			'id'	=>$idd,
			'dataAdmin'	=>$data,
		];
		return view('crew/akun/edit_admin')->with($params);
	}

	function insertAdmin(Request $request)
	{
		$id = decrypt_url($request->guru);
		$guru = User_guru::find($id);

		if($request->skl == "all"){
			$skl = null;
		}
		else{
			$skl = $request->skl;
		}

		$data = new User_admin();
		$data->admSklId = $skl;
		$data->admRole = $request->hak;
		$data->admUsername = $guru->ugrUsername;
		$data->admPassword = $guru->ugrPassword;
		$data->admFirstName = $guru->ugrFirstName;
		$data->admLastName = $guru->ugrLastName;
		if($data->save()){
			$response = ['save'=>'Berhasil Tambah Akun'];
		}
		else{
			$response = ['error'=>'Opss Gagal '];
		}
		return response()->json($response,200);

	}

	function updateAkunAdmin(Request $request)
	{
		$id = $request->id;
		$username = decrypt_url($request->guru);
		$guru = User_guru::find($username);

		if($request->skl == "all"){
			$skl = null;
		}
		else{
			$skl = $request->skl;
		}

		$data = User_admin::find($id);
		$data->admSklId = $skl;
		$data->admRole = $request->hak;
		$data->admUsername = $guru->ugrUsername;
		$data->admPassword = $guru->ugrPassword;
		$data->admFirstName = $guru->ugrFirstName;
		$data->admLastName = $guru->ugrLastName;
		if($data->save()){
			$response = ['save'=>'Berhasil Update Akun'];
		}
		else{
			$response = ['error'=>'Opss Gagal '];
		}
		return response()->json($response,200);

	}

	function ResetPassword(Request $request){
		$set = Setting::first();
    $idd = decrypt_url($request->id);
    $data = User_admin::find($idd);
    $data->admPassword  = Hash::make($set->setResetPassAdmin);
    $data->admUpdated = date("Y-m-d H:i:s");
    $data->admUpdatedBy = Auth::user()->admId;
    
    if($data->save()){
      return response()->json([
        'success' => 'Akun Berhasil Di Reset Passwordnya '.$set->setResetPassAdmin,
      ]);
    }
    else{
      return response()->json([
        'error' => 'Opsss Gagal !'
      ]);
    }
	}

	
}
