<?php
require_once '../utilities/storage.php';
require_once '../utilities/auth.php';

session_start();
$auth = new Auth();

if (!$auth->is_authenticated()) {
    header("Location: login.php");
    exit();
}

$jstore = new Storage(new JsonIO("../data/data.json"));

$id = $_GET['id'];
$selectedPoll = $jstore->findById($id);

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

    <div class="container" style="max-width: fit-content;">
        <div class="row">

        </div>
        <h1 class="text-center">Voting Site </h1>
        <hr>

        <div class="card bg-light m-1">
            <img src="../data/img/vote-default.jpg" class="card-img-top" alt="Voting - animated image">
            <div class="card-body ">
                <h5 class="card-title"><?= $selectedPoll['question'] ?></h5>
                <p class="card-text">
                    <strong>Created at: <?= $selectedPoll['createdAt'] ?></strong> <br>
                    <strong>Deadline: <?= $selectedPoll['deadline'] ?></strong>
                </p>
            </div>

            <div class="container">
                <hr class="bg-dark">
                <h5> Your Vote: </h5>
                <form method="post" novalidate>
                    <div class="form-group">
                        <?php

                        if ($selectedPoll['isMultiple']) {
                            foreach ($selectedPoll['options'] as $option) : ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name=<?= $option ?> value="<?= $option ?>">
                                    <label class="form-check-label" for=<?= $option ?>>
                                        <?= $option ?>
                                    </label>
                                </div>
                            <?php
                            endforeach;
                        } else {
                            foreach ($selectedPoll['options'] as $option) : ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="<?= $option ?>" value="<?= $option ?>">
                                    <label class="form-check-label" for=<?= $option ?>>
                                        <?= $option ?>
                                    </label>
                                </div>
                        <?php
                            endforeach;
                        }

                        ?>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg btn-block mt-2">
                        <?php

                        $votedBefore = in_array($_SESSION['user']['username'], $selectedPoll['voted']);

                        if ($votedBefore) {
                            echo "Update";
                        } else {
                            echo "Vote";
                        }
                        ?>

                    </button>
                </form>
            </div>

            <?php

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if (empty($_POST)) : ?>
                    <div class="alert alert-danger text-center">
                        The vote is not sent! You did not selected an option!
                    </div>
                <?php else : ?>
                    <div class="alert alert-success text-center">
                        Vote is sent! Thank you!
                    </div>
            <?php

                    foreach ($_POST as $option) {

                        $selectedPoll['answers'][$option]++;

                        if (!in_array($_SESSION['user']['username'], $selectedPoll['voted'])) {
                            $selectedPoll['voted'][] = $_SESSION['user']['username'];
                        }

                        $jstore->update($id, $selectedPoll);
                    }

                endif;
            }

            ?>

        </div>
    </div>

    </div>
</body>

</html>