<?php
/**
 * Created by PhpStorm.
 * Date: 08.12.2017
 */
/*include_once ROOT . '/models/category.php';
include_once ROOT . '/models/product.php';*/
class siteController
{
    public function actionIndex()
    {
        $categories = array();
        $categories = category::getCategoriesList();

        $latestProducts = array();
        $latestProducts = product::getLatestProducts(6);

        /*// Список товаров для слайдера
        $sliderProducts = Product::getRecommendedProducts();*/

        $pageTitle = "Home";
        $pageDescription = "Home страница магазина";

require_once (ROOT.'/views/main/index.php');
        return true;
    }

    public function actionContact() {

        $userEmail = '';
        $userText = '';
        $result = false;

        if (isset($_POST['submit'])) {

            $userEmail = $_POST['userEmail'];
            $userText = $_POST['userText'];

            $errors = false;

            // Field validation
            if (!user::checkEmail($userEmail)) {
                $errors[] = 'Invalid email';
            }

            if ($errors == false) {
                $adminEmail = 'admin@test.com';
                $message = "Текст: {$userText}. От {$userEmail}";
                $subject = 'Email subject';
                $result = mail($adminEmail, $subject, $message);
                $result = true;
            }

        }
        $pageTitle = "Contacts";
        $pageDescription = "Store contacts page";
        require_once(ROOT . '/views/main/contact.php');

        return true;
    }

    public function actionBLog()
    {
        $pageTitle = "Blog";
        $pageDescription = "Blog page";
        require_once(ROOT . '/views/blog/index.php');
        return true;
    }

    public function actionAbout()
    {
        $pageTitle = "About Us";
        $pageDescription = "Success story";
        require_once(ROOT . '/views/about/index.php');
        return true;
    }

/*    public function actionTest() {

        $pageTitle = "About Us";
        $pageDescription = "Success story";
        require_once(ROOT . '/views/test.php');
        return true;
    }*/

}