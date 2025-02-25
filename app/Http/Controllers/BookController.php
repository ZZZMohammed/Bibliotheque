<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Events\OperationLogged;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::query();

        if ($request->categorie && $request->categorie !== 'Tous') {
            $query->where('categorie', $request->categorie);
        }

        if ($request->has('hello')) {
            $query->whereIn('type', $request->hello);
        }

        if ($request->min_price) {
            $query->where('prix', '>=', $request->min_price);
        }

        if ($request->max_price) {
            $query->where('prix', '<=', $request->max_price);
        }

        $books = $query->paginate(6);
        return view('book.index', compact('books'));
    }

    public function create()
    {
        return view('book.create');
    }

    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'designation' => 'required|string|max:255',
            'auteur' => 'required|string|max:255',
            'prix' => 'required|numeric|min:0',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $book = new Book;

        if ($request->hasFile('cover')) {
            $file = $request->file('cover');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/img/book'), $filename);
            $book->cover = $filename;
        } else {
            $book->cover = 'image.jpg';
        }

        $book->fill($request->except('cover'));
        $book->save();

        Log::info("Book added: {$book->id}");
        event(new OperationLogged('created', 'books', Auth::id() ?? 0));

        return redirect()->route('book.index');
    }

    public function show($id)
    {
        $book = Book::findOrFail($id);
        return view('book.show', compact('book'));
    }

    public function edit($id)
    {
        $book = Book::findOrFail($id);
        return view('book.edit', compact('book'));
    }

    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $request->validate([
            'designation' => 'required|string|max:255',
            'auteur' => 'required|string|max:255',
            'prix' => 'required|numeric|min:0',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('cover')) {
            if ($book->cover != 'image.jpg' && $book->cover != null) {
                $oldCoverPath = public_path('assets/img/book/' . $book->cover);
                if (file_exists($oldCoverPath)) {
                    unlink($oldCoverPath);
                }
            }
            $file = $request->file('cover');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/img/book'), $filename);
            $book->cover = $filename;
        }

        $book->fill($request->except('cover'));
        $book->save();

        Log::info("Book updated: {$book->id}");
        event(new OperationLogged('updated', 'books', Auth::id() ?? 0));

        return redirect()->route('book.index');
    }

    public function destroy($id)
    {
        $book = Book::findOrFail($id);

        if ($book->cover != 'image.jpg' && $book->cover != null) {
            $coverPath = public_path('assets/img/book/' . $book->cover);
            if (file_exists($coverPath)) {
                unlink($coverPath);
            }
        }

        $book->delete();

        Log::warning("Book deleted: {$book->id}");
        event(new OperationLogged('soft_deleted', 'books', Auth::id() ?? 0));

        return redirect()->route('book.index');
    }
}
