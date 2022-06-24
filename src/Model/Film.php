<?php
declare(strict_types=1);

namespace App\Model;

class Film
{
    private int $id;
    private string $title;
    private string $category;

    public function __construct(int $id, string $title, string $category)
    {
        $this->id = $id;
        $this->title = $title;
        $this->category = $category;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }
}