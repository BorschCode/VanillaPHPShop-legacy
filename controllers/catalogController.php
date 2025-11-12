<?php
// Ensure necessary models and components are included
include_once ROOT . '/models/category.php';
include_once ROOT . '/models/product.php';
// Assuming the Pagination class is located here:
include_once ROOT . '/function/pagination.php';

class catalogController {
    /**
     * Handles the display of the main catalog page.
     */
    public function actionIndex()
    {
        // Fetch the list of all categories
        $categories = category::getCategoriesList();

        // Fetch the 12 latest products for the main catalog view
        $latestProducts = product::getLatestProducts(12);

        $pageTitle = "Product Catalog";
        $pageDescription = "Browse all product categories and latest items.";

        require_once(ROOT . '/views/catalog/index.php');

        return true;
    }

    /**
     * Displays a list of products belonging to a specific category, with pagination.
     * * @param int $categoryId The ID of the category.
     * @param int $page The current page number (defaults to 1).
     */
    public function actionCategory($categoryId, $page = 1)
    {
        // Fetch the list of all categories (usually for the sidebar/menu)
        $categories = category::getCategoriesList();

        // Fetch products for the given category and page
        $categoryProducts = product::getProductsListByCategory($categoryId, $page);

        // Get the total number of products in this category
        $total = product::getTotalProductsInCategory($categoryId);

        // Create a Pagination object
        // SHOW_BY_DEFAULT is assumed to be a constant defined in the Product model
        $pagination = new pagination($total, $page, product::SHOW_BY_DEFAULT, 'page-');

        $pageTitle = "Category Listing";
        // Fetch the text description or name for the current category
        $pageDescription = category::getCategoryText($categoryId);

        require_once(ROOT . '/views/catalog/category.php');

        return true;
    }

}