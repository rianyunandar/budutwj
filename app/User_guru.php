<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class User_guru extends Model
{
    protected $table = 'user_guru';
    protected $primaryKey = 'ugrId';
    public $incrementing = false;
    public $timestamps = false;

    public function profile_guru()
    {
        return $this->belongsTo(Profile_guru::class,'ugrUsername','prgUgrUsername');  
    }
    
    public function master_jurusan()
    {
       return $this->belongsTo(Master_jurusan::class,'ugrJrsId','jrsId');
       
    }
    
    public function master_rombel()
    {
       return $this->belongsTo(Master_rombel::class,'ugrRblId','rblId');
       
    }
    public function master_sekolah()
    {
       return $this->belongsTo(Master_sekolah::class,'ugrSklId','sklId');
       
    }
    

    public function master_jabatan()
    {
       return $this->belongsTo(Master_jabatan::class,'ugrTugasTambahan','mjbKode');
       
    }

    public function master_jenis_ptk()
    {
       return $this->belongsTo(Master_jenis_ptk::class,'ugrPtkKode','ptkKode');
       
    }

    public function master_status_kepegawaian()
    {
       return $this->belongsTo(Master_status_kepegawaian::class,'ugrMskpKode','mskpKode');
       
    }

    //get guru by id sekolah
    public function getGuruSkl($id)
    {
        if (Cache::has('guru_skl'.$id)){ $data = Cache::where('ugrIsActive',1)->get('guru_skl'.$id); }
        else{ 
            if(empty($id)){ $data = User_guru::get(); }
            else{ $data = User_guru::where('ugrSklId', $id)
               ->where('ugrIsActive',1)
               ->get(); }
            $chace = Cache::put('guru_skl'.$id, $data, ChaceJam());
        }
        return $data;
    }

   
}
