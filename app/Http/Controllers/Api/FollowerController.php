<?php

namespace App\Http\Controllers\Api;

use App\Models\Follower;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\FollowerResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FollowerController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        //get posts
        $posts = Follower::latest()->paginate(5);

        //return collection of posts as a resource
        return new FollowerResource(true, 'List Data Posts', $posts);
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
            'user_id'   => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/followers', $image->hashName());

        //create post
        $post = Follower::create([
            'user_id'   => $request->user_id,
        ]);

        //return response
        return new FollowerResource(true, 'Data Post Berhasil Ditambahkan!', $post);
    }

    /**
     * show
     *
     * @param  mixed $post
     * @return void
     */
    public function show(Follower $post)
    {
        //return single post as a resource
        return new FollowerResource(true, 'Data Post Ditemukan!', $post);
    }

    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $post
     * @return void
     */
    public function update(Request $request, Follower $post)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'user_id'   => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $post->update([
            'user_id'   => $request->user_id,
        ]);


        //return response
        return new FollowerResource(true, 'Data Post Berhasil Diubah!', $post);
    }

    /**
     * destroy
     *
     * @param  mixed $post
     * @return void
     */
    public function destroy(Follower $post)
    {
        //delete image
        Storage::delete('public/followers/' . $post->image);

        //delete post
        $post->delete();

        //return response
        return new FollowerResource(true, 'Data Post Berhasil Dihapus!', null);
    }
}
