<div>
    @if ($message != '')
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">{{ $message }}</span>
            <span wire:click="cancelAlert" class="absolute top-0 bottom-0 right-0 px-4 py-3">
            <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
            </span>
        </div>
    @endif
    @if ($finish == true)
        <h2 class="text-2xl">Results</h2>
        <span class="text-lime-500">Correct: {{ $correct_answers }}</span>
        <span class="text-rose-700">Incorrect: {{ $incorrect_answers}}</span> <br>
        <a class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" href="{{ route('home') }}">Home</a> <a class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" href="{{ route('Quiz', $quiz->id) }}">Again</a>
    @else
        <span class="text-3xl">{{ $question }}</span> <br>
        <number class="text-blue-800">{{ $current_question+1 }}/{{ $quiz->number_of_questions }}</number>
        <br> <br>
        <div class="items-center mb-3">
            <input @if($user_answer == $answer1) checked @endif wire:model="user_answer" id="answer1" type="radio" value="{{ $answer1 }}" name="answer" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
            <label for="answer1" class="ml-2 text-sm font-medium text-gray-900">{{ $answer1 }}</label>
        </div>
        <div class="items-center mb-3">
            <input @if($user_answer == $answer2) checked @endif wire:model="user_answer" id="answer2" type="radio" value="{{ $answer2 }}" name="answer" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
            <label for="answer2" class="ml-2 text-sm font-medium text-gray-900">{{ $answer2 }}</label>
        </div>
        <div class="items-center mb-3">
            <input @if($user_answer == $answer3) checked @endif wire:model="user_answer" id="answer3" type="radio" value="{{ $answer3 }}" name="answer" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
            <label for="answer3" class="ml-2 text-sm font-medium text-gray-900">{{ $answer3 }}</label>
        </div>
        <div class="items-center mb-5">
            <input @if($user_answer == $answer4) checked @endif wire:model="user_answer" id="answer4" type="radio" value="{{ $answer4 }}" name="answer" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
            <label for="answer4" class="ml-2 text-sm font-medium text-gray-900">{{ $answer4 }}</label>
        </div>

        <div class="inline-flex">
            @if ($current_question != 0)
                <button wire:click.prevent="back" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-l">
                    Prev
                </button>        
            @endif
            <button wire:click.prevent="next" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-r">
                @if ($current_question+1 == $quiz->number_of_questions)
                    Finish quiz
                @else
                    Next
                @endif
            </button>
        </div>
    @endif
</div>