<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [SiteController::class, 'index'])->name('index');
Route::get('/login', [SiteController::class, 'login'])->name('login');
Route::post('/login/autenticacao', [SiteController::class, 'authLogin'])->name('login_auth');
Route::get('/logout', [SiteController::class, 'fazerLogout'])->name('logout');
Route::post('/paciente/sign_up', [UserController::class, 'createAccountPaciente'])->name('paciente.criar_conta');
Route::get('/criar-conta',[SiteController::class, 'createAccount'])->name('paciente.createAcount');


Route::group(['middleware' => ['auth']], function () {
    Route::get('/admin', function () {
        return view('Admin.dashboard');
    })->name('dashboard');

    Route::get('/admin/listar-doctores', function () {
        return view('Admin.Listagem.todosDotores');
    })->name('admin.doctor.todosDoctores');

    Route::get('/admin/agendar-procedimento', function () {
        return view('Admin.Doctor.agendarProcedimento');
    })->name('admin.doctor.agendarProcedimento');

    Route::get('/admin/listar-procedimentos', function () {
        return view('Admin.Doctor.listarProcedimento');
    })->name('admin.doctor.listarProcedimento');

    Route::get('/admin/listar-pacientes', function () {
        return view('Admin.listarPaciente');
    })->name('admin.listarPaciente');

    Route::get('/admin/perfil-Doctor', function () {
        return view('Admin.Doctor.perfil');
    })->name('admin.doctor.pefil');

    Route::get('/admin/agenda-medica', function () {
        return view('Admin.Doctor.agendaMedica');
    })->name('admin.doctor.agendaMedica');


    ////////////////////////

    Route::get('/admin/perfil-paciente', function () {
        return view('Admin.Paciente.perfil');
    })->name('admin.paciente.perfil');
});
