<?php
require_once '../utilities/storage.php';
require_once '../utilities/auth.php';

session_start();
$auth = new Auth();

if ($auth->is_authenticated()) {
    if (!$auth->is_admin()) {
        header("Location: polls.php");
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}


$jstore = new Storage(new JsonIO("../data/data.json"));
$createdPoll;
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote Creator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

</head>

<body>

    <?php include_once './boiler/navbar.php'; ?>


    <?php

    $questionError = false;
    $optionsError = false;
    $deadlineError = false;

    $question = "";
    $options = ["Option1, option2..."];
    $deadline = date('Y-m-d');
    $created = date('Y-m-d');
    $multiple = true;


    if (!empty($_POST)) {

        $questionError = false;
        $optionsError = false;
        $deadlineError = false;

        $question = $_POST['question'];
        $options = array_filter(array_map('trim', explode(',', $_POST['options'])), function ($value) {
            return $value !== "";
        });
        
        $deadline = $_POST['deadline'];
        $multiple = filter_var($_POST['multiple'], FILTER_VALIDATE_BOOLEAN);

        if (!$question) {
            $questionError = true;
        }

        if (count($options) < 2) {
            $optionsError = true;
        }

        if ($deadline < $created) {
            $deadlineError = true;
        }

        $answers = [];

        foreach ($options as $option) {
            $answers[$option] = 0;
        }

        if (!$questionError && !$deadlineError && !$optionsError) {
            $obj = [
                'question' => $question,
                'options' => $options,
                'deadline' => $deadline,
                'createdAt' => $created,
                'isMultiple' => $multiple,
                'answers' => $answers,
                'voted' => []
            ];

            $jstore->add($obj);
        }
    }
    ?>

    <div class="container" style="width: fit-content;">
        <h1 class="text-center">Vote Creator Site </h1>
        <hr>
        <form method="post" novalidate>
            <div class="form-group">
                <label for="question">Poll's Question</label>
                <input class="form-control" type="text" name="question" placeholder="Write your question here. Ok?" value="<?= $question ?>">

                <?php if ($questionError) : ?>

                    <div class="alert alert-danger m-1" role="alert">
                        Question is required!
                    </div>

                <?php endif; ?>

            </div>
            <div class="form-group">
                <label for="options">Poll's Options (separate with ,) </label>
                <textarea name="options" cols="20" rows="5" class="form-control" value="<?= $options ?>"> <?php
                                                                                                            if (!empty($options)) {
                                                                                                                foreach ($options as $option) {
                                                                                                                    echo $option . ",";
                                                                                                                }
                                                                                                            } ?></textarea>
                <?php if ($optionsError) : ?>
                    <div class="alert alert-danger m-1" role="alert">
                        2 or more options needed!
                    </div>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="" class="m-1">Is the poll's choice multiple or not? </label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="multiple" value="true" <?php if ($multiple) {
                                                                                                    echo "checked";
                                                                                                } ?>>
                    <label class="form-check-label" for="multiple">
                        Yes, multiple (checkbox)
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="multiple" value="false" <?php if (!$multiple) {
                                                                                                    echo "checked";
                                                                                                } ?>>
                    <label class="form-check-label" for="multiple">
                        No, not multiple (radio)
                    </label>
                </div>
            </div>
            <div class="form-group">
                <label for="deadline">Poll's Deadline</label>
                <input class="form-control" type="date" name="deadline" value="<?php echo $deadline ?>">
                <?php if ($deadlineError) : ?>
                    <div class="alert alert-danger m-1" role="alert">
                        Deadline can't be earlier than created date!
                    </div>

                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="created">Poll's Created at </label>
                <input class="form-control" type="date" name="created" value="<?php echo $created ?>" disabled>
            </div>

            <button type="submit" class="btn btn-primary btn-lg btn-block mt-2">Create Vote</button>
            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST') :

                if ($questionError || $deadlineError || $optionsError) : ?>

                    <div class="alert alert-danger m-2" role="alert">
                        Vote is not created. Fix the problems.
                    </div>

                <?php else : ?>
                    <div class="alert alert-success m-2" role="alert">
                        Vote is created successfully.
                    </div>

            <?php endif;
            endif; ?>
    </div>

    </form>


    </div>


</body>

</html>