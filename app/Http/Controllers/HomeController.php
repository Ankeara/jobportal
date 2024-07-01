<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Jobs;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::where('status', 1)->orderBy('name','ASC')->take(6)->get();
        $newCategories = Category::where('status', 1)->orderBy('name','ASC')->take(6)->get();
        $featuredJob = Jobs::where('status', 1)->with('jobType')->orderBy('created_at','DESC')->where('isFeatured', 1)->take(6)->get();
        $latestJob = Jobs::where('status', 1)->with('jobType')->orderBy('created_at','DESC')->take(6)->get();
        return view ('frontend.home', [
            'categories' => $categories,
            'featuredJob' => $featuredJob,
            'latestJob' => $latestJob,
            'newCategories' => $newCategories
        ]);
    }
}