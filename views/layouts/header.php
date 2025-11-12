<?php
/**
 * Header template for the simple-shop application.
 *
 * This file contains the standard HTML head section, includes CSS/JS assets,
 * and renders the main site header with top contact info, logo, main navigation,
 * and a simple cart/user menu.
 *
 * It also includes a breadcrumbs implementation using PHP and the
 * data-vocabulary.org microformat (which is largely deprecated but functional
 * for demonstration purposes).
 *
 * @var string $pageTitle The title of the current page, displayed in the <title> tag.
 * @var string $pageDescription The meta description of the current page.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!--<meta name="description" content="">-->
    <meta name="author" content="">
    <title><?php echo $pageTitle; ?></title>
    <meta name="description" content="<?php echo $pageDescription; ?>">
    <link href="/assets/styles/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/styles/font-awesome.min.css" rel="stylesheet">
    <link href="/assets/styles/prettyPhoto.css" rel="stylesheet">
    <link href="/assets/styles/price-range.css" rel="stylesheet">
    <link href="/assets/styles/animate.css" rel="stylesheet">
    <link href="/assets/styles/main.css" rel="stylesheet">
    <link href="/assets/styles/responsive.css" rel="stylesheet">
    <!--<!--[if lt IE 9]>-->
    <script src="/assets/js/html5shiv.js"></script>
</head><!--/head-->

<body>
<header id="header"><!--header-->
    <div class="header_top"><!--header_top-->
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <div class="contactinfo">
                        <ul class="nav nav-pills">
                            <li><a href="tel:+38 093 093 093"><i class="fa fa-phone"></i> +38 093 093 093</a></li>
                            <li><a href="mailto:testmail@gmail.com"><i class="fa fa-envelope"></i> testmail@gmail.com</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="social-icons pull-right">
                        <ul class="nav navbar-nav">
                            <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                            <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div><!--/header_top-->

    <div class="header-middle"><!--header-middle-->
        <div class="container">
            <div class="row">
                <div class="col-sm-4">
                    <div class="logo pull-left">
                        <a href="/"><img src="/assets/img/home/logo.png" alt="" /></a>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="shop-menu pull-right">
                        <ul class="nav navbar-nav">
                            <li><a href="/cart">
                                    <i class="fa fa-shopping-cart"></i> Cart
                                    (<span id="cart-count"><?php echo cart::countItems(); ?></span>)
                                </a>
                            </li>
                            <?php if (user::isGuest()): ?>
                                <li><a href="/user/login/"><i class="fa fa-lock"></i> Sign In</a></li>
                            <?php else: ?>
                                <li><a href="/cabinet/"><i class="fa fa-user"></i> Account</a></li>
                                <li><a href="/user/logout/"><i class="fa fa-unlock"></i> Sign Out</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div><!--/header-middle-->

    <div class="header-bottom"><!--header-bottom-->
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>
                    <div class="mainmenu pull-left">
                        <ul class="nav navbar-nav collapse navbar-collapse">
                            <li><a href="/">Home</a></li>
                            <li class="dropdown"><a href="#">Shop<i class="fa fa-angle-down"></i></a>
                                <ul role="menu" class="sub-menu">
                                    <li><a href="/catalog/">Product Catalog</a></li>
                                    <li><a href="/cart/">Cart</a></li>
                                </ul>
                            </li>
                            <li><a href="/blog/">Blog</a></li>
                            <li><a href="/about/">About Store</a></li>
                            <li><a href="/contacts/">Contacts</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div><!--/header-bottom-->
    <!-- Implementation of breadcrumbs using microformat, fully realized on the fly -->
    <?php
    //$path = $_SERVER["PHP_SELF"];
    $path = $_SERVER['REQUEST_URI'];
    $parts = explode('/',$path);
    $domenName = 'http://wezom.test'; // This should ideally be dynamically detected or configured
    //print_r($parts);
    // Checking the current navigation level
    if (count($parts) < 2)
    {
        echo("<div itemscope itemtype=\"http://data-vocabulary.org/Breadcrumb\"><a href=\"\" itemprop=\"url\"><span itemprop=\"title\">$pageDescription</span></a></div>");
    }
    // If we are on a nested sub-level, determine the location + add links to the path,
    // using the domain name variable since microformat requires the full path.
    else
    {
        echo ("<div class='breadcrumb col-lg-offset-1' itemscope itemtype=\"http://data-vocabulary.org/Breadcrumb\"><a href=\"$domenName\" itemprop=\"url\"><span itemprop=\"title\">Home</span></a> &raquo; ");
        //print_r($parts);
        for ($i = 2; $i < count($parts); $i++)
        {
            if (!strstr($parts[$i],"."))
            {
                echo("<a href=$domenName/");
                for ($j = 0; $j <= $i; $j++) {echo $parts[$j]."/";};
                //echo("\">". str_replace('-', ' ', $parts[$i])."</a> » ");
                echo("\">". $pageDescription."</a> » ");
            }
            else
            {
                $str = $parts[$i];
                $pos = strrpos($str,".");
                $parts[$i] = substr($str, 0, $pos);
                echo str_replace('-', ' ', $parts[$i]);
            };
        };
    };
    ?>

</header><!--/header-->