<?php
/**
 * Created by PhpStorm.
 * Date: 10.12.2017
 * Time: 1:41
 * Controller: AdminCategoryController
 * Manages product categories in the admin panel
 */
class adminCategoryController extends adminBase
{

    /**
     * Action for the "Manage Categories" page
     */
    public function actionIndex()
    {
        // Check admin access
        self::checkAdmin();

        // Get a list of categories for the admin panel
        $categoriesList = category::getCategoriesListAdmin();

        // Include the view
        require_once(ROOT . '/views/admin_category/index.php');
        return true;
    }

    /**
     * Action for the "Add Category" page
     */
    public function actionCreate()
    {
        // Check admin access
        self::checkAdmin();

        // Handle form submission
        if (isset($_POST['submit'])) {
            // If the form was submitted
            // Get data from the form
            $name = $_POST['name'];
            $sortOrder = $_POST['sort_order'];
            $status = $_POST['status'];

            // Flag for form errors
            $errors = false;

            // Basic validation: check if the name is set and not empty
            if (!isset($name) || empty($name)) {
                $errors[] = 'Fill in all required fields';
            }


            if ($errors == false) {
                // If there are no errors
                // Add the new category
                category::createCategory($name, $sortOrder, $status);

                // Redirect the user to the category management page
                header("Location: /admin/category");
            }
        }

        require_once(ROOT . '/views/admin_category/create.php');
        return true;
    }

    /**
     * Action for the "Edit Category" page
     * @param int $id The ID of the category to edit
     */
    public function actionUpdate($id)
    {
        // Check admin access
        self::checkAdmin();

        // Get data for the specific category
        $category = category::getCategoryById($id);

        // Handle form submission
        if (isset($_POST['submit'])) {
            // If the form was submitted
            // Get data from the form
            $name = $_POST['name'];
            $sortOrder = $_POST['sort_order'];
            $status = $_POST['status'];

            // Save the changes
            category::updateCategoryById($id, $name, $sortOrder, $status);

            // Redirect the user to the category management page
            header("Location: /admin/category");
        }

        // Include the view
        require_once(ROOT . '/views/admin_category/update.php');
        return true;
    }

    /**
     * Action for the "Delete Category" page
     * @param int $id The ID of the category to delete
     */
    public function actionDelete($id)
    {
        // Check admin access
        self::checkAdmin();

        // Handle form submission (confirmation)
        if (isset($_POST['submit'])) {
            // If the form was submitted
            // Delete the category
            category::deleteCategoryById($id);

            // Redirect the user to the category management page
            header("Location: /admin/category");
        }

        // Include the view
        require_once(ROOT . '/views/admin_category/delete.php');
        return true;
    }

}