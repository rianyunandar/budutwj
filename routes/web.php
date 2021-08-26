<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Admin\CadminWaliKelas;
use App\Http\Controllers\Admin\CadminAkun;
use App\Http\Controllers\Admin\CadminMapel;
use App\Http\Controllers\Admin\CadminSiswaAlumni;
use App\Http\Controllers\Siswa\CsiswaHome;
use App\Http\Controllers\Siswa\CsiswaMateri;
use App\Http\Controllers\Siswa\CsiswaTugas;
use App\Http\Controllers\Siswa\CsiswaBarcode;
use App\Http\Controllers\Guru\CguruHome;
use App\Http\Controllers\Guru\CguruAbsensi;
use App\Http\Controllers\Guru\CguruMateri;
use App\Http\Controllers\Guru\CguruTugas;
use App\Http\Controllers\Guru\CguruTugasNilai;
use App\Http\Controllers\Guru\CguruAbsenKehadiranGuru;
use App\Http\Controllers\Admin\CadminAbsenGuru;


use App\Http\Controllers\UploadFotoController;
use Illuminate\Http\Request;
/*
|--------------
| List Router
| @mryes | https://web.facebook.com/youngkq
| Way Jepara Lampung Timur
|--------------
1.Router Bagian Admin
2.Router Bagian Siswa
3.Router Bagian Wali
*/

//Route::get('/redis', 'TesRedis@showProfile');
// Route::get('/noredis', 'TesRedis@getUser');
// Route::get('/hchace', 'TesRedis@hapusChace');
// Route::get('/', function () {
//     return view('login_user');
// });

//login admin
Route::get('/budut', [LoginController::class, 'getLoginAdmin'])->name('login');
Route::post('/login', [LoginController::class, 'CekLogin'])->name('ceklogin');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/', [LoginController::class, 'getLogin'])->name('login.user');


Route::get('/phpinfo',function (){ phpinfo(); });
Route::get('/clear-all-cache-redis',function (){ ClearAllCache(); })->name('clear.cache.redis');
Route::get('/hapus-key-redis/{id}',function (Request $request){ hapusKeyRedis($request['id']); });

Route::get('/cek-data/{id}', [CsiswaBarcode::class, 'ViewDataBarcode'])->name('cek-data-siswa');


Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web','auth:admin,guru']], function () {
	\UniSharp\LaravelFilemanager\Lfm::routes();
});


