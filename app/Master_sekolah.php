<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Master_sekolah extends Model
{
    protected $table = 'master_sekolah';
    protected $primaryKey = 'sklId';
    public $incrementing = false;
    public $timestamps = false;

    public function master_yayasan()
    {
        return $this->belongsTo(Master_yayasan::class,'sklMsysId','msysId');  
    }
    public function profile_sekolah()
    {
        return $this->belongsTo(Profile_sekolah::class,'sklNpsn','prsklSklNpsn');  
    }
    
    public function getSekolah($id=null)
    {
        //Cache Redis --------------------------------------------------------------------
        if (Cache::has('master_sekolah'.$id)){ $skl = Cache::get('master_sekolah'.$id); }
        else{ 
        	if(empty($id)){ $skl = Master_sekolah::get(); }
    		else{ $skl = Master_sekolah::where('sklId', $id)->get(); }
            $chace = Cache::put('master_sekolah'.$id, $skl, ChaceJam());
        }
		return $skl;
        //Cache Redis --------------------------------------------------------------------
    }
    
}
