<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
{
    $query = Book::query();

    // Filter by category
    if ($request->categorie && $request->categorie !== 'Tous') {
        $query->where('categorie', $request->categorie);
    }

    // Filter by type (checkboxes)
    if ($request->has('hello')) {
        $query->whereIn('type', $request->hello);
    }

    // Filter by price range
    if ($request->min_price) {
        $query->where('prix', '>=', $request->min_price);
    }

    if ($request->max_price) {
        $query->where('prix', '<=', $request->max_price);
    }

    // Include soft-deleted records
    $books = $query->paginate(6);

    return view('book.index', compact('books'));
}


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('book.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $book = new Book;
        if ($request->hasFile('cover')) {
            $file = $request->file('cover');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/img/book'), $filename);
            $book->cover = $filename;
        }
        else{
            $book->cover = 'image.jpg';
        }
        $book->type = $request->type;
        $book->categorie = $request->categorie;
        $book->langue = $request->langue;
        $book->editeur = $request->editeur;
        $book->designation = $request->designation;
        $book->auteur = $request->auteur;
        $book->description = $request->description;
        $book->prix = $request->prix;
        $book->save();
        Log::info('Un nouveau livre a été ajouté avec l\'id: ' . $book->id);
        return redirect()->route('book.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $book = Book::findOrFail($id);
        return view('book.show', compact('book'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $book = Book::findOrFail($id);
        return view('book.edit', compact('book'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        if ($request->hasFile('cover')) {
            if ($book->cover != 'book.png' && $book->cover != null) {
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
        $book->type = $request->type;
        $book->categorie = $request->categorie;
        $book->langue = $request->langue;
        $book->editeur = $request->editeur;
        $book->designation = $request->designation;
        $book->auteur = $request->auteur;
        $book->description = $request->description;
        $book->prix = $request->prix;
        //$book->cover = $request->cover;
        $book->save();
        
        Log::info('Le livre a été mis à jour :' . $book->id);
        return redirect()->route('book.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $book = Book::findOrFail($id);
        if ($book->cover != 'image.jpg' && $book->cover != null) {
            $coverPath = public_path('assets/img/book/' . $book->cover);
            if (file_exists($coverPath)) {
                unlink($coverPath);
            }
        }
        
        $book->delete();
        Log::warning('Le livre a été supprimé :' . $book->id);
        return redirect()->route('book.index');
    }
}
