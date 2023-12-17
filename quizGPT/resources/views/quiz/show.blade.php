@extends('layouts.app')

@section('content')
    <div id="quiz-container" class="max-w-2xl mx-auto my-10 p-6 bg-white rounded-lg shadow-lg">
        <div id="error-message" class="hidden text-red-500 mb-4"></div>

        <form id="quiz-form" method="POST" action="{{ route('quiz.submit', $quiz->id) }}">
            @csrf
            @foreach ($questions as $question)
                <div class="question mb-10 p-4 bg-gradient-to-r from-green-100 to-blue-200 rounded-lg shadow-2xl">
                    <h3 class="font-bold text-xl text-gray-700 mb-4">{{ $question->title }}</h3>
                    <div class="propositions grid grid-cols-2 gap-4">
                        @php
                            $propositions = ['a' => $question->a, 'b' => $question->b, 'c' => $question->c, 'd' => $question->d];
                        @endphp
                        @foreach ($propositions as $key => $value)
                            <label class="prop-label bg-white hover:bg-green-100 border border-green-300 rounded-lg p-3 cursor-pointer shadow-md" for="question-{{ $question->id }}-{{ $key }}">
                                <input type="checkbox" id="question-{{ $question->id }}-{{ $key }}" name="answers[{{ $question->id }}][]" value="{{ $key }}" class="hidden">
                                <span>{{ $value }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
            <button type="submit" class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg shadow-md">Corriger</button>
        </form>
    </div>

    <script>
        // JavaScript pour la gestion des clics sur les propositions et la validation du formulaire...
        document.getElementById('quiz-form').addEventListener('submit', function(event) {
            const errorMessage = document.getElementById('error-message');
            const questions = document.querySelectorAll('.question');
            let allAnswered = true;

            questions.forEach(question => {
                if (!Array.from(question.querySelectorAll('input[type="checkbox"]')).some(checkbox => checkbox.checked)) {
                    allAnswered = false;
                    question.classList.add('bg-red-100'); // Mettez en évidence la question non répondue
                } else {
                    question.classList.remove('bg-red-100');
                }
            });

            if (!allAnswered) {
                event.preventDefault();
                errorMessage.textContent = 'Veuillez répondre à toutes les questions avant de soumettre.';
                errorMessage.classList.remove('hidden');
            } else {
                errorMessage.classList.add('hidden');
            }
        });
    </script>
@endsection


