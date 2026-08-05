<?php

namespace App\DTO;

readonly class SearchBookDTO
{
    public function __construct(
        public ?string $title,
        public ?string $author,
        public ?float $minRating,
        public ?string $sortBy,
        public ?string $order
    ) {

    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function getMinRating(): ?float
    {
        return $this->minRating;
    }

    public function getSortBy(): ?string
    {
        return $this->sortBy;
    }

    public function getOrder(): ?string
    {
        return $this->order;
    }
}