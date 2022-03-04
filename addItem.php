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
                <p>Item name:</p>
                <input class="textArea" type="text" name="itemName" required>
            </div>
            <div class="formInput">
                <p>Description</p>
                <input class="textArea" type="text" name="itemDescription" required>
            </div>
            <div class="formInput">
            <p>Category</p>
                <select class="textArea" name="category">
                    <option value="phones">Phones</option>
                    <option value="computers">Computers</option>
                    <option value="components">Components</option>
                    <option value="smartWatches">Smart Watches</option>
                    <option value="smartHome">Smart Home</option>
                </select>
            </div>
            <div class="formInput">
                <p>Price</p>
                <input class="textArea" type="text" name="price" required>
            </div>
            <div class="formInput">
                <p>Adult item</p>
                <input type="radio" name="adultItem" value="1">
                <label for="yes">yes</label>
                <input type="radio" name="adultItem" value="0">
                <label for="no">no</label>
            </div>
            <div class="formInput">
                <p>On sale?</p>
                <input type="radio" name="discountItem" value="1">
                <label for="yes">yes</label>
                <input type="radio" name="discountItem" value="0">
                <label for="no">no</label>
            </div>
            <div class="formInput">
                <p>If so until when? (date mentioned here will be the date the discount ends)</p>
                <label for="discountEndDay">Day:</label>
                <input class="textArea" type="text" name="discountEndDay">
                <label for="discountEndMonth">Month:</label>
                <input class="textArea" type="text" name="discountEndMonth">
                <label for="discountEndYear">Year:</label>
                <input class="textArea" type="text" name="discountEndYear">
            </div>
            <div class="formInput">
                <p>By how much percent?</p>
                <input class="textArea" type="text" name="discountPercent">
            </div>
            <div class="formInput">
                <p>Upload main item image here.</p>
                <input type="file" name="mainImage" required>
            </div>
            <input class="formInput" id="submit" type="submit" name="submit" value="submit">
        </form>
    </body>
</html>
<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //filtering all variables
        $title = filter_input(INPUT_POST, "itemName", FILTER_SANITIZE_SPECIAL_CHARS);
        $description = filter_input(INPUT_POST, "itemDescription", FILTER_SANITIZE_SPECIAL_CHARS);
        $category = filter_input(INPUT_POST, "category", FILTER_SANITIZE_SPECIAL_CHARS);
        $price = filter_input( INPUT_POST, "price", FILTER_SANITIZE_NUMBER_FLOAT);
        $adult = filter_input(INPUT_POST, "adultItem", FILTER_SANITIZE_NUMBER_INT);
        $discounted = filter_input(INPUT_POST, "discountItem", FILTER_SANITIZE_NUMBER_INT);;
        //+automatically sets discount time to 0 if there is none provided.
        if (!empty($_POST["discountEndYear"])) {
            $dayEnd = filter_input(INPUT_POST, "discountEndDay", FILTER_SANITIZE_NUMBER_INT);
            $monthEnd = filter_input(INPUT_POST, "discountEndMonth", FILTER_SANITIZE_NUMBER_INT);
            $yearEnd =  filter_input(INPUT_POST, "discountEndYear", FILTER_SANITIZE_NUMBER_INT);
            $discountedUntil = $yearEnd . $monthEnd . $dayEnd;
            echo $discountedUntil;
        } else {
            $discountedUntil = 0;
        }
        if (!empty($_POST["discountPercent"])) {
            $discountedPercentage = filter_input(INPUT_POST, "discountPercent", FILTER_SANITIZE_NUMBER_INT);
        } else {
            $discountedPercentage = 0;
        }
        //+setting up image where image is stored
        $acceptedImageTypes = ["image/jpg", "image/jpeg", "image/png", "image/gif"];
        //finfo file = returns information about a file, finfo open = turns it into an array, mimetype = extension, !!!!!check it yourself
        $mainImageType = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $_FILES["mainImage"]["tmp_name"]);
        //if extension is one of the accepted ones (needle, haystack)
        if (in_array($mainImageType, $acceptedImageTypes)){
            //mime type returns image/extension type so we remove the image
            $fileExtension = str_replace("image/", "",$mainImageType);
            //makes a file base which is the current date:day month year hour minutes seconds;
            $newFileName = date('dmYHis');
            //moves the image to the appropriate folder and renames it into the file base.
            if (move_uploaded_file($_FILES["mainImage"]["tmp_name"],"./images/".$newFileName.".".$fileExtension)) {
                $mainImage = $newFileName . "." . $fileExtension;
            } else {
                echo "<div><p>Upload failed</p></div>";
                die();
            }
        } else {
            echo "<div><p>Incorrect image type!</p></div>";
            die();
        }
        //getting the db ready
        if ($conn = mysqli_connect("localhost","root","")) {
            mysqli_select_db($conn,"webshop");
            //setting the input
            $sql = "INSERT INTO items (title, description, category, price, adult, discounted, discountedUntil, discountedPercentage, mainImage) VALUES (?,?,?,?,?,?,?,?,?)";

            if ($stmt = mysqli_prepare($conn, $sql)) {
                mysqli_stmt_bind_param($stmt, "sssdiiiis", $title, $description, $category, $price, $adult, $discounted, $discountedUntil, $discountedPercentage, $mainImage);
                if (mysqli_stmt_execute($stmt)){
                    echo "Entry added (or so you hope).";
                } else {
                    echo "failed execution";
                }
            } else {
                echo "error at prepare";
            }
        } else {
            echo "error at connect";
        }
        //close connection & Statement
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }
?>