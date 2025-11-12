<?php
/**
 * Created by PhpStorm.
 * User: George
 * Date: 10.12.2017
 * Time: 1:12
 */

class adminController extends adminBase
{
    /**
     * Action для стартовой страницы "Панель администратора"
     */
    public function actionIndex()
    {
        // Access check
        self::checkAdmin();

        // Connect view
        require_once(ROOT . '/views/admin/index.php');
        return true;
    }

}