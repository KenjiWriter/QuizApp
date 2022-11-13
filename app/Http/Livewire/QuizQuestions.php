<?php

namespace App\Http\Livewire;

use App\Models\Quiz;
use Livewire\Component;

class QuizQuestions extends Component
{
    public $current_question = 0, $quiz,
            $question, $answers,
            $answer1, $answer2, $answer3, $answer4,
            $user_answer, $user_answers, $prev_answer,
            $message = '', $finish = false, $correct_answers = 0, $incorrect_answers = 0;
    
    public function cancelAlert()
    {
        $this->message = '';
    }

    public function next()
    {
        if(!empty($this->user_answer)) {
            $this->cancelAlert();
            if(isset($this->user_answers[$this->current_question])) {
                $this->user_answers[$this->current_question] = $this->user_answer;
            } else {
                $this->user_answers[] = $this->user_answer;
            }
            unset($this->user_answer);
            if($this->current_question != $this->quiz->number_of_questions) $this->current_question += 1;
            if(isset($this->user_answers[$this->current_question])) {
                $this->user_answer = $this->user_answers[$this->current_question];
            }
        } else {
            $this->message = "You cannot skip this question";
        }
    }

    public function back()
    {
        if($this->current_question > 0) {
            $this->current_question -= 1;
            $this->user_answer = $this->user_answers[$this->current_question];
        } else {
            $this->message = "This is the first question";
        }
    }

    public function render()
    {
        $quiz = Quiz::find('2');
        $this->quiz = $quiz;
        $questions = json_decode($quiz->questions, true);
        $answers = json_decode($quiz->answers, true);

        if($this->current_question == $this->quiz->number_of_questions) {
            $this->finish = true;
            foreach($this->user_answers as $key => $user_answer) {
                    if($this->answers[$key][1] == $user_answer) {
                        $this->correct_answers += 1;
                    } else {
                        $this->incorrect_answers += 1;
                    }
            }
        } else {
            $this->question = $questions[$this->current_question];
            $this->answers = $answers;
    
            $this->answer1 = $answers[$this->current_question][0][0];
            $this->answer2 = $answers[$this->current_question][0][1];
            $this->answer3 = $answers[$this->current_question][0][2];
            $this->answer4 = $answers[$this->current_question][0][3];
        }


        return view('livewire.quiz-questions');
    }
}
