<?php
$values = $_SESSION['values'];
$members = $_SESSION['names'];
$levels = $_SESSION['levels'];


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Skillmatrix</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N"
            crossorigin="anonymous"></script>
    <link href="../scss/main.css" rel="stylesheet"/>
</head>
<body>
<table>
    <tr id="header">
        <th>Skills</th>
        <?php
        foreach ($members as $index => $member) {
            echo '<th data-bs-toggle="modal" data-bs-target="#modal1" class="username" id ="id:';
            echo $index;
            echo '">';
            echo $member;
            echo '</th>';
        }
        ?>
    </tr>
    <?php
    foreach ($values as $key => $value) {
        echo '<tr>';
        echo '<td id="id-';
        echo $key;
        echo '">';
        echo $value;
        echo '</td>';
        foreach ($members as $key2 => $member) {
            echo '<td class="knowledge" onclick="colorizeKnowledge(this)" id="id-';
            echo $key;
            echo '-';
            echo $key2;
            echo '">';
            foreach ($levels as $index => $level) {
                if ($level[1] == strval($key2) && $level[2] == strval($key)) {
                    echo $level[0];
                } else {
                    echo '';
                }
            }

            echo '</td>';
        }
        echo '</tr>';
    }
    ?>

</table>
<div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modaltitle"></h5>
                <button type="button" class="btn close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modalbody">


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button id="changes" type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
<script src="../js/script.js"></script>
</body>

</html>