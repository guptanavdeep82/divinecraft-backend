<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PageController extends Controller
{
    public function index()
    {
        return view('admin.pages.index', [
            'pages' => Page::query()->orderBy('title')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.pages.form', ['page' => new Page]);
    }

    public function store(Request $request)
    {
        $page = new Page;
        $this->save($request, $page);

        return redirect()->route('admin.pages')->with('status', 'Page saved.');
    }

    public function edit(Page $page)
    {
        return view('admin.pages.form', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $this->save($request, $page);

        return redirect()->route('admin.pages')->with('status', 'Page updated.');
    }

    public function destroy(Page $page)
    {
        $page->delete();

        return redirect()->route('admin.pages')->with('status', 'Page removed.');
    }

    private function save(Request $request, Page $page): void
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'slug' => ['nullable', 'string', 'max:160', Rule::unique('pages', 'slug')->ignore($page->id)],
            'eyebrow' => ['nullable', 'string', 'max:80'],
            'summary' => ['nullable', 'string', 'max:400'],
            'body' => ['nullable', 'string', 'max:20000'],
        ]);

        $page->fill([
            'title' => $data['title'],
            'slug' => $data['slug'] ?: Str::slug($data['title']),
            'eyebrow' => $data['eyebrow'] ?? null,
            'summary' => $data['summary'] ?? null,
            'body' => $data['body'] ?? '',
        ])->save();
    }
}
