<?php

require_once "../utilities/auth.php";

session_start();

$auth = new Auth();
$invalid = false;

if (!empty($_POST)) {

    $user = $auth->check_credentials($_POST['username'], $_POST['password']);

    if ($user) {
        $auth->login($_POST);
        header('Location: polls.php');
        exit();
    } else {
        $invalid = true;
    }
}


?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

</head>

<body>

    <?php include_once './boiler/navbar.php'; ?>

    <div class="d-flex flex-column justify-content-center align-items-center h-100 border">

        <form class="bg-light rounded p-5" novalidate method="post">

            <h5>Sign into your account</h5> <br>


            <div class="form-group">
                <input type="text" name="username" class="form-control form-control-lg mb-1" placeholder="Username" />
                <input type="password" name="password" class="form-control form-control-lg mb-1" placeholder="Password" />
            </div>


            <button class="btn btn-dark btn-lg btn-block mt-3" type="submit">Login</button>
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </form>

        <?php

        if ($invalid) : ?>

            <div class="alert alert-danger">
                Invalid username or password.
            </div>

        <?php endif; ?>
    </div>


</body>

</html>