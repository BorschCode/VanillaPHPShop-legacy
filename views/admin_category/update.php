<?php
/**
 * @var array<string, mixed> $category The associative array containing the category data (e.g., 'id', 'name', 'sort_order', 'status').
 */
include ROOT . '/views/layouts/header_admin.php'; ?>

    <section>
        <div class="container">
            <div class="row">

                <br/>

                <div class="breadcrumbs">
                    <ol class="breadcrumb">
                        <li><a href="/admin">Admin Panel</a></li>
                        <li><a href="/admin/category">Manage Categories</a></li>
                        <li class="active">Edit Category</li>
                    </ol>
                </div>


                <h4>Edit Category "<?php echo $category['name']; ?>"</h4>

                <br/>

                <div class="col-lg-4">
                    <div class="login-form">
                        <form action="#" method="post">

                            <p>Name</p>
                            <input type="text" name="name" placeholder="" value="<?php echo $category['name']; ?>">

                            <p>Sort Order</p>
                            <input type="text" name="sort_order" placeholder="" value="<?php echo $category['sort_order']; ?>">

                            <p>Status</p>
                            <select name="status">
                                <option value="1" <?php if ($category['status'] == 1) echo ' selected="selected"'; ?>>Displayed</option>
                                <option value="0" <?php if ($category['status'] == 0) echo ' selected="selected"'; ?>>Hidden</option>
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