Route::group(['middleware' => ['auth:admin,guru']], function(){
	//untuk select auto Filter 
	Route::post('/select-jurusan', 'Select@SelectJurusan')->name('pilih.jurusan');
	Route::post('/select-rombel', 'Select@SelectRombel')->name('pilih.rombel');
	Route::post('/select-rombel-kelas', 'Select@SelectRombelKelas')->name('pilih.rombel.kelas');
	Route::post('/select-rombel-sekolah', 'Select@SelectRombelSkl')->name('pilih.rombel.skl');
	Route::post('/select-rombel-sekolah-kelas', 'Select@SelectRombelSklKelas')->name('pilih.rombel.skl.kelas');
	Route::post('/select-siswa', 'Select@SelectSiswa')->name('pilih.siswa');
	Route::post('/select-siswa-username', 'Select@SelectSiswa')->name('pilih.siswa.username');
	Route::post('/select-smp', 'Select@FilterSmpSelect')->name('pilih.smp');
	Route::get('/select-kolom', 'Select@ListNamaKolomUpdateDataSiswa')->name('pilih.kolom');
	Route::post('/select-mapel', 'Select@SelectMataPelajaran')->name('pilih.mapel');
	Route::post('/select-mapel-sekolah-kelas', 'Select@SelectMataPelajaranKelas')->name('pilih.mapel.sekolah.kelas');
	Route::post('/select-jadwal-guru', 'Select@SelectJadwalGuru')->name('pilih.jadwal.guru');

});
//Router untuk User admin ---------------------------------------------------
// 1.Router Bagian Admin
Route::group(['prefix' => 'crew', 'middleware' => ['auth:admin']], function(){
	Route::get('/', function () {
    	return redirect('/crew/home');
	});
	Route::get('/home', 'Admin\CadminHome@index')->name('homeAdmin');
	Route::get('/list-akun-admin', [CadminAkun::class, 'listAkunAdmin'])->name('list.akun.admin');
	Route::get('/add-akun-admin', [CadminAkun::class, 'add'])->name('add.akun.admin');
	Route::get('/edit-akun-admin/{id}', [CadminAkun::class, 'edit'])->name('edit.akun.admin');
	Route::post('/insert-akun-admin', [CadminAkun::class, 'insertAdmin'])->name('insert.akun.admin');
	Route::put('/update-akun-admin', [CadminAkun::class, 'updateAkunAdmin'])->name('update.akun.admin');
	Route::put('/reset-password-admin', [CadminAkun::class, 'ResetPassword'])->name('reset.password.admin');

//Bagian akun profile admin ---------------------------------------------------------------------------------
	Route::get('/lihat-profile', [CadminAkun::class, 'index'])->name('profile.admin');
	Route::put('/update-profile/{id}',[CadminAkun::class, 'UpdateAdmin']);
	

//Bagian Upload Foto ---------------------------------------------------------------------------------
	Route::post('/upload-foto-admin', [UploadFotoController::class, 'UploadFotoAdmin'])->name('upload.foto.admin');
	

//Bagian siswa ----------------------------------------------------------------------------------------------
	Route::get('/addsiswa', 'Admin\CadminSiswa@add')->name('tambah.siswa');
	Route::post('/insertsiswa', 'Admin\CadminSiswa@Insert')->name('insertSiswa');
	Route::get('/json-data-siswa', 'Admin\CadminSiswa@jsonSiswa')->name('jsonsiswa');
	Route::get('/json-data-siswa-all', 'Admin\CadminSiswa@jsonAllSiswa')->name('json.all.siswa');
	Route::get('/json-siswa-off', 'Admin\CadminSiswa@jsonSiswaOff')->name('json.siswa.off');
	Route::get('/lihat-siswa', 'Admin\CadminSiswa@lihatSiswa')->name('lihatsiswa');
	Route::get('/all-siswa', 'Admin\CadminSiswa@allSiswa')->name('lihatsemuadatasiswa');
	Route::get('/lihat-siswa-off', 'Admin\CadminSiswa@LihatSiswaOff')->name('lihat.siswa.off');
	Route::get('/edit-siswa/{id}','Admin\CadminSiswa@editSiswa')->name('editsiswa');
	Route::put('/update-siswa/{id}','Admin\CadminSiswa@Update');
	Route::put('/{id}/deletesiswa','Admin\CadminSiswa@deleteSiswa');
	Route::put('/reset-password-siswa','Admin\CadminSiswa@resetPasswordSiswa')->name('reset.password.siswa');

	Route::get('/rincian-siswa', 'Admin\CadminSiswa@RincianSiswa')->name('rincian.siswa');
	Route::get('/rincian-agama-siswa', 'Admin\CadminSiswa@RincianAgamaSiswa')->name('rincian.agama.siswa');
	Route::get('/rincian-transpot-siswa', 'Admin\CadminSiswa@RincianTranspotSiswa')->name('rincian.transpot.siswa');

//Alumni Siswa 08-6-2020 ----------------------------------------------------------------------------------------------
	Route::get('/add-alumni', [CadminSiswaAlumni::class, 'add'])->name('add.alumni');
	Route::get('/all-alumni', [CadminSiswaAlumni::class, 'allAlumni'])->name('all.alumni');
	Route::post('/insert-alumni',  [CadminSiswaAlumni::class, 'Insert'])->name('insert.alumni');
	Route::get('/json-data-alumni', [CadminSiswaAlumni::class, 'JsonDataAlumni'])->name('json.alumni');
	Route::get('/hapus-siswa-angkatan', [CadminSiswaAlumni::class, 'hapusSiswaAngkatan'])->name('hapus.siswa.angkatan');
	Route::post('/hapus-siswa-by-angkatan',  [CadminSiswaAlumni::class, 'hapusSiswaByAngkatan'])->name('hapus.siswa.by.angkatan');
	
	
	//import data siswa ----------------------------------------------------------------------------------------------
	Route::get('/form-import-siswa', 'Admin\CadminSiswa@formImportSiswa')->name('form.import.siswa');
	Route::post('/import-data-siswa', 'Admin\CadminSiswa@ImportDataSiswa')->name('import.data.siswa');
	Route::post('/import-update-data-siswa', 'Admin\CadminSiswa@ImportUpdateDataSiswa')->name('import.update.data.siswa');
	Route::get('/form-import-guru', 'Admin\CadminGuru@formImportSiswa')->name('form.import.guru');
	Route::post('/import-data-guru', 'Admin\CadminGuru@ImportDataGuru')->name('import.data.guru');
	Route::get('/form-import-mapel', [CadminMapel::class, 'FormMapel'])->name('form.import.mapel');
	Route::post('/import-data-mapel', [CadminMapel::class, 'ImportDataMapel'])->name('import.data.mapel');

//Bagian sekolah ----------------------------------------------------------------------------------------------
	Route::get('/lihat-sekolah', 'Admin\CadminSekolah@lihatSekolah')->name('lihat.sekolah');
	Route::get('/json-sekolah', 'Admin\CadminSekolah@jsonSekolah')->name('json.sekolah');
  Route::get('/add-sekolah', 'Admin\CadminSekolah@add')->name('tambah.sekolah');
  Route::post('/insert-sekolah', 'Admin\CadminSekolah@InsertSekolah')->name('insert.sekolah');
  Route::get('{id}/edit-sekolah','Admin\CadminSekolah@edit')->name('edit.sekolah');
  Route::put('/update-sekolah/{id}','Admin\CadminSekolah@UpdateSekolah');

//jabatan ----------------------------------------------------------------------------------------------
	Route::get('/lihat-jabatan', 'Admin\CadminSekolah@LihatJabatan')->name('lihat.jabatan');
	Route::get('/add-jabatan', 'Admin\CadminSekolah@addJabatan')->name('tambah.jabatan');
	Route::post('/insert-jabatan', 'Admin\CadminSekolah@InsertJabatan')->name('insert.jabatan');

//agama ------------------------------------------------------------------------------------------------
	Route::get('/lihat-agama', 'Admin\CadminSekolah@LihatAgama')->name('lihat.agama');

//penghasilan ------------------------------------------------------------------------------------------------
	Route::get('/lihat-penghasilan', 'Admin\CadminSekolah@LihatPenghasilan')->name('lihat.penghasilan');

//tingkat pendidikan ------------------------------------------------------------------------------------------------
	Route::get('/lihat-tingkat-pendidikan', 'Admin\CadminSekolah@LihatTingkatPendidikan')->name('lihat.tingkat.pendidikan');

//tahun ajaran ------------------------------------------------------------------------------------------------
	Route::get('/lihat-tahun-ajaran', 'Admin\CadminSekolah@LihatTahunAjaran')->name('lihat.tahun.ajaran');
  Route::post('/insert-tahun-ajaran', 'Admin\CadminSekolah@InsertTahunAjaran')->name('insert.tahun.ajaran');
  Route::post('/update-tahun-ajaran', 'Admin\CadminSekolah@UpdateTahunAjaran')->name('update.tahun.ajaran');

//semester ------------------------------------------------------------------------------------------------
	Route::get('/lihat-semester', 'Admin\CadminSekolah@LihatSemester')->name('lihat.semester');

//SMP ------------------------------------------------------------------------------------------------
	Route::get('/lihat-smp', 'Admin\CadminSekolah@LihatSmp')->name('lihat.smp');

//transportasi ------------------------------------------------------------------------------------------------
	Route::get('/lihat-transportasi', 'Admin\CadminSekolah@LihatTransportasi')->name('lihat.transportasi');

//Bagian jurusan ------------------------------------------------------------------------------------------------
	Route::get('/lihat-jurusan', 'Admin\CadminJurusan@lihatJurusan')->name('lihatJurusan');
	Route::get('/json-data-jurusan', 'Admin\CadminJurusan@jsonJurusan')->name('jsonjurusan');
	Route::get('/add-jurusan', 'Admin\CadminJurusan@add')->name('tambahjurusan');
	Route::post('/insertjurusan', 'Admin\CadminJurusan@insertJurusan')->name('insert.jurusan');
	Route::delete('/{id}/deletejurusan','Admin\CadminJurusan@deleteJurusan');
	Route::get('{id}/edit-jurusan','Admin\CadminJurusan@editJurusan')->name('edit.jurusan');
	Route::put('/update-jurusan/{id}','Admin\CadminJurusan@UpdateJurusan');

//Bagian guru ------------------------------------------------------------------------------------------------
	Route::get('/lihat-guru', 'Admin\CadminGuru@lihatGuru')->name('lihat.guru');
	Route::get('/lihat-guru-off', 'Admin\CadminGuru@lihatGuruOff')->name('lihat.guru.off');
	Route::get('/json-guru', 'Admin\CadminGuru@jsonGuru')->name('json.guru');
	Route::get('/json-guru-off', 'Admin\CadminGuru@jsonGuruOff')->name('json.guru.off');
	Route::get('/addguru', 'Admin\CadminGuru@add')->name('add.guru');
	Route::get('{id}/edit-guru','Admin\CadminGuru@editGuru')->name('edit.guru');
	Route::post('/insertguru', 'Admin\CadminGuru@InsertGuru')->name('insert.guru');
	Route::put('/update-guru/{id}','Admin\CadminGuru@UpdateGuru');
	Route::put('/{id}/deleteguru','Admin\CadminGuru@deleteGuru');
	Route::put('/reset-password-guru','Admin\CadminGuru@ResetPassword')->name('reset.password.guru');

//Bagian kelas ------------------------------------------------------------------------------------------------
//Bagian rombel ------------------------------------------------------------------------------------------------
	Route::get('/lihat-rombel', 'Admin\CadminRombel@lihatRombel')->name('lihat.rombel');
	Route::get('/add-rombel', 'Admin\CadminRombel@addRombel')->name('tambah.rombel');
	Route::get('{id}/edit-rombel','Admin\CadminRombel@editRombel')->name('edit.rombel');
	//----ProsesCRUD-----------
	Route::get('/json-rombel', 'Admin\CadminRombel@jsonRombel')->name('json.rombel');
	Route::post('/insert-rombel', 'Admin\CadminRombel@InsertRombel')->name('insert.rombel');
	Route::put('/update-rombel/{id}','Admin\CadminRombel@UpdateRombel');

//Bagian anggota rombel ------------------------------------------------------------------------------------------------
	Route::get('/lihat-anggota-rombel', 'Admin\CadminRombel@lihatAnggotaRombel')->name('lihat.anggota.rombel');
	Route::get('/add-anggota-rombel', 'Admin\CadminRombel@addAnggotaRombel')->name('tambah.anggota.rombel');
	//----ProsesCRUD-----------
	Route::get('/cek-rombel', 'Admin\CadminRombel@cekSiswaRombelKosong');
	Route::post('/insert-anggota-rombel', 'Admin\CadminRombel@InsertAnggotaRombel')->name('insert.anggota.rombel');
	Route::get('/json-anggota-rombel', 'Admin\CadminRombel@jsonAnggotaRombel')->name('json.anggota.rombel');

//Bagian wali kelas ------------------------------------------------------------------------------------------------
	Route::post('/insert-wali-kelas', 'Admin\CadminWaliKelas@InsertWakas')->name('insert.wakas');
	Route::put('/update-wakas/{id}',[CadminWaliKelas::class, 'UpdateWakas']);
	Route::put('/{id}/delete-wakas',[CadminWaliKelas::class, 'DeleteWakas']);
	//----ProsesCRUD-----------
	Route::get('/lihat-wali-kelas', 'Admin\CadminWaliKelas@lihatWaliKelas')->name('lihat.wali.kelas');
	Route::get('/add-wali-kelas', 'Admin\CadminWaliKelas@AddWaliKelas')->name('tambah.wali.kelas');
	Route::get('/{id}/edit-wakas',[CadminWaliKelas::class, 'editWakas'])->name('edit.wali.kelas');
	Route::get('/json-data-wali-kelas', 'Admin\CadminWaliKelas@jsonWaliKelas')->name('json.wali.kelas');

//Bagian ketua jurusan -------------------------------------------------------------------------------------------
 
	Route::post('/insert-kajur', 'Admin\CadminKajur@InsertKajur')->name('insert.kajur');
  Route::post('/update-kajur', 'Admin\CadminKajur@UpdateKajur')->name('update.kajur');
  Route::put('/{id}/delete-kajur','Admin\CadminKajur@DeleteKajur');
  //----ProsesCRUD-----------
  Route::get('/lihat-kajur', 'Admin\CadminKajur@lihatKajur')->name('lihat.kajur');
	Route::get('/add-kajur', 'Admin\CadminKajur@AddKajur')->name('tambah.kajur');
  Route::get('/json-kajur', 'Admin\CadminKajur@jsonKajur')->name('json.kajur');
	
//Bagina Import Absen Finger -------------------------------------------------------------------------------------
	Route::get('/form-import-absen-finger', 'Admin\CadminAbsenFinger@FormImportAbsen')->name('form.import.absen.finger');
	Route::post('/import-absen-finger', 'Admin\CadminAbsenFinger@ImportAbsenFingerSiswa')->name('import.absen.finger');
	Route::get('/lihat-absen-finger', 'Admin\CadminAbsenFinger@LihatAbsenFinger')->name('lihat.absen.finger');
	Route::get('/json-absen-finger', 'Admin\CadminAbsenFinger@jsonAbsenFinger')->name('json.absen.finger');
	Route::get('/add-absen-finger', 'Admin\CadminAbsenFinger@add')->name('add.absen.finger');
	
	
	Route::post('/insert-absen-finger', 'Admin\CadminAbsenFinger@InsertAbsenFinger')->name('insert.absen.finger');
  Route::put('/update-absen-finger/{id}','Admin\CadminAbsenFinger@UpdateAbsenFinger');
	Route::put('/{id}/delete-absen-finger','Admin\CadminAbsenFinger@deleteAbsenFinger');

	//rekap absen sekolah siswa atau rekap absen fingerprint 02-08-2021 -----------------------------------------------------
	Route::get('/view-rekap-absen-finger', 'Admin\CadminAbsenFinger@ViewRekapAbsenFinger')->name('view.rekap.absen.finger');
	Route::get('/cetak-view-rekap-absen-finger', 'Admin\CadminAbsenFinger@CetakViewRekapAbsenFinger')->name('cetak.view.rekap.absen.finger');


//Bagian Informasi -----------------------------------------------------------------------------------------------------------
	Route::get('/add-informasi-sekolah', 'Admin\CadminInformasi@AddInformasi')->name('add.informasi.sekolah');
	Route::get('/edit-informasi-sekolah/{id}', 'Admin\CadminInformasi@EditInformasi');
	Route::get('/lihat-informasi-sekolah', 'Admin\CadminInformasi@LihatInformasi')->name('lihat.informasi.sekolah');

	Route::post('/insert-informasi-sekolah','Admin\CadminInformasi@InsertInformasiSekolah')->name('insert.informasi.sekolah');
	Route::put('/update-informasi-sekolah/{id}','Admin\CadminInformasi@UpdateInformasiSekolah');
	Route::post('/upload-foto-informasi-sekolah',[UploadFotoController::class, 'UploadFotoInformasi'])->name('upload.foto.informasi.sekolah');
	Route::put('/{id}/deleteinfo','Admin\CadminInformasi@DeleteInformasiSekolah');

//Bagian Mapel ------------------------------------------------------------------------------
	Route::get('/add-mapel', [CadminMapel::class, 'addMapel'])->name('add.mapel');
	Route::get('{id}/edit-mapel/', [CadminMapel::class, 'EditMapel'])->name('edit.mapel');
	Route::get('/lihat-mapel', [CadminMapel::class, 'lihatMapel'])->name('lihat.mapel');

	Route::post('/insert-mapel', [CadminMapel::class, 'insertMapel'])->name('insert.mapel');
	Route::put('/update-mapel', [CadminMapel::class, 'updateMapel'])->name('update.mapel');
	Route::get('/json-mapel', [CadminMapel::class, 'jsonGetMapel'])->name('json.mapel');

//Absensi Guru----------------------------------------------------------------------------------------------
	Route::get('/lihat-absen-guru', [CadminAbsenGuru::class, 'view'])->name('lihat.absen.guru');
	Route::get('/json-absen-guru', [CadminAbsenGuru::class, 'jsonAbsenGuru'])->name('json.absen.guru');
	Route::get('/rekap-absen-guru', [CadminAbsenGuru::class, 'rekapAbsenGuru'])->name('rekap.absen.guru');
	Route::get('/json-cetak-absen-guru', [CadminAbsenGuru::class, 'JsonCetakRekapAbsenGuru'])->name('json.cetak.absen.guru');
	Route::get('/cetak-absen-guru', [CadminAbsenGuru::class, 'CetakRekapAbsenGuru'])->name('cetak.absen.guru');
	Route::get('/absen-guru-manual', [CadminAbsenGuru::class, 'ManualAbsenGuru'])->name('manual.absen.guru');
	Route::put('/insert-absen-manual-guru', [CadminAbsenGuru::class, 'InsertAbsenManualGuru'])->name('insert.absen.manual.guru');

	Route::get('/insert-absen-guru-random', [CadminAbsenGuru::class, 'randomInsertDataGuru']);


}); //end route admin


