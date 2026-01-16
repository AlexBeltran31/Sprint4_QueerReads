<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserBookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $books = $user->books()->with('categories')->get();
        return view('user_books.index', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $books = Book::all();
        return view('user_books.create', compact('books'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'status' => 'required|in:to_read,reading,finished',
        ]);

        $user = Auth::user();
        $user->books()->syncWithoutDetaching([
            $validated['book_id'] => ['status' => $validated['status']]
        ]);

        return redirect()->route('user-books.index')->with('success', 'Book added to your list!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $bookId)
    {
        $validated = $request->validate([
            'status' => 'required|in:to_read,reading,finished',
        ]);

        $user = Auth::user();
        $user->books()->updateExistingPivot($bookId, ['status' => $validated['status']]);

        return redirect()->route('user-books.index')->with('success', 'Book status updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($bookId)
    {
        $user = Auth::user();
        $user->books()->detach($bookId);

        return redirect()->route('user-books.index')->with('success', 'Book removed from your list.');
    }
}
