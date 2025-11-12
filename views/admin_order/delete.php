<?php
/**
 * @var int $id The ID of the order currently being deleted.
 */
include ROOT . '/views/layouts/header_admin.php'; ?>

    <section>
        <div class="container">
            <div class="row">

                <br/>

                <div class="breadcrumbs">
                    <ol class="breadcrumb">
                        <li><a href="/admin">Admin Panel</a></li>
                        <li><a href="/admin/order">Manage Orders</a></li>
                        <li class="active">Delete Order</li>
                    </ol>
                </div>


                <h4>Delete Order #<?php echo $id; ?></h4>


                <p>Are you sure you want to delete this order?</p>

                <form method="post">
                    <input type="submit" name="submit" value="Delete" class="btn btn-danger" />
                </form>

            </div>
        </div>
    </section>

<?php include ROOT . '/views/layouts/footer_admin.php'; ?>