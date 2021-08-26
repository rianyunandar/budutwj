<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Master_mapel_jadwal extends Model
{
    protected $table = 'master_mapel_jadwal';
    protected $primaryKey = 'majdId';
    public $incrementing = false;
    public $timestamps = false;

    public function user_guru()
    {
      //return $this->hasOne(User_siswa::class,'ssaUsername','psSsaUsername');
      return $this->hasOne(User_guru::class,'ugrId','majdUgrId'); 
    }
    public function master_rombel()
    {
       return $this->belongsTo(Master_rombel::class,'rblKode','majdRblKode');
       
    }
    public function getJadwalMapel($kodeRombel,$kodeKelas){
      //Cache Redis --------------------------------------------------------------------
      if (Cache::has('getJadwalMapelListSiswa'.$kodeRombel.$kodeKelas)){ $data3 = Cache::get('getJadwalMapelListSiswa'.$kodeRombel.$kodeKelas); }
      else{ 
        $data = Master_mapel_jadwal::where('majdRblKode',$kodeRombel)
        ->where('majdKlsKode',$kodeKelas)
        ->groupby('majdMapelKode')
        ->orderby('majdNama', 'asc')
        ->distinct()
        ->with('master_rombel')
        ->get();
        foreach($data as $val){
          $count = El_materi_anggota_rombel::where('mtraMapelKode',$val->majdMapelKode)
          ->where('mtraRblKode',$kodeRombel)
          ->where('mtraTampilSiswa',1)
          ->count();
          $data2[] = array(
            'kode_mapel' => $val->majdMapelKode,
            'nama_mapel' => $val->majdNama,
            'kode_kelas' => $val->majdKlsKode,
            'jml_materi' => $count,
          );
        }
        if(empty($data2)){
          $data3 =[];
        }
        else{
          $data3 = $data2;
        }
        Cache::put('getJadwalMapelListSiswa'.$kodeRombel.$kodeKelas, $data3, ChaceJam());
      }
      //Cache Redis --------------------------------------------------------------------
      return $data3;
    }
    // public function getJadwalTugas($kodeRombel,$kodeKelas){
    //   //Cache Redis --------------------------------------------------------------------
    //   if (Cache::has('getJadwalMapelTugasListSiswa'.$kodeRombel.$kodeKelas)){ 
    //     $data3 = Cache::get('getJadwalMapelTugasListSiswa'.$kodeRombel.$kodeKelas); }
    //   else{ 
    //     $data = Master_mapel_jadwal::where('majdRblKode',$kodeRombel)
    //     ->where('majdKlsKode',$kodeKelas)
    //     ->groupby('majdMapelKode')
    //     ->orderby('majdNama', 'asc')
    //     ->distinct()
    //     ->with('master_rombel')
    //     ->get();
    //     foreach($data as $val){
          
    //       $count = El_tugas_anggota_rombel::where('tgsarMapelKode',$val->majdMapelKode)
    //       ->with('el_tugas', function ($query) {
    //         $query->where('tugasTampil',1);
    //       })
          
         
    //       ->where('tgsarRblKode',$kodeRombel)
    //       ->where('tgsarTampilSiswa',1)
    //       ->count();
    //       $data2[] = array(
    //         'kode_mapel' => $val->majdMapelKode,
    //         'nama_mapel' => $val->majdNama,
    //         'kode_kelas' => $val->majdKlsKode,
    //         //'jml_tugas' => $val->el_tugas->tugasJudul,
    //         'jml_tugas' => $count,
           
    //       );
    //     }
    //     if(empty($data2)){
    //       $data3 =[];
    //     }
    //     else{
    //       $data3 = $data2;
    //     }
    //    // Cache::put('getJadwalMapelTugasListSiswa'.$kodeRombel.$kodeKelas, $data3, ChaceJam());
    //   }
    //   //Cache Redis --------------------------------------------------------------------
    //   return $data3;
    // }
    
    
    
}
