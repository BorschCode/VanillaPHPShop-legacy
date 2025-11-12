<?php
/**
 * Created by PhpStorm.
 * User: George
 * Date: 09.12.2017
 * Time: 20:41
 */

class userController
{

    public function actionRegister()
    {
        $name = '';
        $email = '';
        $password = '';
        $result = false;

        if (isset($_POST['submit'])) {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            $errors = false;

            if (!user::checkName($name)) {
                $errors[] = 'Name must be at least 2 characters long';
            }

            if (!User::checkEmail($email)) {
                $errors[] = 'Invalid email';
            }

            if (!user::checkPassword($password)) {
                $errors[] = 'Password must be at least 6 characters long';
            }

            if (user::checkEmailExists($email)) {
                $errors[] = 'This email is already in use';
            }

            if ($errors == false) {
                $result = user::register($name, $email, $password);
            }

        }
        $pageTitle = "Authorization";
        $pageDescription = "Cabinet login";

        require_once(ROOT . '/views/user/register.php');

        return true;
    }

    public function actionLogin()
    {
        $email = '';
        $password = '';

        if (isset($_POST['submit'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $errors = false;

            // Field validation
            if (!user::checkEmail($email)) {
                $errors[] = 'Invalid email';
            }
            if (!user::checkPassword($password)) {
                $errors[] = 'Password must be at least 6 characters long';
            }

            // Проверяем существует ли пользователь
            $userId = user::checkUserData($email, $password);

            if ($userId == false) {
                // Если данные неправильные - показываем ошибку
                $errors[] = 'Invalid login credentials';
            } else {
                // Если данные правильные, запоминаем пользователя (сессия)
                user::auth($userId);

                // Перенаправляем пользователя в закрытую часть - кабинет
                header("Location: /cabinet/");
            }

        }
        $pageTitle = "User Cabinet";
        $pageDescription = "Purchase and data management";

        require_once(ROOT . '/views/user/login.php');

        return true;
    }

    /**
     * Remove user data from session
     */
    public function actionLogout()
    {
        unset($_SESSION["user"]);
        header("Location: /");
    }
}
