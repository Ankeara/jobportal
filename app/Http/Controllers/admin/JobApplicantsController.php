<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\Request;

class JobApplicantsController extends Controller
{
    public function index()
    {
        $jobApplications = JobApplication::orderBy('created_at','DESC')->with(['job','user','employer'])->paginate(10);

        return view('admin.jobapp.job_app',[
            'jobApplications' => $jobApplications
        ]);
    }
}