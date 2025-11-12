<?php
/**
 * Class db - Establishes PDO database connection
 */
class db
{
    /**
     * Connects to the database using parameters from db_params.php
     * * @return PDO <p>PDO database connection object</p>
     */
    public static function getConnection()
    {
        // Path to database connection parameters
        $paramsPath = ROOT . '/config/db_params.php';
        $params = include($paramsPath);

        // Data Source Name (DSN)
        $dsn = "mysql:host={$params['host']};dbname={$params['dbname']}";

        try {
            // Establish the PDO connection
            $db = new PDO($dsn, $params['user'], $params['password']);

            // $db->exec("set names utf8"); // Uncomment this if there are encoding issues (like hieroglyphs) on the website

            return $db;
        } catch (PDOException $e) {
            // Display error message if connection fails
            print "Error!: " . $e->getMessage() . "<br/>";

            // include(ROOT.'/function/errorPage.php'); // redirect for the error page

            // Terminate script with a detailed error message
            die('MySQL received an invalid request or incorrect data for database interaction.');
        }
    }
}