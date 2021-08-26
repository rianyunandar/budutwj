<?php

//membaca lokasi Controller dalam Folder
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash; //menjalankan printah Hash Enkripsi
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Auth;//menjalankan printah auth
use DB;
use DataTables;
use App\Master_sekolah;
use App\Tahun_ajaran;
use App\Absen_hadir_guru;
use App\Absen_kategori;
use App\Semester;
use App\User_guru;





class CadminAbsenGuru extends Controller
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
  // panggil fungsi pada model
  function getSekolah()
  {
  	$data = new Master_sekolah();
    return $data->getSekolah($this->getSkl());
  }
 
  function getSemester()
  { //ambil kode semester yg aktif
    if (Cache::has('semesterkode')){ $data = Cache::get('semesterkode'); }
    else{ 
      $data = Semester::where('smIsActive',1)->first();
      Cache::put('semesterkode', $data, ChaceJam());
    }
		return $data->smKode;
	}
  function getTahunAjaran()
	{ //ambil kode tahun ajaran yg aktif
		if (Cache::has('tahunAjaranAktif')){ $data= Cache::get('tahunAjaranAktif'); }
		else{ 
			$data = Tahun_ajaran::where('tajrIsActive',1)->first();
			Cache::put('tahunAjaranAktif', $data, ChaceJam() );
		}
		return $data->tajrKode;
	}
  function getTahunAjaranNama()
	{ //ambil kode tahun ajaran yg aktif
		if (Cache::has('NamatahunAjaranAktif')){ $data= Cache::get('NamatahunAjaranAktif'); }
		else{
			$data = Tahun_ajaran::where('tajrIsActive',1)->first();
		Cache::put('NamatahunAjaranAktif', $data, ChaceJam() );
		}
		return $data->tajrNama;
	}

 
  function getBulanTahunAbsen(){
    if (Cache::has('ta_asben'.$this->getSkl())){ $data = Cache::get('ta_asben'.$this->getSkl()); }
    else{ 
    $data = Absen_hadir_guru::select(DB::raw('MONTH(hgTgl) AS bulan,YEAR(hgTgl) AS tahun'))
    ->groupBy('tahun')
    ->get();
     Cache::put('ta_asben'.$this->getSkl(), $data, ChaceJam());
    }
    return $data;
    
  }
  function getKategoriAbsen(){
    if (Cache::has('ktg_absen'.$this->getSkl())){ $data = Cache::get('ktg_absen'.$this->getSkl()); }
    else{ 
      $data = Absen_kategori::All();
      $cek = Cache::put('ktg_absen'.$this->getSkl(), $data, ChaceJam());
    }
    return $data;
    
  }
  
