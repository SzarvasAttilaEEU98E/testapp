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

    public function search(SearchBookDTO $dto): Collection
    {
        $sortBy = $dto->getSortBy()?? 'title';
        $order = $dto->getOrder() ?? 'asc';
        $allowedSorts = ['title', 'author', 'publishedYear', 'averageRating'];

        $query = Book::query()
        ->withCount('reviews as reviewCount' )
        ->with('reviews as averageRating', 'rating')

        ->when (!empty($dto->getTitle()),
            function ($query) use ($dto) {
                $query->where('title', 'like', '%' . $dto->getTitle() . '%');
            }
        )
        ->when (!empty($dto->getAuthor()),
            function ($query) use ($dto) {
                $query->where('author', 'like', '%' . $dto->getAuthor() . '%');
            }
        )
         ->when ($dto->getMinRating() !== null,
            function ($query) use ($dto) {
                $query->having('averageRating', '>=', $dto->getMinRating());
            }
        );

        if (!in_array($sortBy, $allowedSorts, true)) {
            $sortBy = 'title';
        }

        if (!in_array($order, ['asc', 'desc'], true)) {
            $order = 'asc';
        }

        $query->orderBy($sortBy, $order);

        return $query->get();
        
    }
}