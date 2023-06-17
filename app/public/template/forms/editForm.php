<?php
$errorsArray = $_SESSION['errorsArray'];
$email = $_SESSION['email'];
$departmentValues = $_SESSION['departmentValues'];
$positionValues = $_SESSION['positionValues'];
$companyValues = $_SESSION['companyValues'];
$lastName = $_SESSION['lastName'];
$firstName = $_SESSION['firstName'];
$values = $_SESSION['values'];
$previousPlaceValues = $_SESSION['previousPlaceValues'];
$hobbyValues = $_SESSION['hobbyValues'];
$interestValues = $_SESSION['interestValues'];
$socialMediaValues = $_SESSION['socialMediaValues'];
$volunteeringValues = $_SESSION['volunteeringValues'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font: 14px sans-serif;
        }

        .wrapper {
            width: 360px;
            padding: 20px;
        }
    </style>
    <script src="./../../js/formFields.js"></script>
</head>
<body>
<div class="wrapper">
    <h2>Edit</h2>
    <p>This is a form for editing your user data</p>
    <form method="post" name="edit">

        <div class="form-group">
            <label>Vorname *</label>
            <input type="text" name="firstName" class="form-control <?php
            echo (!empty($errorsArray["firstName"])) ? 'is-invalid' : ''; ?>" value="<?php
            echo $values['first_name']; ?>">
            <span class="invalid-feedback"><?php
                echo $errorsArray["firstName"]; ?></span>
        </div>

        <div class="form-group">
            <label>Nachname *</label>
            <input type="text" name="lastName" class="form-control <?php
            echo (!empty($errorsArray["lastName"])) ? 'is-invalid' : ''; ?>" value="<?php
            echo $values['last_name'];; ?>">
            <span class="invalid-feedback"><?php
                echo $errorsArray["lastName"]; ?></span>
        </div>


        <div class="form-group">
            <label>Email</label>
            <input type="text" name="email" class="form-control <?php
            echo (!empty($errorsArray["email"])) ? 'is-invalid' : ''; ?>" value="<?php
            echo $values['email'];; ?>">
            <span class="invalid-feedback"><?php
                echo $errorsArray["email"]; ?></span>
        </div>

        <div class="form-group">
            <label>Altes Password</label>
            <input type="password" name="oldPassword" class="form-control <?php
            echo (!empty($errorsArray["oldPassword"])) ? 'is-invalid' : ''; ?>">
            <span class="invalid-feedback"><?php
                echo $errorsArray["oldPassword"]; ?></span>
        </div>
        <div class="form-group">
            <label> Neues Password</label>
            <input type="password" name="newPassword" class="form-control <?php
            echo (!empty($errorsArray["newPassword"])) ? 'is-invalid' : ''; ?>">
            <span class="invalid-feedback"><?php
                echo $errorsArray["newPassword"]; ?></span>
        </div>
        <div class="form-group">
            <label>Neues Password bestätigen</label>
            <input type="password" name="confirm_password" class="form-control <?php
            echo (!empty($errorsArray["confirm_password"])) ? 'is-invalid' : ''; ?>">
            <span class="invalid-feedback"><?php
                echo $errorsArray["confirm_password"]; ?></span>
        </div>
        <!--      <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" class="form-control <?php
        /*echo (!empty($errorsArray["confirm_password"])) ? 'is-invalid' : ''; */ ?>" >
            <span class="invalid-feedback"><?php
        /*echo $errorsArray["confirm_password"]; */ ?></span>
        </div>-->

        <div class="form-group">
            <label>Unternehmen *</label>
            <select name="company" id="company">
                <?php
                foreach ($companyValues as $companyValue)
                {
                    if ($companyValue === $values['company_id'])
                    {
                        echo '<option selected="selected" value="';
                    } else
                    {
                        echo '<option value="';
                    }
                    echo $companyValue;
                    echo '">';
                    echo $companyValue;
                    echo '</option>';
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label>Abteilung *</label>
            <select name="department" id="department">
                <?php
                foreach ($departmentValues as $departmentValue)
                {
                    if ($departmentValue === $values['department_id'])
                    {
                        echo '<option selected="selected" value="';
                    } else
                    {
                        echo '<option value="';
                    }
                    echo $departmentValue;
                    echo '">';
                    echo $departmentValue;
                    echo '</option>';
                }
                ?>
            </select>
            <span class="invalid-feedback"><?php
                ?></span>
        </div>

        <div class="form-group">
            <label>Position *</label>
            <select name="position" id="position">
                <?php
                foreach ($positionValues as $positionValue)
                {
                    if ($positionValue === $values['position_id'])
                    {
                        echo '<option selected="selected" value="';
                    } else
                    {
                        echo '<option value="';
                    }
                    echo $positionValue;
                    echo '">';
                    echo $positionValue;
                    echo '</option>';
                }
                ?>
            </select>
            <span class="invalid-feedback"><?php
                ?></span>
        </div>


        <div class="form-group">
            <label>Startdatum *</label>
            <input type="date" id="startDate" name="startDate"
                   value="<?php
                   echo $values['start_date'];; ?>">
            <span class="invalid-feedback"><?php
                echo $errorsArray["startDate"]; ?></span>
        </div>

        <div class="form-group">
            <label>Birthday</label>
            <input type="date" id="birthday" name="birthday"
                   value="<?php
                   echo $values['birthday'];; ?>">
            <span class="invalid-feedback"><?php
                echo $errorsArray["startDate"]; ?></span>
        </div>

        <div class="form-group">
            <label>Geburtsort</label>
            <input type="text" name="birthplace" class="form-control <?php
            echo (!empty($errorsArray["birthplace"])) ? 'is-invalid' : ''; ?>" value="<?php
            echo $values['birthplace_id'];; ?>">
            <span class="invalid-feedback"><?php
                echo $errorsArray["birthplace"]; ?></span>
        </div>

        <div class="form-group">
            <label>Aktueller Ort</label>
            <input type="text" name="currentPlace" class="form-control <?php
            echo (!empty($errorsArray["currentPlace"])) ? 'is-invalid' : ''; ?>" value="<?php
            echo $values['current_living_place_id'];; ?>">
            <span class="invalid-feedback"><?php
                echo $errorsArray["currentPlace"]; ?></span>
        </div>

        <div class="form-group">
            <label>Anzahl Kinder</label>
            <input type="text" name="children" class="form-control <?php
            echo (!empty($errorsArray["children"])) ? 'is-invalid' : ''; ?>" value="<?php
            echo $values['children']; ?>">
            <span class="invalid-feedback"><?php
                echo $errorsArray["children"]; ?></span>
        </div>

        <div id="previousPlace">
            <?php
            foreach ($previousPlaceValues as $index => $value)
            {
                echo '<div class="form-group"> <label>';
                if ($index > 0)
                {
                    echo 'Vorheriger Wohnort ' . $index;
                } else
                {
                    echo 'Vorheriger Wohnort';
                }
                echo '</label>';
                echo '<input type="text" name="';
                echo 'previousPlace[' . $index . ']';
                echo '" class="form-control" value="';
                echo $value;
                echo '"></div>';
            }
            ?>
        </div>
        <button type="button" id="previousPlaceButton" onclick="addRow('previousPlace', 'Vorheriger Wohnort')">
            Vorherigen Wohnort hinzufügen
        </button>


        <div id="hobby">
            <?php
            foreach ($hobbyValues as $index => $value)
            {
                echo '<div class="form-group"> <label>';
                if ($index > 0)
                {
                    echo 'Hobby ' . $index;
                } else
                {
                    echo 'Hobby';
                }
                echo '</label>';
                echo '<input type="text" name="';
                echo 'hobby[' . $index . ']';
                echo '" class="form-control" value="';
                echo $value;
                echo '"></div>';
            }
            ?>
        </div>
        <button type="button" onclick="addRow('hobby', 'Hobby')">Hobby hinzufügen</button>

        <div id="interest">
            <?php
            foreach ($interestValues as $index => $value)
            {
                echo '<div class="form-group"> <label>';
                if ($index > 0)
                {
                    echo 'Interesse ' . $index;
                } else
                {
                    echo 'Interesse';
                }
                echo '</label>';
                echo '<input type="text" name="';
                echo 'interest[' . $index . ']';
                echo '" class="form-control" value="';
                echo $value;
                echo '"></div>';
            }
            ?>
        </div>
        <button type="button" onclick="addRow('interest', 'Interesse')">Interessen hinzufügen</button>

        <div id="volunteering">
            <?php
            foreach ($volunteeringValues as $index => $value)
            {
                echo '<div class="form-group"> <label>';
                if ($index > 0)
                {
                    echo 'Ehrenamt ' . $index;
                } else
                {
                    echo 'Ehrenamt';
                }
                echo '</label>';
                echo '<input type="text" name="';
                echo 'volunteering[' . $index . ']';
                echo '" class="form-control" value="';
                echo $value;
                echo '"></div>';
            }
            ?>
        </div>
        <button type="button" onclick="addRow('volunteering', 'Ehrenamt')">Ehrenamt hinzufügen</button>

        <div id="social_media">
            <?php
            foreach ($socialMediaValues as $index => $value)
            {
                echo '<div class="form-group"> <label>';
                if ($index > 0)
                {
                    echo 'Soziale Medien URL ' . $index;
                } else
                {
                    echo 'Soziale Medien URL';
                }
                echo '</label>';
                echo '<input type="text" name="';
                echo 'social_media[' . $index . ']';
                echo '" class="form-control" value="';
                echo $value;
                echo '"></div>';
            }
            ?>
        </div>
        <button type="button" onclick="addRow('social_media', 'Soziale Medien Url')">Soziale Medien Profile hinzufügen
        </button>

        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit">
            <input type="reset" class="btn btn-secondary ml-2" value="Reset">
        </div>
    </form>
</div>
</body>
</html>