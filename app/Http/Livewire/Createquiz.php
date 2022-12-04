<?php

namespace App\Http\Livewire;

use App\Models\Quiz;
use Livewire\Component;

class Createquiz extends Component
{
    public $step = 1, $message = "",
        $title, $description,
        $currentQuestion = 0, $questions = [],
        $question, $answer1, $answer2, $answer3, $answer4, $correctAnswer,
        $successfullyNeeded;
    
    public function cancelAlert()
    {
        $this->message = '';
    }

    public function postQuiz()
    {
        if(!empty($this->successfullyNeeded)) {
            $quiz = new Quiz;
            foreach($this->questions as $question) {
                $questions[] = $question['question'];
                $answers[] = [[$question['ans1'], $question['ans2'], $question['ans3'], $question['ans4']], $question['correctAns']];
            }
    
            $quiz->name = $this->title;
            $quiz->description = $this->description;
    
            $quiz->questions = json_encode($questions);
            $quiz->answers = json_encode($answers);
            $quiz->number_of_questions = count($this->questions);
            $quiz->needed_to_success = $this->successfullyNeeded;
            $quiz->save();
            return redirect()->route('home');
        }
    }

    public function summary()
    {
        if(count($this->questions) < 3) {
            $this->message = "There must be no less than 3 questions in the quiz";
        } else {
            $this->step = 3;
        }
    }

    public function backQuestion($key)
    {
        $question = $this->questions[$key];
        $this->currentQuestion = $key;
        
        $this->question = $question['question'];

        $this->answer1 = $question['ans1'];
        $this->answer2 = $question['ans2'];
        $this->answer3 = $question['ans3'];
        $this->answer4 = $question['ans4'];

        $this->correctAnswer = $question['correctAns'];
    }

    public function prevStep()
    {
        if($this->step != 1) {
            $this->step -= 1;
        } else {
            $this->message = "This is the first step";
        }
    }

    public function next()
    {
        if($this->step == 1) {
            $valid = true;
            if(empty($this->description) || empty($this->title)) {
                $this->message = "Title and description cannot be empty";
                $valid = false;
            }

            if(strlen($this->title) > 32 ) {
                $this->message = "Title cannot be longer then 32 characters";
                $valid = false;
            }

            if(strlen($this->description) > 255 ) {
                $this->message = "Description cannot be longer then 255 characters";
                $valid = false;
            }

            if($valid == true) {
                $this->step += 1; 
                $this->message = "";
            }
        } elseif ($this->step == 2) {
            $valid = true;
            if(empty($this->question) || empty($this->answer1) || empty($this->answer2) || empty($this->answer3) || empty($this->answer4) || empty($this->correctAnswer)) {
                $this->message = "Question and answers must be filled in";
                $valid= false;
            }

            if (strlen($this->question) > 255 || strlen($this->answer1) > 255 || strlen($this->answer2) > 255 || strlen($this->answer3) > 255 || strlen($this->answer4) > 255) {
                $this->message = "Question and answers cannot be longer then 255 characters";
                $valid= false;
            }

            if($valid == true) {
                $correctAns = 'answer'.$this->correctAnswer;
                if(isset($this->questions[$this->currentQuestion])) {
                    $question = ['question' => $this->question, 'ans1' => $this->answer1, 'ans2' => $this->answer2, 'ans3' => $this->answer3, 'ans4' => $this->answer4, 'correctAns' => $this->$correctAns];
                    $this->questions[$this->currentQuestion] = $question;
                    $this->currentQuestion = count($this->questions);
                } else {
                    $question = ['question' => $this->question, 'ans1' => $this->answer1, 'ans2' => $this->answer2, 'ans3' => $this->answer3, 'ans4' => $this->answer4, 'correctAns' => $this->$correctAns];
                    $this->questions[] = $question;
                    $this->currentQuestion += 1;
                }
                $this->question = '';
                $this->answer1 = '';
                $this->answer2 = '';
                $this->answer3 = '';
                $this->answer4 = '';
                $this->correctAnswer = '';
            }
        }
    }

    public function render()
    {
        return view('livewire.createquiz');
    }
}
