<?php
/**
 * Created by PhpStorm.
 * User: George
 * Date: 09.12.2017
 * Time: 22:21
 */
// Include necessary models and components
include_once ROOT . '/models/cart.php';
include_once ROOT . '/models/category.php';
include_once ROOT . '/models/product.php';
include_once ROOT . '/models/user.php';
include_once ROOT . '/function/order.php';

class cartController
{

    /**
     * Adds a product to the cart and redirects the user back to the previous page.
     * @param int $id The product ID.
     */
    public function actionAdd($id)
    {
        // Add product to cart
        cart::addProduct($id);

        // Redirect user back to the previous page
        $referrer = $_SERVER['HTTP_REFERER'];
        header("Location: $referrer");
        exit(); // Crucial to stop script execution after redirect
    }

    /**
     * Removes a single item of a product from the cart.
     * @param int $id The product ID.
     */
    public function actionDelete($id)
    {
        // Remove product from cart
        cart::deleteItem($id);

        // Redirect user to the cart page
        header("Location: /cart/");
        exit(); // Crucial to stop script execution after redirect
    }

    /**
     * Adds a product to the cart via AJAX and returns the new item count.
     * @param int $id The product ID.
     * @return bool
     */
    public function actionAddAjax($id)
    {
        // Add product to cart and echo the new count
        echo cart::addProduct($id);
        return true;
    }

    /**
     * Displays the main shopping cart page.
     * @return bool
     */
    public function actionIndex()
    {
        // Fetch categories for the menu
        $categories = category::getCategoriesList();

        $productsInCart = false;
        $products = [];
        $totalPrice = 0;

        // Get product IDs and quantities from the cart session
        $productsInCart = cart::getProducts();

        if ($productsInCart) {
            // Get full product information for the list
            $productsIds = array_keys($productsInCart);
            $products = product::getProductsByIds($productsIds);

            // Get total cost of products
            $totalPrice = cart::getTotalPrice($products);
        }

        $pageTitle = "Shopping Cart";
        $pageDescription = "Review and manage your items before checkout.";
        require_once(ROOT . '/views/cart/index.php');

        return true;
    }

    /**
     * Handles the checkout process, including form validation and order placement.
     * @return bool
     */
    public function actionCheckout()
    {
        // List of categories for the left menu
        $categories = category::getCategoriesList();

        // Status of successful order placement
        $result = false;
        $errors = [];

        // Variables for the form (initialized as null for clarity)
        $userName = null;
        $userPhone = null;
        $userComment = null;

        // Cart and order details variables
        $productsInCart = cart::getProducts();
        $products = [];
        $totalPrice = 0;
        $totalQuantity = 0;


        // Check if the form was submitted
        if (isset($_POST['submit'])) {
            // Form submitted - Yes

            // Read form data safely
            $userName = $_POST['userName'] ?? '';
            $userPhone = $_POST['userPhone'] ?? '';
            $userComment = $_POST['userComment'] ?? '';

            // --- Field validation ---
            if (!user::checkName($userName)) {
                $errors[] = 'Invalid name. Name must be at least 2 characters long.';
            }
            if (!user::checkPhone($userPhone)) {
                $errors[] = 'Invalid phone number.';
            }

            // Is the form filled out correctly?
            if (empty($errors)) {
                // Form filled out correctly - Yes

                // Gather order information
                $productsInCart = cart::getProducts();
                $userId = user::isGuest() ? false : user::checkLogged();

                // Save order to DB
                $result = order::save($userName, $userPhone, $userComment, $userId, $productsInCart);

                if ($result) {
                    // Notify the administrator about the new order (optional)
                    $adminEmail = 'admin@test.com';
                    $message = 'http://wezom.test/admin/orders';
                    $subject = 'New order!';
                    // mail($adminEmail, $subject, $message);

                    // Clear the cart
                    cart::clear();
                }
            } else {
                // Form filled out correctly? - No
                // Recalculate totals to display on the form again
                $productsInCart = cart::getProducts();
                $productsIds = array_keys($productsInCart);
                $products = product::getProductsByIds($productsIds);
                $totalPrice = cart::getTotalPrice($products);
                $totalQuantity = cart::countItems();
            }
        } else {
            // Form submitted - No

            // Get data from cart
            $productsInCart = cart::getProducts();

            // Are there items in the cart?
            if ($productsInCart == false) {
                // Items in cart - No
                // Redirect user to the homepage to find products
                header("Location: /");
                exit();
            } else {
                // Items in cart - Yes

                // Calculate totals: total price, total quantity
                $productsIds = array_keys($productsInCart);
                $products = product::getProductsByIds($productsIds);
                $totalPrice = cart::getTotalPrice($products);
                $totalQuantity = cart::countItems();

                // Check if user is logged in to pre-fill the form
                if (!user::isGuest()) {
                    // Yes, logged in
                    // Get user information from DB by ID
                    $userId = user::checkLogged();
                    $user = user::getUserById($userId);

                    // Pre-fill the form with user data
                    $userName = $user['name'];
                }
            }
        }

        $pageTitle = "Checkout";
        $pageDescription = "Finalize your order and provide delivery details.";
        require_once(ROOT . '/views/cart/checkout.php');

        return true;
    }

}