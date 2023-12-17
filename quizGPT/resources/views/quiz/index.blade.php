@extends('layouts.app')

@section('content')
    <div class="text-center">
        <h2 class="text-2xl font-bold mb-6">Bienvenue sur QuizGPT</h2>
        <p class="mb-6">Choisissez un quiz pour tester vos connaissances ou créez-en un nouveau.</p>

        <div class="grid md:grid-cols-3 gap-4 mb-6">
            @foreach ($quizzes as $quiz)
                <div class="bg-white rounded-lg shadow-lg p-4 relative hover:scale-105 transform transition-all duration-300">
                    <h3 class="font-bold text-xl mb-2"><a href="{{ route("quiz.show-questions", $quiz->id) }}">{{ $quiz->title }}</a></h3>
                    <p>{{ $quiz->subject }}</p>
                    <p class="text-sm text-gray-600 mb-2">Questions: {{ $quiz->questions->count() }}</p> <!-- Assuming you have the 'questions_count' attribute -->
                    <div class="flex justify-between">
                            <button onclick="window.location='{{ route('quiz.single-question', ['quiz' => $quiz->id]) }}'" class="bg-cyan-500 hover:bg-cyan-700 text-white font-bold py-2 px-4 rounded">
                                Unique
                            </button>
                        <button onclick="window.location='{{ route('quiz.show', ['quiz' => $quiz->id]) }}'" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Commencer
                        </button>
                        <button onclick="window.location='{{ route('quiz.generate-questions-view', ['quiz' => $quiz->id]) }}'" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                            Ajouter
                        </button>
                    </div>

                    @if($quiz->status === 'pending')
                        <div class="loader absolute top-0 right-0 bottom-0 left-0 bg-white bg-opacity-75 flex items-center justify-center">
                            <div class="spin"></div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <button onclick="window.location='{{ route('quiz.create') }}'" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
            Créer un Nouveau Quiz
        </button>
    </div>

    {{-- Styles pour la roue tournante et autres effets --}}
    <style>
        /* Styles pour le loader */
        .loader {
            border: 4px solid #f3f3f3;
            border-radius: 50%;
            border-top: 4px solid blue;
            width: 40px;
            height: 40px;
            -webkit-animation: spin 2s linear infinite;
            animation: spin 2s linear infinite;
        }

        @-webkit-keyframes spin {
            0% { -webkit-transform: rotate(0deg); }
            100% { -webkit-transform: rotate(360deg); }
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Styles pour les hover des cartes */
        .shadow-lg:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
    </style>
@endsection
@push('scripts')
    <script>
        @foreach ($quizzes as $quiz)
        if (document.getElementById('quiz-{{ $quiz->id }}')) {
            setInterval(function() {
                fetch(`/quiz-status/{{ $quiz->id }}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.status !== 'pending') {
                            const loader = document.getElementById('loader-{{ $quiz->id }}');
                            if (loader) {
                                loader.style.display = 'none';
                            }
                        }
                    })
                    .catch(error => console.error('Erreur:', error));
            }, 2000); // Mettez à jour toutes les 2 secondes
        }
        @endforeach
    </script>
@endpush
