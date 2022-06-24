<?php

declare(strict_types=1);

namespace App\Controller;

use App\View\AuthView;
use App\Repository\UsersRepository;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class UsersController
{
    private AuthView $authView;
    private UsersRepository $usersRepository;

    public function __construct(AuthView $authView, UsersRepository $usersRepository)
    {
        $this->authView = $authView;
        $this->usersRepository = $usersRepository;
    }

    public function SignIn(string $login, string $password): string
    {
        $user = $this->usersRepository->findUser($login);

        if ($user != null && $user->getPassword() == $password)
        {
            setcookie('user', json_encode($user));
            header('Location: /');
            return 'Загрузка...';
        }
        else
        {
            return 'Не верные логин или пароль!';
        }
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function SignUp(string $username, string $password) : string
    {
        $this->usersRepository->addUser($username, $password);

        header('Location: /signIn');

        return $this->authView->showSignIn();
    }
}