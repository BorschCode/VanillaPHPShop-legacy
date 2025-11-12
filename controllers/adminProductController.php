<?php
/**
 * Created by PhpStorm.
 * User: George
 * Date: 10.12.2017
 * Time: 1:25
 */
/**
 * Контроллер AdminProductController
 * Управление товарами в админпанели
 */
class adminProductController extends adminBase
{

    /**
     * Action для страницы "Управление товарами"
     */
    public function actionIndex()
    {
        // Access check
        self::checkAdmin();

        // Получаем список товаров
        $productsList = product::getProductsList();

        // Connect view
        require_once(ROOT . '/views/admin_product/index.php');
        return true;
    }

    /**
     * Action для страницы "Добавить товар"
     */
    public function actionCreate()
    {
        // Access check
        self::checkAdmin();

        // Получаем список категорий для выпадающего списка
        $categoriesList = category::getCategoriesListAdmin();
        //echo $_POST['tittle'];
        // Обработка формы
        if (isset($_POST['submit'])) {
            // Если форма отправлена
            // Получаем данные из формы

            $options['tittle'] = $_POST['tittle'];
            $options['code'] = $_POST['code'];
            $options['price'] = $_POST['price'];
            $options['category_id'] = $_POST['category_id'];
            $options['brand'] = $_POST['brand'];
            $options['availability'] = $_POST['availability'];
            $options['description'] = $_POST['description'];
            $options['is_new'] = $_POST['is_new'];
            $options['is_recommended'] = $_POST['is_recommended'];
            $options['status'] = $_POST['status'];

            // Флаг ошибок в форме
            $errors = false;

            // При необходимости можно валидировать значения нужным образом
            if (!isset($options['tittle']) || empty($options['tittle'])) {
                $errors[] = 'Заполните поля';
            }

            if ($errors == false) {
                // Если ошибок нет
                // Добавляем новый товар
                $id = product::createProduct($options);

                // Если запись добавлена
                if ($id) {
                    // Проверим, загружалось ли через форму изображение
                    if (is_uploaded_file($_FILES["image"]["tmp_name"])) {
                            // Желаемая структура папок
                            $structure = "./upload/images/products/{$id}";
                            // Для создания вложенной структуры необходимо указать параметр
                            if (file_exists($structure)) {
                                // Если загружалось, переместим его в нужную папке, дадим новое имя
                                move_uploaded_file($_FILES["image"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . "{$structure}/product_450.jpg");
                            } else {
                                if (!mkdir($structure, 0777, true))
                                {
                                    print_r($structure);
                                    die('Не удалось создать директории...');
                                }
                                else
                                {
                                    // Если загружалось, переместим его в нужную папке, дадим новое имя
                                    move_uploaded_file($_FILES["image"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . "{$structure}/product_450.jpg");
                                }
                            }
                            /*// Желаемая структура папок
                            $structure = "./upload/images/products/{$id}";
                            // Для создания вложенной структуры необходимо указать параметр

                            if (!mkdir($structure, 0777, true)) {
                                print_r($structure);
                                die('Не удалось создать директории...');
                            }
                        // Если загружалось, переместим его в нужную папке, дадим новое имя
                        move_uploaded_file($_FILES["image"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . "{$structure}/product_450.jpg");*/
                        //теперь нужно загружаемую картинку имзенить до размеров 450х450
                        // файл и новый размер
                        $fileNamePath = "{$structure}/product_450.jpg";
                        $image = new SimpleImage();
                        $image->load($fileNamePath);
                        $image->resize(450, 450);
                        $image->save($fileNamePath);
                        //Делаем изображение размером 250х250
                        $newFileNamePath = "{$structure}/product_250.jpg";
                        $image->resize(250, 250);
                        $image->save($newFileNamePath);
                        //Делаем изображение размером 110х110
                        $newFileNamePath = "{$structure}/product_110.jpg";
                        $image->resize(110, 110);
                        $image->save($newFileNamePath);


                    }
                };

                // Перенаправляем пользователя на страницу управлениями товарами
                header("Location: /admin/product");
            }
        }

        // Connect view
        require_once(ROOT . '/views/admin_product/create.php');
        return true;
    }

    /**
     * Action для страницы "Редактировать товар"
     */
    public function actionUpdate($id)
    {
        // Access check
        self::checkAdmin();

        // Получаем список категорий для выпадающего списка
        $categoriesList = category::getCategoriesListAdmin();

        // Получаем данные о конкретном заказе
        $product = product::getProductById($id);

        // Обработка формы
        if (isset($_POST['submit'])) {
            // Если форма отправлена
            // Получаем данные из формы редактирования. При необходимости можно валидировать значения
            $options['tittle'] = $_POST['tittle'];
            $options['code'] = $_POST['code'];
            $options['price'] = $_POST['price'];
            $options['price_new'] = $_POST['price_new'];
            $options['category_id'] = $_POST['category_id'];
            $options['categories'] = json_encode($_POST['categories']);
            $options['brand'] = $_POST['brand'];
            $options['availability'] = $_POST['availability'];
            $options['description'] = $_POST['description'];
            $options['is_new'] = $_POST['is_new'];
            $options['is_recommended'] = $_POST['is_recommended'];
            $options['status'] = $_POST['status'];

            //print_r($_POST);

            // Сохраняем изменения
            if (product::updateProductById($id, $options)) {


                // Если запись сохранена
                // Проверим, загружалось ли через форму изображение
                if (is_uploaded_file($_FILES["image"]["tmp_name"]))
                {
                    // Желаемая структура папок
                    $structure = "./upload/images/products/{$id}";
                    // Для создания вложенной структуры необходимо указать параметр
                    if (file_exists($structure)) {
                        // Если загружалось, переместим его в нужную папке, дадим новое имя
                        move_uploaded_file($_FILES["image"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . "{$structure}/product_450.jpg");

                    } else {
                        if (!mkdir($structure, 0777, true))
                        {
                            print_r($structure);
                            die('Не удалось создать директории...');
                        }
                        else
                        {
                            // Если загружалось, переместим его в нужную папке, дадим новое имя
                            move_uploaded_file($_FILES["image"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . "{$structure}/product_450.jpg");
                        }
                    }

                    //теперь нужно загружаемую картинку имзенить до размеров 450х450
                    // файл и новый размер
                    $fileNamePath = "{$structure}/product_450.jpg";
                    $image = new SimpleImage();
                    $image->load($fileNamePath);
                    $image->resize(450, 450);
                    $image->save($fileNamePath);
                    //Делаем изображение размером 250х250
                    $newFileNamePath = "{$structure}/product_250.jpg";
                    $image->resize(250, 250);
                    $image->save($newFileNamePath);
                    //Делаем изображение размером 110х110
                    $newFileNamePath = "{$structure}/product_110.jpg";
                    $image->resize(110, 110);
                    $image->save($newFileNamePath);
                }
            }
            // Перенаправляем пользователя на страницу управлениями товарами
            header("Location: /admin/product");
        }
        // Connect view
        require_once(ROOT . '/views/admin_product/update.php');
        return true;
    }

    /**
     * Action для страницы "Delete товар"
     */
    public function actionDelete($id)
    {
        // Access check
        self::checkAdmin();

        // Обработка формы
        if (isset($_POST['submit'])) {
            // Если форма отправлена
            // Удаляем товар
            product::deleteProductById($id);

            // Перенаправляем пользователя на страницу управлениями товарами
            header("Location: /admin/product");
        }

        // Connect view
        require_once(ROOT . '/views/admin_product/delete.php');
        return true;
    }





}