//End Router untuk User admin ------------------------------------------------

// 2.Router Untuk Siswa ------------------------------------------------------
	Route::group(['prefix' => 'siswa', 'middleware' => ['auth:siswa']], function(){
		Route::get('/home-siswa', [CsiswaHome::class, 'index'])->name('homeSiswa');
		Route::get('/akun',[CsiswaHome::class, 'EditProfile'])->name('edit.akun.siswa');
		Route::get('/jadwal-siswa',[CsiswaHome::class, 'JadwalSiswa'])->name('jadwal.siswa');
		Route::get('/foto-siswa',[CsiswaHome::class, 'UploadFoto'])->name('foto.siswa');
		Route::get('/password',[CsiswaHome::class, 'Password'])->name('password');
		Route::get('/anggota-kelas',[CsiswaHome::class, 'AnggotaKelas'])->name('anggota.kelas');
		Route::get('/wali-kelas',[CsiswaHome::class, 'WaliKelas'])->name('wali.kelas');
		Route::get('/data-sekolah',[CsiswaHome::class, 'DataSekolah'])->name('data.sekolah');
		//absensi --------------------
		Route::get('/absensi-mapel', [CsiswaHome::class, 'AbsensiMapel'])->name('absensi.mapel');
		Route::get('/absensi-sekolah', [CsiswaHome::class, 'AbsensiSekolah'])->name('absensi.sekolah');
		Route::get('/total-absensi-sekolah', [CsiswaHome::class, 'TotalAbsensiSekolah'])->name('total.absensi.sekolah');
		Route::put('/insert-absen-mapel-siswa', [CsiswaHome::class, 'InsertAbsensiMapelSiswa'])->name('insert.absen.mapel.siswa');
		Route::put('/update-data-siswa',[CsiswaHome::class, 'UpdateSiswa'])->name('update.data.siswa');
		Route::get('/json-anggota-kelas',[CsiswaHome::class, 'jsonSiswaAnggotaKelas'])->name('json.anggota.kelas');
		Route::post('/upload-foto-siswa', [UploadFotoController::class, 'UploadFotoSiswa'])->name('upload.foto.siswa');
		Route::put('/update-password-siswa',[CsiswaHome::class, 'UpdatePassword'])->name('update.password.siswa');
		Route::get('/json-log-absen-sekolah', [CsiswaHome::class, 'JsonLogAbsensiSekolah'])->name('json.log.absensi.sekolah');
		Route::put('/insert-sekolah-mapel-siswa', [CsiswaHome::class, 'InsertAbsensisekolahSiswa'])->name('insert.absen.sekolah.siswa');
		Route::get('/json-total-absen-sekolah',[CsiswaHome::class, 'JsonTotalAbsenSekolah'])->name('json.total.absen.sekolah');
		
		//Elearning ----------------------------------------------------
		//materi -------------------------------------------------------
		Route::get('/list-jadwal-mapel', [CsiswaMateri::class, 'ListJadwalMapel'])->name('list.jadwal.mapel');
		Route::get('/baca-materi-siswa/{id}', [CsiswaMateri::class, 'BacaMateriSiswa'])->name('baca.materi.siswa');
		//Tugas 05-08-2021 ------------------------------------------------------------------------------------------
		Route::get('/list-tugas-mapel', [CsiswaTugas::class, 'ListMapelTugas'])->name('list.tugas.siswa');
		Route::get('/baca-tugas-siswa/{id}', [CsiswaTugas::class, 'BacaTugasSiswa'])->name('baca.tugas.siswa');

			
	});
