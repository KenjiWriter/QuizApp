<?php

namespace App\Http\Livewire;

use App\Models\Quiz;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class QuizQuestions extends Component
{
    public  $quizId ,$current_question = 0, $current_result = 0, $quiz,
            $question, $answers,
            $answer1, $answer2, $answer3, $answer4,
            $user_answer, $user_answers, $prev_answer,
            $message = '',
            $finish = false, $results = false,
            $correct_answers = 0, $incorrect_answers = 0, $correct_option,
            $PR;
    
    public function cancelAlert()
    {
        $this->message = '';
    }

    public function showResults()
    {
        $this->results = true;
    }

    public function nextResults()
    {
        if($this->current_result != $this->quiz->number_of_questions) $this->current_result += 1;
    }

    public function backResults()
    {
        if($this->current_result > 0) {
            $this->current_result -= 1;
        } else {
            $this->message = "This is the first question";
        }
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
        $quiz = Quiz::find($this->quizId);
        $this->quiz = $quiz;
        $questions = json_decode($quiz->questions, true);
        $answers = json_decode($quiz->answers, true);

        $user = User::find(Auth::id());
        if(Auth::check()) {
            $quizzes = json_decode($user->quizzes, true);
            if(isset($quizzes[$quiz->id])) {
                $this->PR = $quizzes[$quiz->id][1];
            }
        }

        if($this->current_question == $this->quiz->number_of_questions) {
            $this->finish = true;
            if($this->current_result != $this->quiz->number_of_questions && $this->results == false) {
                foreach($this->user_answers as $key => $user_answer) {
                    if($this->answers[$key][1] == $user_answer) {
                        $this->correct_answers += 1;
                    } else {
                        $this->incorrect_answers += 1;
                    }
                }      
            }
            if($this->correct_answers >= $quiz->needed_to_success) {
                $quiz->successful += 1;
            } else {
                $quiz->fails += 1;
            }
            $quiz->save();
            if(Auth::check()) {
                $user = User::find(Auth::id());
                if($this->correct_answers >= $quiz->needed_to_success) {
                    $success = 1;
                    $data = [$quiz->id => [$success, $this->correct_answers]];
                } else {
                    $success = 0;
                    $data = [$quiz->id => [$success, $this->correct_answers]];
                }
                $data = json_encode($data);
                if(empty($user->quizzes)) {
                    $user->quizzes = $data;
                } else {
                    if(isset($quizzes[$quiz->id])) {
                        // Check if user pass this quiz if no check if now user successed
                        if($quizzes[$quiz->id][0] == 0) {
                            if($this->correct_answers >= $quiz->needed_to_success) $quizzes[$quiz->id][0] = 1;
                        }
                        // Check if user now have higher score then his last best attemp
                        if($quizzes[$quiz->id][1] < $this->correct_answers) $quizzes[$quiz->id][1] = $this->correct_answers;
                    } else {
                        $quizzes[] = [$quiz->id => [$success, $this->correct_answers]];
                    }
                    $user->quizzes = json_encode($quizzes);
                }
                $user->save();
                
            } else {
                $this->message = "To save your scores you need to be login";
            }
        } else {
            $this->question = $questions[$this->current_question];
            $this->answers = $answers;
    
            $this->answer1 = $answers[$this->current_question][0][0];
            $this->answer2 = $answers[$this->current_question][0][1];
            $this->answer3 = $answers[$this->current_question][0][2];
            $this->answer4 = $answers[$this->current_question][0][3];
        }


        if($this->results == true) {
            $this->finish = false;
            if($this->current_result == $this->quiz->number_of_questions) {
                $this->finish = true;
                $this->results = false;
                $this->current_result = 0;
            } else {
                $this->question = $questions[$this->current_result];
                $this->correct_option = $answers[$this->current_result][1];
                $this->user_answer = $this->user_answers[$this->current_result];
    
                $this->answer1 = $answers[$this->current_result][0][0];
                $this->answer2 = $answers[$this->current_result][0][1];
                $this->answer3 = $answers[$this->current_result][0][2];
                $this->answer4 = $answers[$this->current_result][0][3];
            }
        }


        return view('livewire.quiz-questions');
    }
}
