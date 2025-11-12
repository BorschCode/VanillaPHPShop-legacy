<?php
/**
 * Product Model (Utility Class).
 *
 * This model is responsible for handling all database operations related to products.
 */
class Product
{

    /**
     * Default number of products to show on a page.
     */
    const SHOW_BY_DEFAULT = 6;

    /**
     * Returns an array of the latest products.
     *
     * @param int $count [optional] The number of products to return.
     * @return array<int, array{id: int, tittle: string, price: float, price_new: float, is_new: int}> Array of products.
     */
    public static function getLatestProducts($count = self::SHOW_BY_DEFAULT)
    {
        // Database connection
        $db = db::getConnection();

        // SQL query text
        $sql = 'SELECT id, tittle, price, price_new, is_new FROM products '
            . 'WHERE status = "1" ORDER BY id DESC '
            . 'LIMIT :count';

        // Use a prepared statement
        $result = $db->prepare($sql);
        $result->bindParam(':count', $count, PDO::PARAM_INT);

        // Set fetch mode to associative array
        $result->setFetchMode(PDO::FETCH_ASSOC);

        // Execute the command
        $result->execute();

        // Fetch and return results
        $i = 0;
        $productsList = array();
        while ($row = $result->fetch()) {
            $productsList[$i]['id'] = $row['id'];
            $productsList[$i]['tittle'] = $row['tittle'];
            $productsList[$i]['price'] = $row['price'];
            $productsList[$i]['price_new'] = $row['price_new'];
            $productsList[$i]['is_new'] = $row['is_new'];
            $i++;
        }
        return $productsList;
    }

    /**
     * Returns a list of products in the specified category, with pagination.
     *
     * @param int $categoryId The ID of the category.
     * @param int $page [optional] The current page number.
     * @return array<int, array{id: int, tittle: string, price: float, price_new: float, is_new: int}> Array of products.
     */
    public static function getProductsListByCategory($categoryId, $page = 1)
    {
        $limit = self::SHOW_BY_DEFAULT;
        // Offset (for the query)
        $offset = ($page - 1) * self::SHOW_BY_DEFAULT;

        // Database connection
        $db = db::getConnection();

        // SQL query text
        $sql = 'SELECT id, tittle, price, price_new, is_new FROM products '
            . 'WHERE status = 1 AND category_id = :category_id '
            . 'ORDER BY id ASC LIMIT :limit OFFSET :offset';

        // Use a prepared statement
        $result = $db->prepare($sql);
        $result->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
        $result->bindParam(':limit', $limit, PDO::PARAM_INT);
        $result->bindParam(':offset', $offset, PDO::PARAM_INT);

        // Execute the command
        $result->execute();

        // Fetch and return results
        $i = 0;
        $products = array();
        while ($row = $result->fetch()) {
            $products[$i]['id'] = $row['id'];
            $products[$i]['tittle'] = $row['tittle'];
            $products[$i]['price'] = $row['price'];
            $products[$i]['price_new'] = $row['price_new'];
            $products[$i]['is_new'] = $row['is_new'];
            $i++;
        }
        return $products;
    }

    /**
     * Returns a product with the specified ID.
     *
     * @param int $id The ID of the product.
     * @return array<string, mixed> Array with product information.
     */
    public static function getProductById($id)
    {
        // Database connection
        $db = db::getConnection();

        // SQL query text
        $sql = 'SELECT * FROM products WHERE id = :id';

        // Use a prepared statement
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);

        // Set fetch mode to associative array
        $result->setFetchMode(PDO::FETCH_ASSOC);

        // Execute the command
        $result->execute();

