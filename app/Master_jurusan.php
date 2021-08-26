<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Master_jurusan extends Model
{
    protected $table = 'master_jurusan';
    protected $primaryKey = 'jrsId';
    public $incrementing = false;
    public $timestamps = false;

    // public function user_siswa()
    // {
    //    return $this->belongsTo(User_siswa::class,'jrsId','ssaJrsId');
       
    // }
    public function master_sekolah()
    {
        return $this->belongsTo(Master_sekolah::class,'jrsSklId','sklId');  
    }
    public function getJurusan($id)
    {
        //Cache Redis --------------------------------------------------------------------
        if (Cache::has('master_jurusan'.$id)){ $skl = Cache::get('master_jurusan'.$id); }
        else{ 
        	if(empty($id)){ $skl = Master_jurusan::get(); }
    		else{ $skl = Master_jurusan::where('jrsSklId', $id)->get(); }
            $chace = Cache::put('master_jurusan'.$id, $skl, ChaceJam());
        }
		return $skl;
        //Cache Redis --------------------------------------------------------------------
    }
    public function user_guru()
    {
       return $this->belongsTo(User_guru::class,'jrsId','ugrJrsId');
       
    }

    
}
