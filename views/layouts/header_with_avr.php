<?php
/**
 * Created by PhpStorm.
 * User: George
 * Date: 10.12.2017
 * Time: 22:54
 */?>
<html lang="en">
<head>
<title><?php echo $pageTitle; ?></title>
<meta name="description" content="<?php echo $pageDescription; ?>">
<?php
// If canonical URL is specified, include canonical link element
if($pageCanonical)
{
    echo '<link rel="canonical" href="' . $pageCanonical . '">';
}
// If meta robots content is specified, include robots meta tag
if($pageRobots)
{
    echo '<meta name="robots" content="' . $pageRobots . '">';
}
?>

<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="wrapper">





    <?php breadCrumbs::run(); if (!empty($crumbs)) { ?>
        <section id="inner-headline">
            <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <?php foreach ($crumbs as $item) { ?>
                            <?php if (isset($item)) { ?>
                                <li>
                                    <?php if (!empty($item['url'])) { ?>
                                        <a href="<?php echo $item['url'] ?>"><?php echo $item['text'] ?></a>
                                    <?php } else { ?>
                                        <?php echo $item['text'] ?>
                                    <?php } ?>
                                </li>
                            <?php } ?>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </section>
    <?php } ?>

    <div class="breadcrumbs">
        <ol class="breadcrumb">
            <li><a href="/admin">Админпанель</a></li>
            <li><a href="/admin/order">Управление заказами</a></li>
            <li class="active">Редактировать заказ</li>
        </ol>
    </div>


    <div itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="$item['url']"> itemprop="url">
            <span itemprop="title">$item['text'] </span>
        </a>
    </div>

    <div itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="адрес страницы 2го уровня (например, рубрика)" itemprop="url">
            <span itemprop="title">название страницы</span>
        </a>
    </div>

    <div itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="адрес страницы 3го уровня (например, подрубрика)" itemprop="url">
            <span itemprop="title">название страницы</span>
        </a>
    </div>
