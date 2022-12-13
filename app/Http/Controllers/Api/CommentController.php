<?php

namespace App\Http\Controllers\Api;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        //get posts
        $posts = Comment::latest()->paginate(5);

        //return collection of posts as a resource
        return new CommentResource(true, 'List Data Posts', $posts);
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
            'content'       => 'required',
            'user_id'       => 'required',
            'post_id'       => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/comments', $image->hashName());

        //create post
        $post = Comment::create([
            'content'       => $request->content,
            'user_id'       => $request->user_id,
            'post_id'       => $request->post_id,
        ]);

        //return response
        return new CommentResource(true, 'Data Post Berhasil Ditambahkan!', $post);
    }

    /**
     * show
     *
     * @param  mixed $post
     * @return void
     */
    public function show(Comment $post)
    {
        //return single post as a resource
        return new CommentResource(true, 'Data Post Ditemukan!', $post);
    }

    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $post
     * @return void
     */
    public function update(Request $request, Comment $post)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'content'       => 'required',
            'user_id'       => 'required',
            'post_id'       => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $post->update([
            'content'       => $request->content,
            'user_id'       => $request->user_id,
            'post_id'       => $request->post_id,
        ]);


        //return response
        return new CommentResource(true, 'Data Post Berhasil Diubah!', $post);
    }

    /**
     * destroy
     *
     * @param  mixed $post
     * @return void
     */
    public function destroy(Comment $post)
    {
        //delete image
        Storage::delete('public/comments/' . $post->image);

        //delete post
        $post->delete();

        //return response
        return new CommentResource(true, 'Data Post Berhasil Dihapus!', null);
    }
}
