<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\postcontroller;

Route::get('/',[postcontroller::class,'showpost']);

Route::get('/posting', function () {
    return view('create');
});

Route::post('/insert',[postcontroller::class,'insert']);

/*
Route::get('/', function () {
    return "<a href='/login'>Log in</a></n>
    <a href='".route('resetpassword')."'>Kimhun</a>";//ใช้ชื่อแทนไม่ต้องพิมพ์ url ยาวๆ
});
*/

Route::get('/login', function () {
    return "<h1>ไปหน้าล็อกอิน</h1>";
});

Route::get('/login/forgot/resetpw/emailkimhun/jringjringna', function () {
    return "<h1>ตั้งค่ารหัสใหม่</h1>";
})->name('resetpassword');//ตั้งชื่อ url

Route::fallback(function(){
    return "<h1>ไม่มี path นี้ในระบบ</h1> <a href='/'>Home<a>";
});