<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\Request;

class mainController extends Controller
{
    public function Show()
    {
        $quizzes = Quiz::select('id', 'name', 'description')->paginate(8);
        return view('index', [
            'quizzes' => $quizzes, 
        ]);
    }

    public function Create()
    {
        return view('create.quiz');
    }

    public function Quiz($id)
    {
        $quiz = Quiz::find($id);
        return view('quiz',[
            'quiz' => $quiz
        ]);
    }
}
