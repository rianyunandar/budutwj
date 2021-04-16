<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;
use App\User_guru;
use App\Profile_guru;
use App\Master_sekolah;
use App\Master_agama;
use Auth;


//ToCollection
//class ImportDataSiswa implements ToModel, WithHeadingRow
class ImportDataGuru implements ToCollection, WithHeadingRow
{
    public $hasil; //varibale untuk menampung hasil dan di kirim kembali
    public $jm = 0;
    public function __construct(){
     $this->hasil = [];
   }
  
  

  function UbahTanggal($value, $format = 'Y-m-d')
  {
    if(!empty($value)){
      try {
        return \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
      } catch (\ErrorException $e) {
        return \Carbon\Carbon::createFromFormat($format, $value);
      }
    }
    else{
      return null;
    }
  }
  
  function sekolah($value){
    $skl = Master_sekolah::where('sklKode',$value)->first();
    return $skl->sklId;
  }
  
  public function collection(Collection $rows)
   	//public function model(array $rows)
  {

    foreach ($rows as $key => $row) 
    {
      //echo $this->jurusan($row['slag_jurusan']);
      $data_akun = array(
        'ugrUsername'  =>$row['username'],
        'ugrPassword' =>Hash::make($row['password']),
        'ugrSklId'  =>$this->sekolah($row['kode_sekolah']),
        //'ugrRole' => serialize(array($row['kode_role'])),
        'ugrPtkKode' => $row["ptk"],
        'ugrMskpKode' => $row["kepegawaian"],
        'ugrTugasTambahan' => $row["tugas_tambahan"],
        'ugrEmail' =>$row['email'],
        'ugrFirstName' => strtoupper($row['nama_depan']),
        'ugrLastName' => strtoupper($row['nama_belakang']),
        'ugrHp' =>$row["no_wa"],
        'ugrWa' => $row["no_hp"],
        'ugrGelarDepan' => $row["gelar_belakang"],
        'ugrGelarBelakang' => $row["gelar_depan"],
        'ugrCreatedBy' =>Auth::user()->admId,
      );
      $data_profil = array(
        'prgUgrUsername' =>$row['username'],
        'prgMpjKode' =>$row['pendidikan_terakhir'],
        'prgAgama' =>$row["agama"],
        'prgJsk' =>$row["jenis_kelamin"],
        'prgTgl' =>$this->UbahTanggal($row["tangal_lahir"]),
        'prgTpl' =>strtoupper($row["tempat_lahir"]),
        'prgNik' =>$row["nik"],
        'prgNip' =>$row["nip"],
        'prgNuptk' =>$row["nuptk"],

        'prgAlamat' =>strtoupper($row["alamat"]),
        'prgRt' =>$row["rt"],
        'prgRw' =>$row["rw"],
        'prgDusun' =>$row["dusun"],
        'prgDesa' =>$row["desa"],
        'prgKabupaten' =>$row["kabupaten"],
        'prgKecamatan' =>$row["kecamatan"],
        'prgProvinsi' =>$row["provinsi"],
        //DATA ORANG TUA
        'prgNamaAyah' =>$row["nama_ayah"],
        'prgNamaIbu' =>$row["nama_ibu"],
        //DATA KAMPUS
        'prgNoIjazah' =>$row["no_ijasah_kampus"],
        'prgNamaKampus' =>$row["nama_kampus"],
        'prgTglLulus' =>$this->UbahTanggal($row["tanggal_ijasah"]),
        'prgIpk' =>$row["ipk"],
        'prgFalkultas' =>$row["falkultas"],
        'prgBidangStudi' =>$row["program_studi"],
        'prgKependidikan' =>$row["kependidikan"],
        'prgNamaSmaSmk' => $row["nama_smk_sma_smea"],
        //DATA AWAL MASUK SEKOLAH
        'prgTglMasuk' =>$row["tanggal_pengangakatan"],
        'prgBlnMasuk' =>$row["bulan_pengangakatan"],
        'prgTahunMasuk' =>$row["tahun_pengangakatan"],
        'prgTmt' =>$this->UbahTanggal($row["tmt_pengangkatan"]),
        'prgSkPengangkatan' =>$row["sk_pengangkatan"],
       
         
      );
      $data_akun2[] = $data_akun;
      $data_profil2[] = $data_profil;
      ++$this->jm;
    }
    $cek = User_guru::insert($data_akun2);
    if($cek){
      $cek2 = Profile_guru::insert($data_profil2);
          $this->hasil = 1; //untuk mengembalikan hasil menjadi array 
          $this->jm;
        }
        else{
          $this->hasil = 0;
        }
  }

  //untuk membaca data pada awal kolom berapa pada excel
  public function headingRow(): int
  {
    return 7;
  }
  //Limit Bari yg di proses 
  public function chunkSize(): int
  {
    return 1000; //jumlah baris yg akan di ekseskusi
  }

}
