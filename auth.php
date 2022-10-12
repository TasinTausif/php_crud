<?php
session_start([
    'cookie_lifetime' => 30,
]);

$error = false;
$username = filter_input(INPUT_POST, 'username', FILTER_UNSAFE_RAW);
$password = filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW);

$fp = fopen('data\\users.txt', "r");

if ($username && $password) {
    $_SESSION['loggedin'] = false;
    $_SESSION['user']  = false;
    $_SESSION['role'] = false;
    while ($data = fgetcsv($fp)) {
        if ($data[0] == $username && $data[1] == sha1($password)) {
            $_SESSION['loggedin'] = true;
            $_SESSION['user']  = $username;
            $_SESSION['role'] = $data[2];
            header('location:index.php');
        }
    }
    if (!$_SESSION['loggedin']) {
        $error = true;
    }
}

if (isset($_GET['logout'])) {
    $_SESSION['loggedin'] = false;
    $_SESSION['user']  = false;
    $_SESSION['role'] = false;
    session_destroy();
    header('location:index.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentication example</title>
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
            <div class="column column-60 column-offset-20">
                <h2>Simple Auth example</h2>
            </div>
        </div>
        <div class="row">
            <div class="column column-60 column-offset-20">
                <?php
                if (isset($_SESSION['loggedin'])) {
                    echo "Hello, Admin. Welcome";
                } else {
                    echo "Hello, Stranger. Please Log in first";
                }
                ?>
            </div>
        </div>
        <div class="row">
            <div class="column column-60 column-offset-20">
                <?php
                if ($error) {
                    echo "<blockquote>Username or password didn't match</blockquote>";
                }
                if (!isset($_SESSION['loggedin'])) :
                ?>
                    <form method="POST">
                        <label for="username">Username</label>
                        <input type="text" name='username' id="username" placeholder="Username">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" placeholder="Password">
                        <button type="submit" class="button-primary" name="submit">Log in</button>
                    </form>
                <?php
                else :
                ?>
                    <form action="auth.php?logout=true" method="POST">
                        <input type="hidden" name="logout" value="1">
                        <button type="submit" class="button-primary" name="submit">Log out</button>
                    </form>
                <?php
                endif;
                ?>
            </div>
        </div>
    </div>
</body>

</html>