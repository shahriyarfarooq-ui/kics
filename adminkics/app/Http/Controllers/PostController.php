<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KicPost;
use App\Models\Designation;

class PostController extends Controller
{
    // Show all posts
    public function index()
    {
        $posts = KicPost::with('designation')->orderBy('post_seqno', 'ASC')->get();
        $designations = Designation::orderBy('designation_name')->get();
        return view('admin.post', compact('posts', 'designations'));
    }

    // Store new post
    public function store(Request $request)
    {
        $request->validate([
            'post_name' => 'required|string|max:255',
            'post_seqno' => 'required|integer',
            'des_id' => 'required|integer|exists:designation,designation_id',
        ]);

        KicPost::create([
            'post_name' => $request->post_name,
            'post_seqno' => $request->post_seqno,
            'des_id' => $request->des_id,
        ]);

        return redirect()->back()->with('success', 'Post added successfully.');
    }

    // Update post
    public function update(Request $request, $id)
    {
        $post = KicPost::findOrFail($id);

        $request->validate([
            'post_name' => 'required|string|max:255',
            'post_seqno' => 'required|integer',
            'des_id' => 'required|integer|exists:designation,designation_id',
        ]);

        $post->update([
            'post_name' => $request->post_name,
            'post_seqno' => $request->post_seqno,
            'des_id' => $request->des_id,
        ]);

        return redirect()->back()->with('success', 'Post updated successfully.');
    }

    // Delete post
    public function destroy($id)
    {
        $post = KicPost::findOrFail($id);
        $post->delete();
        return redirect()->back()->with('success', 'Post deleted successfully.');
    }
}
