<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use App\Models\TopicCategory;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    /**
     * Display the Topics Page
     *
     * @param  \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $topic_categories = TopicCategory::with('topics')->get();

        return view('application.topics.index', [
            'topic_categories' => $topic_categories,
        ]);
    }
}
