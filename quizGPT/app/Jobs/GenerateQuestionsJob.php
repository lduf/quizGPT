<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Services\OpenAIService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Quiz;
use Illuminate\Support\Facades\URL;

class GenerateQuestionsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $quiz;
    protected $numberOfQuestions;
    protected $specialInstructions;

    public $timeout = 120;

    public function __construct(Quiz $quiz, int $numberOfQuestions, $specialInstructions)
    {
        $this->quiz = $quiz;
        $this->numberOfQuestions = $numberOfQuestions;
        $this->specialInstructions = $specialInstructions;
    }

    public function handle()
    {
        URL::
        $openaiService = resolve(OpenAIService::class);
        $openaiService->generateAndStoreQuestions($this->quiz,
            $this->numberOfQuestions,
            $this->specialInstructions);
        $this->quiz->update(['status' => 'completed']);
    }
}
