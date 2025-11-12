<?php
/**
 * Created by PhpStorm.
 * User: George
 * Date: 10.12.2017
 * Time: 1:12
 */

// Include necessary base classes
include_once ROOT . '/function/adminBase.php';

/**
 * AdminController
 * Main controller for the Admin Panel start page
 */
class AdminController extends AdminBase
{
    /**
     * Action for the main "Administrator Panel" start page.
     * @return bool
     */
    public function actionIndex()
    {
        // Access check: ensures only admin users can access this page
        self::checkAdmin();

        // Connect view
        require_once(ROOT . '/views/admin/index.php');
        return true;
    }

}