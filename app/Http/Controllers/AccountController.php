<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordEmail;
use Illuminate\Support\Facades\Mail;
use App\Models\Category;
use App\Models\JobApplication;
use App\Models\Jobs;
use App\Models\JobType;
use App\Models\saveJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class AccountController extends Controller
{
    public function registation()
    {
        return view('frontend.account.registation');
    }

    public function processRegistation(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:5|same:confirm_pass',
            'confirm_pass' => "required",
        ]);

        if($validator->passes())
        {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();

            session()->flash("success", 'You have registerd successfully.');

            return response()->json([
                'status' => true,
                'errors' => []
            ]);
        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function login()
    {
        return view('frontend.account.login');
    }

    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => "required",
        ]);

        if($validator->passes())
        {
            if(Auth::attempt(['email' => $request->email, 'password' => $request->password]))
            {
                return redirect()->route('account.profile');
            }else{
                return redirect()->route('account.login')->with("error","Either Email/password is incorrect?");
            }
        }else{
            return redirect()->route('account.login')->withErrors($validator)->withInput($request->only('email'));
        }
    }

    public function profile()
    {
        $id = Auth::user()->id ;
        $user = User::where('id', $id)->first();

        return view("frontend.account.profile",[
            'user' => $user
        ]);
    }

    public function updateProfile(Request $request)
    {
        $id = Auth::user()->id;

        $validator = Validator::make($request->all(),[
            'name' => 'required|min:5|max:30',
            'email' => 'required|email|unique:users,email,'.$id.',id'
        ]);

        if($validator->passes())
        {
            $user = User::find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->description = $request->description;
            $user->mobile = $request->mobile;
            $user->save();

            session()->flash('success', 'Profile updated successfully.');

            return response()->json([
                'status' => true,
                'errors' => []
            ]);
        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

     public function updateProfilePic(Request $request)
    {
        $id = Auth::user()->id;

        $validator = Validator::make($request->all(),[
            'image' => 'required|image'
        ]);

        if($validator->passes())
        {
            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = $id.'-'.time().'.'.$ext;
            $image->move(public_path('/profile/'), $imageName);

            // Delete old image (if exists)
            $oldImage = Auth::user()->image;
            if ($oldImage) {
                File::delete(public_path('/profile/'.$oldImage));
            }
            User::where('id',$id)->update(['image' => $imageName]);

            session()->flash('success', 'Profile updated successfully.');

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

    public function logout()
    {
        Auth::logout();
        return redirect()->route('account.login')->with('success','you are logout successfully');
    }

    public function createJob()
    {
        $categories = Category::orderBy('name','ASC')->where('status',1)->get();
        $jobTypes = JobType::orderBy('name','ASC')->where('status',1)->get();
        return view ('frontend.account.job.create', [
            'categories' => $categories,
            'jobTypes' => $jobTypes,
        ]);
    }

    public function saveJob(Request $request)
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
            $job = new Jobs();
            $job->title = $request->title;
            $job->category_id = $request->category;
            $job->job_type_id = $request->jobTypes;
            $job->user_id = Auth::user()->id;
            $job->vacancy = $request->vacancy;
            $job->salary = $request->salary;
            $job->location = $request->location;
            $job->description = $request->description;
            $job->benefits = $request->benefits;
            $job->responsibility = $request->responsibility;
            $job->qualifications = $request->qualifications;
            $job->keywords = $request->keywords;
            $job->experience = $request->experience;
            $job->company_name = $request->company_name;
            $job->company_location = $request->company_location;
            $job->company_website = $request->company_website;
            $job->save();

            session()->flash('success', 'Job added successfully.');

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

    public function myJob(Request $request)
    {
        $jobs = Jobs::where('user_id',Auth::user()->id)->with('jobType')->paginate(10);
        return view("frontend.account.job.myjobs",[
            'jobs' => $jobs
        ]);
    }

    public function editJob(Request $request, $id)
    {
        $categories = Category::orderBy('name', 'ASC')->where('status', 1)->get();
        $jobTypes = JobType::orderBy('name', 'ASC')->where('status', 1)->get();

        $job = Jobs::where([
            'user_id' => Auth::user()->id,
            'id' => $id
        ])->first();

        if ($job == null) {
            abort(404);
        }

        return view('frontend.account.job.edit', [
            'categories' => $categories,
            'jobTypes' => $jobTypes,
            'job' => $job // Pass the $job variable to the view
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
            $job = Jobs::find($id);
            $job->title = $request->title;
            $job->category_id = $request->category;
            $job->job_type_id = $request->jobTypes;
            $job->user_id = Auth::user()->id;
            $job->vacancy = $request->vacancy;
            $job->salary = $request->salary;
            $job->location = $request->location;
            $job->description = $request->description;
            $job->benefits = $request->benefits;
            $job->responsibility = $request->responsibility;
            $job->qualifications = $request->qualifications;
            $job->keywords = $request->keywords;
            $job->experience = $request->experience;
            $job->company_name = $request->company_name;
            $job->company_location = $request->company_location;
            $job->company_website = $request->company_website;
            $job->save();

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


    public function delete($id)
    {
        $job = Jobs::find($id);

        if (!$job) {
            // Job not found
            session()->flash('error', 'Job not found.');

            return response()->json([
                'status' => false
            ]);
        }

        // Delete the job
        $job->delete();
        session()->flash('success', 'Job deleted successfully.');
        return response()->json([
            'status' => true
        ]);
    }

    // Job applied
    public function myJobApplication()
    {
        $jobApplications = JobApplication::where('user_id', Auth::user()->id)
        ->with(['job', 'job.jobType', 'job.applications'])
        ->orderBy('created_at','DESC')
        ->paginate(10);

        return view('frontend.account.job.my-job-applications', [
            'jobApplications' => $jobApplications
        ]);
    }

    // remove job application
    public function removeJob(Request $request)
    {
        $jobApplication = JobApplication::where([
            'id' => $request->id,
            'user_id' => Auth::user()->id
        ])->first();

        if ($jobApplication == null) {
            session()->flash('error', 'Job application not found.');
            return response()->json([
                'status' => false,
                'message' => 'Job application not found.'
            ]);
        }

        jobApplication::find($request->id)->delete();
        $msg = "Job application removed successfully.";

        session()->flash("success",$msg);

            return response()->json([
                'status' => true,
                'message' => $msg,
            ]);
    }

    // saved job want to apply later
    public function savedJob(){
        $savedJob = saveJob::where([
            'user_id' => Auth::user()->id,
        ])->with(['job', 'job.jobType', 'job.applications'])
        ->orderBy('created_at','DESC')
        ->paginate(10);

        return view('frontend.account.job.saved-job',[
            'savedJob' => $savedJob
        ]);
    }

    // remove job on saved
    public function removeSavedJob(Request $request)
    {
           $id = $request->id;
        $savedJob = saveJob::find($id);

        if ($savedJob == null) {
            session()->flash('error', 'Job not found.');
            return response()->json([
                'status' => false
            ]);
        }

        $savedJob->delete();
        session()->flash("success",'Job removed successfully.');

            return response()->json([
                'status' => true
            ]);
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'old_password' => 'required',
            'new_password' => 'required|min:5',
            'confirm_password' => 'required|same:new_password',
        ]);

        if($validator->fails())
        {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }

        if(Hash::check($request->old_password, Auth::user()->password) == false){
            $msg = "Your old password is incurrect.";
            session()->flash("error",$msg);
            return response()->json([
                'status' => true,
            ]);
        }

        $user = User::find(Auth::user()->id);
        $user->password = Hash::make($request->new_password);
        $user->save();

        $msg = "Your password updated successfully.";

        session()->flash("success",$msg);

            return response()->json([
                'status' => true,
            ]);
    }

    public function forgetPassword()
    {
        return view('frontend.account.forgetpassword');
    }

    public function processForgetPassword(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email|exists:users,email'
        ]);

        if($validator->fails())
        {
            return redirect()->route("account.forgetPassword")->withInput()->withErrors($validator);
        }

        $token = Str::random(60);

        \DB::table("password_reset_tokens")->where('email',$request->email)->delete();

        \DB::table("password_reset_tokens")->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now(),
        ]);

        // Send mail
        $user = User::where('email',$request->email)->first();
        $mailData =[
            'token' => $token,
            'user' => $user,
            'subject' => 'You have requested to change your password.',
        ];

        Mail::to($request->email)->send(new ResetPasswordEmail($mailData));

        return redirect()->route('account.forgetPassword')->with('success','Reset password email has been send to your box.');
    }

    public function resetPassword($tokenString)
    {
        $token = \DB::table('password_reset_tokens')->where('token',$tokenString)->first();

        if($token == null)
        {
            return redirect()->route('account.forgetPassword')->with("error","Invalid token");
        }

        return view('frontend.account.reset-password',[
            'tokenString' => $tokenString
        ]);
    }

    public function processResetPassword(Request $request)
    {
        $token = \DB::table('password_reset_tokens')->where('token',$request->token)->first();

        if($token == null)
        {
            return redirect()->route('account.forgetPassword')->with("error","Invalid token");
        }

        $validator = Validator::make($request->all(),[
            'new_password' => 'required|min:5',
            'confirm_password' => 'required|same:confirm_password'
        ]);

        if($validator->fails())
        {
            return redirect()->route("account.resetPassword",$request->token)->withErrors($validator);
        }

        User::where('email',$token->email)->update([
            'password' => Hash::make($request->new_password),
        ]);

            return redirect()->route('account.login')->with("success","Your are successfully to change your password");

    }
}
