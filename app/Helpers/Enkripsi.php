<?php

function selectAktif($data1=null,$data2=null)
{
    //untuk select option aktif jika ke dua varibel sama
    $selected = 'selected="selected"';
    if(!$data1==null){
        if($data1==$data2){ echo $selected;  }
        else{ echo"";}
    }
    else{
        if($data2=='all'){ echo $selected;  }
    }
} 
function selectAktifEmpty($data)
{
    //untuk select option aktif jika ke dua varibel sama
    $selected = 'selected="selected"';
    if(empty($data)){
        echo $selected;
    }
    else{
        echo"";
    }
} 
function NotEmptyTampil($data=null){
    echo $data;
}


function encrypt_url($string) {

    $output = false;
    /*
    * read security.ini file & get encryption_key | iv | encryption_mechanism value for generating encryption code
    */        
    
    $secret_key     = env('SECRET_KEY');
    $secret_iv      = env('SECRET_IV');
    $encrypt_method = "aes-256-cbc";

    // hash
    $key    = hash("sha256", $secret_key);

    // iv – encrypt method AES-256-CBC expects 16 bytes – else you will get a warning
    $iv     = substr(hash("sha256", $secret_iv), 0, 16);

    //do the encryption given text/string/number
    $result = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
    $output = base64_encode($result);
    return $output;
}



function decrypt_url($string) {

    $output = false;
    /*
    * read security.ini file & get encryption_key | iv | encryption_mechanism value for generating encryption code
    */

    $secret_key     = env('SECRET_KEY');
    $secret_iv      = env('SECRET_IV');
    $encrypt_method = "aes-256-cbc";

    // hash
    $key    = hash("sha256", $secret_key);

    // iv – encrypt method AES-256-CBC expects 16 bytes – else you will get a warning
    $iv = substr(hash("sha256", $secret_iv), 0, 16);

    //do the decryption given text/string/number

    $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    return $output;
}
function convert($size)
{
    $unit=array('b','kb','mb','gb','tb','pb');
    return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
}