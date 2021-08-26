<?php

//membaca lokasi Controller dalam Folder
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash; //menjalankan printah Hash Enkripsi
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Carbon;
use Auth;//menjalankan printah auth
use DB;
use Excel;
use DataTables;
use App\User_guru;
use App\Profile_guru;
use App\Master_sekolah;
use App\Master_jabatan;
use App\Master_jenis_ptk;
use App\Master_status_kepegawaian;
use App\Master_jenjang_pendidikan;
use App\Master_agama;
use App\Log_aksi_user;
use App\Imports\ImportDataGuru;
use App\Setting;


class CadminGuru extends Controller
{
	public function __construct()
  {
    $this->middleware('auth:admin');
  }

  function getSkl()
  {
  	$idskl = Auth::user()->admSklId; 
  	return $idskl;
  }
  function getSekolah()
  {
    $data = new Master_sekolah();
    return $data->getSekolah($this->getSkl());
  }
  function getJabatan()
  {
    $data = new Master_jabatan();
    return $data->getJabatan($this->getSkl());
  }
  function getPtk()
  {
    $data = new Master_jenis_ptk();
    return $data->getJenisPtk();
  }

  function getStatusPegawai()
  {
    $data = new Master_status_kepegawaian();
    return $data->getStatusPegawai();
  }
  function getAgama()
  {
    $data = new Master_agama();
    return $data->getAgama();
  }
  function getJenjangPendidikan()
  {
    $data = new Master_jenjang_pendidikan();
    return $data->getJenjangPendidikan();
  }

  
 
	public function index()
	{
		
		//return view('crew/crew_home')->with($params);
	}
	public function lihatGuru()
	{	
		$params = [
			'title'	=>'Data Guru',
			'label'	=>'FORM DATA GURU ',
		];
		return view('crew/guru/viewguru')->with($params);
	}
  public function lihatGuruOff()
	{	
		$params = [
			'title'	=>'Data Guru OFF',
			'label'	=>'FORM DATA GURU OFF',
		];
		return view('crew/guru/viewguruoff')->with($params);
	}
  public function editGuru($id)
  {
    $idd = Crypt::decrypt($id);
    $dataguru = User_guru::find($idd);
    $params = [
      'title' =>'Edit Guru',
      'label' =>'<b>FORM EDIT DATA GURU</b>',
      'idguru' =>$id,
      'getSekolah' => $this->getSekolah(),
      'getJabatan' => $this->getJabatan(),
      'getPtk' => $this->getPtk(),
      'getPegawai' =>$this->getStatusPegawai(),
      'getAgama' =>$this->getAgama(),
      'getPendidikan' =>$this->getJenjangPendidikan(),
      'guru' =>$dataguru,
    ];
    return view('crew/guru/editguru')->with($params);
  }
  public function add()
  {
    $params = [
      'title' =>'Tambah Aku Guru',
      'label' =>'<b>FORM TAMBAH AKUN GURU </b>',
      'getSekolah' => $this->getSekolah(),
      'getJabatan' => $this->getJabatan(),
      'getPtk' => $this->getPtk(),
      'getPegawai' =>$this->getStatusPegawai(),
      'getAgama' =>$this->getAgama(),
      'getPendidikan' =>$this->getJenjangPendidikan(),
    ];
    return view('crew/guru/addguru')->with($params);
  }
  public function formImportSiswa(){
    $params = [
      'title' =>'Import Data Guru',
      'label' =>'<b>FORM IMPORT DATA GURU </b>',
    ];
    return view('crew/guru/form_import_data_guru')->with($params);
  }
  public function ImportDataGuru(Request $request)
  {
    $file = $request->file('import_data_guru');
    $extensi = $file->getClientOriginalExtension();

    if($extensi == 'xls' or $extensi == 'xlsx' ){
      $import = new ImportDataGuru();
      Excel::import($import,$file);
      $insert = get_object_vars($import); //mengubah objek ke data array
      if($insert['hasil'] ==1){
        $response = ['save'=>'Berhasil Upload Data '.$insert['jm'].' Guru'];
      }
      elseif ($insert['hasil'] == 0) {
        $response = ['error'=>'Gagal Upload Data Guru'];
      }
      else{
        $response = ['error'=>'Sistem Error'];
      }

    }
    else{
      $response = ['danger'=>'Cek File, Pastikan Ber extensi atau Format XLSX'];
     }
     //return redirect('crew/form-import-siswa')->with($response);
     return response()->json($response,200);    
  }

