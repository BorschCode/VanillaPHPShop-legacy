<?php
/**
 * Created by PhpStorm.
 * User: George
 * Date: 10.12.2017
 * Time: 1:48
 */

// Include necessary models and base classes
include_once ROOT . '/function/adminBase.php';
include_once ROOT . '/function/order.php';
include_once ROOT . '/models/product.php';

/**
 * AdminOrderController
 * Manages orders in the admin panel
 */
class adminOrderController extends AdminBase
{

    /**
     * Action for the "Manage Orders" page.
     * @return bool
     */
    public function actionIndex()
    {
        // Access check
        self::checkAdmin();

        // Get the list of orders
        $ordersList = Order::getOrdersList();

        // Connect view
        require_once(ROOT . '/views/admin_order/index.php');
        return true;
    }

    /**
     * Action for the "Edit Order" page.
     * @param int $id The order ID to update.
     * @return bool
     */
    public function actionUpdate($id)
    {
        // Access check
        self::checkAdmin();

        // Get data for the specific order
        $order = Order::getOrderById($id);

        // Form processing
        if (isset($_POST['submit'])) {
            // If the form is submitted
            // Get data from the form
            $userName = $_POST['userName'];
            $userPhone = $_POST['userPhone'];
            $userComment = $_POST['userComment'];
            $date = $_POST['date'];
            $status = $_POST['status'];

            // Save changes
            Order::updateOrderById($id, $userName, $userPhone, $userComment, $date, $status);

            // Redirect user to the order view page
            header("Location: /admin/order/view/$id");
            exit();
        }

        // Connect view
        require_once(ROOT . '/views/admin_order/update.php');
        return true;
    }

    /**
     * Action for the "View Order" page.
     * @param int $id The order ID to view.
     * @return bool
     */
    public function actionView($id)
    {
        // Access check
        self::checkAdmin();

        // Get data for the specific order
        $order = Order::getOrderById($id);

        // Get an array with product IDs and quantities (stored as JSON)
        $productsQuantity = json_decode($order['products'], true);

        // Get an array with product IDs
        $productsIds = array_keys($productsQuantity);

        // Get the list of products in the order
        $products = product::getProductsByIds($productsIds);

        // Connect view
        require_once(ROOT . '/views/admin_order/view.php');
        return true;
    }

    /**
     * Action for the "Delete Order" page.
     * @param int $id The order ID to delete.
     * @return bool
     */
    public function actionDelete($id)
    {
        // Access check
        self::checkAdmin();

        // Form processing
        if (isset($_POST['submit'])) {
            // If the form is submitted, delete the order
            Order::deleteOrderById($id);

            // Redirect user to the order management page
            header("Location: /admin/order");
            exit();
        }

        // Connect view (displays confirmation form)
        require_once(ROOT . '/views/admin_order/delete.php');
        return true;
    }

}