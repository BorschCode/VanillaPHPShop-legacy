<?php
/**
 * Created by PhpStorm.
 * User: George
 * Date: 09.12.2017
 * Time: 20:41
 */
include_once ROOT . '/models/user.php'; // Ensure the User model is included

class userController
{

    /**
     * Handles the user registration process.
     */
    public function actionRegister()
    {
        $name = '';
        $email = '';
        $password = '';
        $result = false;
        $errors = []; // Initialize as an empty array for collecting messages

        if (isset($_POST['submit'])) {
            // Use null coalescing operator for safe access to POST data
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            // --- Validation Checks ---
            if (!user::checkName($name)) {
                $errors[] = 'Name must be at least 2 characters long.';
            }

            if (!User::checkEmail($email)) {
                $errors[] = 'Invalid email address.';
            }

            if (!user::checkPassword($password)) {
                $errors[] = 'Password must be at least 6 characters long.';
            }

            if (user::checkEmailExists($email)) {
                $errors[] = 'This email is already in use.';
            }

            // If there are no errors, attempt registration
            if (empty($errors)) {
                $result = user::register($name, $email, $password);
            }
        }

        $pageTitle = "User Registration";
        $pageDescription = "Create a new account.";

        require_once(ROOT . '/views/user/register.php');

        return true;
    }

    /**
     * Handles the user login process.
     */
    public function actionLogin()
    {
        $email = '';
        $password = '';
        $errors = []; // Initialize as an empty array

        if (isset($_POST['submit'])) {
            // Use null coalescing operator for safe access to POST data
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            // --- Validation Checks ---
            if (!user::checkEmail($email)) {
                $errors[] = 'Invalid email address.';
            }
            if (!user::checkPassword($password)) {
                $errors[] = 'Password must be at least 6 characters long.';
            }

            // Check if the user exists and credentials are correct
            $userId = user::checkUserData($email, $password);

            if ($userId === false) {
                // If data is incorrect, show an error
                $errors[] = 'Invalid email or password.';
            } else {
                // If data is correct, log in the user (session)
                user::auth($userId);

                // Redirect the user to the private area - the cabinet
                header("Location: /cabinet/");
            }

        }
        $pageTitle = "User Login";
        $pageDescription = "Access your account and manage orders.";

        require_once(ROOT . '/views/user/login.php');

        return true;
    }

    /**
     * Removes user data from the session and redirects to the home page.
     */
    public function actionLogout()
    {
        // Session variables are generally accessed via superglobals, ensure session is started globally
        unset($_SESSION["user"]);
        header("Location: /");
        exit(); // Always call exit after a header redirect
    }
}