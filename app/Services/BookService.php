<?php

namespace App\Services;

use App\Models\Book;
use App\Repositories\BookRepository;
use Illuminate\Database\Eloquent\Collection;
use App\DTO\SearchBookDTO;

readonly class BookService
{
    public function __construct(private BookRepository $bookRepository)
    {
    }

    public function createBook(array $data): Book
    {
        return $this->bookRepository->create($data);
    }

    public function getAllBooks(): Collection
    {
        return $this->bookRepository->getAll();
    }

    public function getOneBook(int $id): ?Book
    {
        return $this->bookRepository->geById($id);
    }

    public function updateBook(Book $book, array $data): Book
    {
        return $this->bookRepository->update($book, $data);
    }

    public function deleteBook(Book $book): bool
    {
       return $this->bookRepository->delete($book);
    }

    public function getBookReviews(int $bookId, int $page = 1): ?array
    {
        return $this->bookRepository->getReviews($bookId, $page);
    }

    public function searchBooks(SearchBookDTO $dto): array
    {
        return $this->bookRepository->search($dto)->map(
            function (Book $book) {
                return [
                    'id' => $book->id,
                    'title' => $book->title,
                    'author' => $book->author,
                    'publishedYear' => $book->publishedYear,
                    'reviewCount' => $book->reviewCount,
                    'averageRating' => $book->averageRating !== null ? round((float) $book->averageRating, 2) : null,
                ];
            }
        )->toArray();
        
    }

}