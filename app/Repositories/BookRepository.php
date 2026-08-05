<?php

namespace App\Repositories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Collection;
use App\DTO\SearchBookDTO;

Class BookRepository
{
    private const PER_PAGE = 10;

    public function create(array $data): Book
    {
        return Book::create($data);
    }

    public function getAll(): Collection
    {
        return Book::query()->get();
    }
    public function geById(int $id): ?Book
    {
        return Book::find($id);
    }

    public function update(Book $book, array $data): Book
    {
        $book->update($data);
        return $book;
    }
    public function delete(Book $book): bool
    {
        return $book->delete();
    }

    public function getReviews(int $bookId, int $page = 1): ?array
    {
        $perPage = self::PER_PAGE;
        $offset = ($page - 1) * $perPage;
        $book = Book::find($bookId);

        if (!$book) {
            return null;
        }
        $items = $book->reviews()
        ->orderByDesc('created_at')
        ->offset($offset)
        ->limit($perPage)
        ->get();
        $totalItems = $book->reviews()->count();

        return [
            'page' => $page,
            'pageSize' => $perPage,
            'totalItems' => $totalItems,
            'items' => $items,
        ];
    }

   public function search(SearchBookDto $dto): Collection
{
    $query = Book::query()
        ->with('reviews')
        ->when(
            $dto->getTitle(),
            function ($query, $title) {
                $query->where('title', 'like', '%' . $title . '%');
            }
        )
        ->when(
            $dto->getAuthor(),
            function ($query, $author) {
                $query->where('author', 'like', '%' . $author . '%');
            }
        )
        ->when(
            $dto->getMinRating() !== null,
            function ($query) use ($dto) {
                $query->whereHas(
                    'reviews',
                    function ($reviewQuery) use ($dto) {
                        $reviewQuery->where(
                            'rating',
                            '>=',
                            $dto->getMinRating()
                        );
                    }
                );
            }
        )
        ->orderBy($dto->getSortBy(), $dto->getOrder());
 
    return $query->get();
}
}