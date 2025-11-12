<?php
/**
 * Created by PhpStorm.
 * user: George
 * Date: 09.12.2017
 * Time: 21:25
 */

class cabinetController
{

    public function actionIndex()
    {
        // Get user identifier from session
        $userId = user::checkLogged();

        // Get user information from DB
        $user = user::getUserById($userId);

        $pageTitle = "Cabinet";
        $pageDescription = "User Cabinet";
        require_once(ROOT . '/views/cabinet/index.php');

        return true;
    }

    public function actionEdit()
    {
        // Get user identifier from session
        $userId = user::checkLogged();

        // Get user information from DB
        $user = user::getUserById($userId);

        $name = $user['name'];
        $password = $user['password'];

        $result = false;

        if (isset($_POST['submit'])) {
            $name = $_POST['name'];
            $password = $_POST['password'];

            $errors = false;

            if (!user::checkName($name)) {
                $errors[] = 'Name must be at least 2 characters long';
            }

            if (!user::checkPassword($password)) {
                $errors[] = 'Password must be at least 6 characters long';
            }

            if ($errors == false) {
                $result = user::edit($userId, $name, $password);
            }

        }
        $pageTitle = "Cabinet";
        $pageDescription = "User Cabinet режим редактирования";
        require_once(ROOT . '/views/cabinet/edit.php');

        return true;
    }

}