<?php
/**
 * Routing Configuration File.
 *
 * All application routes are defined here.
 * The structure is: 'pattern' => 'controller/action/param1/param2'
 */

/*
 * Routing structure aliases:
 * Domen/alias/c<id> - Product Category --> CatalogController
 * Domen/alias/p<id> - Single Product --> ProductController
 */

return array(

    // Individual Product Page: /alias/p{id}
    'alias/p([0-9]+)' => 'product/view/$1', // actionView in ProductController

    // Catalog Main Page: /catalog
    'catalog' => 'catalog/index', // actionIndex in CatalogController

    // Category with Pagination: /alias/c{id}/page-{number}
    'alias/c([0-9]+)/page-([0-9]+)' => 'catalog/category/$1/$2', // actionCategory in CatalogController
    // Category without Pagination: /alias/c{id}
    'alias/c([0-9]+)' => 'catalog/category/$1', // actionCategory in CatalogController

    // Cart routes:
    'cart/checkout' => 'cart/checkout',         // actionCheckout in CartController (previously actionAdd - fixed comment)
    'cart/delete/([0-9]+)' => 'cart/delete/$1', // actionDelete in CartController
    'cart/add/([0-9]+)' => 'cart/add/$1',       // actionAdd in CartController (direct redirect after adding)
    'cart/addAjax/([0-9]+)' => 'cart/addAjax/$1', // actionAddAjax in CartController (for AJAX calls)
    'cart' => 'cart/index',                     // actionIndex in CartController

    // User/Cabinet routes:
    'user/register' => 'user/register',
    'user/login' => 'user/login',
    'user/logout' => 'user/logout',
    'cabinet/edit' => 'cabinet/edit',
    'cabinet' => 'cabinet/index',

    // Admin Product Management:
    'admin/product/create' => 'adminProduct/create',
    'admin/product/update/([0-9]+)' => 'adminProduct/update/$1',
    'admin/product/delete/([0-9]+)' => 'adminProduct/delete/$1',
    'admin/product' => 'adminProduct/index',

    // Admin Category Management:
    'admin/category/create' => 'adminCategory/create',
    'admin/category/update/([0-9]+)' => 'adminCategory/update/$1',
    'admin/category/delete/([0-9]+)' => 'adminCategory/delete/$1',
    'admin/category' => 'adminCategory/index',

    // Admin Order Management:
    'admin/order/update/([0-9]+)' => 'adminOrder/update/$1',
    'admin/order/delete/([0-9]+)' => 'adminOrder/delete/$1',
    'admin/order/view/([0-9]+)' => 'adminOrder/view/$1',
    'admin/order' => 'adminOrder/index',

    // Admin Panel Main Page:
    'admin' => 'admin/index',

    // Site information pages:
    'contacts' => 'site/contact', // actionContact in SiteController
    'about' => 'site/about',       // actionAbout in SiteController
    'blog' => 'site/blog',         // actionBlog in SiteController

    // Home Page (Default route):
    '' => 'site/index', // actionIndex in SiteController
);