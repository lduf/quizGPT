{{-- resources/views/generate-questions.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-6">Générer des Questions de Quiz</h2>

        <form id="generate-questions-form" method="POST" action="{{ route('quiz.generate-questions', $quiz->id) }}">
            @csrf

            <div class="mb-4">
                <label for="number_of_questions" class="block text-gray-700 text-sm font-bold mb-2">Nombre de Questions:</label>
                <input type="number" name="number_of_questions" id="number_of_questions" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required min="1", max="5">
            </div>

            <div class="mb-4">
                <label for="special_instructions" class="block text-gray-700 text-sm font-bold mb-2">Instruction spéciale:</label>
                <input type="text" name="special_instructions" id="special_instructions" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" value="None">
            </div>


            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Générer</button>
        </form>
    </div>
@endsection
