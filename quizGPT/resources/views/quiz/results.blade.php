@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto my-10 p-6 bg-white rounded-lg shadow-lg">
        <h2 class="text-3xl font-bold text-center text-gray-700 mb-6">Résultats du Quiz</h2>
        <p class="text-xl text-center mb-8">
            <span class="font-bold">Score:</span> {{ $score }} / {{ $totalQuestions }}
            (<span class="font-bold">{{ $percentageScore }}%</span>)
        </p>

        @foreach ($questions as $question)
            @php
                $isQuestionCorrect = collect($correctAnswers[$question->id])->sort()->join(',') === collect($userAnswers[$question->id] ?? [])->sort()->join(',');
                $bgColor = $isQuestionCorrect ? 'from-green-200 to-green-100' : 'from-red-200 to-red-100';
            @endphp
            <div class="mb-6 p-4 rounded-lg bg-gradient-to-r {{ $bgColor }}">
                <p class="text-lg font-semibold text-gray-700 mb-4">{{ $question->title }}</p>
                <div class="grid grid-cols-2 gap-4">
                    @foreach (['a', 'b', 'c', 'd'] as $option)
                        @php
                            $isCorrect = in_array($option, $correctAnswers[$question->id]);
                            $isSelected = in_array($option, $userAnswers[$question->id] ?? []);
                            $btnColor = $isCorrect ? 'bg-green-300' : 'bg-gray-200';
                        @endphp
                        <button class="rounded-lg p-3 text-left {{ $btnColor }} {{ $isSelected ? 'ring-2 ring-offset-2 ring-blue-300' : '' }}">
                            {{ $question->$option }}
                        </button>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
@endsection