//---------------------------------------------------------------------------------------------------------
  function view()
  {
    $params = [
      'title' =>'Lihat Absen Finger',
      'label' =>'<b>DATA ABSEN FINGER</b> ',
      'getSekolah' => $this->getSekolah(),
      
      'getBulanTahunAbsen' => $this->getBulanTahunAbsen(),
    ];
    return view('crew/absen_guru/view')->with($params);
  }
  function jsonAbsenGuru(Request $request){
    $skl = decrypt_url($request->input('skl'));
    $thn = decrypt_url($request->input('amp;thn'));
    $bln = decrypt_url($request->input('amp;bln'));
    if(empty($skl)){
      $data=[];
      $dt= DataTables::of($data);
    }
    else{
    //   if (Cache::has('absen_siswa_finger'.$skl.$rbl)){ $data = Cache::get('absen_siswa_finger'.$skl.$rbl); }
    //   else{
        $data = Absen_hadir_guru::where('hgSklId',$skl)
        // ->whereYear('hgTgl', $thn)
        ->where('hgTahun', $thn)
        ->whereMonth('hgTgl', $bln)
        ->get();
      //  Cache::put('absen_siswa_finger'.$skl.$rbl, $data, ChaceJam()); 
      // }


      $dt= DataTables::of($data)
      ->addColumn('no','')
      ->addColumn('tanggal',function ($data) { 
        $tgl = date('d-m-Y', strtotime($data->hgTgl));
        return $tgl;
      })
      ->addColumn('sekolah',function ($data) { 
        $val = $data->hgSklId;
        if($val ==1){
          $val2 = "BU1";
        }
        else{
          $val2 = "BU2";
        }
        return $val2;
        
      })
      ->addColumn('hari',function ($data) { 
        return hariIndo($data->hgHari);
      })
     
    
      // ->addColumn('sekolah',function ($data) { 
      //     return $data->master_sekolah->sklKode;
      //   })
    
    
      ->addColumn('aksi',function ($data) { 
      $id = Crypt::encrypt($data->afsId);
      $button = '<a href="'.$id.'/edit-absen-guru" title="Edit Data" class="btn btn-sm btn-outline bg-primary text-primary border-primary legitRipple" ><i class="icon-pencil7"></i></a> ';
      $button .='<a title="Hapus Data" id="delete" class="btn btn-sm btn-outline bg-danger text-danger border-danger legitRipple" data-id="'.$id.'"><i class="icon-trash"></i></a>';
      return $button;
      })
      ->rawColumns(['tanggal','hari','sekolah','aksi']);
    
    }
  return $dt->make(); 

  }

  function rekapAbsenGuru(){
    $params = [
      'title' =>'Lihat Absen Finger',
      'label' =>'<b>DATA ABSEN FINGER</b> ',
      'getSekolah' => $this->getSekolah(),
      'getBulanTahunAbsen' => $this->getBulanTahunAbsen(),
    ];
    return view('crew/absen_guru/rekap_absen_guru')->with($params);
  }

  function JsonCetakRekapAbsenGuru(Request $request){
      $semster = $this->getSemester();
      $skl = decrypt_url($request->input('skl'));
      $thn = decrypt_url($request->input('amp;thn'));
      $bln = decrypt_url($request->input('amp;bln'));
     

		$data = Absen_hadir_guru::where('hgSklId',$skl)
      ->where('hgSmKode',$semster)
			->where('hgTahun',$thn)
			->whereMonth('hgTgl', $bln)
      ->selectRaw("hgNamaFull,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=1,hgKodeAbsen,NULL))) AS tgl1,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=2,hgKodeAbsen,NULL))) AS tgl2,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=3,hgKodeAbsen,NULL))) AS tgl3,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=4,hgKodeAbsen,NULL))) AS tgl4,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=5,hgKodeAbsen,NULL))) AS tgl5,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=6,hgKodeAbsen,NULL))) AS tgl6,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=7,hgKodeAbsen,NULL))) AS tgl7,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=8,hgKodeAbsen,NULL))) AS tgl8,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=9,hgKodeAbsen,NULL))) AS tgl9,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=10,hgKodeAbsen,NULL))) AS tgl10,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=11,hgKodeAbsen,NULL))) AS tgl11,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=12,hgKodeAbsen,NULL))) AS tgl12,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=13,hgKodeAbsen,NULL))) AS tgl13,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=14,hgKodeAbsen,NULL))) AS tgl14,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=15,hgKodeAbsen,NULL))) AS tgl15,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=16,hgKodeAbsen,NULL))) AS tgl16,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=17,hgKodeAbsen,NULL))) AS tgl17,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=18,hgKodeAbsen,NULL))) AS tgl18,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=19,hgKodeAbsen,NULL))) AS tgl19,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=20,hgKodeAbsen,NULL))) AS tgl20,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=21,hgKodeAbsen,NULL))) AS tgl21,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=22,hgKodeAbsen,NULL))) AS tgl22,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=23,hgKodeAbsen,NULL))) AS tgl23,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=24,hgKodeAbsen,NULL))) AS tgl24,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=25,hgKodeAbsen,NULL))) AS tgl25,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=26,hgKodeAbsen,NULL))) AS tgl26,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=27,hgKodeAbsen,NULL))) AS tgl27,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=28,hgKodeAbsen,NULL))) AS tgl28,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=29,hgKodeAbsen,NULL))) AS tgl29,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=30,hgKodeAbsen,NULL))) AS tgl30,
				GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=31,hgKodeAbsen,NULL))) AS tgl31,
				SUM(CASE WHEN hgKodeAbsen='H' THEN 1 ELSE 0 END) AS HADIR,
        SUM(CASE WHEN hgKodeAbsen='A' THEN 1 ELSE 0 END) AS ALPHA,
        SUM(CASE WHEN hgKodeAbsen='I' THEN 1 ELSE 0 END) AS IZIN,
        SUM(CASE WHEN hgKodeAbsen='S' THEN 1 ELSE 0 END) AS SAKIT
				")
      ->groupBy('hgUserGuru')
      ->get();

      // SUM(CASE WHEN hgKodeAbsen='K' THEN 1 ELSE 0 END) AS KEGIATAN,
      // SUM(CASE WHEN hgKodeAbsen='U' THEN 1 ELSE 0 END) AS ULANGAN,
      // SUM(CASE WHEN hgKodeAbsen='L' THEN 1 ELSE 0 END) AS LIBUR,
      // SUM(CASE WHEN hgKodeAbsen='A' THEN 1 ELSE 0 END) AS ALPHA,
			// 	SUM(CASE WHEN hgKodeAbsen='B' THEN 1 ELSE 0 END) AS BOLOS,
			// 	SUM(CASE WHEN hgKodeAbsen='I' THEN 1 ELSE 0 END) AS IZIN,
			// 	SUM(CASE WHEN hgKodeAbsen='T' THEN 1 ELSE 0 END) AS TERLAMBAT,
			// 	SUM(CASE WHEN hgKodeAbsen='S' THEN 1 ELSE 0 END) AS SAKIT
       //return $data;
       
    $dt= DataTables::of($data);
    return $dt->make(); 
   
			
  }
  function CetakRekapAbsenGuru(Request $request){
    
    $semster = $this->getSemester();
    $skl = decrypt_url($request->input('skl'));
    $thn = decrypt_url($request->input('thn'));
    $bln = decrypt_url($request->input('bln'));

    $namaskl = Master_sekolah::find($skl);

    $kepsek = getKepalaSekolah($skl);

    $data = Absen_hadir_guru::where('hgSklId',$skl)
      ->where('hgSmKode',$semster)
      ->where('hgTahun',$thn)
      ->whereMonth('hgTgl', $bln)
      ->orderBy('hgNamaFull', 'asc')
      ->selectRaw("hgNamaFull,
        GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=1,hgKodeAbsen,NULL))) AS tgl1,
        GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=2,hgKodeAbsen,NULL))) AS tgl2,
        GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=3,hgKodeAbsen,NULL))) AS tgl3,
        GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=4,hgKodeAbsen,NULL))) AS tgl4,
        GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=5,hgKodeAbsen,NULL))) AS tgl5,
        GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=6,hgKodeAbsen,NULL))) AS tgl6,
        GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=7,hgKodeAbsen,NULL))) AS tgl7,
        GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=8,hgKodeAbsen,NULL))) AS tgl8,
        GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=9,hgKodeAbsen,NULL))) AS tgl9,
        GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=10,hgKodeAbsen,NULL))) AS tgl10,
        GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=11,hgKodeAbsen,NULL))) AS tgl11,
        GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=12,hgKodeAbsen,NULL))) AS tgl12,
        GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=13,hgKodeAbsen,NULL))) AS tgl13,
        GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=14,hgKodeAbsen,NULL))) AS tgl14,
        GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=15,hgKodeAbsen,NULL))) AS tgl15,
        GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=16,hgKodeAbsen,NULL))) AS tgl16,
        GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=17,hgKodeAbsen,NULL))) AS tgl17,
        GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=18,hgKodeAbsen,NULL))) AS tgl18,
        GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=19,hgKodeAbsen,NULL))) AS tgl19,
        GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=20,hgKodeAbsen,NULL))) AS tgl20,
        GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=21,hgKodeAbsen,NULL))) AS tgl21,
        GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=22,hgKodeAbsen,NULL))) AS tgl22,
        GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=23,hgKodeAbsen,NULL))) AS tgl23,
        GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=24,hgKodeAbsen,NULL))) AS tgl24,
        GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=25,hgKodeAbsen,NULL))) AS tgl25,
        GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=26,hgKodeAbsen,NULL))) AS tgl26,
        GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=27,hgKodeAbsen,NULL))) AS tgl27,
        GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=28,hgKodeAbsen,NULL))) AS tgl28,
        GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=29,hgKodeAbsen,NULL))) AS tgl29,
        GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=30,hgKodeAbsen,NULL))) AS tgl30,
        GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=31,hgKodeAbsen,NULL))) AS tgl31,
        SUM(CASE WHEN hgKodeAbsen='H' THEN 1 ELSE 0 END) AS HADIR,
        SUM(CASE WHEN hgKodeAbsen='A' THEN 1 ELSE 0 END) AS ALPHA,
        SUM(CASE WHEN hgKodeAbsen='I' THEN 1 ELSE 0 END) AS IZIN,
        SUM(CASE WHEN hgKodeAbsen='S' THEN 1 ELSE 0 END) AS SAKIT
        ")
      ->groupBy('hgUserGuru')
      ->get();

    $params = [
      'judul' =>'REKAPITULASI ABSENSI KEHADIRAN GURU',
      'sekolah' =>$namaskl->sklNama,
      'ajaran'	=>'TAHUN PELAJARAN ' .$this->getTahunAjaranNama(),
      'absen' => $data,
      'bulan'	=> bulanIndo($bln),
      'kepsek' => $kepsek,

    ];

    return view('crew/absen_guru/cetak_absen_guru')->with($params);
    
  }
  function ManualAbsenGuru(){
    if(!empty($_GET['skl'])){
      $idskl = decrypt_url($_GET['skl']);
      $data= User_guru:: where('ugrSklId',$idskl)
      // ->where('ugrPtkKode','GURUMAPEL')
      ->get();
    }
    else{
      $data=[];
    }

    $params = [
      'title' =>'Absen Guru',
      'label' =>'<b>ABSEN MANUAL GURU</b> ',
      'getSekolah' => $this->getSekolah(),
      'dataguru' => $data
    ];
    return view('crew/absen_guru/absen_manual_izin')->with($params);
  }
  function InsertAbsenManualGuru(Request $request){
    $tahunajaran = $this->getTahunAjaran();
    $semster = $this->getSemester();
    $namahari = date('l', strtotime($request->tgl));
		$tgl = date('Y-m-d', strtotime($request->tgl));
    $tahun = date('Y');
    $skl = decrypt_url($request->skl);

    $datapilih = $request->cekpilih;
    $dataStatus = $request->status;
		$dataKtr = $request->ktr;


    //YG ASLI -----------------
    // $jamin = $request->jamin;
    // $jamout = $request->jamout;
    //YG ASLI -----------------

    //jika data absen tidak kosong
    if(!empty($datapilih)){
      $sudahada=0; $belumada=0;
      foreach($datapilih as $data){
        //SEMETARA DULU ------------------
          $rand = rand(10,59);
          $rand2 = rand(10,59);
          $jamMulai = "07:".$rand.':00';
          $jamPulang = "11:".$rand2.':00';
          $jamin = date($jamMulai);
          $jamout = date($jamPulang);
        //SEMETARA DULU ------------------

        $cek = Absen_hadir_guru::where('hgUserGuru',$data)
				->where('hgSklId',$skl)
				->where('hgTgl',$tgl)
				->count();

				//cek apakah sudaha da absenya
        if($cek > 0){
					$sudahada++;
				}
        else{
          //cek dan ambil data statusnya dulu
          foreach($dataStatus as $key => $val){
						if($data == $key){
							$dataKehadiran = $val;
						}
					}
          //cek dan ambil data keterangan
					foreach($dataKtr as $key2 => $val2){
						if($data == $key2){
							$dataaKeterangan = $val2;
						}
					}
          $dataguru = User_guru::where('ugrUsername',$data)->first();
          $data2[]=array(
						'hgSklId' => $skl,
						'hgSmKode' => $semster,
						'hgTajrKode' => $tahunajaran,
						'hgTahun' =>$tahun, 
						'hgUserGuru'	=> $data,
						'hgNamaDepan' => $dataguru->ugrFirstName,
						'hgNamaBelakang' => $dataguru->ugrLastName,
						'hgNamaFull' => $dataguru->ugrFirstName.' '.$dataguru->ugrLastName,
						'hgTgl' =>$tgl,
						'hgHari' =>$namahari,
						'hgJamIn' =>$jamin, 
						'hgJamout' => $jamout,
            'hgKodeAbsen' => $dataKehadiran,
            'hgKeterangan' => $dataaKeterangan,
            'hgCreatedByGuru' => Auth::user()->admId,
            'hgUserAksi' => 2,
						);
            $belumada++;

        }//end if

      }//end foreach
      
      if(empty($data2)){
				$response = ['error'=> $sudahada.' Guru Sudah Absen'];
				return response()->json($response,202);
			}
			else{
        //save data absen array
        $add = Absen_hadir_guru::insert($data2);
        if($add){
          $response = ['success'=>'Berhasil Tambah Absen '.$belumada.' Guru | '.$sudahada.' Sudah Ada Absen'];
          return response()->json($response,200);
        }

      }
     
    }
    else{
      $response = ['error'=>'Silahkan Pilih Siswa Terlebih Dahulu'];
			return response()->json($response,202);
    }

  }





  //untuk menambah data absensi guru
  // function randomInsertDataGuru(){
	// 	$idskl = 1;
	// 	//ubah pakai tahun bukan tahun ajaran
	// 	$tahunajaran = 2022;
	// 	$semster = $this->getSemester();

  //   $tanggala = "2021-04-30";

	// 	$namahari = date('l', strtotime($tanggala));
	// 	$tahun = date('Y');
  //   $tgl = $tanggala;
	

  //   $data_guru = User_guru:: where('ugrSklId',$idskl)
  //   ->where('ugrPtkKode','GURUMAPEL')
  //   ->get();
  //   $no=0;
  //   foreach($data_guru as $guru){
  //     $rand = rand(10,59);
  //     $rand2 = rand(10,59);
  //     $jamMulai = "07:".$rand.':00';
  //     $jamPulang = "11:".$rand2.':00';
  //     $jamsekarang = date($jamMulai);
  //     $jampulang = date($jamPulang);
     
  //     $data = new Absen_hadir_guru();
	// 			$data->hgSklId = $idskl;
	// 			$data->hgSmKode = $semster;
	// 			$data->hgTajrKode = $tahunajaran;
	// 			$data->hgTahun 	= $tahun;
	// 			$data->hgUserGuru = $guru->ugrUsername;
	// 			$data->hgNamaDepan = $guru->ugrFirstName;
	// 			$data->hgNamaBelakang = $guru->ugrLastName;
	// 			$data->hgNamaFull = $guru->ugrFirstName.' '.$guru->ugrLastName;
	// 			$data->hgTgl = $tgl;
	// 			$data->hgHari = $namahari;
	// 			$data->hgJamIn = $jamsekarang;
  //       $data->hgJamOut = $jampulang;
	// 			$data->hgKodeAbsen = 'H';
	// 			$data->hgKeterangan = "";
	// 			$data->hgCreatedByGuru = $guru->ugrId;
	// 			$data->hgUserAksi = 1;

  //       if($data->save()){
  //         $no++;
  //       }

  //   }
  //   echo $no;
    

  // }

  
}
