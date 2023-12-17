@extends('layouts.app')

@section('content')
    <div id="single-question-container" class="max-w-md mx-auto my-10 p-6 bg-white rounded-lg shadow-lg">
        <form id="quiz-form" action="{{ route('quiz.correct-single-question', ['quiz' => $quiz->id, 'question' => $question->id]) }}" method="POST">
            @csrf
            <div class="question mb-4 p-4 bg-gradient-to-r from-green-100 to-blue-200 rounded-lg shadow-2xl">
                <h3 class="font-bold text-xl text-gray-700 mb-4">{{ $question->title }}</h3>
                <div class="propositions">
                    @php
                        $propositions = ['a' => $question->a, 'b' => $question->b, 'c' => $question->c, 'd' => $question->d];
                    @endphp
                    @foreach ($propositions as $key => $value)
                        <label class="prop-label bg-white hover:bg-green-100 border border-green-300 rounded-lg p-3 cursor-pointer shadow-md mb-2 block" for="question-{{ $question->id }}-{{ $key }}">
                            <input type="checkbox" id="question-{{ $question->id }}-{{ $key }}" name="answers[]" value="{{ $key }}" class="hidden">
                            <span>{{ $value }}</span>
                        </label>
                    @endforeach
                </div>
            <button type="button" id="submit-answer" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg shadow-md mb-2 mt-4">Corriger</button>
            </div>
        </form>
        <button id="next-question" class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg shadow-md">Question Suivante</button>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Scroll automatique pour centrer la question lors du chargement
            const questionContainer = document.getElementById('single-question-container');
            questionContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
        });

        document.getElementById('submit-answer').addEventListener('click', function() {
            var form = document.getElementById('quiz-form');
            var formData = new FormData(form);
            var actionUrl = form.getAttribute('action');

            fetch(actionUrl, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
                .then(response => response.json())
                .then(data => {
                    // Réinitialiser les styles pour toutes les réponses
                    document.querySelectorAll('.prop-label').forEach(label => {
                        label.classList.remove('correct-answer', 'incorrect-answer', 'missed-answer', 'bold-text');
                    });

                    // Mettre en évidence les réponses correctes
                    data.correctAnswers.forEach(correctAnswer => {
                        document.querySelectorAll(`input[value="${correctAnswer}"]`).forEach(input => {
                            input.parentElement.classList.add('correct-answer');
                        });
                    });

                    // Mettre en évidence les réponses incorrectes choisies par l'utilisateur
                    var userAnswers = formData.getAll('answers[]');
                    userAnswers.forEach(answer => {
                        var answerElement = document.querySelector(`input[value="${answer}"]`).parentElement;
                        if (!data.correctAnswers.includes(answer)) {
                            answerElement.classList.add('incorrect-answer');
                        }
                        answerElement.classList.add('bold-text');
                    });

                    // Mettre en évidence les bonnes réponses manquées
                    data.correctAnswers.forEach(correctAnswer => {
                        if (!userAnswers.includes(correctAnswer)) {
                            document.querySelector(`input[value="${correctAnswer}"]`).parentElement.classList.add('missed-answer');
                        }
                    });

                    // Désactiver la soumission de formulaire ultérieure et changer le bouton
                    document.getElementById('submit-answer').style.display = 'none';
                    document.getElementById('next-question').style.display = 'block';
                })
                .catch(error => console.error('Erreur:', error));
        });

        document.getElementById('next-question').addEventListener('click', function() {
            window.location.href = "{{ route('quiz.single-question', ['quiz' => $quiz->id]) }}";
        });
    </script>

    <style>
        .prop-label {
            transition: background-color 0.3s, border 0.3s;
            font-size: 1rem; /* Taille de police adaptée */
        }
        .correct-answer {
            background-color: #86f1a1; /* Vert clair */
            border-color: #86f1a1; /* Vert plus foncé */
        }
        .incorrect-answer {
            background-color: #ff747e; /* Rouge clair */
            border-color: #ff747e; /* Rouge plus foncé */
        }
        .missed-answer {
            background-color: #86f1a1; /* Vert clair */
            border: 2px solid #ff747e; /* Contour rouge */
        }
        .bold-text {
            font-weight: bold;
        }
        #submit-answer, #next-question {
            font-size: 1.25rem; /* Boutons plus grands pour une meilleure accessibilité */
        }
    </style>






@endsection
