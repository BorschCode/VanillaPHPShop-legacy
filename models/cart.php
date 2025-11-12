<?php
/**
 * Cart Model (Utility Class).
 *
 * This class handles all shopping cart logic, including adding, removing, counting,
 * and calculating the total price of products stored in the PHP session ('products').
 */
class Cart
{

    /**
     * Adds a product to the shopping cart (stored in session).
     * If the product already exists, its quantity is incremented.
     *
     * @param int $id The ID of the product to add.
     * @return int The new total count of items in the cart.
     */
    public static function addProduct($id)
    {
        $id = intval($id);

        // Empty array for products in the cart
        $productsInCart = array();

        // Check if products already exist in the cart (stored in session)
        if (isset($_SESSION['products'])) {
            // Fill our array with existing products
            $productsInCart = $_SESSION['products'];
        }

        // If the product exists in the cart but was added again, increment the quantity
        if (array_key_exists($id, $productsInCart)) {
            $productsInCart[$id] ++;
        } else {
            // Add a new product to the cart with quantity 1
            $productsInCart[$id] = 1;
        }

        $_SESSION['products'] = $productsInCart;

        return self::countItems();
    }

    /**
     * Counts the total number of items in the cart (stored in session).
     *
     * @return int The total number of items in the cart. Returns 0 if the cart is empty.
     */
    public static function countItems()
    {
        if (isset($_SESSION['products'])) {
            $count = 0;
            foreach ($_SESSION['products'] as $id => $quantity) {
                $count = $count + $quantity;
            }
            return $count;
        } else {
            return 0;
        }
    }

    /**
     * Retrieves the list of product IDs and their quantities from the session.
     *
     * @return array|false An associative array of product IDs and quantities (e.g., [id => quantity]) or false if the cart is empty.
     */
    public static function getProducts()
    {
        if (isset($_SESSION['products'])) {
            return $_SESSION['products'];
        }
        return false;
    }

    /**
     * Calculates the total price of the products currently in the cart.
     * Requires an array of full product details (including price).
     *
     * @param array $products An array of product details (from the database) that are currently in the cart.
     * @return float The total price of all items in the cart.
     */
    public static function getTotalPrice($products)
    {
        $productsInCart = self::getProducts();

        $total = 0.0;

        if ($productsInCart) {
            foreach ($products as $item) {
                // Ensure the product exists in the cart session before calculating
                if (array_key_exists($item['id'], $productsInCart)) {
                    $total += $item['price'] * $productsInCart[$item['id']];
                }
            }
        }

        return $total;
    }

    /**
     * Clears the entire shopping cart (removes the 'products' session variable).
     *
     * @return void
     */
    public static function clear()
    {
        if (isset($_SESSION['products'])) {
            unset($_SESSION['products']);
        }
    }

    /**
     * Deletes one item instance (decrements quantity) or removes the product entirely from the cart.
     *
     * @param int $id The ID of the product to decrement/remove.
     * @return int The new total count of items in the cart.
     */
    public static function deleteItem($id)
    {
        $id = intval($id);
        $productsInCart = false;

        // Check if products are in the session
        if (isset($_SESSION['products'])) {
            $productsInCart = $_SESSION['products'];
        } else {
            return 0; // Cart is empty, nothing to delete
        }

        // Check if the removable element is in the cart and its quantity
        // If greater than 1, just decrease the quantity by one
        if (array_key_exists($id, $productsInCart) && $productsInCart[$id] > 1) {
            $productsInCart[$id] --;
        } elseif (array_key_exists($id, $productsInCart)) {
            // Otherwise, if quantity is 1, remove the product entirely
            unset($productsInCart[$id]);
        }

        // Update the global session variable
        $_SESSION['products'] = $productsInCart;
        return self::countItems();
    }
}