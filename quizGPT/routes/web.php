<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuizController;

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

Route::get('/',
    'App\Http\Controllers\QuizController@index'
);

Route::resource(
    'quiz',
    'App\Http\Controllers\QuizController'
);

Route::get('/quiz/{quiz}/{nbQuestions?}',
    'App\Http\Controllers\QuizController@show'
)->name('quiz.show');


Route::get('/generate-questions/{quiz}', 'App\Http\Controllers\QuizController@getGenerateQuestionsView')
    ->name('quiz.generate-questions-view');

Route::post('/generate-questions/{quiz}', 'App\Http\Controllers\QuizController@generateQuestions')
    ->name('quiz.generate-questions');

Route::get('/quiz-status/{quiz}', [QuizController::class, 'getQuizStatus']);

Route::post('/quizzes/{quiz}/submit-answers', [QuizController::class, 'submitAnswers'])->name("quiz.submit");

// Route pour soumettre la réponse à une question unique
Route::get('/single-question/{quiz}', [QuizController::class, 'singleQuestion'])
    ->name('quiz.single-question');
Route::post('/single-question/{quiz}/questions/{question}/correct', [QuizController::class, 'correctSingleQuestion'])
    ->name('quiz.correct-single-question');

Route::get('/quizzes/{quiz}/questions', [QuizController::class, 'showQuestions'])->name('quiz.show-questions');







