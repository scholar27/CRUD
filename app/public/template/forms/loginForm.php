<?php
$errorsArray = $_SESSION['errorsArray'];
$email = $_SESSION['email'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
<div class="wrapper">
    <h2>Login</h2>
    <p>Please fill in your credentials to login.</p>

    <form method="post">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="email" class="form-control <?php echo (!empty($errorsArray["email"])|| !empty($errorsArray["typo"])) ? "is-invalid" : ""; ?>" value="<?php echo $email; ?>">
            <span class="invalid-feedback"><?php echo $errorsArray["email"] ?></span>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control <?php echo (!empty($errorsArray["password"]) || !empty($errorsArray["typo"])) ? "is-invalid" : ""; ?>">
            <span class="invalid-feedback"><?php echo $errorsArray["password"]; ?></span>
            <span class="invalid-feedback"><?php echo $errorsArray["typo"] ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Login">
        </div>
        <p>Don"t have an account? <a href="../../index.php?site=register">Sign up now</a>.</p>
    </form>
</div>
</body>
</html>