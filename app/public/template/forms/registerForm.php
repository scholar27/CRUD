<?php
$errorsArray = $_SESSION['errorsArray'];
$email = $_SESSION['email'];
$departmentValues = $_SESSION['departmentValues'];
$positionValues = $_SESSION['positionValues'];
$companyValues = $_SESSION['companyValues'];
$lastName = $_SESSION['lastName'];
$firstName = $_SESSION['firstName'];
/*$startDate = $_SESSION['startDate'];*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
<div class="wrapper">
    <h2>Sign Up</h2>
    <p>Please fill this form to create an account.</p>
    <form  method="post">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="email" class="form-control <?php echo (!empty($errorsArray["email"])) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
            <span class="invalid-feedback"><?php echo $errorsArray["email"]; ?></span>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control <?php echo (!empty($errorsArray["password"])) ? 'is-invalid' : ''; ?>">
            <span class="invalid-feedback"><?php echo $errorsArray["password"]; ?></span>
        </div>
        <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" class="form-control <?php echo (!empty($errorsArray["confirm_password"])) ? 'is-invalid' : ''; ?>" >
            <span class="invalid-feedback"><?php echo $errorsArray["confirm_password"]; ?></span>
        </div>
  
        <div class="form-group">
            <label>Abteilung *</label>
            <select name="department" id="department">
                <?php
                foreach ($departmentValues as $departmentValue) {
                    echo '<option value="';
                    echo $departmentValue;
                    echo '">';
                    echo $departmentValue;
                    echo '</option>';
                }
                ?>
            </select>
            <span class="invalid-feedback"><?php ?></span>
        </div>
        
        <div class="form-group">
            <label>Position *</label>
            <select name="position" id="position">
                <?php
                foreach ($positionValues as $positionValue) {
                    echo '<option value="';
                    echo $positionValue;
                    echo '">';
                    echo $positionValue;
                    echo '</option>';
                }
                ?>
            </select>
            <span class="invalid-feedback"><?php ?></span>
        </div>

        <div class="form-group">
            <label>Vorname *</label>
            <input type="text" name="firstName" class="form-control <?php echo (!empty($errorsArray["firstName"])) ? 'is-invalid' : ''; ?>" value="<?php echo $firstName; ?>">
            <span class="invalid-feedback"><?php echo $errorsArray["firstName"]; ?></span>
        </div>

        <div class="form-group">
            <label>Nachname *</label>
            <input type="text" name="lastName" class="form-control <?php echo (!empty($errorsArray["lastName"])) ? 'is-invalid' : ''; ?>" value="<?php echo $lastName; ?>">
            <span class="invalid-feedback"><?php echo $errorsArray["lastName"]; ?></span>
        </div>

        <div class="form-group">
            <label>Unternehmen *</label>
            <select name="company" id="company">
                <?php
                foreach ($companyValues as $companyValue) {
                    echo '<option value="';
                    echo $companyValue;
                    echo '">';
                    echo $companyValue;
                    echo '</option>';
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label>Startdatum *</label>
            <input type="date" id="startDate" name="startDate"
                   value="">
            <span class="invalid-feedback"><?php echo $errorsArray["startDate"]; ?></span>
        </div>
        
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit">
            <input type="reset" class="btn btn-secondary ml-2" value="Reset">
        </div>
        <p>Already have an account? <a href="../../index.php?site=login">Login here</a>.</p>
    </form>
</div>
</body>
</html>