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
        <?php
            if ($conn = mysqli_connect("localhost","root","")) {
                $id = $_GET["id"];
                mysqli_select_db($conn, "webshop");
                $sql = "SELECT title, description, price, adult, mainImage FROM items WHERE id = ?";
                if ($stmt = mysqli_prepare($conn, $sql)) {
                    mysqli_stmt_bind_param($stmt, "i", $id);
                    if(mysqli_stmt_execute($stmt)){
                        mysqli_stmt_bind_result($stmt, $title, $description, $price, $adult, $mainImage);
                        mysqli_stmt_store_result($stmt);
                        mysqli_stmt_fetch($stmt);
                    } else {
                        echo "error at execute";
                    }
                } else {
                    echo "error at prepare";
                }
                $sqlImage = "SELECT imageId, image FROM images WHERE itemId = ?";
                if ($stmtImage = mysqli_prepare($conn, $sqlImage)) {
                    mysqli_stmt_bind_param($stmtImage, "i", $id);
                    if(mysqli_stmt_execute($stmtImage)){
                        mysqli_stmt_bind_result($stmtImage, $imageId, $image);
                        mysqli_stmt_store_result($stmtImage);
                    } else {
                        echo "error at execute image";
                    }
                } else {
                    echo "error at prepare image";
                }
            } else {
                echo "error ar connect";
            }
        ?>
        <div id="mainItem">
            <div id="item">
                <div id="mainImage"><img src="images/<?php echo $mainImage; ?>"></div>
                <div id="itemInfo">
                    <div><h1><?php echo $title; ?></h1></div>
                    <div><p class="bigLetters">&euro;<?php echo $price; ?></p></div>
                    <div><p><?php echo $description; ?></p></div>
                </div>
                <div id="buyArea">
                    <div id="buyButton">
                        <form>
                            <input type="button" name="buy">
                        </form>
                    </div>
                </div>
            </div>

            <div id="images">
                <?php
                    if(mysqli_stmt_num_rows($stmtImage) > 0)
                    {
                        while(mysqli_stmt_fetch($stmtImage))
                        {
                            echo "<div class='image'><img src='images/notPrimary/" . $image . "'</img></div>";
                        }
                    }
                ?>
            </div>
        </div>
    </body>
</html>