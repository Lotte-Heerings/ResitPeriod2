<?php
    $id = $_GET["id"];
    if ($conn = mysqli_connect("localhost", "root", "")) {
        mysqli_select_db($conn, "webshop");

        $sql = "DELETE FROM images WHERE imageId=?";
        if($stmt = mysqli_prepare($conn, $sql))
        {
            mysqli_stmt_bind_param($stmt, "i", $id);
            if(mysqli_stmt_execute($stmt))
            {
                header('Location: panelItems.php');
                exit();
            }
        }
    }
