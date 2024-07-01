<?php

namespace App\Http\Controllers;

use App\Mail\JobNotificationEmail;
use App\Models\Category;
use App\Models\JobApplication;
use App\Models\Jobs;
use App\Models\JobType;
use App\Models\saveJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('status', 1)->get();
        $jobTypes = JobType::where('status', 1)->get();
        $job = Jobs::where('status', 1);

        if (!empty($request->keywords)) {
            $job = $job->where(function ($query) use ($request) {
                $query->orWhere('title', 'like', '%' . $request->keywords . '%');
                $query->orWhere('keywords', 'like', '%' . $request->keywords . '%');
            });
        }

        if (!empty($request->location)) {
            $job = $job->where('location',$request->location);
        }


        if (!empty($request->category)) {
            $job = $job->where('category_id',$request->category);
        }

        $jobTypeArr = [];
        if (!empty($request->jobType)) {
            $jobTypeArr = explode(',', $request->jobType);
            $job = $job->whereIn('job_type_id', $jobTypeArr);
        }

         if (!empty($request->experience)) {
            $job = $job->where('experience',$request->experience);
        }

        $job = $job->with('jobType'); // Fetch the results

        if($request->sort == '0')
        {
            $job = $job->orderBy('created_at','ASC');
        }else{
            $job = $job->orderBy('created_at','DESC');
        }
        $job = $job->paginate(5);

        return view('frontend.jobs', [
            'categories' => $categories,
            'jobTypes' => $jobTypes,
            'job' => $job,
            'jobTypeArr' => $jobTypeArr,
        ]);
    }

    public function detail( $id)
    {
        $job = Jobs::where([
            'id' => $id,
            'status' => 1
        ])->with(['jobType','category'])->first();

        $count = 0;
        if(Auth::user())
        {
            $count = saveJob::where([
                'user_id' => Auth::user()->id,
                'job_id' => $id
                ])->count();
        }

        // fix on if user login not yet user can see all detail job
        $applications = JobApplication::where('job_id',$id)->with('user')->get();

        return view('frontend.jobDetail',[
            "job" => $job,
            'count' => $count,
            'applications' => $applications
        ]);
    }

    public function applyJob(Request $request)
    {
        $id = $request->id;

        $job = Jobs::where('id',$id)->first();

        // if job not fount
        if($job == null)
        {
            $msg = "Job does not exist";
            session()->flash("error",$msg);

            return response()->json([
                'status' => false,
                'message' => $msg,
            ]);
        }

        // you cant apply on your own job
        $employer_id = $job->user_id;
        if($employer_id == Auth::user()->id)
        {
            $msg = "you cant apply on your own job";
            session()->flash("error",$msg);

            return response()->json([
                'status' => false,
                'message' => $msg,
            ]);
        }

        $jobAppCount = JobApplication::where([
            'user_id' => Auth::user()->id,
            'job_id' => $id,
        ])->count();

        if($jobAppCount > 0)
        {
            $msg = "you already applied on this job.";
            session()->flash("error",$msg);

            return response()->json([
                'status' => false,
                'message' => $msg,
            ]);
        }

        $application = new JobApplication();
        $application->job_id = $id;
        $application->user_id = Auth::user()->id;
        $application->employer_id = $employer_id;
        $application->applied_date = now();
        $application->save();

        $employer = User::where('id', $employer_id)->first();
        $mailData = [
            'employer' => $employer,
            'user' => Auth::user(),
            'job' => $job,
        ];
        Mail::to($employer->email)->send(new JobNotificationEmail($mailData));

        $msg = "you have successfully to applied.";

        session()->flash("success",$msg);

            return response()->json([
                'status' => true,
                'message' => $msg,
            ]);
    }

    public function saveJob(Request $request)
    {
        $id = $request->id;

        $job = Jobs::find($id);

        // Check if job not found
        if($job == null)
        {
            $msg = "Job does not found";
            session()->flash("error",$msg);

            return response()->json([
                'status' => false,
                'message' => $msg,
            ]);

        }
        // Check if job saved
        $count = saveJob::where([
            'user_id' => Auth::user()->id,
            'job_id' => $id
        ])->count();

        if($count > 0 )
        {
            $msg = "You already saved this job.";
        session()->flash("error",$msg);

        return response()->json([
            'status' => false,
            'message' => $msg,
        ]);
        }

        $saveJob = new saveJob();
        $saveJob->job_id = $id;
        $saveJob->user_id = Auth::user()->id;
        $saveJob->save();
         $msg = "Your job saved successfully.";
        session()->flash("success",$msg);

        return response()->json([
            'status' => true,
            'message' => $msg,
        ]);
    }
}