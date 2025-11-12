<?php
/**
 * Created by PhpStorm.
 * User: George
 * Date: 09.12.2017
 * Time: 21:25
 */

// Include necessary models
include_once ROOT . '/models/user.php';
include_once ROOT . '/models/category.php'; // Often needed for navigation

class cabinetController
{

    /**
     * Displays the main user cabinet page.
     * @return bool
     */
    public function actionIndex()
    {
        // Get user identifier from session
        $userId = user::checkLogged();

        // Get user information from DB
        $user = user::getUserById($userId);

        $pageTitle = "User Cabinet";
        $pageDescription = "View your profile information.";
        require_once(ROOT . '/views/cabinet/index.php');

        return true;
    }

    /**
     * Handles the user profile edit functionality (name and password).
     * @return bool
     */
    public function actionEdit()
    {
        // Get user identifier from session
        $userId = user::checkLogged();

        // Get user information from DB
        $user = user::getUserById($userId);

        // Pre-fill form fields with current data
        $name = $user['name'];
        // Note: Password field is usually not pre-filled for security, but we keep the variable for potential form submission
        $password = null;

        $result = false;
        $errors = [];

        if (isset($_POST['submit'])) {
            // Retrieve submitted data
            $name = $_POST['name'] ?? $user['name']; // Use current name if submitted name is empty
            $password = $_POST['password'] ?? '';

            // --- Validation ---
            if (!user::checkName($name)) {
                $errors[] = 'Name must be at least 2 characters long.';
            }
            // Only validate password if the user actually submitted a new one
            if (!empty($password) && !user::checkPassword($password)) {
                $errors[] = 'Password must be at least 6 characters long.';
            }

            if (empty($errors)) {
                // If the password field was left empty, do not try to update it
                $passwordToUpdate = !empty($password) ? $password : $user['password'];

                $result = user::edit($userId, $name, $passwordToUpdate);
            }

        }

        $pageTitle = "Edit Profile";
        $pageDescription = "Update your user cabinet details.";
        require_once(ROOT . '/views/cabinet/edit.php');

        return true;
    }

}