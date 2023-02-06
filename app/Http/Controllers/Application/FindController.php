<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FindController extends Controller
{
    /**
     * Display the Find Page
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $source = $request->source;
        $place=$request->place;
        $topic = get_topic_name($request->topic);
        $category = get_topic_category_name($request->category);
        return view('application.find.index', [
            'source' => $source,
            'place'=>$place,
            'topic' => $topic,
            'category' => $category,
        ]);
    }
}