//End Router untuk Siswa------------------------------------------------------

// 3.Router Untuk Guru ------------------------------------------------------
Route::group(['prefix' => 'guru', 'middleware' => ['auth:guru']], function(){
	Route::get('/home-guru', [CguruHome::class, 'index'])->name('home.guru');
	Route::get('/guru-data-siswa', [CguruHome::class, 'GuruDataSiswa'])->name('guru.data.siswa');
	Route::get('/guru-cari-data-siswa', [CguruHome::class, 'GuruCariDataSiswa'])->name('guru.cari.data.siswa');
	Route::get('/guru-data-akun', [CguruHome::class, 'GuruDataAkun'])->name('guru.data.akun');
	Route::get('/guru-data-profile', [CguruHome::class, 'GuruDataProfile'])->name('guru.data.profile');

	Route::put('/guru-password', [CguruHome::class, 'GuruUpdatePassword'])->name('guru.password');
	Route::put('/guru-update-data-profile', [CguruHome::class, 'GuruUpdateDataProfile'])->name('guru.update.data.profile');
	Route::get('/guru-data-siswa-json', [CguruHome::class, 'GuruDataSiswaJson'])->name('guru.data.siswa.json');
	Route::post('/guru-upload-foto', [UploadFotoController::class, 'UploadFotoGuru'])->name('guru.upload.foto');

	//absensi kehadiran guru
	Route::get('/guru-absen-sekolah', [CguruAbsenKehadiranGuru::class, 'viewAbsenGuru'])->name('guru.absen.sekolah');
	Route::post('/insert-absen-kehadiran-guru', [CguruAbsenKehadiranGuru::class, 'insert'])->name('insert.absen.kehadiran.guru');
	Route::get('/json-log-absen-guru-sekolah', [CguruAbsenKehadiranGuru::class, 'JsonLogAbsensiGuruSekolah'])->name('json.log.absensi.guru.sekolah');

	//jadwal mapel absensi ---------------------------------------------------
	Route::get('/guru-jadwal-mapel-absen', [CguruAbsensi::class, 'GuruJadwalMapelAbsen'])->name('guru.jadwal.mapel.absen');
	Route::get('/json-guru-jadwal-mapel', [CguruAbsensi::class, 'JsonGuruJadwalMapel'])->name('json.guru.jadwal.mapel');
	Route::post('/insert-guru-jadwal-mapel', [CguruAbsensi::class, 'GuruInsertJadwalMapelAbsen'])->name('insert.guru.jadwal.mapel');
	Route::put('/{id}/delete-jadwal',[CguruAbsensi::class, 'GuruHapusJadwal'])->name('guru.hapus.jadwal.mapel');
	Route::put('/update-jadwal',[CguruAbsensi::class, 'GuruUpdateJadwal'])->name('guru.update.jadwal.mapel');
	
	Route::get('/mapel-absen-manual-guru', [CguruAbsensi::class, 'MapelAbsenManualGuru'])->name('mapel.absen.manual.guru');
	Route::get('/mapel-absen-manual-guru-izin', [CguruAbsensi::class, 'MapelAbsenManualGuruIzin'])->name('mapel.absen.manual.guru.izin');
	Route::get('/rincian-mapel-absen-guru', [CguruAbsensi::class, 'RincianMapelAbsenGuru'])->name('rincian.mapel.absen.guru');
	Route::get('/total-mapel-absen-guru-bulan', [CguruAbsensi::class, 'TotalMapelAbsenGuruBulan'])->name('total.mapel.absen.guru.bulan');
	Route::get('/total-all-absen-guru', [CguruAbsensi::class, 'TotalALLMapelAbsenGuru'])->name('total.mapel.all.guru');
	Route::get('/rekap-mapel-absen-guru-bulan', [CguruAbsensi::class, 'RekapMapelAbsenGuruBulan'])->name('rekap.mapel.absen.bulan.guru');
	
	Route::put('/insert-mapel-absen-manual-guru', [CguruAbsensi::class, 'InsertMapelAbsenManualGuru'])->name('insert.mapel.absen.manual.guru');
	Route::put('/insert-mapel-absen-izin-guru', [CguruAbsensi::class, 'InsertMapelAbsenIzinGuru'])->name('insert.mapel.absen.izin.guru');
	Route::get('/json-rekap-absen-mapel/', [CguruAbsensi::class, 'JsonRekapAbsenMapel'])->name('json.rekap.absen.mapel');
	Route::get('/json-total-absen-mapel/', [CguruAbsensi::class, 'JsonTotalAbsenMapel'])->name('json.total.absen.mapel');
	Route::get('/json-tota-all-absen-mapel/', [CguruAbsensi::class, 'JsonTotalAllAbsenMapel'])->name('json.total.all.absen.mapel');
	Route::get('/cetak-absen-mapel-detail', [CguruAbsensi::class, 'CetakAbsenMapelDetail'])->name('cetak.absen.mapel.detail');
	
	//Elearning --------------------------------------------------------------------
	//materi -----------------------------------------------------------------------
	Route::get('/add-materi', [CguruMateri::class, 'addMateri'])->name('add.materi');
	Route::get('/edit-materi/{id}', [CguruMateri::class, 'editMateri'])->name('edit.materi');
	Route::get('/edit-materi-rombel/{id}/{idd}', [CguruMateri::class, 'editMateriRombel'])->name('edit.materi.rombel');
	Route::put('/edit-data-materi-rombel/', [CguruMateri::class, 'editDataMateriRombel'])->name('edit.data.materi.rombel');

	Route::get('/lihat-materi', [CguruMateri::class, 'LihatMateri'])->name('lihat.materi');
	//crudm ateri
	Route::get('/json-materi', [CguruMateri::class, 'JsonMateri'])->name('json.materi');
	Route::post('/insert-materi', [CguruMateri::class, 'InsertMateri'])->name('insert.materi');
	Route::put('/{id}/delete-materi', [CguruMateri::class, 'DeleteMateri'])->name('delete.materi');
	Route::post('/update-materi', [CguruMateri::class, 'UpdateMateri'])->name('update.materi');
	Route::put('/hapus-materi-rombel', [CguruMateri::class, 'DeleteMateriRombel'])->name('delete.materi.rombel');
	Route::post('/update-materi-rombel', [CguruMateri::class, 'UpdateMateriRombel'])->name('update.materi.rombel');

	//Tugas  05-08-2021-----------------------------------------------------------------------
	Route::get('/add-tugas', [CguruTugas::class, 'addTugas'])->name('add.tugas.guru');
	Route::get('/lihat-tugas', [CguruTugas::class, 'LihatTugas'])->name('lihat.tugas.guru');
	Route::get('/json-tugas', [CguruTugas::class, 'JsonTugas'])->name('json.tugas.guru');
	Route::get('/edit-tugas/{id}', [CguruTugas::class, 'editTugas'])->name('edit.tugas');
	Route::get('/edit-tugas-rombel/{id}/{idd}', [CguruTugas::class, 'editTugasRombel'])->name('edit.tugas.rombel.guru');
	
	//crud tugas
	Route::post('/insert-tugas', [CguruTugas::class, 'InsertTugas'])->name('insert.tugas.guru');
	Route::post('/update-tugas', [CguruTugas::class, 'UpdateTugas'])->name('update.tugas.guru');
	Route::post('/update-tugas-rombel', [CguruTugas::class, 'UpdateTugasRombel'])->name('update.tugas.rombel.guru');
	Route::put('/edit-data-tugas-rombel/', [CguruTugas::class, 'editDataTugasRombel'])->name('edit.data.tugas.rombel.guru');
	Route::put('/hapus-tugas-rombel', [CguruTugas::class, 'DeleteTugasRombel'])->name('delete.tugas.rombel.guru');
	Route::put('/{id}/delete-tugas', [CguruTugas::class, 'DeleteTugas'])->name('delete.tugas.guru');

  //Penilaian Tugas  17-08-2021-----------------------------------------------------------------------
	Route::get('/add-nilai-tugas', [CguruTugasNilai::class, 'addTugasNilai'])->name('add.tugas.nilai.guru');
	Route::get('/rekap-nilai-tugas', [CguruTugasNilai::class, 'rekapTugasNilai'])->name('rekap.tugas.nilai.guru');
	//crud Penilaian Tugas
	Route::put('/insert-nilai-tugas-guru', [CguruTugasNilai::class, 'insertNilaiTugas'])->name('insert.nilai.tugas.guru');


	//Absensi FingerPrint Siswa 02-08-2021----------------------------------------------------------------
	Route::get('/lihat-absen-sekolah-guru', [CguruAbsensi::class, 'LihatAbsenSekolah'])->name('lihat.absen.sekolah.guru');
	Route::get('/json-absen-sekolah-guru', [CguruAbsensi::class, 'jsonAbsenFinger'])->name('json.absen.sekolah.guru');
	Route::get('/view-rekap-absen-sekolah-guru', [CguruAbsensi::class, 'ViewRekapAbsenFinger'])->name('view.rekap.absen.sekolah.guru');
	Route::get('/cetak-rekap-absen-sekolah-guru', [CguruAbsensi::class, 'CetakViewRekapAbsenFinger'])->name('cetak.rekap.absen.sekolah.guru');



});
//End Router untuk Guru------------------------------------------------------


// 4.Router Untuk Wali -------------------------------------------------------
	Route::group(['prefix' => 'wali'], function(){
		Route::get('/home-wali', 'Wali\CwaliHome@index')->name('homeWali');
	});
//End Router untuk Wali------------------------------------------------------