<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Support\Uploads;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BlogController extends Controller
{
    public function index()
    {
        return view('admin.blogs.index', [
            'blogs' => Blog::query()->latest()->get(),
        ]);
    }

    public function create()
    {
        return view('admin.blogs.form', ['blog' => new Blog(['author' => 'DivineKraft'])]);
    }

    public function store(Request $request)
    {
        $blog = new Blog;
        $this->save($request, $blog);

        return redirect()->route('admin.blogs')->with('status', 'Story published.');
    }

    public function edit(Blog $blog)
    {
        return view('admin.blogs.form', compact('blog'));
    }

    public function update(Request $request, Blog $blog)
    {
        $this->save($request, $blog);

        return redirect()->route('admin.blogs')->with('status', 'Story updated.');
    }

    public function destroy(Blog $blog)
    {
        $blog->delete();

        return redirect()->route('admin.blogs')->with('status', 'Story removed.');
    }

    private function save(Request $request, Blog $blog): void
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'slug' => ['nullable', 'string', 'max:160', Rule::unique('blogs', 'slug')->ignore($blog->id)],
            'excerpt' => ['required', 'string', 'max:280'],
            'body' => ['required', 'string', 'max:20000'],
            'author' => ['nullable', 'string', 'max:80'],
            'image' => ['nullable', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'max:8192'],
            'published' => ['nullable', 'boolean'],
        ]);

        $blog->fill([
            'title' => $data['title'],
            'slug' => $data['slug'] ?: Str::slug($data['title']),
            'excerpt' => $data['excerpt'],
            'body' => $data['body'],
            'author' => $data['author'] ?: 'DivineKraft',
            'image' => Uploads::image($request->file('photo'), $data['image'] ?? $blog->image),
            'published_at' => $request->boolean('published') ? ($blog->published_at ?? now()) : null,
        ])->save();
    }
}
