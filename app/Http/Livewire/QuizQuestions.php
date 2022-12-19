<?php

namespace App\Http\Livewire;

use App\Models\Quiz;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class QuizQuestions extends Component
{
    public  $quizId ,$current_question = 0, $current_result = 0, $quiz,
            $user_answer, $user_answers, $prev_answer,
            $message = '',
            $finish = false, $results = false, $resultStatus = false,
            $correct_answers = 0, $incorrect_answers = 0, $correct_option,
            $PR, $PR_points, $time = -1, $points,
            $Quizquestions, $shuffel = false;
    
    public function cancelAlert()
    {
        $this->message = '';
    }

    public function time()
    {
        if($this->time > 0) {
            $this->time -= 1;
        } else {
            $this->user_answers[$this->current_question] = ['NOTIME', 0];
            unset($this->user_answer);
            if($this->current_question != $this->quiz->number_of_questions) $this->current_question += 1;
            $this->time = $this->quiz->timer_per_question;
        }
    }

    public function showResults()
    {
        $this->results = true;
        $this->resultStatus = true;
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
            if($this->quiz->timer == 1) {
                $time_left = round(($this->time/$this->quiz->timer_per_question)*100, 0);
            } else {
                $time_left = 0;
            }
            if(isset($this->user_answers[$this->current_question])) {
                $this->user_answers[$this->current_question] = [$this->user_answer, $time_left];
            } else {
                $this->user_answers[] = [$this->user_answer, $time_left];
            }
            unset($this->user_answer);
            if($this->current_question != $this->quiz->number_of_questions) $this->current_question += 1;
            if($this->quiz->timer == 1) {
                $this->time = $this->quiz->timer_per_question;
            }
            if(isset($this->user_answers[$this->current_question])) {
                $this->user_answer = $this->user_answers[$this->current_question][0];
            }
        } else {
            $this->message = "You cannot skip this question";
        }
    }

    public function back()
    {
        if($this->current_question > 0) {
            $this->current_question -= 1;
            $this->user_answer = $this->user_answers[$this->current_question][0];
        } else {
            $this->message = "This is the first question";
        }
    }

    public function render()
    {
        $quiz = Quiz::find($this->quizId);
        $this->quiz = $quiz;
        if($this->time == -1) {
            $this->time = $quiz->timer_per_question;
        }
        $questions = json_decode($quiz->questions, true);
        $answers = json_decode($quiz->answers, true);
        
        foreach($questions as $key => $question) {
            $this->Quizquestions[] = ['question' => $question, 'answers' => $answers[$key][0], 'answer' => $answers[$key][1]];
        }
        if($this->shuffel == false) {
            if(shuffle($this->Quizquestions)) {
                $this->shuffel = true;
            }
        }

        $user = User::find(Auth::id());
        if(Auth::check()) {
            $quizzes = json_decode($user->quizzes, true);
            if(isset($quizzes[$quiz->id])) {
                $this->PR = $quizzes[$quiz->id][1];
                $this->PR_points = $quizzes[$quiz->id][2];
            }
        }

        if($this->current_question == $this->quiz->number_of_questions) {
            $this->finish = true;

            if($this->resultStatus == false) {
                foreach($this->user_answers as $key => $user_answer) {
                    if($this->Quizquestions[$key]['answer'] == $user_answer[0]) {
                        $this->correct_answers += 1;
                        if($user_answer[1] > 90) {
                            $this->points += 100;
                        } else {
                            $this->points += $user_answer[1];
                        }
                    } else {
                        $this->incorrect_answers += 1;
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
                        $data = [$quiz->id => [$success, $this->correct_answers, $this->points]];
                    } else {
                        $success = 0;
                        $data = [$quiz->id => [$success, $this->correct_answers, $this->points]];
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
                            // Check if user now have higher score then in his last best attemp
                            if($quizzes[$quiz->id][1] < $this->correct_answers) $quizzes[$quiz->id][1] = $this->correct_answers;

                            // Check if user now have more points then in his last best attemp
                            if($quizzes[$quiz->id][2] < $this->points) $quizzes[$quiz->id][2] = $this->points;
                        } else {
                            $quizzes[] = [$quiz->id => [$success, $this->correct_answers, $this->points]];
                        }
                        $user->quizzes = json_encode($quizzes);
                    }
                    $user->save();
                    
                } else {
                    $this->message = "To save your scores you need to be login";
                }
            }

            if($this->results == true) {
                $this->finish = false;
                if($this->current_result == $this->quiz->number_of_questions) {
                    $this->finish = true;
                    $this->results = false;
                    $this->current_result = 0;
                }
            }
        }
        return view('livewire.quiz-questions');
    }
}
