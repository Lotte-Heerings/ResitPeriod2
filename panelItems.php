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
        <div id="main">
            <?php
                if ($conn = mysqli_connect("localhost","root","")) {
                    mysqli_select_db($conn, "webshop");
                    $sql = "SELECT id, title, description, category, price, adult, discounted, discountedUntil, discountedPercentage, mainImage FROM items";
                    if ($stmt = mysqli_prepare($conn, $sql)) {
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_bind_result($stmt, $id, $title, $description, $category, $price, $adult, $discounted, $discountedUntil, $discountedPercentage, $mainImage);
                        mysqli_stmt_store_result($stmt);

                        echo "<div>";
                        echo "<table>
                                <tr>
                                    <th>Item ID</th>
                                    <th>Item Title</th>
                                    <th>Item Description</th>
                                    <th>Item Category</th>
                                    <th>18+</th>
                                    <th>Discounted right now</th>
                                    <th>Until when</th>
                                    <th>By how much %</th>
                                    <th>Main image name</th>
                                    <th>Edit</th>
                                    <th>Add extra images</th>
                                </tr>";
                        if (mysqli_stmt_num_rows($stmt) > 0) {
                            while (mysqli_stmt_fetch($stmt)) {
                                echo "<tr>";
                                echo    "<td>" . $id . "</td>
                                         <td>" . $title . "</td>
                                         <td>" . $description . "</td>
                                         <td>" . $category . "</td>
                                         <td>" . $price . "</td>
                                         <td>" . $adult . "</td>
                                         <td>" . $discounted . "</td>
                                         <td>" . $discountedUntil . "</td>
                                         <td>" . $discountedPercentage . "</td>
                                         <td>" . $mainImage . "</td>
                                         <td><a href='editItem.php?id=" .$id . "'><b>Edit</b></a></td>
                                         <td><a href='addExtraImages.php?id=" .$id . "'><b>Add extra images</b></a></td></td>";
                                echo "</tr>";
                            }
                        }
                        echo "</table></div>";
                        mysqli_stmt_close($stmt);
                        mysqli_close($conn);
                    } else {
                        echo "error at prepare";
                    }
                } else {
                    echo "error at connect";
                }
            ?>
        </div>
    </body>
</html>

