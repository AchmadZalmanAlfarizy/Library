<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $books = Book::with('category')->get();
        
        return response()->json($books);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), $this->bookValidationRules());

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $bookData = $request->except(['book_cover']);

        if ($request->hasFile('book_cover')) {
            $bookData['book_cover'] = $this->handleImageUpload($request->file('book_cover'));
        }

        $book = Book::create($bookData);
        
        return response()->json($book->load('category'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $book = Book::with('category')->find($id);

        if (!$book) {
            return $this->notFoundResponse();
        }

        return response()->json($book);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $book = Book::find($id);

        if (!$book) {
            return $this->notFoundResponse();
        }

        $validator = Validator::make($request->all(), $this->bookValidationRules());

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $bookData = $request->except(['book_cover']);

        if ($request->hasFile('book_cover')) {
            $this->deleteImageIfExists($book->book_cover);
            $bookData['book_cover'] = $this->handleImageUpload($request->file('book_cover'));
        }

        $book->update($bookData);
        
        return response()->json($book->load('category'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $book = Book::find($id);

        if (!$book) {
            return $this->notFoundResponse();
        }

        $this->deleteImageIfExists($book->book_cover);
        $book->delete();

        return response()->json(['message' => 'Book deleted']);
    }
    
    /**
     * Search books by title, author, or category.
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->get('query');

        $books = Book::with('category')
            ->where('title', 'like', "%{$query}%")
            ->orWhere('author', 'like', "%{$query}%")
            ->orWhereHas('category', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->get();

        return response()->json($books);
    }

    /**
     * Update only the book cover image.
     */
    public function updateCover(Request $request, string $id): JsonResponse
    {
        $book = Book::find($id);

        if (!$book) {
            return $this->notFoundResponse();
        }

        $validator = Validator::make($request->all(), [
            'book_cover' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $this->deleteImageIfExists($book->book_cover);
        
        $book->update([
            'book_cover' => $this->handleImageUpload($request->file('book_cover'))
        ]);

        return response()->json($book->load('category'));
    }

    /**
     * Delete book cover image.
     */
    public function deleteCover(string $id): JsonResponse
    {
        $book = Book::find($id);

        if (!$book) {
            return $this->notFoundResponse();
        }

        if ($book->book_cover) {
            $this->deleteImageIfExists($book->book_cover);
            $book->update(['book_cover' => null]);
        }

        return response()->json($book->load('category'));
    }

    // =========================================================================
    // PRIVATE HELPER METHODS (Untuk mencegah pengulangan kode)
    // =========================================================================

    /**
     * Handle image upload, conversion to WebP, and storage.
     */
    private function handleImageUpload($file): string
    {
        $filename = uniqid() . '_' . time() . '.webp';
        
        Storage::disk('public')->makeDirectory('book_cover');
        
        $image = Image::read($file);
        $image->cover(3000, 3600);
        $webpData = $image->toWebp(80);
        
        $path = 'book_cover/' . $filename;
        Storage::disk('public')->put($path, $webpData);
        
        return Storage::disk('public')->url($path);
    }

    /**
     * Get default validation rules for book.
     */
    private function bookValidationRules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:1|max:10',
            'book_cover' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ];
    }

    /**
     * Delete image from storage if the path exists.
     */
    private function deleteImageIfExists(?string $coverUrl): void
    {
        if ($coverUrl) {
            Storage::disk('public')->delete('book_cover/' . basename($coverUrl));
        }
    }

    /**
     * Standardized 404 response.
     */
    private function notFoundResponse(): JsonResponse
    {
        return response()->json(['message' => 'Book not found'], 404);
    }
}