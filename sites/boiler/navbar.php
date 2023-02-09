<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid fs-5">

        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-2">
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="./polls.php">Polls</a>
                </li>
                <?php
                if ($auth->is_admin()) :
                ?>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="./vote-creator.php">Create Vote</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>

        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <?php if (!$auth->is_authenticated()) : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="./register.php">Register</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./login.php">Login</a>
                    </li>
                <?php else : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="?logout">Logout</a>
                    </li>
                <?php endif;


                ?>
            </ul>
        </div>
    </div>
</nav>


<?php

if ($_GET) {
    if (isset($_GET['logout'])) {
        $auth->logout();
        header("Location: ./polls.php");
        exit();
    }
}
?>