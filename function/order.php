<?php
/**
 * Class Order - model for working with orders
 */
class Order
{
    /**
     * Saves a new order
     *
     * @param string $userName <p>User name</p>
     * @param string $userPhone <p>User phone</p>
     * @param string $userComment <p>User comment</p>
     * @param integer $userId <p>User ID (can be 0 for guests)</p>
     * @param array $products <p>Array of product IDs and quantities</p>
     * @return boolean <p>Result of method execution</p>
     */
    public static function save($userName, $userPhone, $userComment, $userId, $products)
    {
        // Database connection
        $db = db::getConnection();

        // SQL query text
        $sql = 'INSERT INTO product_order (user_name, user_phone, user_comment, user_id, products) '
            . 'VALUES (:user_name, :user_phone, :user_comment, :user_id, :products)';

        // Encode the products array into a JSON string for storage
        $products = json_encode($products);

        $result = $db->prepare($sql);
        $result->bindParam(':user_name', $userName, PDO::PARAM_STR);
        $result->bindParam(':user_phone', $userPhone, PDO::PARAM_STR);
        $result->bindParam(':user_comment', $userComment, PDO::PARAM_STR);
        $result->bindParam(':user_id', $userId, PDO::PARAM_STR); // Storing as STR for flexibility, but INT is often better practice
        $result->bindParam(':products', $products, PDO::PARAM_STR);

        return $result->execute();
    }

    /**
     * Returns a list of all orders
     *
     * @return array <p>List of orders</p>
     */
    public static function getOrdersList()
    {
        // Database connection
        $db = db::getConnection();

        // Fetching and returning results
        $result = $db->query('SELECT id, user_name, user_phone, date, status FROM product_order ORDER BY id DESC');
        $ordersList = array();
        $i = 0;
        while ($row = $result->fetch()) {
            $ordersList[$i]['id'] = $row['id'];
            $ordersList[$i]['user_name'] = $row['user_name'];
            $ordersList[$i]['user_phone'] = $row['user_phone'];
            $ordersList[$i]['date'] = $row['date'];
            $ordersList[$i]['status'] = $row['status'];
            $i++;
        }
        return $ordersList;
    }

    /**
     * Returns a text explanation of the order status:<br/>
     * <i>1 - New order, 2 - Processing, 3 - Delivering, 4 - Closed</i>
     *
     * @param integer $status <p>Status code</p>
     * @return string <p>Text explanation of the status</p>
     */
    public static function getStatusText($status)
    {
        switch ($status) {
            case '1':
                return 'New order';
                break;
            case '2':
                return 'Processing';
                break;
            case '3':
                return 'Delivering';
                break;
            case '4':
                return 'Closed';
                break;
        }
    }

    /**
     * Returns an order with the specified ID
     *
     * @param integer $id <p>Order ID</p>
     * @return array <p>Array with order information</p>
     */
    public static function getOrderById($id)
    {
        // Database connection
        $db = db::getConnection();

        // SQL query text
        $sql = 'SELECT * FROM product_order WHERE id = :id';

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);

        // Specify that we want to get the data as an associative array
        $result->setFetchMode(PDO::FETCH_ASSOC);

        // Execute the query
        $result->execute();

        // Return the data
        return $result->fetch();
    }

    /**
     * Deletes an order with the given ID
     *
     * @param integer $id <p>Order ID</p>
     * @return boolean <p>Result of method execution</p>
     */
    public static function deleteOrderById($id)
    {
        // Database connection
        $db = db::getConnection();

        // SQL query text
        $sql = 'DELETE FROM product_order WHERE id = :id';

        // Fetching and returning results. A prepared statement is used.
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        return $result->execute();
    }

    /**
     * Edits an order with the given ID
     *
     * @param integer $id <p>Order ID</p>
     * @param string $userName <p>Client name</p>
     * @param string $userPhone <p>Client phone</p>
     * @param string $userComment <p>Client comment</p>
     * @param string $date <p>Order date</p>
     * @param integer $status <p>Status code</p>
     * @return boolean <p>Result of method execution</p>
     */
    public static function updateOrderById($id, $userName, $userPhone, $userComment, $date, $status)
    {
        // Database connection
        $db = db::getConnection();

        // SQL query text
        $sql = "UPDATE product_order
            SET 
                user_name = :user_name, 
                user_phone = :user_phone, 
                user_comment = :user_comment, 
                date = :date, 
                status = :status 
            WHERE id = :id";

        // Fetching and returning results. A prepared statement is used.
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':user_name', $userName, PDO::PARAM_STR);
        $result->bindParam(':user_phone', $userPhone, PDO::PARAM_STR);
        $result->bindParam(':user_comment', $userComment, PDO::PARAM_STR);
        $result->bindParam(':date', $date, PDO::PARAM_STR);
        $result->bindParam(':status', $status, PDO::PARAM_INT);
        return $result->execute();
    }

}