<?php
/**
 * Category Model (Utility Class).
 *
 * This model is responsible for handling all database operations related to product categories.
 */
class Category
{

    /**
     * Returns an array of categories for the public-facing site list.
     * Only categories with status = 1 (enabled) are included.
     *
     * @return array<int, array{id: int, name: string}> Array of categories.
     */
    public static function getCategoriesList()
    {
        // Database connection
        $db = db::getConnection();

        // SQL query to the database
        $result = $db->query('SELECT id, name FROM category WHERE status = "1" ORDER BY sort_order, name ASC');

        // Fetching and returning results
        $i = 0;
        $categoryList = array();
        while ($row = $result->fetch()) {
            $categoryList[$i]['id'] = $row['id'];
            $categoryList[$i]['name'] = $row['name'];
            $i++;
        }
        return $categoryList;
    }

    /**
     * Returns an array of all categories for the admin panel.
     * Includes both enabled (1) and disabled (0) categories.
     *
     * @return array<int, array{id: int, name: string, sort_order: int, status: int}> Array of categories.
     */
    public static function getCategoriesListAdmin()
    {
        // Database connection
        $db = db::getConnection();

        // SQL query to the database
        $result = $db->query('SELECT id, name, sort_order, status FROM category ORDER BY sort_order ASC');

        // Fetching and returning results
        $categoryList = array();
        $i = 0;
        while ($row = $result->fetch()) {
            $categoryList[$i]['id'] = $row['id'];
            $categoryList[$i]['name'] = $row['name'];
            $categoryList[$i]['sort_order'] = $row['sort_order'];
            $categoryList[$i]['status'] = $row['status'];
            $i++;
        }
        return $categoryList;
    }

    /**
     * Deletes a category with the specified ID.
     *
     * @param int $id The ID of the category to delete.
     * @return bool Result of the method execution.
     */
    public static function deleteCategoryById($id)
    {
        // Database connection
        $db = db::getConnection();

        // SQL query text
        $sql = 'DELETE FROM category WHERE id = :id';

        // Prepare and execute the statement
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        return $result->execute();
    }

    /**
     * Edits/updates a category with the specified ID.
     *
     * @param int $id The ID of the category.
     * @param string $name The new category name.
     * @param int $sortOrder The new sort order.
     * @param int $status The new status (1 for enabled, 0 for disabled).
     * @return bool Result of the method execution.
     */
    public static function updateCategoryById($id, $name, $sortOrder, $status)
    {
        // Database connection
        $db = db::getConnection();

        // SQL query text
        $sql = "UPDATE category
            SET
                name = :name,
                sort_order = :sort_order,
                status = :status
            WHERE id = :id";

        // Prepare and execute the statement
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':name', $name, PDO::PARAM_STR);
        $result->bindParam(':sort_order', $sortOrder, PDO::PARAM_INT);
        $result->bindParam(':status', $status, PDO::PARAM_INT);
        return $result->execute();
    }

    /**
     * Returns a single category by its ID.
     *
     * @param int $id The ID of the category.
     * @return array<string, mixed> Array with category information.
     */
    public static function getCategoryById($id)
    {
        // Database connection
        $db = db::getConnection();

        // SQL query text
        $sql = 'SELECT * FROM category WHERE id = :id';

        // Prepare the statement
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);

        // Set fetch mode to associative array
        $result->setFetchMode(PDO::FETCH_ASSOC);

        // Execute the query
        $result->execute();

        // Return the data
        return $result->fetch();
    }

    /**
     * Returns the name of a category by its ID.
     *
     * @param int $id The ID of the category.
     * @return string The category name.
     */
    public static function getCategoryText($id)
    {
        $category = self::getCategoryById($id);
        if ($category) {
            return $category['name'];
        }
        return '';
    }

    /**
     * Adds a new category to the database.
     *
     * @param string $name The category name.
     * @param int $sortOrder The sort order value.
     * @param int $status The status (1 for enabled, 0 for disabled).
     * @return bool Result of adding the record.
     */
    public static function createCategory($name, $sortOrder, $status)
    {
        // Database connection
        $db = db::getConnection();

        // SQL query text
        $sql = 'INSERT INTO category (name, sort_order, status) '
            . 'VALUES (:name, :sort_order, :status)';

        // Prepare and execute the statement
        $result = $db->prepare($sql);
        $result->bindParam(':name', $name, PDO::PARAM_STR);
        $result->bindParam(':sort_order', $sortOrder, PDO::PARAM_INT);
        $result->bindParam(':status', $status, PDO::PARAM_INT);
        return $result->execute();
    }

    /**
     * Attempts to save/update category data for a product.
     * NOTE: The original SQL query uses INSERT INTO with a WHERE clause, which is invalid
     * and should likely be an UPDATE statement if the intention is to modify an existing product.
     *
     * @param array $categories Array of categories to save (will be JSON encoded).
     * @param int $productId The ID of the product to update (missing in original function signature).
     * @return bool Result of the database operation.
     */
    public function saveCategories($categories) {

        // Database connection
        $db = db::getConnection();

        // The original query is likely intended to be an UPDATE, but is written as an INSERT.
        // It's left as-is, but note that it's syntactically incorrect for typical UPDATE/INSERT use.
        // Assumes product ID is passed elsewhere or needs to be added to the function signature.
        $sql = 'INSERT INTO products (categories) '
            . 'VALUES (:categories) WHERE id =:id'; // Incorrect SQL syntax for INSERT with WHERE

        $products = json_encode($categories);

        $result = $db->prepare($sql);
        $result->bindParam(':categories', $products, PDO::PARAM_STR);

        // NOTE: The binding for :id is missing here, which will cause an error.
        // The original code has logic flaws here, but is retained for translation purposes.
        // Example fix (requires $id to be passed): $result->bindParam(':id', $productId, PDO::PARAM_INT);

        // Attempt execution (will likely fail due to SQL syntax and missing bindParam(':id'))
        return $result->execute();
    }
}