  function InsertGuru(Request $request)
  {
    $pesan =[
        'ugrUsername.required' =>'Username Tidak Boleh Kosong',
        'unique' => 'Username Sudah Terdaftar Di Database',
      ];

      $validator = Validator::make(request()->all(), [
        'ugrUsername' => 'required|unique:user_guru',
      ],$pesan);

    if($validator->fails()) {
      $response = $validator->messages();
    }
    else{ 
      $guru = new User_guru();
      $guru->ugrUsername = request()->ugrUsername;
      $guru->ugrPassword = Hash::make(request()->password);

      $guru->ugrGelarDepan = request()->gdepan;
			$guru->ugrGelarBelakang = request()->gbelakang;

      $guru->ugrFirstName = strtoupper(request()->firstname);
      $guru->ugrLastName = strtoupper(request()->lastname);
      $guru->ugrFullName = strtoupper(request()->fullname);
      $guru->ugrSklId = request()->skl;
      $guru->ugrRole = serialize(request()->jabatan);
      $guru->ugrPtkKode = request()->ptk;
      $guru->ugrMskpKode = request()->spg;
      $guru->ugrHp = request()->nohp;
      $guru->ugrWa = request()->nowa;
      $guru->ugrEmail = request()->email;
      $guru->ugrCreatedBy = Auth::user()->admId;

      //Profile Guru
      $profil = new Profile_guru();
      $profil->prgUgrUsername = request()->ugrUsername;
      $profil->prgMpjKode = request()->pendidikan;
      $profil->prgAgama = request()->agama;
      $profil->prgJsk = request()->jsk;
      $profil->prgTgl = Carbon::parse($request->tgl)->translatedFormat('Y-m-d');
      $profil->prgTpl = strtoupper(request()->tpl);
      $profil->prgAlamat = strtoupper(request()->alamat);
      $profil->prgRt = request()->rt;
      $profil->prgRw = request()->rw;
      $profil->prgDesa = strtoupper(request()->desa);
      $profil->prgKabupaten = strtoupper(request()->kabut);
      $profil->prgKecamatan = strtoupper(request()->keca);
      $profil->prgProvinsi = strtoupper(request()->provinsi);
      $profil->prgNamaIbu = strtoupper(request()->ibu);
      $profil->prgNamaAyah = strtoupper(request()->ayah);

      $profil->prgNoIjazah = request()->ijazah;
      $profil->prgNamaKampus = strtoupper(request()->kampus);
      $profil->prgTglLulus = date('Y-m-d',strtotime(request()->tgl_lulus));
      $profil->prgIpk = request()->ipk;
      
      $profil->prgTglMasuk = request()->tgl1;
      $profil->prgBlnMasuk = request()->bln1;
      $profil->prgTahunMasuk = request()->thn1;
      //$profil->prgEmailGSE = request()->jsk;

      if($guru->save()){ 
        if($profil->save()){ 
          Cache::forget('user_guru'.$this->getSkl());
          Cache::forget('guru_skl'.$this->getSkl());
          //catat aksi ke dalam log database
          $dataArray = array(
						'laIdAdmin' =>Auth::user()->admId,
						'laNamaUser' => FullNama(),
						'laNamaAksi' =>'Menambah Guru '.strtoupper(request()->firstname).' '.strtoupper(request()->lastname),
						'laDateTIme' =>date("Y-m-d H:i:s"),
					);
          Log_aksi_user::insert($dataArray);
          
          $response = ['save'=>'Berhasil Tambah Akun Guru'];
        }
        else{
          User_guru::where('ugrUsername',request()->ugrUsername)->delete();
          $response = ['error'=>'Gagal Tambah Akun Guru'];
        }
      }
      else{ $response = ['error'=>'Gagal Tambah Akun Guru'];
      }

    }
    return response()->json($response,200);
  }
  function UpdateGuru($id,Request $request)
  {
    $idd = Crypt::decrypt($id);
    $pesan =[
        'ugrUsername.required' =>'Username Tidak Boleh Kosong',
      ];

      $validator = Validator::make(request()->all(), [
        'ugrUsername' => 'required',
      ],$pesan);

    if($validator->fails()) {
      $response = $validator->messages();
    }
    else{ 
      $guru = User_guru::find($idd);
      $guru->ugrUsername = request()->ugrUsername;
      //$guru->ugrPassword = Hash::make(request()->password);

      $guru->ugrGelarDepan = request()->gdepan;
			$guru->ugrGelarBelakang = request()->gbelakang;
      
      $guru->ugrFirstName = strtoupper(request()->firstname);
      $guru->ugrLastName = strtoupper(request()->lastname);
      $guru->ugrFullName = strtoupper(request()->fullname);
      $guru->ugrSklId = request()->skl;
      $guru->ugrRole = serialize(request()->jabatan);
      $guru->ugrPtkKode = request()->ptk;
      $guru->ugrMskpKode = request()->spg;
      $guru->ugrHp = request()->nohp;
      $guru->ugrWa = request()->nowa;
      $guru->ugrEmail = request()->email;
      $guru->ugrUpdated = date("Y-m-d H:i:s");
      $guru->ugrUpdatedBy = Auth::user()->admId;
      $guru->ugrIsActive = request()->aktifuser;
      $guru->ugrKeterangan = request()->ktrugr;
      

      //Profile Guru
      $profil = Profile_guru::where('prgUgrUsername',request()->ugrUsername2)->first();
      $profil->prgUgrUsername = request()->ugrUsername;
      $profil->prgMpjKode = request()->pendidikan;
      $profil->prgAgama = request()->agama;
      $profil->prgJsk = request()->jsk;
      $profil->prgTgl = Carbon::parse($request->tgl)->translatedFormat('Y-m-d');//strtotime ('Y-m-d',; 
      $profil->prgTpl = strtoupper(request()->tpl);
      $profil->prgAlamat = strtoupper(request()->alamat);
      $profil->prgRt = request()->rt;
      $profil->prgRw = request()->rw;
      $profil->prgDesa = strtoupper(request()->desa);
      $profil->prgKabupaten = strtoupper(request()->kabut);
      $profil->prgKecamatan = strtoupper(request()->keca);
      $profil->prgProvinsi = strtoupper(request()->provinsi);
      $profil->prgNamaIbu = strtoupper(request()->ibu);
      $profil->prgNamaAyah = strtoupper(request()->ayah);

      $profil->prgNoIjazah = request()->ijazah;
      $profil->prgNamaKampus = strtoupper(request()->kampus);
      $profil->prgTglLulus = date('Y-m-d',strtotime(request()->tgl_lulus));
      $profil->prgIpk = request()->ipk;
      
      $profil->prgTglMasuk = request()->tgl1;
      $profil->prgBlnMasuk = request()->bln1;
      $profil->prgTahunMasuk = request()->thn1;
      //$profil->prgEmailGSE = request()->jsk;

      if($guru->save()){ 
        if($profil->save()){ 
          Cache::forget('user_guru'.$this->getSkl());
          Cache::forget('guru_skl'.$this->getSkl());
          //catat aksi ke dalam log database
          $dataArray = array(
						'laIdAdmin' =>Auth::user()->admId,
						'laNamaUser' => FullNama(),
						'laNamaAksi' =>'Update Data Guru '.strtoupper(request()->firstname).' '.strtoupper(request()->lastname),
						'laDateTIme' =>date("Y-m-d H:i:s"),
					);
          Log_aksi_user::insert($dataArray);
          $response = ['save'=>'Berhasil Update Akun Guru'];
        }
        else{
          $response = ['error'=>'Gagal Update Profile Guru'];
        }
      }
      else{ $response = ['error'=>'Gagal Update Akun Guru'];}
    }
    return response()->json($response,200);
  }
  function deleteGuru($id)
  {
    $idd = Crypt::decrypt($id);
    $guru= User_guru::find($idd);
    $guru->ugrIsDeleted = 0;
    $guru->ugrIsActive = 0;
    $guru->ugrDeleted = date("Y-m-d h:i:s");
    $guru->ugrDeletedBy = Auth::user()->admId;
    if($guru->save()){
      Cache::forget('user_guru'.$this->getSkl());
      Cache::forget('guru_skl'.$this->getSkl());
      return response()->json([
        'success' => 'Data Berhasil Di Hapus'
      ]);
    }
    else{
      return response()->json([
        'error' => 'Gagal Hapus Data!'
      ]);
    }
  }
  public function jsonGuru()
  {
    
    //Cache Redish ---------------------------------------------------------------------------------------
      // if (Cache::has('user_guru'.$this->getSkl())){ $data = Cache::get('user_guru'.$this->getSkl()); }
      // else{ 
        if(empty($this->getSkl())){ 
          $data = User_guru::with('master_sekolah','master_jabatan')->where('ugrIsActive',1)->get(); 
        }
        else{ $data = User_guru::where('ugrSklId',$this->getSkl())
        ->where('ugrIsActive',1)
        ->with('master_sekolah','master_jabatan')
        ->get(); }
      //   $cek = Cache::put('user_guru'.$this->getSkl(), $data, ChaceJam());
      // }
    //Cache Redish ---------------------------------------------------------------------------------------
    foreach ($data as $value) {
      $data2['id'] =$value->ugrId;
      $data2['username'] =$value->ugrUsername;
      $data2['nama'] =$value->ugrFirstName.' '.$value->ugrLastName;
      $data2['jabatan'] =$value->ugrRole;
      $data2['sekolah'] =$value->master_sekolah['sklKode'];
      $data3[]=$data2;
    }

    $dt= DataTables::of($data3)
    ->addColumn('jabatan',function ($data3) { 
      //----unserialize jabatan --------------------------------------------
      if(empty($data3['jabatan'])){
        return  null;
      }
      else{
          $jataban = unserialize($data3['jabatan']);
          foreach($jataban as $valuerole) {
           $jabatan2 = Master_jabatan::where('mjbKode',$valuerole)->first();
           $jabatan3[]=$jabatan2->mjbNama;
          }
          $implode = implode(', ',$jabatan3);
        return  $implode;
      }
      //----unserialize jabatan --------------------------------------------
    })
    ->addColumn('aksi',function ($data3) { 
      $id = Crypt::encrypt($data3['id']);
      
      if(AksiUpdate()){
        $button = '<a href="'.$id.'/edit-guru" title="Edit Data" class="dropdown-item btn btn-sm btn-outline bg-primary text-primary border-primary legitRipple" ><i class="icon-pencil7"> Edit</i></a>';
        $button .='<a title="Reset Password" id="resetpass" class="dropdown-item btn btn-sm btn-outline bg-warning text-warning border-warning legitRipple" data-id="'.$id.'"><i class="icon-reset"> Reset Password </i></a>';  
      }else{ $button = '<a title="No Akses" class="dropdown-item btn btn-sm btn-outline bg-danger text-danger border-danger legitRipple" ><i class="icon-cancel-circle2"> No Akses</i></a>';  }
        if(AksiDelete()){
          $button .='<a title="Hapus Data" id="deletejurusan" class="dropdown-item btn btn-sm btn-outline bg-danger text-danger border-danger legitRipple" data-id="'.$id.'"><i class="icon-trash"> Hapus</i></a>';
        }
        $return  = '<ul class="list-inline list-inline-condensed mb-0 mt-2 mt-sm-0">
          <li class="list-inline-item dropdown">
            <a href="#" class="text-default dropdown-toggle" data-toggle="dropdown"><i class="icon-menu7"></i></a>
            <div class="dropdown-menu dropdown-menu-right">
              '.$button.'
            </div>
          </li>
        </ul>';
        return $return;
        
    })->rawColumns(['aksi']);
    return $dt->make(true); 
    // $data = User_guru::with('master_sekolah','master_jabatan','master_jurusan','master_rombel')->get(); 
    // return $data;
  }
  public function jsonGuruOff()
  {
    
    //Cache Redish ---------------------------------------------------------------------------------------
      // if (Cache::has('user_guru'.$this->getSkl())){ $data = Cache::get('user_guru'.$this->getSkl()); }
      // else{ 
        if(empty($this->getSkl())){ 
          $data = User_guru::with('master_sekolah','master_jabatan')->where('ugrIsActive',0)->get(); 
        }
        else{ $data = User_guru::where('ugrSklId',$this->getSkl())
        ->where('ugrIsActive',0)
        ->with('master_sekolah','master_jabatan')
        ->get(); }
      //   $cek = Cache::put('user_guru'.$this->getSkl(), $data, ChaceJam());
      // }
    //Cache Redish ---------------------------------------------------------------------------------------
    foreach ($data as $value) {
      $data2['id'] =$value->ugrId;
      $data2['username'] =$value->ugrUsername;
      $data2['nama'] =$value->ugrFirstName.' '.$value->ugrLastName;
      $data2['jabatan'] =$value->ugrRole;
      $data2['sekolah'] =$value->master_sekolah['sklKode'];
      $data3[]=$data2;
    }

    $dt= DataTables::of($data3)
    ->addColumn('jabatan',function ($data3) { 
      //----unserialize jabatan --------------------------------------------
      if(empty($data3['jabatan'])){
        return  null;
      }
      else{
          $jataban = unserialize($data3['jabatan']);
          foreach($jataban as $valuerole) {
           $jabatan2 = Master_jabatan::where('mjbKode',$valuerole)->first();
           $jabatan3[]=$jabatan2->mjbNama;
          }
          $implode = implode(', ',$jabatan3);
        return  $implode;
      }
      //----unserialize jabatan --------------------------------------------
    })
    ->addColumn('aksi',function ($data3) { 
      $id = Crypt::encrypt($data3['id']);
      
      if(AksiUpdate()){
        $button = '<a href="'.$id.'/edit-guru" title="Edit Data" class="dropdown-item btn btn-sm btn-outline bg-primary text-primary border-primary legitRipple" ><i class="icon-pencil7"> Edit</i></a>';
        $button .='<a title="Reset Password" id="resetpass" class="dropdown-item btn btn-sm btn-outline bg-warning text-warning border-warning legitRipple" data-id="'.$id.'"><i class="icon-reset"> Reset Password </i></a>';  
      }else{ $button = '<a title="No Akses" class="dropdown-item btn btn-sm btn-outline bg-danger text-danger border-danger legitRipple" ><i class="icon-cancel-circle2"> No Akses</i></a>';  }
        if(AksiDelete()){
          $button .='<a title="Hapus Data" id="deletejurusan" class="dropdown-item btn btn-sm btn-outline bg-danger text-danger border-danger legitRipple" data-id="'.$id.'"><i class="icon-trash"> Hapus</i></a>';
        }
        $return  = '<ul class="list-inline list-inline-condensed mb-0 mt-2 mt-sm-0">
          <li class="list-inline-item dropdown">
            <a href="#" class="text-default dropdown-toggle" data-toggle="dropdown"><i class="icon-menu7"></i></a>
            <div class="dropdown-menu dropdown-menu-right">
              '.$button.'
            </div>
          </li>
        </ul>';
        return $return;
        
    })->rawColumns(['aksi']);
    return $dt->make(true); 
    // $data = User_guru::with('master_sekolah','master_jabatan','master_jurusan','master_rombel')->get(); 
    // return $data;
  }
 
  function ResetPassword(Request $request){
		$set = Setting::first();
    $idd = Crypt::decrypt($request->id);
    $data = User_guru::find($idd);
    $data->ugrPassword  = Hash::make($set->setResetPassGuru);
    $data->ugrUpdated = date("Y-m-d H:i:s");
    $data->ugrUpdatedBy = Auth::user()->admId;
    
    if($data->save()){
      $dataArray = array(
        'laIdAdmin' =>Auth::user()->admId,
        'laNamaUser' => FullNama(),
        'laNamaAksi' =>'Reset Password Guru '.strtoupper($data->ugrFirstName).' '.strtoupper($data->ugrLastName),
        'laDateTIme' =>date("Y-m-d H:i:s"),
      );
      Log_aksi_user::insert($dataArray);

      return response()->json([
        'success' => 'Akun Berhasil Di Reset Passwordnya '.$set->setResetPassGuru,
      ]);
    }
    else{
      return response()->json([
        'error' => 'Opsss Gagal !'
      ]);
    }
	}
	
}
