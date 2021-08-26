<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User_siswa_alumni extends Model
{
  protected $table = 'user_siswa_alumni';
  protected $primaryKey = 'ssaId';
  public $incrementing = false;
  public $timestamps = false;

  protected $fillable = [
    'ssaUsername', 'ssaPassword','ssaRole','ssaEmail','ssaQrCode'
  ];
  
  public function profile_siswa_alumni()
  { 
   return $this->hasOne(Profile_siswa_alumni::class,'psSsaUsername','ssaUsername')->with('master_smp');

  }

  public function master_jurusan()
  {
   return $this->belongsTo(Master_jurusan::class,'ssaJrsId','jrsId');

  }
  public function master_sekolah()
  {
    return $this->belongsTo(Master_sekolah::class,'ssaSklId','sklId');

  }
  public function master_rombel()
  {
    return $this->belongsTo(Master_rombel::class,'ssaRblId','rblId');
  }

  public function anggota_rombel()
  {
    return $this->hasOne(Anggota_rombel::class,'arbSsaId','ssaUsername')->with('master_rombel')->with('master_kelas');
  }
  public function master_status_keadaan()
  {
    return $this->belongsTo(Master_status_keadaan::class,'ssaStatusKeadaan','mstId');
  }
  

 


}
