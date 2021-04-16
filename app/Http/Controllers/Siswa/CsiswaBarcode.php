<?php

//membaca lokasi Controller dalam Folder
namespace App\Http\Controllers\Siswa;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; //menjalankan printah Hash Enkripsi
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use DataTables;
use Auth;//menjalankan printah auth
use App\User_siswa;
use App\Master_sekolah;
use App\Master_jurusan;
use App\Master_rombel;
use App\Master_kelas;
use App\Profile_siswa;
use App\Anggota_rombel;
use App\Master_wali_kelas;
use App\Log_aksi_user;
use App\Master_mapel_jadwal;
use App\Master_mapel_jadwal_rombel;
use App\El_materi;
use App\El_materi_view;
use App\El_materi_anggota_rombel;
use Illuminate\Support\Facades\Redirect;

class CsiswaBarcode extends Controller
{

//----------------------------------------------------------------------------
	public function ViewDataBarcode($id)
	{
    
    //enkripsi pada id siswa di kartu osis
    function dekripsi_kartuosis($string)
    {
      $output = false;

      $encrypt_method = "AES-256-CBC";
      $secret_key = 'SmkBudutWjabcdefghijklmnopqrstuvwxyzABNCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%^&*()_+|}{:?><';
        $secret_iv = 'SmkBudutWjabcdefghijklmnopqrstuvwxyzABNCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%^&*()_+|}{:?><';

      // hash
      $key = hash('sha256', $secret_key);

      // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
      $iv = substr(hash('sha256', $secret_iv), 0, 16);

      $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);

      return $output;
    }
    $idd =  dekripsi_kartuosis($id);
    $data = User_siswa::where('ssaUsername',$idd)->with('profile_siswa')->firstOrFail();;
		$params = [
      'title' =>'DATA SISWA',
      'label' =>'DATA SISWA',
      'datasiswa' =>$data,
    ];
      return view('siswa/cek_data_barcode')->with($params);
    
  }


	
}
