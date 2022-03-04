<!DOCTYPE html>
<html lang="en-US">
    <head>
        <title>NHL Webshop</title>
        <meta charset="UTF-8">
        <link href="styles.css" rel="stylesheet" type="text/css">
    </head>
        <body class="grid-container">
        <div id="header"></div>
        <div class="headerColumns" id="headerInside">
            <div id="logo">
                <a href="index.php"><h2>NHL WEBSHOP</h2></a>
            </div>
            <div id="searchContainer">
                <form>
                    <input id="searchBar" type="text" name="search" placeholder="Search">
                    <input type="submit" name="submit" hidden>
                </form>
            </div>
            <div id="loginButton">
                <a href="#"><h2>Login</h2></a>
            </div>
        </div>
        <form method="POST" action="#" id="addItem" enctype="multipart/form-data">
            <div class="formInput">
                <input type="file" name="image">
            </div>
            <input type="submit" id="submit" name="submit" value="submit">
        </form>
        <?php

            $id = $_GET["id"];
            if ($conn = mysqli_connect("localhost", "root", "")) {
                mysqli_select_db($conn, "webshop");
            $sqlRemove = "SELECT * FROM images WHERE itemId = ?";
            if($stmt = mysqli_prepare($conn, $sqlRemove))
            {
                mysqli_stmt_bind_param($stmt,"i", $id);
                if(mysqli_stmt_execute($stmt))
                {
                    mysqli_stmt_bind_result($stmt, $imageId, $image, $itemId);
                    mysqli_stmt_store_result($stmt);
                    if(mysqli_stmt_num_rows($stmt) > 0)
                    {
                        while(mysqli_stmt_fetch($stmt))
                        {
                            echo "<img src='images/notPrimary/".$image."'</img>";
                            echo "<a href='removeExtraImages.php?id=".$imageId."'>delete</a><br>";
                        }
                    }
                } else {echo "execute error"; }
            } else {echo "prepare error";}
            if($_SERVER["REQUEST_METHOD"] == "POST") {
                //+setting up image where image is stored
                $acceptedImageTypes = ["image/jpg", "image/jpeg", "image/png", "image/gif"];
                //finfo file = returns information about a file, finfo open = turns it into an array, mimetype = extension, !!!!!check it yourself
                $imageType = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $_FILES["image"]["tmp_name"]);
                //if extension is one of the accepted ones (needle, haystack)
                if (in_array($imageType, $acceptedImageTypes)) {
                    //mime type returns image/extension type so we remove the image
                    $fileExtension = str_replace("image/", "", $imageType);
                    //makes a file base which is the current date:day month year hour minutes seconds;
                    $newFileName = date('dmYHis');
                    //moves the image to the appropriate folder and renames it into the file base.
                    if (move_uploaded_file($_FILES["image"]["tmp_name"], "./images/notPrimary/" . $newFileName . "." . $fileExtension)) {
                        $image = $newFileName . "." . $fileExtension;

                            $sql = "INSERT INTO images (image, itemId) VALUES ('" . $image . "','" . $id . "') ";
                            if ($stmt = mysqli_prepare($conn, $sql)) {
                                if (mysqli_stmt_execute($stmt)) {
                                    echo "image added!";
                                    mysqli_stmt_close($stmt);
                                }
                            }
                        }

                    } else {
                        echo "<div><p>Upload failed</p></div>";
                        die();
                    }
                } else {
                    echo "<div><p>Incorrect image type!</p></div>";
                    die();
                }
            }
        ?>
    </body>
</html>