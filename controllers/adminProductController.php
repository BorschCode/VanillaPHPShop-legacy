<?php
/**
 * Created by PhpStorm.
 * User: George
 * Date: 10.12.2017
 * Time: 1:25
 */

// Include necessary models and base classes
include_once ROOT . '/function/adminBase.php';
include_once ROOT . '/models/product.php';
include_once ROOT . '/models/category.php';
include_once ROOT . '/function/simpleImage.php'; // Assuming this component exists

/**
 * AdminProductController
 * Manages products in the admin panel
 */
class AdminProductController extends AdminBase
{

    /**
     * Action for the "Manage Products" page.
     * @return bool
     */
    public function actionIndex()
    {
        // Check admin access
        self::checkAdmin();

        // Fetch the list of all products
        $productsList = Product::getProductsList();

        // Include the view file
        require_once(ROOT . '/views/admin_product/index.php');
        return true;
    }

    /**
     * Action for the "Add Product" page.
     * @return bool
     */
    public function actionCreate()
    {
        // Check admin access
        self::checkAdmin();

        // Fetch the list of categories for the dropdown
        $categoriesList = Category::getCategoriesListAdmin();

        $errors = [];
        $options = [];

        // Form processing
        if (isset($_POST['submit'])) {
            // Retrieve data from the form
            $options['title'] = $_POST['tittle'] ?? ''; // Corrected typo in variable name for consistency
            $options['code'] = $_POST['code'] ?? '';
            $options['price'] = $_POST['price'] ?? 0;
            $options['category_id'] = $_POST['category_id'] ?? 0;
            $options['brand'] = $_POST['brand'] ?? '';
            $options['availability'] = $_POST['availability'] ?? 1;
            $options['description'] = $_POST['description'] ?? '';
            $options['is_new'] = $_POST['is_new'] ?? 0;
            $options['is_recommended'] = $_POST['is_recommended'] ?? 0;
            $options['status'] = $_POST['status'] ?? 1;

            // Validate values as necessary
            if (empty($options['title'])) {
                $errors[] = 'Please fill in the product title.';
            }

            if (empty($errors)) {
                // If there are no errors, add the new product
                $id = Product::createProduct($options);

                // If the record was successfully added
                if ($id) {
                    // Check if an image file was uploaded via the form
                    if (is_uploaded_file($_FILES["image"]["tmp_name"])) {

                        // Desired full path for product image directory
                        $structure = "/upload/images/products/{$id}";
                        $fullPath = $_SERVER['DOCUMENT_ROOT'] . $structure;

                        // Create the directory if it doesn't exist (recursive)
                        if (!is_dir($fullPath)) {
                            if (!mkdir($fullPath, 0777, true)) {
                                // Error handling: Failed to create directory
                                // In a real system, this should be logged, not die()
                                die("Failed to create directories for product ID {$id}.");
                            }
                        }

                        // Define file path after move
                        $fileNamePath_450 = "{$fullPath}/product_450.jpg";

                        // Move the uploaded file
                        if (move_uploaded_file($_FILES["image"]["tmp_name"], $fileNamePath_450)) {
                            // Resize the uploaded image to 450x450
                            $image = new SimpleImage();
                            $image->load($fileNamePath_450);
                            $image->resize(450, 450);
                            $image->save($fileNamePath_450);

                            // Resize to 250x250
                            $fileNamePath_250 = "{$fullPath}/product_250.jpg";
                            $image->resize(250, 250);
                            $image->save($fileNamePath_250);

                            // Resize to 110x110
                            $fileNamePath_110 = "{$fullPath}/product_110.jpg";
                            $image->resize(110, 110);
                            $image->save($fileNamePath_110);
                        }
                    }
                }

                // Redirect user to the product management page
                header("Location: /admin/product");
                exit();
            }
        }

        // Include the view file
        require_once(ROOT . '/views/admin_product/create.php');
        return true;
    }

    /**
     * Action for the "Edit Product" page.
     * @param int $id The product ID to edit.
     * @return bool
     */
    public function actionUpdate($id)
    {
        // Check admin access
        self::checkAdmin();

        // Fetch the list of categories for the dropdown
        $categoriesList = Category::getCategoriesListAdmin();

        // Get data for the specific product
        $product = Product::getProductById($id);

        // Form processing
        if (isset($_POST['submit'])) {
            // Retrieve data from the edit form. Validate values as necessary
            $options['title'] = $_POST['tittle'] ?? $product['title'];
            $options['code'] = $_POST['code'] ?? $product['code'];
            $options['price'] = $_POST['price'] ?? $product['price'];
            $options['price_new'] = $_POST['price_new'] ?? $product['price_new'];
            $options['category_id'] = $_POST['category_id'] ?? $product['category_id'];
            // Store multiple categories as a JSON string
            $options['categories'] = json_encode($_POST['categories'] ?? []);
            $options['brand'] = $_POST['brand'] ?? $product['brand'];
            $options['availability'] = $_POST['availability'] ?? $product['availability'];
            $options['description'] = $_POST['description'] ?? $product['description'];
            $options['is_new'] = $_POST['is_new'] ?? $product['is_new'];
            $options['is_recommended'] = $_POST['is_recommended'] ?? $product['is_recommended'];
            $options['status'] = $_POST['status'] ?? $product['status'];

            // Save changes
            if (Product::updateProductById($id, $options)) {

                // Check if an image file was uploaded via the form
                if (is_uploaded_file($_FILES["image"]["tmp_name"]))
                {
                    // Desired full path for product image directory
                    $structure = "/upload/images/products/{$id}";
                    $fullPath = $_SERVER['DOCUMENT_ROOT'] . $structure;

                    // Create the directory if it doesn't exist (recursive)
                    if (!is_dir($fullPath)) {
                        if (!mkdir($fullPath, 0777, true)) {
                            // Error handling: Failed to create directory
                            die("Failed to create directories for product ID {$id}.");
                        }
                    }

                    // Define file path after move
                    $fileNamePath_450 = "{$fullPath}/product_450.jpg";

                    // Move the uploaded file
                    if (move_uploaded_file($_FILES["image"]["tmp_name"], $fileNamePath_450)) {
                        // Resize the uploaded image to 450x450
                        $image = new SimpleImage();
                        $image->load($fileNamePath_450);
                        $image->resize(450, 450);
                        $image->save($fileNamePath_450);

                        // Resize to 250x250
                        $fileNamePath_250 = "{$fullPath}/product_250.jpg";
                        $image->resize(250, 250);
                        $image->save($fileNamePath_250);

                        // Resize to 110x110
                        $fileNamePath_110 = "{$fullPath}/product_110.jpg";
                        $image->resize(110, 110);
                        $image->save($fileNamePath_110);
                    }
                }
            }
            // Redirect user to the product management page
            header("Location: /admin/product");
            exit();
        }

        // Include the view file
        require_once(ROOT . '/views/admin_product/update.php');
        return true;
    }

    /**
     * Action for the "Delete Product" page.
     * @param int $id The product ID to delete.
     * @return bool
     */
    public function actionDelete($id)
    {
        // Check admin access
        self::checkAdmin();

        // Form processing
        if (isset($_POST['submit'])) {
            // If the form was submitted, delete the product
            Product::deleteProductById($id);

            // Redirect user to the product management page
            header("Location: /admin/product");
            exit();
        }

        // Include the view file (displays confirmation form)
        require_once(ROOT . '/views/admin_product/delete.php');
        return true;
    }
}