<?php
//membaca lokasi Controller dalam Folder
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; //menjalankan printah Hash Enkripsi

use Auth;//menjalankan printah auth

class LoginController extends Controller
{
	public function getLogin()
	{
		if(Auth::guard('admin')->check()){
			return redirect()->route('homeAdmin');
		}
		elseif(Auth::guard('guru')->check()){
			return redirect()->route('home.guru');
		}
		elseif(Auth::guard('siswa')->check()){
			return redirect()->route('homeSiswa');
		}
		else{
			return view('login_user');
		}
	}
	public function getLoginAdmin(){
		// cek apakah session login masih ada 
		if(Auth::guard('admin')->check()){
			return redirect()->route('homeAdmin');
		}
		else{
			return view('login_admin');
		}
	}

	public function CekLogin(Request $request)
	{
			// siswa -------------------------------------------------------
			if($request->jabatan == 'SIS'){ //jika jabatan siswa
				
				//jika di ceklis maka benar, jika tidak maka salah
				$ingat = $request->remember ? true : false; 

				//validasi reques yang masuk
				$this->validate($request, [
					'username' => 'required', 
					'jabatan' => 'required', 
					'password' => 'required|string|min:6',
				]);
				$credentials = [
					'ssaUsername' => $request->username,
					'ssaIsActive' =>1, 
					'password' => $request->password,
				];
				if (Auth::guard('siswa')->attempt($credentials,$ingat)) { return redirect()->route('homeSiswa'); }
				else{ return redirect()->route('login.user')->with(['error' => 'Username/Password salah!']); }
			}
			// admin -------------------------------------------------------
			if($request->jabatan == 'ADM'){ //jika jabtan admin
				//validasi reques yang masuk
				$this->validate($request, [
					'username' => 'required', 
					'jabatan' => 'required', 
					'password' => 'required|string|min:6',
				]);
				$credentials = [
					'admUsername' => $request->username, 
					'password' => $request->password,
				];
				if (Auth::guard('admin')->attempt($credentials)) { return redirect()->route('homeAdmin'); }
				else{ return redirect()->route('login')->with(['error' => 'Username/Password salah!']); }
				
			}
			// ortu -------------------------------------------------------
			if($request->jabatan == 'ORTU'){ //jika jabatan orang tua
				if (Auth::guard('wali')->attempt($credentials)) { return redirect()->route('homeWali'); }
				else{ return redirect()->route('ogin.user')->with(['error' => 'Username/Password salah!']); }
			}
			// guru -------------------------------------------------------
			if($request->jabatan == 'GURU'){ //jika jabatan guru

				//jika di ceklis maka benar, jika tidak maka salah
				$ingat = $request->remember ? true : false; 

				$this->validate($request, [
					'username' => 'required', 
					'jabatan' => 'required', 
					'password' => 'required|string|min:6',
				]);
				$login = [
					'ugrUsername' => $request->username, 
					'ugrIsActive' =>1,
					'password' => $request->password,
					
				];
				if (Auth::guard('guru')->attempt($login,$ingat)) {
					return redirect()->route('home.guru');
				}
				else{
					//JIKA SALAH, MAKA KEMBALI KE LOGIN DAN TAMPILKAN NOTIFIKASI 
					return redirect()->route('login.user')->with(['error' => 'Username/Password salah!']);
				}
			}
			
	}
	public function logout(){
		if(Auth::guard('admin')->check()){
			Auth::guard('admin')->logout();
			return redirect()->route('login');
		}
		elseif(Auth::guard('siswa')->check()){
			Auth::guard('siswa')->logout();
			return redirect()->route('login.user')->with(['success' => 'Berhasil Logout']);
		}
		elseif(Auth::guard('guru')->check()){
			Auth::guard('guru')->logout();
			return redirect()->route('login.user')->with(['success' => 'Berhasil Logout']);
		}
		else{
			return redirect()->route('login.user')->with(['success' => 'Berhasil Logout']);
		}
		
    
	}

}
