<?php ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Welcome</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <style>
            body {
                font: 32px sans-serif;
                text-align: center;
            }
            .center {
                position: absolute;
                top: 50%;
                left: 25%;
                transform: translate(0, -50%);
            }
        </style>
    </head>
    <body>

        <div class="container d-grid gap-2 col-6 mx-auto center">
            <div class="row"></div>
            <div class="row my-5 gap-2">
                <a href="login.php" class="btn btn-primary btn-lg">Log in</a>
                <a href="create_new_user.php" class="btn btn-success btn-lg">Create New User</a>
            </div>
        </div>
    </body>
</html>
