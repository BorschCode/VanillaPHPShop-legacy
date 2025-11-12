<?php
/**
 * User Model (Utility Class).
 *
 * This model handles user registration, authentication, editing, and validation.
 */
class User
{

    /**
     * Registers a new user.
     *
     * @param string $name The user's name.
     * @param string $email The user's email address.
     * @param string $password The user's password (should be hashed before insertion in a real app).
     * @return bool Result of the database insertion.
     */
    public static function register($name, $email, $password)
    {

        $db = db::getConnection();

        $sql = 'INSERT INTO user (name, email, password) '
            . 'VALUES (:name, :email, :password)';

        $result = $db->prepare($sql);
        $result->bindParam(':name', $name, PDO::PARAM_STR);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->bindParam(':password', $password, PDO::PARAM_STR);

        return $result->execute();
    }

    /**
     * Edits/updates user data (name and password).
     *
     * @param int $id The ID of the user to update.
     * @param string $name The new name.
     * @param string $password The new password.
     * @return bool Result of the database update.
     */
    public static function edit($id, $name, $password)
    {
        $db = db::getConnection();

        $sql = "UPDATE user
            SET name = :name, password = :password
            WHERE id = :id";

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':name', $name, PDO::PARAM_STR);
        $result->bindParam(':password', $password, PDO::PARAM_STR);
        return $result->execute();
    }

    /**
     * Checks if a user exists with the given email and password.
     *
     * @param string $email The user's email.
     * @param string $password The user's password (plain text, comparing against plain text in DB).
     * @return int|false The user ID if credentials are valid, otherwise false.
     */
    public static function checkUserData($email, $password)
    {
        $db = db::getConnection();

        $sql = 'SELECT * FROM user WHERE email = :email AND password = :password';

        $result = $db->prepare($sql);
        $result->bindParam(':email', $email, PDO::PARAM_STR); // Fixed type to STR
        $result->bindParam(':password', $password, PDO::PARAM_STR); // Fixed type to STR
        $result->execute();

        $user = $result->fetch();
        if ($user) {
            return (int) $user['id'];
        }

        return false;
    }

    /**
     * Stores the user ID in the session for authentication.
     *
     * @param int $userId The ID of the user to authenticate.
     * @return void
     */
    public static function auth($userId)
    {
        // NOTE: Session must be started before this call.
        $_SESSION['user'] = $userId;
    }

    /**
     * Checks if the user is logged in. If not, redirects to the login page.
     *
     * @return int The ID of the logged-in user.
     */
    public static function checkLogged()
    {
        // If the session exists, return the user ID
        if (isset($_SESSION['user'])) {
            return (int) $_SESSION['user'];
        }

        header("Location: /user/login");
        // Exit to prevent further script execution after redirect
        exit;
    }

    /**
     * Checks if the current user is a guest (not logged in).
     *
     * @return bool True if the user is a guest, false otherwise.
     */
    public static function isGuest()
    {
        if (isset($_SESSION['user'])) {
            return false;
        }
        return true;
    }

    /**
     * Checks if the name is valid (at least 2 characters).
     *
     * @param string $name The name to check.
     * @return bool True if valid, false otherwise.
     */
    public static function checkName($name)
    {
        if (strlen($name) >= 2) {
            return true;
        }
        return false;
    }


    /**
     * Checks if the phone number is valid (at least 10 characters).
     *
     * @param string $phone The phone number to check.
     * @return bool True if valid, false otherwise.
     */
    public static function checkPhone($phone)
    {
        if (strlen($phone) >= 10) {
            return true;
        }
        return false;
    }


    /**
     * Checks if the password is valid (at least 6 characters).
     *
     * @param string $password The password to check.
     * @return bool True if valid, false otherwise.
     */
    public static function checkPassword($password)
    {
        if (strlen($password) >= 6) {
            return true;
        }
        return false;
    }

    /**
     * Checks if the email format is valid.
     *
     * @param string $email The email to check.
     * @return bool True if valid, false otherwise.
     */
    public static function checkEmail($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }

    /**
     * Checks if a user with the given email already exists in the database.
     *
     * @param string $email The email to check.
     * @return bool True if the email exists, false otherwise.
     */
    public static function checkEmailExists($email)
    {

        $db = db::getConnection();

        $sql = 'SELECT COUNT(*) FROM user WHERE email = :email';

        $result = $db->prepare($sql);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->execute();

        if ($result->fetchColumn())
            return true;
        return false;
    }

    /**
     * Returns user data by ID.
     *
     * @param int $id The user ID.
     * @return array<string, mixed>|false The user data as an associative array, or false if not found.
     */
    public static function getUserById($id)
    {
        if ($id) {
            $db = db::getConnection();
            $sql = 'SELECT * FROM user WHERE id = :id';

            $result = $db->prepare($sql);
            $result->bindParam(':id', $id, PDO::PARAM_INT);

            // Set fetch mode to associative array
            $result->setFetchMode(PDO::FETCH_ASSOC);
            $result->execute();

            return $result->fetch();
        }
        return false;
    }
}