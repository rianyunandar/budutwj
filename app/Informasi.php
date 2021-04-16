<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Informasi extends Model
{
    protected $table = 'informasi';
    protected $primaryKey = 'infoId';
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = []; //tambahkan baris ini

    public function getInfoSekolah($id=null)
    {
        //Cache Redis --------------------------------------------------------------------
        if (Cache::has('informasi_sekolah'.$id)){ $data = Cache::get('informasi_sekolah'.$id); }
        else{ 
        	if(empty($id)){ $data = Informasi::where('infoIsActive',1)->get(); }
    		else{ $data = Informasi::where('infoSkl', $id)->where('infoIsActive',1)->get(); }
            Cache::put('informasi_sekolah'.$id, $data, ChaceJam());
        }
		return $data;
        //Cache Redis --------------------------------------------------------------------
    }

    

   
}
