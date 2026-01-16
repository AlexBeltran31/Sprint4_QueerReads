<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::with('categories')->get();
        return view('books.create', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories= Category::all();
        return view('books.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'publication_year' => 'nullable|integer|min:1000|max:' . date('Y'),
            'categories' => 'array'
        ]);

        $book = Book::create($validated);
        if(isset($validated['categories'])) {
            $book->categories()->attach($validated['categories']);
        }
        return redirect()->route('books.index')->with('success', 'Book created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        return view('books.show', compact('book'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        $categories = Category::all();
        return view('books.edit', compact('book', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'publication_year' =>'nullable|integer|min:1000|max:' . date('Y'),
            'categories' => 'array'
        ]);

        $book->update($validated);
        $book->categories()->sync($validated['categories'] ?? []);

        return redirect()->route('books.index')->with('success', 'Book updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $book->categories()->detach();
        $book->delete();

        return redirect()->route('books.index')->with('success', 'Book deleted successfully.');
    }

    public function random()
    {
        $book = Book::inRandomOrder()->first();
        return view('books.show', compact('book'));
    }
}
