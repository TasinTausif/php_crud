<?php
session_start();
require_once "inc/functions.php";

$info = "";
$task = $_GET['task'] ?? "report";
$error = $_GET['error'] ?? "0";

if ('edit' == $task) {
    if (!hasPrevilege()) { //this is a check, if someone tries to do something through link without logging
        header('location: index.php?task=report');
    }
}

if ('seed' == $task) {
    if (!isAdmin()) {
        header('location: index.php?task=report'); //if don't have permission, will redirect to index page
        return;
    }
    seed();
    $info = 'Seeding is complete';
}

if ('delete' == $task) {
    if (!isAdmin()) {
        header('location: index.php?task=report');
        return;
    }
    $id = filter_input(INPUT_GET, 'id', FILTER_UNSAFE_RAW);
    if ($id > 0) {
        deleteStudent($id);
        header('location: index.php?task=report');
    }
}

$fname = '';
$lname = '';
$roll = '';

if (isset($_POST['submit'])) {
    $fname = filter_input(INPUT_POST, 'fname', FILTER_UNSAFE_RAW);
    $lname = filter_input(INPUT_POST, 'lname', FILTER_UNSAFE_RAW);
    $roll = filter_input(INPUT_POST, 'roll', FILTER_UNSAFE_RAW);
    $id = filter_input(INPUT_POST, 'id', FILTER_UNSAFE_RAW);

    //Updating existing student
    if ($id) {
        if ($fname != '' && $lname != '' && $roll != '') {
            $result = updateStudent($id, $fname, $lname, $roll);
            if ($result == true) {
                header('location: index.php?task=report');
            } else {
                $error = "1";
            }
        }
    }
    //Adding new data
    else {
        if ($fname != '' && $lname != '' && $roll != '') {
            $result = addStudent($fname, $lname, $roll);
            if ($result == true) {
                header('location: index.php?task=report');
            } else {
                $error = "1";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD</title>
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto+Slab">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.css">
    <style>
        body {
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="column column-60 column-offset-20 ">
                <h2>CRUD Project</h2>
                <p>Sample Project to use CRUD operations in plain file and php</p>
                <?php include_once('inc/templates/nav.php') ?>
                <hr />
                <?php
                if ($info != '') {
                    echo "<p>$info</p>";
                }
                ?>
            </div>
        </div>
        <?php
        if ('1' == $error) :
        ?>
            <div class="row">
                <div class="column column-60 column-offset-20 ">
                    <blockquote>
                        Duplicate Roll number
                    </blockquote>
                </div>
            </div>
        <?php
        endif;
        ?>
        <?php
        if ('report' == $task) :
        ?>
            <div class="row">
                <div class="column column-60 column-offset-20 ">
                    <?php generateReport(); ?>
                </div>
            </div>
        <?php
        endif;
        ?>
        <?php
        if ('add' == $task) :
        ?>
            <div class="row">
                <div class="column column-60 column-offset-20 ">
                    <form action="index.php?task=add" method="POST">
                        <label for="fname">First Name: </label>
                        <input type="text" name="fname" id="fname" value="<?php echo $fname; ?>" autocomplete="off" placeholder="First Name">
                        <label for="lname">Last Name: </label>
                        <input type="text" name="lname" id="lname" value="<?php echo $lname; ?>" placeholder="Last Name">
                        <label for="roll">Roll: </label>
                        <input type="number" name="roll" id="roll" value="<?php echo $roll; ?>" placeholder="Roll">
                        <button type="submit" class="button-primary" name="submit">Save</button>
                    </form>
                </div>
            </div>
        <?php
        endif;
        ?>
        <?php
        if ('edit' == $task) :
            $id = filter_input(INPUT_GET, 'id', FILTER_UNSAFE_RAW);
            $student = getStudent($id);
            if ($student) :
        ?>
                <div class="row">
                    <div class="column column-60 column-offset-20 ">
                        <form method="POST">
                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                            <label for="fname">First Name: </label>
                            <input type="text" name="fname" id="fname" value="<?php echo $student['fname']; ?>" placeholder="First Name">
                            <label for="lname">Last Name: </label>
                            <input type="text" name="lname" id="lname" value="<?php echo $student['lname']; ?>" placeholder="Last Name">
                            <label for="roll">Roll: </label>
                            <input type="number" name="roll" id="roll" value="<?php echo $student['roll']; ?>" placeholder="Roll">
                            <button type="submit" class="button-primary" name="submit">Update</button>
                        </form>
                    </div>
                </div>
        <?php
            endif;
        endif;
        ?>
    </div>

    <script type="text/javascript" src="assets/js/script.js"></script>
</body>

</html>