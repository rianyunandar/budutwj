<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\User_siswa;
use Illuminate\Support\Facades\Cache;

class TesRedis extends Controller
{
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */
    public function showProfile()
    {
       
        // if (Cache::has('siswa_all_dancok'))
        // {
        //     $query = Cache::get('siswa_all_dancok');
        //     foreach ($query as $q) {
        //       echo $q->profile_siswa['jrsSlag'] ;
        //     }
        // }
        // else{
        //   //echo"Ga ada";
        //   $query = Cache::remember("siswa_all_dancok", 1 * 60, function () { //cach selama 1 menit
        //     return User_siswa::all();
        //   });
        //   foreach ($query as $q) {
        //       echo "<li>{$q->ssaUsername}</li>";
        //   }
        // }
        $query =  User_siswa::all();
            foreach ($query as $q) {
              //echo $q->profile_siswa->jrsSlag ;
              echo  ChaceJam();
            }
       
    }
    public function getUser()
    {
        $query = User_siswa::all();
        foreach ($query as $q) {
            echo "<li>{$q->ssaUsername}</li>";
        }
        //Cache::forget('siswa_all');
    }
    public function hapusChace()
    {
      Cache::forget('siswa_all');

    }
}