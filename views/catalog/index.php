<?php include ROOT . '/views/layouts/header.php'; ?>


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
                <div class="features_items"><!--features_items-->
                    <h2 class="title text-center">Latest Products</h2>

                    <?php foreach ($latestProducts as $product): ?>

                        <div class="col-sm-4">
                            <div class="product-image-wrapper">
                                <div class="single-products">
                                    <div class="productinfo text-center">
                                        <div class="imageProduct">
                                            <img src="<?php echo product::getLowImage($product['id']); ?>" />
                                            <img src="<?php echo product::getMediumImage($product['id']); ?>"/>
                                        </div>

                                        <!--<img src="/assets/img/home/product1.jpg" alt="" />-->
                                        <?php if ($product['price_new']): ?>
                                            <h4><s>$<?php echo $product['price'];?></s></h4>
                                            <h2>$<?php echo $product['price_new'];?></h2>
                                        <?php else: ?>
                                            <h2>$<?php echo $product['price'];?></h2>
                                        <?php endif; ?>
                                        <p>
                                            <a href="/alias/p<?php echo $product['id'];?>">
                                                <?php echo $product['tittle'];?>
                                            </a>
                                        </p>
                                        <a href="/cart/add/<?php echo $product['id']; ?>" class="btn btn-default add-to-cart" data-id="<?php echo $product['id']; ?>"><i class="fa fa-shopping-cart"></i>Add to Cart</a>
                                    </div>
                                    <?php if ($product['is_new']): ?>
                                        <img src="/assets/img/home/new.png" class="new" alt="" />
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>
                </div><!--features_items-->


            </div>
        </div>
    </div>
</section>

<?php include ROOT . '/views/layouts/footer.php'; ?>