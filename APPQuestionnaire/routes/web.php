<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

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


use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

// Routes pour l'authentification
Route::get('login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('login', [UserController::class, 'login']);
Route::post('logout', [UserController::class, 'logout'])->name('logout');

// Routes pour l'enregistrement des utilisateurs
Route::get('register', [UserController::class, 'create'])->name('users.create');  // Afficher le formulaire
Route::post('register', [UserController::class, 'store']);  // Soumettre le formulaire pour créer un utilisateur

// Routes pour la gestion des utilisateurs (ajouter un nouvel utilisateur)
Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
Route::post('/users/store', [UserController::class, 'store'])->name('users.store');


Route::get('/home', [HomeController::class, 'index'])->name('home');

