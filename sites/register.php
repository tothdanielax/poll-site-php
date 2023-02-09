<?php

require_once "../utilities/auth.php";
$auth = new Auth();

$username = "";
$email = "";
$pw1 = "";
$pw2 = "";

$usernameError = null;
$emailError = null;
$pw1Error = null;
$pw2Error = null;
$existing = null;

if (!empty($_POST)) {

    $username = $_POST["username"];
    $email = $_POST['email'];
    $pw1 = $_POST['password1'];
    $pw2 = $_POST['password2'];

    if (empty($username)) {
        $usernameError = "Username is required!";
    }

    if (empty($email)) {
        $emailError = "Email is required!";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailError = "Email format is invalid!";
    }

    if (empty($pw1)) {
        $pw1Error = "Password is required!";
    }

    if (empty($pw2)) {
        $pw2Error = "Password confirmation is required!";
    }

    if (!$pw1Error && !$pw2Error) {
        if ($pw1 != $pw2) {
            $pw1Error = $pw2Error = "Password is not matching!";
        }
    }

    if (!$usernameError && !$emailError && !$pw1Error && !$pw2Error) {

        $user = [
            "username" => $username,
            "email" => $email,
            "password" => $pw1
        ];

        if ($auth->save($user)) {
            header("Location: login.php");
            exit();
        } else {
            $existing = true;
        }
    }
}


?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

</head>

<body>

    <?php include_once './boiler/navbar.php'; ?>

    <div class="d-flex flex-column justify-content-center align-items-center h-100 border">

        <form class="bg-light rounded p-5" method="post" novalidate>

            <h5>Register your account</h5> <br>


            <div class="form-group">
                <input type="text" name="username" class="form-control form-control-lg mb-1" placeholder="Username" value="<?= $username ?>" />

                <?php if ($usernameError) {
                    echo "<p class='font-weight-bold text-danger'>$usernameError</p>";
                } ?>

                <input type="email" name="email" class="form-control form-control-lg mb-1" placeholder="E-mail" value="<?= $email ?>" />

                <?php if ($emailError) {
                    echo "<p class='font-weight-bold text-danger'>$emailError</p>";
                } ?>

                <input type="password" name="password1" class="form-control form-control-lg mb-1" placeholder="Password" value="<?= $pw1 ?>" />

                <?php if ($pw1Error) {
                    echo "<p class='font-weight-bold text-danger'>$pw1Error</p>";
                } ?>

                <input type="password" name="password2" class="form-control form-control-lg mb-1" placeholder="Confirm Password" value="<?= $pw2 ?>" />

                <?php if ($pw2Error) {
                    echo "<p class='font-weight-bold text-danger'>$pw2Error</p>";
                } ?>

            </div>


            <button class="btn btn-dark btn-lg btn-block mt-3" type="submit">Register</button>
            <p>Have an account? <a href="login.php">Login here</a></p>
        </form>

        <?php

        if ($existing) : ?>

            <div class="alert alert-danger">
                User already exists.
            </div>

        <?php endif; ?>

    </div>


</body>

</html>