<?php

require_once '../utilities/storage.php';
require_once '../utilities/auth.php';

session_start();

$auth = new Auth();

$pollsJson = new Storage(new JsonIO("../data/data.json"));
$polls = $pollsJson->findAll();

usort($polls, function ($a, $b) {

    $createdA = new DateTime($a['createdAt']);
    $createdB = new DateTime($b['createdAt']);

    return $createdB <=> $createdA;
});

?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voting Site</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body>

    <?php include_once './boiler/navbar.php'; ?>

    <div class="container">
        <h1 class="text-center">Voting Site</h1>
        <p class="text-center">A website for voting</p>
        <hr>

        <div>
            <h2>Latest Polls</h2>

            <div class="row row-cols-1 row-cols-md-3 mt-3 g-4">

                <?php



                foreach ($polls as $i => $poll) :

                    $deadline = $poll['deadline'];
                    $today = date("Y-m-d");


                    if ($deadline < $today) {
                        continue;
                    }

                    $id = $poll['id'];
                    $question = $poll['question'];
                    $createdAt = $poll['createdAt'];

                    $votedBefore = false;

                    if ($auth->is_authenticated()) {
                        $votedBefore = in_array($_SESSION['user']['username'], $poll['voted']);
                    }

                    $cardNum = $i + 1;
                ?>


                    <div class="col">
                        <div class="card bg-light border-success h-100">
                            <img src="../data/img/vote-default.jpg" class="card-img-top" alt="Voting - animated image">
                            <div class="card-body text-success">
                                <h5 class="card-title"><?= $cardNum . ". " . $question  ?></h5>
                            </div>

                            <div class="card-footer d-flex justify-content-between">
                                <small class="text-muted text-left">
                                    Created at: <?= $createdAt ?><br>
                                    Deadline: <?= $deadline ?>
                                </small>

                                <div class="d-flex justify-content-between">
                                    <form action=<?php if ($auth->is_authenticated()) {
                                                        echo "./voting-site.php";
                                                    } else {
                                                        echo "./login.php";
                                                    } ?> method="get" novalidate class="p-1">
                                        <input type="text" name="id" value=<?= $id ?> hidden>
                                        <button type="submit" class="btn btn-primary">
                                            <?php
                                            if ($votedBefore) {
                                                echo "Update";
                                            } else {
                                                echo "Vote";
                                            }
                                            ?>
                                        </button>
                                    </form>
                                    <?php
                                    if ($auth->is_admin()) :
                                    ?>

                                        <form method="post" class="p-1" novalidate>
                                            <input type="text" name="delete-id" value=<?= $id ?> hidden>
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>


                                    <?php endif;

                                    if (isset($_POST['delete-id'])) {
                                        $id = $_POST['delete-id'];
                                        $pollsJson->delete($id);
                                        header("Refresh:0");
                                    } ?>
                                </div>



                            </div>


                        </div>
                    </div>

                <?php endforeach; ?>
            </div>


        </div>

        <hr>

        <div>
            <h2>Closed Polls</h2>

            <div class="row row-cols-1 row-cols-md-3 mt-3 g-4">

                <?php

                foreach ($polls as $i => $poll) :

                    $deadline = $poll['deadline'];
                    $today = date("Y-m-d");

                    if ($deadline >= $today) {
                        continue;
                    }

                    $id = $poll['id'];
                    $question = $poll['question'];
                    $createdAt = $poll['createdAt'];

                    $cardNum = $i + 1;


                ?>


                    <div class="col">
                        <div class="card bg-secondary text-white h-100">
                            <img src="../data/img/vote-default.jpg" class="card-img-top" alt="Voting - animated image">
                            <div class="card-body">
                                <h5 class="card-title"><?= $cardNum . ". " . $question  ?></h5>
                            </div>

                            <div class="card-footer d-flex justify-content-between text-white align-middle">
                                <small class="text-left">
                                    Created at: <?= $createdAt ?><br>
                                    Expired at: <?= $deadline ?>
                                </small>

                                <div class="d-flex justify-content-between">

                                    <form action="./results.php" method="get" class="p-1" novalidate>
                                        <input type="text" name="id" value=<?= $id ?> hidden>
                                        <button type="submit" class="btn btn-primary">Results</button>
                                    </form>

                                </div>
                            </div>



                        </div>
                    </div>

                <?php endforeach; ?>
            </div>


        </div>
    </div>


</body>

</html>