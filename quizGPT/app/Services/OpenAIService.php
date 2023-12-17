<?php
namespace App\Services;

use OpenAI;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\ApiResponse;
use Illuminate\Support\Facades\Log;

class OpenAIService
{
    protected $client;

    public function __construct()
    {
        $apiKey = env('OPENAI_API_KEY');
        $this->client = OpenAI::client($apiKey);
    }

    // Dans App\Services\OpenAIService.php

    public function generateAndStoreQuestions(Quiz $quiz, $numberOfQuestions, $special_instructions = null)
    {

        $existingTitles = json_encode($quiz->questions->pluck('title'));
        $subject = $quiz->subject;
        $title = $quiz->title;
        $prompt = "Générez $numberOfQuestions questions sur le quiz '$title' dont le sujet est '$subject' en évitant ces questions existantes : $existingTitles. /n. Instruction spéciale : $special_instructions";

        // Appel à l'API OpenAI
        $response = $this->client->chat()->create([
            'model' => 'gpt-4-1106-preview',
            'messages' => [
                ["role" => "system", "content" => env('OPENAI_SYSTEM_PROMPT')],
                ["role" => "user", "content" => $prompt],
            ],
            'max_tokens' => (int)env('OPENAI_MAX_TOKENS', 500)
        ]);
        $msg = $response->choices[0]->message->content;
        $msgClean = str_replace(["```json\n", "\n", "```"], '', $msg);
        ApiResponse::create(["quiz_id" => $quiz->id,'response' => $msgClean]);
        $questions = json_decode($msgClean, true);
        if ($questions === null && json_last_error() !== JSON_ERROR_NONE) {
            Log::error("Erreur lors du décodage JSON", [
                'json_error' => json_last_error_msg(),
                'original_msg' => $msg
            ]);
        }
        // Stockage dans la base de données
        //$questions = $questions[0]['questions'];
        foreach ($questions["questions"] as $question) {
            // Assurez-vous que la question n'existe pas déjà pour éviter les doublons
            Question::create([
                'quiz_id' => $quiz->id,
                'title' => $question['title'],
                'subject' => $question['subject'],
                'difficulty' => $question['difficulty'],
                'a' => $question["propositions"]['a'],
                'b' => $question["propositions"]['b'],
                'c' => $question["propositions"]['c'],
                'd' => $question["propositions"]['d'],
                'answer' => json_encode($question['answer'])
            ]);
        }
    }

}
