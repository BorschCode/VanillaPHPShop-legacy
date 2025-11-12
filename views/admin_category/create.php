<?php include ROOT . '/views/layouts/header_admin.php'; ?>

    <section>
        <div class="container">
            <div class="row">

                <br/>

                <div class="breadcrumbs">
                    <ol class="breadcrumb">
                        <li><a href="/admin">Admin Panel</a></li>
                        <li><a href="/admin/category">Manage Categories</a></li>
                        <li class="active">Add Category</li>
                    </ol>
                </div>


                <h4>Add New Category</h4>

                <br/>

                <?php if (isset($errors) && is_array($errors)): ?>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li> - <?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <div class="col-lg-4">
                    <div class="login-form">
                        <form action="#" method="post">

                            <p>Name</p>
                            <input type="text" name="name" placeholder="" value="">

                            <p>Sort Order</p>
                            <input type="text" name="sort_order" placeholder="" value="">

                            <p>Status</p>
                            <select name="status">
                                <option value="1" selected="selected">Displayed</option>
                                <option value="0">Hidden</option>
                            </select>

                            <br><br>

                            <input type="submit" name="submit" class="btn btn-default" value="Save">
                        </form>
                    </div>
                </div>


            </div>
        </div>
    </section>

<?php include ROOT . '/views/layouts/footer_admin.php'; ?>