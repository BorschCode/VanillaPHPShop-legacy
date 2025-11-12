<?php
/**
 * Created by PhpStorm.
 * Date: 10.12.2017
 * Time: 1:41
 */

// Include necessary base classes and models
include_once ROOT . '/function/adminBase.php';
include_once ROOT . '/models/category.php';

/**
 * AdminCategoryController
 * Manages product categories in the admin panel
 */
class AdminCategoryController extends AdminBase
{

    /**
     * Action for the "Manage Categories" page
     * @return bool
     */
    public function actionIndex()
    {
        // Check admin access
        self::checkAdmin();

        // Get a list of categories for the admin panel
        $categoriesList = Category::getCategoriesListAdmin();

        // Include the view
        require_once(ROOT . '/views/admin_category/index.php');
        return true;
    }

    /**
     * Action for the "Add Category" page
     * @return bool
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
                // If there are no errors, create the new category
                Category::createCategory($name, $sortOrder, $status);

                // Redirect the user to the category management page
                header("Location: /admin/category");
                exit();
            }
        }

        require_once(ROOT . '/views/admin_category/create.php');
        return true;
    }

    /**
     * Action for the "Edit Category" page
     * @param int $id The ID of the category to edit
     * @return bool
     */
    public function actionUpdate($id)
    {
        // Check admin access
        self::checkAdmin();

        // Get data for the specific category
        $category = Category::getCategoryById($id);

        // Handle form submission
        if (isset($_POST['submit'])) {
            // If the form was submitted
            // Get data from the form
            $name = $_POST['name'];
            $sortOrder = $_POST['sort_order'];
            $status = $_POST['status'];

            // Save the changes
            Category::updateCategoryById($id, $name, $sortOrder, $status);

            // Redirect the user to the category management page
            header("Location: /admin/category");
            exit();
        }

        // Include the view
        require_once(ROOT . '/views/admin_category/update.php');
        return true;
    }

    /**
     * Action for the "Delete Category" page
     * @param int $id The ID of the category to delete
     * @return bool
     */
    public function actionDelete($id)
    {
        // Check admin access
        self::checkAdmin();

        // Handle form submission (confirmation)
        if (isset($_POST['submit'])) {
            // If the form was submitted, delete the category
            Category::deleteCategoryById($id);

            // Redirect the user to the category management page
            header("Location: /admin/category");
            exit();
        }

        // Include the view
        require_once(ROOT . '/views/admin_category/delete.php');
        return true;
    }

}