<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::latest()->paginate(10);
        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'link' => [
                'required',
                'string',
                'max:255',
                'regex:/^search\?(category=\d+(&type=\d+)?|type=\d+(&category=\d+)?)$/'
            ],
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'link.regex' => 'The link must start with search? and contain category and/or type parameters, e.g. search?category=1&type=2',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('posts_photos', 'public');
        }

        Post::create([
            'name' => $validatedData['name'],
            'link' => $validatedData['link'],
            'photo_path' => $photoPath,
        ]);

        return redirect()->route('posts.index')->with('success', 'Post created successfully.');
    }

    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'link' => [
                'required',
                'string',
                'max:255',
                'regex:/^search\?(category=\d+(&type=\d+)?|type=\d+(&category=\d+)?)$/'
            ],
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'link.regex' => 'The link must start with search? and contain category and/or type parameters, e.g. search?category=1&type=2',
        ]);

        $photoPath = $post->photo_path;
        if ($request->hasFile('photo')) {
            if ($photoPath) {
                Storage::disk('public')->delete($photoPath);
            }
            $photoPath = $request->file('photo')->store('posts_photos', 'public');
        }

        $post->update([
            'name' => $validatedData['name'],
            'link' => $validatedData['link'],
            'photo_path' => $photoPath,
        ]);

        return redirect()->route('posts.index')->with('success', 'Post updated successfully.');
    }

    public function destroy(Post $post)
    {
        if ($post->photo_path) {
            Storage::disk('public')->delete($post->photo_path);
        }
        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Post deleted successfully.');
    }
}
