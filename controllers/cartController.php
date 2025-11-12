<?php
/**
 * Created by PhpStorm.
 * User: George
 * Date: 09.12.2017
 * Time: 22:21
 */

class cartController
{

    public function actionAdd($id)
    {
        // Add product to cart
        cart::addProduct($id);

        // Return user to page
        $referrer = $_SERVER['HTTP_REFERER'];
        header("Location: $referrer");
    }

    public function actionDelete($id)
    {
        // Remove product from cart
        cart::deleteItem($id);
        // Return user to page
        $pageTitle = "Cart";
        $pageDescription = "Cart пользователя";
        header("Location: /cart/");
    }

    public function actionAddAjax($id)
    {
        // Add product to cart
        echo cart::addProduct($id);
        return true;
    }

    public function actionIndex()
    {
        $categories = array();
        $categories = category::getCategoriesList();

        $productsInCart = false;

        // Get data from cart
        $productsInCart = cart::getProducts();

        if ($productsInCart) {
            // Get full product information for list
            $productsIds = array_keys($productsInCart);
            $products = product::getProdustsByIds($productsIds);

            // Get total cost of products
            $totalPrice = cart::getTotalPrice($products);
        }

        $pageTitle = "Cart";
        $pageDescription = "Cart пользователя";
        require_once(ROOT . '/views/cart/index.php');

        return true;
    }

    public function actionCheckout()
    {

        // Список категорий для левого меню
        $categories = array();
        $categories = category::getCategoriesList();


        // Статус успешного оформления заказа
        $result = false;


        // Форма отправлена?
        if (isset($_POST['submit'])) {
            // Форма отправлена? - Да
            // Считываем данные формы
            $userName = $_POST['userName'];
            $userPhone = $_POST['userPhone'];
            $userComment = $_POST['userComment'];

            // Field validation
            $errors = false;
            if (!user::checkName($userName))
                $errors[] = 'Invalid name';
            if (!user::checkPhone($userPhone))
                $errors[] = 'Invalid phone number';

            // Форма заполнена корректно?
            if ($errors == false) {
                // Форма заполнена корректно? - Да
                // Сохраняем заказ в базе данных
                // Собираем информацию о заказе
                $productsInCart = cart::getProducts();
                if (user::isGuest()) {
                    $userId = false;
                } else {
                    $userId = user::checkLogged();
                }

                // Сохраняем заказ в БД
                $result = order::save($userName, $userPhone, $userComment, $userId, $productsInCart);

                if ($result) {
                    // Оповещаем администратора о новом заказе
                    $adminEmail = 'admin@test.com';
                    $message = 'http://wezom.test/admin/orders';
                    $subject = 'New order!';
                    mail($adminEmail, $subject, $message);

                    // Очищаем корзину
                    cart::clear();
                }
            } else {
                // Форма заполнена корректно? - Нет
                // Итоги: общая стоимость, количество товаров
                $productsInCart = cart::getProducts();
                $productsIds = array_keys($productsInCart);
                $products = product::getProdustsByIds($productsIds);
                $totalPrice = cart::getTotalPrice($products);
                $totalQuantity = cart::countItems();
            }
        } else {
            // Форма отправлена? - Нет
            // Получием данные из корзины
            $productsInCart = cart::getProducts();

            // В корзине есть товары?
            if ($productsInCart == false) {
                // В корзине есть товары? - Нет
                // Отправляем пользователя на главную искать товары
                header("Location: /");
            } else {
                // В корзине есть товары? - Да
                // Итоги: общая стоимость, количество товаров
                $productsIds = array_keys($productsInCart);
                $products = product::getProdustsByIds($productsIds);
                $totalPrice = cart::getTotalPrice($products);
                $totalQuantity = cart::countItems();


                $userName = false;
                $userPhone = false;
                $userComment = false;

                // Пользователь авторизирован?
                if (user::isGuest()) {
                    // Нет
                    // Значения для формы пустые
                } else {
                    // Да, авторизирован
                    // Get user information from DB по id
                    $userId = user::checkLogged();
                    $user = user::getUserById($userId);
                    // Подставляем в форму
                    $userName = $user['name'];
                }
            }
        }
        $pageTitle = "Cart";
        $pageDescription = "Cart пользователя";
        require_once(ROOT . '/views/cart/checkout.php');

        return true;
    }

}
