<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $books = Book::all() ;
        return response()->json($books) ;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
        {
            $validatedData = $request->validate([
                'designation' => 'required|string|unique:books,designation',
                'description' => 'nullable|string',
                'type' => 'required|string|in:Texte,Audio,Video',
                'langue' => 'required|string',
                'editeur' => 'required|string',
                'categorie' => 'required|string',
                'prix' => 'required|numeric|min:0',
                'auteur' => 'required|string',
                'cover' => 'nullable|image|mimes:jpg,png,jpeg|max:2048' // Ensures valid image upload
            ]);

            // Handle file upload if a cover image is provided
            if ($request->hasFile('cover')) {
                $file = $request->file('cover');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/covers'), $filename);
                $validatedData['cover'] = 'uploads/covers/' . $filename;
            }

            // Create the book
            $book = Book::create($validatedData);

            return response()->json(['message' => 'Book created successfully', 'book' => $book], 201);
}


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book)
    {
        return $book ;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Book $book)
    {
        $request->validate([
            'designation' => 'required|string|unique:books,designation,' . $book->id,
            'description' => 'nullable|string',
            'type' => 'required|string|in:Texte,Audio,Video',
            'langue' => 'required|string',
            'editeur' => 'required|string',
            'categorie' => 'required|string',
            'prix' => 'required|numeric|min:0',
            'auteur' => 'required|string',
            'cover' => 'nullable|image|mimes:jpg,png,jpeg|max:2048' // Ensure it's an image if updated
        ]);
    
        // Update the book with the validated data
        $book->update($request->all());
    
        return response()->json(['message' => 'Book updated successfully', 'book' => $book], 200);
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $book)
    {
        $book->delete() ;

        return response()->json('le book est bien supprime !') ;

       }
}
