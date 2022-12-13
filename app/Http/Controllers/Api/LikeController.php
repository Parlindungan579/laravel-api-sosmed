<?php

namespace App\Http\Controllers\Api;

use App\Models\Like;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\LikeResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class LikeController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        //get posts
        $posts = Like::latest()->paginate(5);

        //return collection of posts as a resource
        return new LikeResource(true, 'List Data Posts', $posts);
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
            'post_id'   => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/likes', $image->hashName());

        //create post
        $post = Like::create([
            'user_id'   => $request->user_id,
            'post-id'   => $request->post_id,
        ]);

        //return response
        return new LikeResource(true, 'Data Post Berhasil Ditambahkan!', $post);
    }

    /**
     * show
     *
     * @param  mixed $post
     * @return void
     */
    public function show(Like $post)
    {
        //return single post as a resource
        return new LikeResource(true, 'Data Post Ditemukan!', $post);
    }

    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $post
     * @return void
     */
    public function update(Request $request, Like $post)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'user_id'   => 'required',
            'post_id'   => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $post->update([
            'user_id'   => $request->user_id,
            'post-id'   => $request->post_id,
        ]);


        //return response
        return new LikeResource(true, 'Data Post Berhasil Diubah!', $post);
    }

    /**
     * destroy
     *
     * @param  mixed $post
     * @return void
     */
    public function destroy(Like $post)
    {
        //delete image
        Storage::delete('public/likes/' . $post->image);

        //delete post
        $post->delete();

        //return response
        return new LikeResource(true, 'Data Post Berhasil Dihapus!', null);
    }
}
