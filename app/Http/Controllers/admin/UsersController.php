<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at','DESC')->paginate(5);
        return view('admin.users.lists',[
            'users' => $users
        ]);
    }

    public function edit($id)
    {
        $users = User::find($id);
        return view('admin.users.edit',[
            'users' => $users
        ]);
    }

    public function update($id,Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|min:5|max:30',
            'email' => 'required|email|unique:users,email,'.$id.',id'
        ]);

        if($validator->passes())
        {
            $users = User::find($id);
            $users->name = $request->name;
            $users->email = $request->email;
            $users->description = $request->description;
            $users->mobile = $request->mobile;
            $users->save();
           session()->flash('success','User information updated successfully.');
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
   public function deleteUser(Request $request)
    {
        $id = $request->id;
        $deleteUser = User::find($id);

        if ($deleteUser == null) {
            session()->flash('error', 'User not found.');
            return response()->json([
                'status' => false
            ]);
        }

       // Check if the user to be deleted is an admin
        if ($deleteUser->role == 'admin') {
            // Check if the logged-in user is trying to delete themselves
            if (auth()->user()->id == $deleteUser->id) {
                session()->flash('error', "You can't delete your own account.");
                return response()->json([
                    'status' => false
                ]);
            }
        }

        $deleteUser->delete();
        session()->flash("success",'User deleted successfully.');
            return response()->json([
                'status' => true
            ]);
    }
}
