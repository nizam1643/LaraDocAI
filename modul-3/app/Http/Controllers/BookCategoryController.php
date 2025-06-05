<?php

namespace App\Http\Controllers;

use App\Models\BookCategory;
use Illuminate\Http\Request;

class BookCategoryController extends Controller
{
    public function index()
    {
        return view('book-category.index');
    }

    public function list()
    {
        return response()->json(BookCategory::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:book_categories,code',
            'description' => 'nullable|string|max:255',
        ]);
        return BookCategory::create($request->all());
    }

    public function show($id)
    {
        return BookCategory::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $book = BookCategory::findOrFail($id);
        $book->update($request->all());
        return $book;
    }

    public function destroy($id)
    {
        BookCategory::destroy($id);
        return response()->json(['status' => 'deleted']);
    }
}
