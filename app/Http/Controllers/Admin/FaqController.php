<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        return view('admin.faqs.index', [
            'faqs' => Faq::query()->orderBy('sort')->orderBy('id')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.faqs.form', ['faq' => new Faq(['sort' => (Faq::query()->max('sort') ?? 0) + 1, 'is_active' => true])]);
    }

    public function store(Request $request)
    {
        $faq = new Faq;
        $this->save($request, $faq);

        return redirect()->route('admin.faqs')->with('status', 'Question saved.');
    }

    public function edit(Faq $faq)
    {
        return view('admin.faqs.form', compact('faq'));
    }

    public function update(Request $request, Faq $faq)
    {
        $this->save($request, $faq);

        return redirect()->route('admin.faqs')->with('status', 'Question updated.');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();

        return redirect()->route('admin.faqs')->with('status', 'Question removed.');
    }

    private function save(Request $request, Faq $faq): void
    {
        $data = $request->validate([
            'question' => ['required', 'string', 'max:200'],
            'answer' => ['required', 'string', 'max:2000'],
            'sort' => ['nullable', 'integer', 'min:0', 'max:999'],
        ]);

        $faq->fill([
            'question' => $data['question'],
            'answer' => $data['answer'],
            'sort' => $data['sort'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ])->save();
    }
}