        // Fetch and return results
        return $result->fetch();
    }

    /**
     * Returns the total number of products in the specified category.
     *
     * @param int $categoryId The ID of the category.
     * @return int The total count of products.
     */
    public static function getTotalProductsInCategory($categoryId)
    {
        // Database connection
        $db = db::getConnection();

        // SQL query text
        $sql = 'SELECT count(id) AS count FROM products WHERE status="1" AND category_id = :category_id';

        // Use a prepared statement
        $result = $db->prepare($sql);
        $result->bindParam(':category_id', $categoryId, PDO::PARAM_INT);

        // Execute the command
        $result->execute();

        // Return the count value
        $row = $result->fetch();
        return (int) $row['count'];
    }

    /**
     * Returns a list of products with the specified identifiers (used primarily for cart display).
     *
     * @param int[] $idsArray Array with product IDs.
     * @return array<int, array{id: int, code: string, tittle: string, price: float}> Array with the list of products.
     */
    public static function getProductsByIds($idsArray)
    {
        // Database connection
        $db = db::getConnection();

        // Convert the array to a comma-separated string for the query condition
        $idsString = implode(',', $idsArray);

        // SQL query text
        $sql = "SELECT * FROM products WHERE status='1' AND id IN ($idsString)";

        // Note: Using query() here is safe since $idsString is guaranteed to be clean integers
        // from a prior call, but prepare() is generally safer.
        $result = $db->query($sql);

        // Set fetch mode to associative array
        $result->setFetchMode(PDO::FETCH_ASSOC);

        // Fetch and return results
        $i = 0;
        $products = array();
        while ($row = $result->fetch()) {
            $products[$i]['id'] = $row['id'];
            $products[$i]['code'] = $row['code'];
            $products[$i]['tittle'] = $row['tittle'];
            $products[$i]['price'] = $row['price'];
            $i++;
        }
        return $products;
    }

    /**
     * Returns a list of recommended products.
     *
     * @return array<int, array{id: int, tittle: string, price: float, price_new: float, is_new: int}> Array of products.
     */
    public static function getRecommendedProducts()
    {
        // Database connection
        $db = db::getConnection();

        // Fetch and return results
        $result = $db->query('SELECT id, tittle, price, price_new, is_new FROM products '
            . 'WHERE status = "1" AND is_recommended = "1" '
            . 'ORDER BY id DESC');
        $i = 0;
        $productsList = array();
        while ($row = $result->fetch()) {
            $productsList[$i]['id'] = $row['id'];
            $productsList[$i]['tittle'] = $row['tittle'];
            $productsList[$i]['price'] = $row['price'];
            $productsList[$i]['price_new'] = $row['price_new'];
            $productsList[$i]['is_new'] = $row['is_new'];
            $i++;
        }
        return $productsList;
    }

    /**
     * Returns a list of all products (for admin panel).
     *
     * @return array<int, array{id: int, tittle: string, price: float, price_new: float, category_id: int, code: string}> Array of products.
     */
    public static function getProductsList()
    {
        // Database connection
        $db = db::getConnection();

        // Fetch and return results
        $result = $db->query('SELECT id, tittle, price, price_new, category_id, code FROM products ORDER BY id ASC');
        $productsList = array();
        $i = 0;
        while ($row = $result->fetch()) {
            $productsList[$i]['id'] = $row['id'];
            $productsList[$i]['tittle'] = $row['tittle'];
            $productsList[$i]['code'] = $row['code'];
            $productsList[$i]['price'] = $row['price'];
            $productsList[$i]['price_new'] = $row['price_new'];
            $productsList[$i]['category_id'] = $row['category_id'];
            $i++;
        }
        return $productsList;
    }

    /**
     * Deletes a product with the specified ID.
     *
     * @param int $id The ID of the product to delete.
     * @return bool Result of the method execution.
     */
    public static function deleteProductById($id)
    {
        // Database connection
        $db = db::getConnection();

        // SQL query text
        $sql = 'DELETE FROM products WHERE id = :id';

        // Use a prepared statement
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        return $result->execute();
    }

    /**
     * Edits/updates a product with the specified ID.
     *
     * @param int $id The ID of the product.
     * @param array<string, mixed> $options Array with product information (fields to update).
     * @return bool Result of the method execution.
     */
    public static function updateProductById($id, $options)
    {
        // Database connection
        $db = db::getConnection();

        // SQL query text
        $sql = "UPDATE products
            SET
                tittle = :tittle,
                code = :code,
                price = :price,
                price_new = :price_new,
                category_id = :category_id,
                brand = :brand,
                availability = :availability,
                description = :description,
                is_new = :is_new,
                is_recommended = :is_recommended,
                status = :status,
                categories  = :categories
            WHERE id = :id";

        // Use a prepared statement
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':tittle', $options['tittle'], PDO::PARAM_STR);
        $result->bindParam(':code', $options['code'], PDO::PARAM_STR);
        $result->bindParam(':price', $options['price'], PDO::PARAM_STR);
        $result->bindParam(':price_new', $options['price_new'], PDO::PARAM_STR);
        $result->bindParam(':category_id', $options['category_id'], PDO::PARAM_INT);
        $result->bindParam(':brand', $options['brand'], PDO::PARAM_STR);
        $result->bindParam(':availability', $options['availability'], PDO::PARAM_INT);
        $result->bindParam(':description', $options['description'], PDO::PARAM_STR);
        $result->bindParam(':is_new', $options['is_new'], PDO::PARAM_INT);
        $result->bindParam(':is_recommended', $options['is_recommended'], PDO::PARAM_INT);
        $result->bindParam(':status', $options['status'], PDO::PARAM_INT);
        $result->bindParam(':categories', $options['categories'], PDO::PARAM_STR); // Changed type to STR as it likely holds JSON/string data
        return $result->execute();
    }

    /**
     * Adds a new product to the database.
     *
     * @param array<string, mixed> $options Array with product information.
     * @return int The ID of the newly inserted record, or 0 on failure.
     */
    public static function createProduct($options)
    {
        // Database connection
        $db = db::getConnection();

        // SQL query text
        // NOTE: price_new is commented out in original SQL, so it's removed here too.
        $sql = 'INSERT INTO products '
            . '(tittle, code, price, category_id, brand, availability,'
            . 'description, is_new, is_recommended, status)'
            . 'VALUES '
            . '(:tittle, :code, :price, :category_id, :brand, :availability,'
            . ':description, :is_new, :is_recommended, :status)';

        // Use a prepared statement
        $result = $db->prepare($sql);
        $result->bindParam(':tittle', $options['tittle'], PDO::PARAM_STR);
        $result->bindParam(':code', $options['code'], PDO::PARAM_STR);
        $result->bindParam(':price', $options['price'], PDO::PARAM_STR);
        //$result->bindParam(':price_new', $options['price_new'], PDO::PARAM_STR); // Removed binding to match SQL query
        $result->bindParam(':category_id', $options['category_id'], PDO::PARAM_INT);
        $result->bindParam(':brand', $options['brand'], PDO::PARAM_STR);
        $result->bindParam(':availability', $options['availability'], PDO::PARAM_INT);
        $result->bindParam(':description', $options['description'], PDO::PARAM_STR);
        $result->bindParam(':is_new', $options['is_new'], PDO::PARAM_INT);
        $result->bindParam(':is_recommended', $options['is_recommended'], PDO::PARAM_INT);
        $result->bindParam(':status', $options['status'], PDO::PARAM_INT);
        if ($result->execute()) {
            // If the query is successful, return the ID of the newly added record
            return (int) $db->lastInsertId();
        }
        // Otherwise, return 0
        return 0;
    }

    /**
     * Returns the textual explanation of product availability:
     * <i>0 - On order, 1 - In stock</i>
     *
     * @param int|string $availability The availability status (0 or 1).
     * @return string The textual explanation in Ukrainian.
     */
    public static function getAvailabilityText($availability)
    {
        switch ($availability) {
            case '1':
                return 'В наявності'; // In stock (Ukrainian)
                break;
            case '0':
                return 'Під замовлення'; // On order (Ukrainian)
                break;
            default:
                return 'Невідомо'; // Unknown (Ukrainian)
                break;
        }
    }

    /**
     * Returns the path to the 110x110 px image.
     *
     * @param int $id The ID of the product.
     * @return string The path to the image or a placeholder.
     */
    public static function getLowImage($id)
    {
        // Name of the placeholder image
        $noImage = 'no_image_110.jpg';
        // Path to the product folder
        $path = '/upload/images/products/';
        // Path to the product image
        $pathToProductImage = $path . $id . '/product_110.jpg';

        if (file_exists($_SERVER['DOCUMENT_ROOT'] . $pathToProductImage)) {
            // If the image for the product exists
            // Return the path to the product image
            return $pathToProductImage;
        }

        // Return the path to the placeholder image
        return $path . $noImage;
    }

    /**
     * Returns the path to the 250x250 px image.
     *
     * @param int $id The ID of the product.
     * @return string The path to the image or a placeholder.
     */
    public static function getMediumImage($id)
    {
        // Name of the placeholder image
        $noImage = 'no_image_250.jpg';
        // Path to the product folder
        $path = '/upload/images/products/';

        // Path to the product image
        $pathToProductImage = $path . $id . '/product_250.jpg';

        if (file_exists($_SERVER['DOCUMENT_ROOT'] . $pathToProductImage)) {
            // If the image for the product exists
            // Return the path to the product image
            return $pathToProductImage;
        }

        // Return the path to the placeholder image
        return $path . $noImage;
    }

    /**
     * Returns the path to the 450x450 px image.
     *
     * @param int $id The ID of the product.
     * @return string The path to the image or a placeholder.
     */
    public static function getLargeImage($id)
    {
        // Name of the placeholder image
        $noImage = 'no_image_450.jpg';
        // Path to the product folder
        $path = '/upload/images/products/';

        // Path to the product image
        $pathToProductImage = $path . $id . '/product_450.jpg';

        if (file_exists($_SERVER['DOCUMENT_ROOT'] . $pathToProductImage)) {
            // If the image for the product exists
            // Return the path to the product image
            return $pathToProductImage;
        }

        // Return the path to the placeholder image
        return $path . $noImage;
    }
}