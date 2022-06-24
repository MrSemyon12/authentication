<?php

declare(strict_types=1);

namespace App\Controller;

use App\View\TableView;
use App\Repository\FilmsRepository;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class FilmsController
{
    private TableView $tableView;
    private FilmsRepository $filmsRepository;

    public function __construct(TableView $tableView, FilmsRepository $filmsRepository)
    {
        $this->tableView = $tableView;
        $this->filmsRepository = $filmsRepository;
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function showTable(): string
    {
        $films = $this->filmsRepository->getAll();

        if (empty($films))
        {
            return 'Список пуст!';
        }
        else
        {
            return $this->tableView->showTable($films);
        }
    }
}