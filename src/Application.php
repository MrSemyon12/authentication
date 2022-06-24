<?php

namespace App;

use App\Controller\FilmsController;
use App\Controller\UsersController;
use App\DI\ServiceLocator;
use App\Repository\Connection;
use App\Repository\FilmsRepository;
use App\Repository\UsersRepository;
use App\View\AuthView;
use App\View\TableView;
use Exception;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Application
{
    private ServiceLocator $serviceLocator;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->serviceLocator = $this->InitServices();
    }

    /**
     * @throws Exception
     */
    public function InitServices(): ServiceLocator
    {
        $serviceLocator = new ServiceLocator();

        $serviceLocator->set('twig',
            new Environment(
                new FilesystemLoader(dirname(__DIR__) . '/templates')));

        $config = include('config.php');
        $connection = new Connection(
            $config['dbHost'],
            $config['dbName'],
            $config['dbUsername'],
            $config['dbPassword']
        );
        $serviceLocator->set(UsersController::class,
            new UsersController(
                new AuthView($serviceLocator->get('twig')),
                new UsersRepository($connection)));

        $serviceLocator->set(FilmsController::class,
            new FilmsController(
                new TableView($serviceLocator->get('twig')),
                new FilmsRepository($connection)));

        return $serviceLocator;
    }

    /**
     * @throws Exception
     */
    public function run(): string
    {
        if ($_COOKIE['user'] != null)
        {
            if ($_GET['action'] === 'logout')
            {
                unset($_COOKIE['user']);

                setcookie('user', '', time() - 3600, '/');

                header('Location: /signIn');

                return $this->serviceLocator->Get('twig')->render('signIn.twig');
            }

            return $this->serviceLocator->Get(FilmsController::class)->showTable();
        }

        if ($_SERVER['REQUEST_URI'] === '/signIn')
        {
            if (empty($_POST))
            {
                return $this->serviceLocator->get('twig')->render('signIn.twig');
            }
            return $this->serviceLocator->get(UsersController::class)->SignIn($_POST['username'], $_POST['password']);
        }

        if ($_SERVER['REQUEST_URI'] === '/signUp')
        {
            if (empty($_POST))
            {
                return $this->serviceLocator->get('twig')->render('signUp.twig');
            }
            return $this->serviceLocator->get(UsersController::class)->SignUp($_POST['username'], $_POST['password']);
        }

        header('Location: /signIn');
        return $this->serviceLocator->get('twig')->render('signIn.twig');
    }
}