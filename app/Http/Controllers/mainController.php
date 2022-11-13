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

    public function Quiz($id)
    {
        $quiz = Quiz::where('id', $id)->get();
        return view('quiz',[
            'quiz' => $quiz[0]
        ]);
    }
}
