<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Jobs;
use App\Models\JobType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class listJobController extends Controller
{
    public function index()
    {
        $job = Jobs::orderBy('created_at',"DESC")->with(['user','applications'])->paginate(10);
        return view('admin.jobs.job',[
            'job' => $job
        ]);
    }
    public function editJob($id)
    {
        $jobs = Jobs::findOrFail($id);

        $categories = Category::orderBy('name','ASC')->where('status', 1)->get();
        $jobTypes = JobType::orderBy('name', 'ASC')->where('status', 1)->get();

        return view('admin.jobs.edit',[
            'categories' => $categories,
            'jobTypes' => $jobTypes,
            'jobs' => $jobs
        ]);
    }

     public function updateJob(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'title' => 'required|min:5|max:200',
            'category' => 'required',
            'jobTypes' => 'required',
            'vacancy' => 'required|integer',
            'location' => 'required|max:100',
            'description' => 'required',
            'company_name' => 'required|min:3|max:100',
        ]);

        if($validator->passes())
        {
            $jobs = Jobs::find($id);
            $jobs->title = $request->title;
            $jobs->category_id = $request->category;
            $jobs->job_type_id = $request->jobTypes;
            $jobs->vacancy = $request->vacancy;
            $jobs->salary = $request->salary;
            $jobs->location = $request->location;
            $jobs->description = $request->description;
            $jobs->benefits = $request->benefits;
            $jobs->responsibility = $request->responsibility;
            $jobs->qualifications = $request->qualifications;
            $jobs->keywords = $request->keywords;
            $jobs->experience = $request->experience;
            $jobs->company_name = $request->company_name;
            $jobs->company_location = $request->company_location;
            $jobs->company_website = $request->company_website;
            $jobs->status = $request->status;
            $jobs->isFeatured = (!empty($request->isFeatured)) ? $request->isFeatured : 0;
            $jobs->save();

            session()->flash('success', 'Job updated successfully.');

            return response()->json([
                'status' => true,
                'errors' => []
            ]);
        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function deleteJob(Request $request)
    {
        $id = $request->id;
        $deleteJob = Jobs::find($id);

        if ($deleteJob == null) {
            session()->flash('error', 'User not found.');
            return response()->json([
                'status' => false
            ]);
        }

        $deleteJob->delete();
        session()->flash("success",'User deleted successfully.');
            return response()->json([
                'status' => true
            ]);
    }
}