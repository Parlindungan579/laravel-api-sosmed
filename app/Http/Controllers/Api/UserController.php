<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        //get posts
        $posts = User::latest()->paginate(5);

        //return collection of posts as a resource
        return new UserResource(true, 'List Data Posts', $posts);
    }

    /**
     * store
     *
     * @param  mixed $request
     * @return void
     */
    public function store(Request $request)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'phone_number'  => 'required',
            'image'         => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'username'      => 'required',
            'firstname'     => 'required',
            'lastname'      => 'required',
            'date_of_birth' => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/users', $image->hashName());

        //create post
        $post = User::create([
            'phone_number'  => $request->phone_number,
            'image'         => $image->hashName(),
            'username'      => $request->username,
            'firstname'     => $request->firstname,
            'lastname'      => $request->lastname,
            'date_of_birth' => $request->date_of_birth,
        ]);

        //return response
        return new UserResource(true, 'Data Post Berhasil Ditambahkan!', $post);
    }

    /**
     * show
     *
     * @param  mixed $post
     * @return void
     */
    public function show(User $post)
    {
        //return single post as a resource
        return new UserResource(true, 'Data Post Ditemukan!', $post);
    }

    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $post
     * @return void
     */
    public function update(Request $request, User $post)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'phone_number'  => 'required',
            'image'         => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'username'      => 'required',
            'firstname'     => 'required',
            'lastname'      => 'required',
            'date_of_birth' => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //check if image is not empty
        if ($request->hasFile('image')) {

            //upload image
            $image = $request->file('image');
            $image->storeAs('public/users', $image->hashName());

            //delete old image
            Storage::delete('public/users/' . $post->image);

            //update post with new image
            $post->update([
                'phone_number'  => $request->phone_number,
                'image'         => $image->hashName(),
                'username'      => $request->username,
                'firstname'     => $request->firstname,
                'lastname'      => $request->lastname,
                'date_of_birth' => $request->date_of_birth,
            ]);
        } else {

            //update post without image
            $post->update([
                'phone_number'  => $request->phone_number,
                'username'      => $request->username,
                'firstname'     => $request->firstname,
                'lastname'      => $request->lastname,
                'date_of_birth' => $request->date_of_birth,
            ]);
        }

        //return response
        return new UserResource(true, 'Data Post Berhasil Diubah!', $post);
    }

    /**
     * destroy
     *
     * @param  mixed $post
     * @return void
     */
    public function destroy(User $post)
    {
        //delete image
        Storage::delete('public/users/' . $post->image);

        //delete post
        $post->delete();

        //return response
        return new UserResource(true, 'Data Post Berhasil Dihapus!', null);
    }
}
