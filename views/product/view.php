<?php
/**
 * Created by PhpStorm.
 * Date: 08.12.2017
 * Time: 09:51
 */
include ROOT.'/views/layouts/header.php'?>

<!DOCTYPE html>



    <section>
        <div class="container">
            <div class="row">
                <div class="col-sm-3">
                    <div class="left-sidebar">
                        <h2>Catalog</h2>
                        <div class="panel-group category-products">
                            <?php foreach ($categories as $categoryItem): ?>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a href="/alias/c<?php echo $categoryItem['id'];?>">
                                                <?php echo $categoryItem['name'];?>
                                            </a>
                                        </h4>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="col-sm-9 padding-right">
                    <div class="product-details"><!--product-details-->
                        <div class="row">
                            <div class="col-sm-5">
                                <div class="view-product">
                                        <img src="<?php echo product::getLargeImage($product['id']); ?>" />
                                </div>
                            </div>
                            <div class="col-sm-7">
                                <div class="product-information"><!--/product-information-->
                                    <?php if ($product['is_new']): ?>
                                    <img src="/assets/img/home/new.jpg" class="newarrival" alt="" />
                                    <?php endif; ?>
                                    <h2><?php echo $product['tittle'];?></h2>
                                    <p>Product Code: <?php echo $product['code'];?></p>
                                    <span>
                                    <span><?php if ($product['price_new']): ?>
                                            US $<?php echo $product['price_new'];?><br>
                                            <h4><s>US $<?php echo $product['price'];?></s></h4>
                                        <?php else: ?>
                                            US $<?php echo $product['price'];?>
                                        <?php endif; ?></span>
                                    <label>Количество:</label>
                                    <input type="text" value="3" />
                                    <a href="/cart/add/<?php echo $product['id']; ?>" class="btn btn-default add-to-cart" data-id="<?php echo $product['id']; ?>"><i class="fa fa-shopping-cart"></i>Add to Cart</a>
                                </span>
                                    <p><b>Наличие:</b> На складе</p>
                                    <p><b>Состояние:</b> <?php if ($product['is_new']): ?>
                                        Новенькое поступление<br>
                                        <?php else: ?>
                                            В продаже<br>
                                    <?php endif; ?></p>
                                    <p><b>Производитель:</b><?php echo $product['brand'];?></p>
                                    <?php if (is_array($product['categories'])):
                                        echo "<p><b>Этот товар также входит в категории: </b></p> ";?>

                                        <?php foreach ($product['categories'] as $category): ?>
                                        <a href="/alias/c<?php echo $category; ?>"> <?php echo category::getCategoryText($category); ?> </a>
                                        <?php endforeach; ?>
                                    <?php endif; ?>





                                </div><!--/product-information-->
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <h5>Описание товара</h5>
                                <?php echo $product['description'];?>
                            </div>
                        </div>
                    </div><!--/product-details-->

                </div>
            </div>
        </div>
    </section>

<?php include ROOT.'/views/layouts/footer.php'?>