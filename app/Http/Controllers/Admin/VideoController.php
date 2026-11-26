<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Support\Uploads;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index()
    {
        return view('admin.videos.index', [
            'videos' => Video::query()->orderBy('sort')->orderBy('id')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.videos.form', ['video' => new Video(['is_active' => true, 'label' => 'POSHAK'])]);
    }

    public function store(Request $request)
    {
        $video = new Video;
        $this->save($request, $video);

        return redirect()->route('admin.videos')->with('status', 'Video saved.');
    }

    public function edit(Video $video)
    {
        return view('admin.videos.form', compact('video'));
    }

    public function update(Request $request, Video $video)
    {
        $this->save($request, $video);

        return redirect()->route('admin.videos')->with('status', 'Video updated.');
    }

    public function destroy(Video $video)
    {
        $video->delete();

        return redirect()->route('admin.videos')->with('status', 'Video removed.');
    }

    private function save(Request $request, Video $video): void
    {
        $data = $request->validate([
            'label' => ['required', 'string', 'max:24'],
            'title' => ['required', 'string', 'max:140'],
            'poster' => ['nullable', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'max:8192'],
            'video_url' => ['nullable', 'string', 'max:255'],
            'clip' => ['nullable', 'file', 'mimetypes:video/mp4,video/webm,video/quicktime', 'max:51200'],
            'href' => ['nullable', 'string', 'max:255'],
            'sort' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $poster = Uploads::image($request->file('photo'), $data['poster'] ?? $video->poster);
        if (! $poster) {
            abort(422, 'Add a poster image. It shows in the filmstrip.');
        }

        $clip = Uploads::media($request->file('clip'));
        $source = $clip ?: (filled($data['video_url'] ?? null) ? $data['video_url'] : $video->video);

        $video->fill([
            'label' => $data['label'],
            'title' => $data['title'],
            'poster' => $poster,
            'video' => $source,
            'href' => $data['href'] ?? null,
            'sort' => $data['sort'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ])->save();
    }
}
