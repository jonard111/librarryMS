<?php

namespace App\Http\Controllers\Head;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Ebook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InventoryController extends Controller
{
    private array $categoryOptions = [
        'education' => 'Education & Learning',
        'science' => 'Science & Technology',
        'literature' => 'Literature / Fiction',
        'history' => 'History',
        'selfhelp' => 'Self-Help / Motivation',
    ];

    public function books()
    {
        $booksByCategory = Book::latest()->get()->groupBy('category');

        return view('head.all_books', [
            'categories' => $this->categoryOptions,
            'booksByCategory' => $booksByCategory,
        ]);
    }

    public function storeBook(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'isbn' => ['nullable', 'string', 'max:50'],
            'publisher' => ['nullable', 'string', 'max:255'],
            'category' => ['required', 'string', 'in:' . implode(',', array_keys($this->categoryOptions))],
            'copies' => ['required', 'integer', 'min:1'],
            'cover' => ['nullable', 'image', 'max:2048'],
        ]);

        $bookData = collect($validated)->except('cover')->toArray();

        if ($request->hasFile('cover')) {
            $bookData['cover_path'] = $request->file('cover')->store('covers', 'public');
        }

        Book::create($bookData);

        return back()->with('book_success', 'Book added successfully!');
    }

    public function editBook(Book $book)
    {
        return response()->json($book);
    }

    public function updateBook(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'isbn' => ['nullable', 'string', 'max:50'],
            'publisher' => ['nullable', 'string', 'max:255'],
            'category' => ['required', 'string', 'in:' . implode(',', array_keys($this->categoryOptions))],
            'copies' => ['required', 'integer', 'min:1'],
            'cover' => ['nullable', 'image', 'max:2048'],
        ]);

        $bookData = collect($validated)->except('cover')->toArray();

        if ($request->hasFile('cover')) {
            // Delete old cover if exists
            if ($book->cover_path) {
                Storage::disk('public')->delete($book->cover_path);
            }
            $bookData['cover_path'] = $request->file('cover')->store('covers', 'public');
        }

        $book->update($bookData);

        return back()->with('book_success', 'Book updated successfully!');
    }

    public function destroyBook(Book $book)
    {
        // Delete cover image if exists
        if ($book->cover_path) {
            Storage::disk('public')->delete($book->cover_path);
        }

        $book->delete();

        return back()->with('book_success', 'Book deleted successfully!');
    }

    public function ebooks()
    {
        $ebooksByCategory = Ebook::latest()->get()->groupBy('category');

        return view('head.all_ebooks', [
            'categories' => $this->categoryOptions,
            'ebooksByCategory' => $ebooksByCategory,
        ]);
    }

    public function storeEbook(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'in:' . implode(',', array_keys($this->categoryOptions))],
            'ebook_file' => ['required', 'file', 'mimes:pdf,epub,mobi', 'max:51200'],
            'cover' => ['nullable', 'image', 'max:2048'],
        ]);

        $filePath = $request->file('ebook_file')->store('ebooks', 'public');
        $coverPath = $request->hasFile('cover')
            ? $request->file('cover')->store('covers', 'public')
            : null;

        Ebook::create([
            'title' => $validated['title'],
            'author' => $validated['author'],
            'category' => $validated['category'],
            'file_path' => $filePath,
            'cover_path' => $coverPath,
        ]);

        return back()->with('ebook_success', 'E-Book uploaded successfully!');
    }

    public function editEbook(Ebook $ebook)
    {
        return response()->json($ebook);
    }

    public function updateEbook(Request $request, Ebook $ebook)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'in:' . implode(',', array_keys($this->categoryOptions))],
            'ebook_file' => ['nullable', 'file', 'mimes:pdf,epub,mobi', 'max:51200'],
            'cover' => ['nullable', 'image', 'max:2048'],
        ]);

        $ebookData = [
            'title' => $validated['title'],
            'author' => $validated['author'],
            'category' => $validated['category'],
        ];

        if ($request->hasFile('ebook_file')) {
            // Delete old file if exists
            if ($ebook->file_path) {
                Storage::disk('public')->delete($ebook->file_path);
            }
            $ebookData['file_path'] = $request->file('ebook_file')->store('ebooks', 'public');
        }

        if ($request->hasFile('cover')) {
            // Delete old cover if exists
            if ($ebook->cover_path) {
                Storage::disk('public')->delete($ebook->cover_path);
            }
            $ebookData['cover_path'] = $request->file('cover')->store('covers', 'public');
        }

        $ebook->update($ebookData);

        return back()->with('ebook_success', 'E-Book updated successfully!');
    }

    public function destroyEbook(Ebook $ebook)
    {
        // Delete files if they exist
        if ($ebook->file_path) {
            Storage::disk('public')->delete($ebook->file_path);
        }
        if ($ebook->cover_path) {
            Storage::disk('public')->delete($ebook->cover_path);
        }

        $ebook->delete();

        return back()->with('ebook_success', 'E-Book deleted successfully!');
    }
}

