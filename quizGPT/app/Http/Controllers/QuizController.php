<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateQuizRequest;
use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Services\OpenAIService;
use App\Jobs\GenerateQuestionsJob;

class QuizController extends Controller
{
    protected $openaiService;

    public function __construct(OpenAIService $openaiService)
    {
        $this->openaiService = $openaiService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $quizzes = Quiz::all();
        return view('quiz.index', ['quizzes' => $quizzes]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('quiz.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateQuizRequest $request)
    {
        Quiz::create($request->validated());
        return redirect()->route('quiz.index')->with('success', 'Quiz créé avec succès.');

    }

    public function getGenerateQuestionsView(Quiz $quiz)
    {
        return view('quiz.generate_questions', ['quiz' => $quiz]);
    }

    public function generateQuestions(Request $request, Quiz $quiz)
    {
        $numberOfQuestions = min((int)$request->input('number_of_questions'),5);
        $specialInstructions = $request->input('special_instructions');

        //$this->openaiService->generateAndStoreQuestions($quiz, $numberOfQuestions, $specialInstructions);
        GenerateQuestionsJob::dispatch($quiz, $numberOfQuestions, $specialInstructions);

        return redirect('/')->with('success', 'Les questions sont en cours de génération.');
    }

    public function getQuizStatus(Quiz $quiz)
    {
        return response()->json(['status' => $quiz->status]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Quiz $quiz, int $nbQuestions = 5)
    {
        $questions = $quiz->questions()->inRandomOrder()->take($nbQuestions)->get();

        return view('quiz.show', compact('questions', 'quiz'));
    }


    public function submitAnswers(Request $request, Quiz $quiz)
    {
        $userAnswers = $request->input('answers', []);
        $questions = $quiz->questions()->whereIn('id', array_keys($userAnswers))->get();

        $correctAnswers = [];
        $score = 0;

        foreach ($questions as $question) {
            $questionCorrectAnswers = json_decode($question->answer);
            $correctAnswers[$question->id] = $questionCorrectAnswers;

            $userAnswersForQuestion = $userAnswers[$question->id] ?? [];

            // Utiliser la méthode isAnswerCorrect pour évaluer la réponse
            if ($this->isAnswerCorrect($questionCorrectAnswers, $userAnswersForQuestion)) {
                $score++;
                $question->correct = true;
            } else {
                $question->correct = false;
            }
        }


        $totalQuestions = count($questions);
        $percentageScore = round(($score / $totalQuestions) * 100,0);

        return view('quiz.results', compact('questions', 'score', 'totalQuestions', 'percentageScore', 'correctAnswers', 'userAnswers'));
    }

    /**
     * Vérifie si la réponse de l'utilisateur est correcte.
     *
     * @param array $correctAnswers Les bonnes réponses.
     * @param array $userAnswers Les réponses de l'utilisateur.
     * @return bool
     */
    protected function isAnswerCorrect($correctAnswers, $userAnswers)
    {
        // Trier les tableaux pour la comparaison
        sort($correctAnswers);
        sort($userAnswers);

        // Vérifier si les tableaux sont identiques
        return $correctAnswers == $userAnswers;
    }




    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
