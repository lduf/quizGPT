@extends('layouts.app')

@section('content')
    <div class="max-w-md mx-auto my-10 p-6 bg-white rounded-lg shadow-lg">
        <form id="single-question-form">
            @csrf
            <div class="question mb-10 p-4 bg-gradient-to-r from-green-100 to-blue-200 rounded-lg shadow-2xl" data-question-id="{{ $question->id }}">
                <h3 class="font-bold text-xl text-gray-700 mb-4">{{ $question->title }}</h3>
                <div class="propositions">
                    @php
                        $propositions = ['a' => $question->a, 'b' => $question->b, 'c' => $question->c, 'd' => $question->d];
                    @endphp
                    @foreach ($propositions as $key => $value)
                        <div class="mb-4">
                            <label class="prop-label bg-white hover:bg-green-100 border border-green-300 rounded-lg p-3 cursor-pointer shadow-md block" for="question-{{ $question->id }}-{{ $key }}">
                                <input type="checkbox" id="question-{{ $question->id }}-{{ $key }}" name="answers[]" value="{{ $key }}" class="hidden">
                                <span>{{ $value }}</span>
                            </label>
                        </div>
                    @endforeach
                </div>
                <button type="button" id="submit-answer" class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg shadow-md">Corriger</button>
            </div>
        </form>

        <div id="correction" class="hidden">
            <!-- La correction sera affichée ici -->
        </div>

        @if ($nextQuestion)
            <a href="{{ route('quiz.single-question', ['quiz' => $quiz->id, 'question' => $nextQuestion->id]) }}" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md block text-center mt-4">
                Question Suivante
            </a>
        @endif
    </div>

    <script>
        document.getElementById('submit-answer').addEventListener('click', function() {
            const questionId = document.querySelector('.question').dataset.questionId;
            const selectedAnswers = Array.from(document.querySelectorAll('input[name="answers[]"]:checked')).map(input => input.value);

            fetch(`/quiz/${questionId}/correct`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({ answers: selectedAnswers })
            })
                .then(response => response.json())
                .then(data => {
                    // Mettre à jour l'interface utilisateur avec la correction
                    // Par exemple, afficher les réponses correctes et incorrectes
                })
                .catch(error => console.error('Erreur:', error));
        });
    </script>
@endsection
