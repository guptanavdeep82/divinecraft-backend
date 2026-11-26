<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        return view('admin.reviews.index', [
            'reviews' => Review::query()->orderBy('sort')->orderBy('id')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.reviews.form', ['review' => new Review(['rating' => 5, 'is_active' => true])]);
    }

    public function store(Request $request)
    {
        $review = new Review;
        $this->save($request, $review);

        return redirect()->route('admin.reviews')->with('status', 'Review saved.');
    }

    public function edit(Review $review)
    {
        return view('admin.reviews.form', compact('review'));
    }

    public function update(Request $request, Review $review)
    {
        $this->save($request, $review);

        return redirect()->route('admin.reviews')->with('status', 'Review updated.');
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return redirect()->route('admin.reviews')->with('status', 'Review removed.');
    }

    private function save(Request $request, Review $review): void
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'city' => ['nullable', 'string', 'max:80'],
            'quote' => ['required', 'string', 'max:500'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'sort' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $review->fill([
            'name' => $data['name'],
            'city' => $data['city'] ?? null,
            'quote' => $data['quote'],
            'rating' => $data['rating'],
            'sort' => $data['sort'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ])->save();
    }
}
