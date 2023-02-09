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

        <h1 class="text-center"> Results </h1>
        <h2 class="text-center"><?= $selectedPoll['question'] ?></h2>
        <hr>


        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Option</th>
                    <th>Number of votes</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($selectedPoll['answers'] as $option => $answer) : ?>
                    <tr>
                        <td><?= $option ?> </td>
                        <td><?= $answer ?> </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

        </table>

    </div>

</body>

</html>