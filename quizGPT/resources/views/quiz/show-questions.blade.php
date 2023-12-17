@extends('layouts.app')

@section('content')
    <div class="container mx-auto my-4">
        <h1 class="text-xl font-bold mb-4">Questions du Quiz: {{ $quiz->title }}</h1>

        @foreach ($questions as $question)
            <div class="mb-4 p-4 bg-white rounded shadow">
                <p class="font-bold">{{ $question->title }}</p>
                <ul class="list-disc ml-8">
                    <li>{{ $question->a }}</li>
                    <li>{{ $question->b }}</li>
                    <li>{{ $question->c }}</li>
                    <li>{{ $question->d }}</li>
                </ul>
            </div>
        @endforeach
    </div>
@endsection
