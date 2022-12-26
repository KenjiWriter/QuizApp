<div class="text-center">
    <h1 class="text-3xl">Step {{ $step }}/3</h1>
    @if ($step == 1)
        Enter the title and description of your quiz
    @elseif ($step == 2)
        Enter questions and answers for them
    @elseif ($step == 3)
        Summary
    @endif

    <br> <br>
    @if ($message != '')
        <div class="bg-orange-100 border border-orange-400 text-orange-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ $message }}</span>
            <span wire:click="cancelAlert" class="absolute top-0 bottom-0 right-0 px-4 py-3">
            <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
            </span>
        </div> <br>
    @endif
    @if ($step == 1)
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
            Title
            </label>
            <input wire:model="title" class="text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" id="title" type="text" placeholder="Title...">
        </div>
        <div class="mb-4">
            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">
                Description
            </label>
            <textarea wire:model="description" id="description" rows="4" class="text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Write description about what is your quiz..."></textarea>
        </div>
        <div class="inline-flex">
            <button wire:click="next" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
              Next
            </button>
        </div>
    @elseif ($step == 2)
        @if (!empty($questions))
            List of questions: <br>
            @foreach ($questions as $key => $question)
                <span wire:click="backQuestion({{ $key }})" class="text-sky-600 hover:underline cursor-pointer">{{ $key+1 }}</span>,
            @endforeach
        @endif
        <h1 class="text-3xl mb-6">Current question {{ $currentQuestion+1 }}</h1> 
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
            Question
            </label>
            <input wire:model="question" class="text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" id="question" type="text" placeholder="Question...">
        </div> <br>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
            Answer 1
            </label>
            <input wire:model="answer1" class="text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" id="answer1" type="text" placeholder="answer 1...">    
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
            Answer 2
            </label>
            <input wire:model="answer2" class="text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" id="answer2" type="text" placeholder="answer 2...">    
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
            Answer 3
            </label>
            <input wire:model="answer3" class="text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" id="answer3" type="text" placeholder="answer 3...">    
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
            Answer 4
            </label>
            <input wire:model="answer4" class="text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" id="answer4" type="text" placeholder="answer 4...">    
        </div> <br>

        <label for="correct_answer" class="block text-gray-700 text-sm font-bold mb-2">Correct question</label>
        <select wire:model="correctAnswer" id="correct_answer" class="m-auto bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 w-28">
        <option value="1">Answer 1</option>
        <option value="2">Answer 2</option>
        <option value="3">Answer 3</option>
        <option value="4">Answer 4</option>
        </select> <br>


        @if (isset($questions[$currentQuestion]))
            <button wire:click.prevent="remove" class="bg-red-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-3">
                Remove question
            </button> <br>
        @endif
        <button wire:click.prevent="next" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-3">
            @if (isset($questions[$currentQuestion]))
                Save question
            @else
                New question
            @endif
        </button> <br>
        <button wire:click.prevent="prevStep" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-3">
            Previous step
        </button>
        <button wire:click.prevent="summary" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-3">
            Next step
        </button>
        @elseif ($step === 3)
        <label for="successfully_needed" class="block text-gray-700 text-sm font-bold mb-2">Select amount of correct option to successfully completed</label>
        <select wire:model="successfullyNeeded" id="correct_answer" class="m-auto bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 w-28">
        <option selected>Select an option</option>        
        <option value="0">0 (no entry)</option>
        @foreach ($questions as $key => $question)
            <option value="{{ $key+1 }}">{{ $key+1 }} ({{ round((($key+1)/count($questions))*100, 0) }}%)</option>
        @endforeach    
        </select> <br>
        <div class="items-center mb-4">
            <input wire:model="timer" id="default-checkbox" type="checkbox" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
            <label for="default-checkbox" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Timer</label>
        </div>
        @if($timer == 1)
            <label for="time_per_question" class="block text-gray-700 text-sm font-bold mb-2">Select time per one question</label>
            <select wire:model="time_per_question" id="correct_answer" class="m-auto bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 w-28">
            <option selected>Select an option</option>        
            <option value="10">10 seconds</option>
            <option value="30">30 seconds</option>
            <option value="60">60 seconds</option>
            </select> <br>
        @endif
        <button wire:click.prevent="postQuiz" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-3">
            Publish your quiz
        </button>
    @endif
</div>
