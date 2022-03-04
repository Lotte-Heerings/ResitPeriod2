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
        <div id="categories">
            <h4>Select one of the following categories</h4>
            <p>
                <a href="index.php?category=phones">Phones</a> -
                <a href="index.php?category=computers">Computers</a> -
                <a href="index.php?category=components">Components</a> -
                <a href="index.php?category=smartWatches">Smart Watches</a> -
                <a href="index.php?category=smartHome">Smart Home</a>
            </p>
        </div>
        <div id="main">
            <div id="topMain">
                <h1>Found products</h1>
            </div>
            <div id="sortTitle">
                <a href="index.php?sort=title<?php if(isset($_GET["category"])) {echo "&category=" . $_GET["category"];} ?>">Sort by title.</a>
            </div>
            <div id="sortPrice">
                <a href="index.php?sort=price<?php if(isset($_GET["category"])) {echo "&category=" . $_GET["category"];} ?>">Sort by price.</a>
            </div>
            <?php
                if ($conn = mysqli_connect("localhost","root","")) {
                    mysqli_select_db($conn, "webshop");
                    if (isset($_GET["sort"])) {
                        switch ($_GET["sort"]) {
                            case "title":
                                $orderType = "title";
                                break;
                            case "price":
                                $orderType = "price";
                                break;
                        }
                    } else {
                        $orderType = "title";
                    }
                    if (isset($_GET["category"])){
                        switch ($_GET["category"]) {
                            case "phones":
                                $sql = "SELECT id, title, category, price, mainImage FROM items WHERE category = 'phones' ORDER BY " . $orderType;
                                break;
                            case "computers":
                                $sql = "SELECT id, title, category, price, mainImage FROM items WHERE category = 'computers' ORDER BY " . $orderType;
                                break;
                            case "components":
                                $sql = "SELECT id, title, category, price, mainImage FROM items WHERE category = 'components' ORDER BY " . $orderType;
                                break;
                            case "smartWatches":
                                $sql = "SELECT id, title, category, price, mainImage FROM items WHERE category = 'smartWatches' ORDER BY " . $orderType;
                                break;
                            case "smartHome":
                                $sql = "SELECT id, title, category, price, mainImage FROM items WHERE category = 'smartHome' ORDER BY " . $orderType;
                                break;
                            default:
                                echo "error switch case.";
                        }
                    } else {
                        $sql = "SELECT id, title, category, price, mainImage FROM items ORDER BY " . $orderType;
                    }
                    if ($stmt = mysqli_prepare($conn, $sql)) {
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_bind_result($stmt, $id, $title, $category, $price, $mainImage);
                        mysqli_stmt_store_result($stmt);

                        if (mysqli_stmt_num_rows($stmt) > 0) {
                            while (mysqli_stmt_fetch($stmt)) {
                                echo
                                "<div class='itemBox'>
                                    <div class='imageBox'>
                                        <a href='item.php?id=" . $id . "' target='_blank'><img src='images/". $mainImage ."'></a>
                                    </div>
                                    <div class='priceBox'>
                                        <h1>&euro;" . $price . "</h1>                               
                                    </div>
                                </div>";
                            }
                            mysqli_stmt_close($stmt);
                            mysqli_close($conn);
                        } else {
                            echo "no entries found.";
                        }
                    } else {
                        echo "<div><p>Connection check error. Try again later</p></div>";
                    }
                } else {
                    echo "<div><p>Connection error. Try again later</p></div>";
                }
            ?>
        </div>
    </body>
</html>