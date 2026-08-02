<?php

namespace App\Http\Controllers;

use App\DTO\SearchBookDTO;
use App\Providers\Requests\SearchBookRequest;
use App\Providers\Requests\BasicBookRequest;
use App\Services\BookService;
use Illuminate\Http\JsonResponse;

class Bookcontroller
{
    public function __construct(private readonly BookService $bookService)
    {
    }

    public function createBook(BasicBookRequest $request): JsonResponse
    {
        $book = $this->bookService->createBook($request->validated());
        return response()->json($book, 201);
    }

    public function getAllBooks(): JsonResponse
    {
        $books = $this->bookService->getAllBooks();
        return response()->json($books);
    }

    public function getOneBook($id): JsonResponse
    {
        $book = $this->bookService->getOneBook($id);
        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }
        return response()->json($book);
    }

    public function updateBook(BasicBookRequest $request, $id): JsonResponse
    {
        $book = $this->bookService->getOneBook($id);
        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }
        $updatedBook = $this->bookService->updateBook($book, $request->validated());
        return response()->json($updatedBook);
    }

    public function deleteBook($id): JsonResponse
    {
        $book = $this->bookService->getOneBook($id);
        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }
        $this->bookService->deleteBook($book);
        return response()->json(['message' => 'Book deleted successfully']);
    }

    public function getBookReviews(BasicBookRequest $request, $id): JsonResponse
    {
        $reviews = $this->bookService->getBookReviews($id, $request->get('page', 1));

        if (!$reviews) 
        {
            return response()->json(['message' => 'Book not found'], 404);
        }
        return response()->json($reviews);
    }

    public function searchBooks(SearchBookRequest $request): JsonResponse
    {
        $dto = new SearchBookDTO(
            title: $request->validated('title'),
            author: $request->validated('author'),
            minRating: $request->validated('minRating'),
            sortBy: $request->validated('sortBy'),
            order: $request->validated('order')
        );
        $books = $this->bookService->searchBooks($dto);
        return response()->json($books);
    }
}