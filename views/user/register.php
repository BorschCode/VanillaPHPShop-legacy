<?php
/**
 * User Registration Template.
 *
 * This template handles the display of the user registration form,
 * success messages upon successful registration, and validation errors.
 *
 * @var bool $result Indicates whether registration was successful (true) or if the form should be shown (false).
 * @var array $errors An array of error messages (strings) to display if validation fails.
 * @var string $name The name entered by the user (used to pre-fill the form).
 * @var string $email The email address entered by the user (used to pre-fill the form).
 * @var string $password The password entered by the user (used to pre-fill the form).
 */
// The header includes are left in place as per the original file structure
include ROOT . '/views/layouts/header.php';
?>

    <section>
        <div class="container">
            <div class="row">

                <div class="col-sm-4 col-sm-offset-4 padding-right">

                    <?php if ($result): ?>
                        <p>You are successfully registered!</p>
                    <?php else: ?>
                        <?php if (isset($errors) && is_array($errors)): ?>
                            <ul>
                                <?php foreach ($errors as $error): ?>
                                    <li> - <?php echo $error; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>

                        <div class="signup-form"><!--sign up form-->
                            <h2>Site Registration</h2>
                            <form action="#" method="post">
                                <input type="text" name="name" placeholder="Name" value="<?php echo $name; ?>"/>
                                <input type="email" name="email" placeholder="E-mail" value="<?php echo $email; ?>"/>
                                <input type="password" name="password" placeholder="Password" value="<?php echo $password; ?>"/>
                                <input type="submit" name="submit" class="btn btn-default" value="Register" />
                            </form>
                        </div><!--/sign up form-->

                    <?php endif; ?>
                    <br/>
                    <br/>
                </div>
            </div>
        </div>
    </section>

<?php include ROOT . '/views/layouts/footer.php'; ?>