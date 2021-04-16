<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Master_rombel extends Model
{
    protected $table = 'master_rombel';
    protected $primaryKey = 'rblId';
    public $incrementing = false;
    public $timestamps = false;

    public function user_guru()
    {
        return $this->belongsTo(User_guru::class,'rblUgrId','ugrId');  
    }
    public function master_wali_kelas()
    {
        return $this->belongsTo(Master_wali_kelas::class,'rblId','wakasRblId');  
    }

    public function master_kelas()
    {
        return $this->belongsTo(Master_kelas::class,'rblKlsId','klsId');  
    }
    public function master_sekolah()
    {
        return $this->belongsTo(Master_sekolah::class,'rblSklId','sklId');  
    }
    public function master_jurusan()
    {
        return $this->belongsTo(Master_jurusan::class,'rblJrsId','jrsId');  
    }
    public function getRombel($id)
    {
        if (Cache::has('master_rombel'.$id)){ $rbl = Cache::get('master_rombel'.$id); }
        else{ 
        	if(empty($id)){ $rbl = Master_rombel::with('master_kelas')->get(); }
    		else{ $rbl = Master_rombel::where('rblSklId', $id)->with('master_kelas')->get(); }
            $chace = Cache::put('master_rombel'.$id, $rbl, ChaceJam());
        }
		return $rbl;
    }
    
    
}
