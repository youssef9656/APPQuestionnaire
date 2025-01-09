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

//session_start();
//$user =$_SESSION['userA'];
//if (!$user) {
//    // Routes pour l'authentification
//    Route::get('login', [UserController::class, 'showLoginForm'])->name('login');
//    Route::post('login', [UserController::class, 'login']);
//    Route::post('logout', [UserController::class, 'logout'])->name('logout');
//}elseif ($user) {
//    if($user['role'] == 'admin'){
//
//    }elseif ($user['role'] == 'user'){
//
//        Route::get('/reponquition', [ReponseController::class, 'index'])->name('reponquition.index');
//        Route::get('reponse/{id}', [ReponseController::class, 'showReponse'])->name('questionsrepose');
//// Route pour soumettre les réponses à un test
//        Route::post('/reponse/{id_test}/reponses', [ReponseController::class, 'store'])->name('reponses.store');
//        Route::get('/test/{id_test}/completion', [ReponseController::class, 'testCompletion'])->name('test.completion');
//
//    }
//
//}else{
//    Route::get('404', function () {
//        return view('');
//    });
//}


    Route::get('login', [UserController::class, 'showLoginForm'])->name('login');
    Route::post('login', [UserController::class, 'login']);
    Route::post('logout', [UserController::class, 'logout'])->name('logout');


// Routes pour l'enregistrement des utilisateurs
Route::get('register', [UserController::class, 'create'])->name('register');  // Afficher le formulaire
Route::post('register', [UserController::class, 'store']);  // Soumettre le formulaire pour créer un utilisateur

// Routes pour la gestion des utilisateurs (ajouter un nouvel utilisateur)
Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
Route::post('/update-temps-test', [UserController::class, 'updateTempsTest'])->name('updateTempsTest');


Route::get('/home', [HomeController::class, 'index'])->name('home');

use App\Http\Controllers\TestController;

Route::resource('tests', TestController::class);

use App\Http\Controllers\QuestionController;

Route::get('/{test}/questions', [QuestionController::class, 'index'])->name('questions.index');
Route::get('/{test}/questions/create', [QuestionController::class, 'create'])->name('questions.create');
Route::post('/{test}/questions', [QuestionController::class, 'store'])->name('questions.store');
Route::get('/{test}/questions/{question}/edit', [QuestionController::class, 'edit'])->name('questions.edit');
Route::put('/{test}/questions/{question}', [QuestionController::class, 'update'])->name('questions.update');
Route::delete('/{test}/questions/{question}', [QuestionController::class, 'destroy'])->name('questions.destroy');

use App\Http\Controllers\QuestionCourteController;
Route::resource('questions/{question}/courtes', QuestionCourteController::class);
Route::resource('tests/{test}/questions', QuestionController::class);





Route::get('/tests', [TestController::class, 'index'])->name('tests.index');



use App\Http\Controllers\ReponseController;

Route::get('/reponquition', [ReponseController::class, 'index'])->name('reponquition.index');

Route::get('reponse/{id}', [ReponseController::class, 'showReponse'])->name('questionsrepose');
// Route pour soumettre les réponses à un test
Route::post('/reponse/{id_test}/reponses', [ReponseController::class, 'store'])
    ->name('reponses.store');
Route::get('/test/{id_test}/completion', [ReponseController::class, 'testCompletion'])->name('test.completion');



use App\Http\Controllers\OptionController;

Route::get('questions/{id_question}/options/create', [OptionController::class, 'create'])->name('options.create');
Route::post('questions/{id_question}/options', [OptionController::class, 'store'])->name('options.store');

// Gestion des options pour une question
Route::prefix('questions/{question}/options')->group(function () {
    Route::get('/', [OptionController::class, 'index'])->name('options.index'); // Lister les options d'une question
    Route::get('/create', [OptionController::class, 'create'])->name('options.create'); // Formulaire pour ajouter une option
    Route::post('/', [OptionController::class, 'store'])->name('options.store'); // Enregistrer une option
    Route::get('/{option}/edit', [OptionController::class, 'edit'])->name('options.edit'); // Modifier une option
    Route::put('/{option}', [OptionController::class, 'update'])->name('options.update'); // Mettre à jour une option
    Route::delete('/{option}', [OptionController::class, 'destroy'])->name('options.destroy'); // Supprimer une option
});


// web.php
// routes/web.php
Route::get('/usersRepo', [ReponseController::class, 'indexRepo'])->name('usersRepo');
