<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Gate;



class UserController extends Controller
{
    /**
     * PASSPORT
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return all users if the user is admin or author
        if (Gate::allows('isAdmin') || Gate::allows('isAuthor')) {
            return User::latest()->paginate(5);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->validate($request,[
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users',
            'password' => 'required|string|min:6',

        ]);

        return User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'type' => $request['type'],
            'bio' => $request['bio'],
            'photo' => $request['photo'],
            'name' => $request['name'],
            'password' => Hash::make($request['password']),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    //load profile data
    public function profile()
    {
        //
        return auth('api')->user();
    }
    
    //update profile data
    public function updateProfile(Request $request)
    {
        $user = auth('api')->user();

        $this->validate($request,[
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users,email,'.$user->id,
            'password' => 'sometimes|required|min:6',
        ]);

        $currentPhoto = $user->photo;

        //if the user modify the photo
        if ($request->photo != $currentPhoto) {
            //extract extension from -> data:image/png;
            $name = time().'.' . explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];
        
            //saving using intervention image
            Image::make($request->photo)->save(public_path('img/profile/').$name);
            $request->merge(['photo' => $name]);

            //delete old photo from server
            $userPhoto = public_path('img/profile/').$currentPhoto;
            if(file_exists($userPhoto)){
                @unlink($userPhoto);
            }
        }

        //if the user updates the pass -> encrypt
        if (!empty($request->password)) {
            $request->merge(['password' => Hash::make($request['password'])]);
        }

        //saving into the database
        $user->update($request->all());
        return['message' => 'Success'];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $user = User::findOrFail($id);

        $this->validate($request,[
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users,email,'.$user->id,
            'password' => 'sometimes|min:6',

        ]);

        $user->update($request->all());

        return ['message'=>'User info updated!'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //GATES -> only admin can delete users
        $this->authorize('isAdmin');
        $user = User::findOrFail($id);

        // delete the user
        $user->delete();

        //return $user;

        return ['message' => 'User Deleted'];
    }

    public function search(){

        if ($search = \Request::get('q')) {
            $users = User::where(function($query) use ($search){
                $query->where('name','LIKE',"%$search%")
                        ->orWhere('email','LIKE',"%$search%");
            })->paginate(20);
        }else{
            $users = User::latest()->paginate(5);
        }

        return $users;
    }
}
