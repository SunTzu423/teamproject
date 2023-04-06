<?php

    $title = '';
    $isbn = '';
    $cover = '';

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(!empty($_POST['title']) && !empty($_POST['isbn']) && !empty($_POST['cover'])) {
            $title = $_POST['title'];
            $isbn = $_POST['isbn'];
            $cover = $_POST['cover'];
        }

    }

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Temporary Title</title>
        <link rel="stylesheet" type="text/css" href="scripts/general-styles.css"/>
        <link rel="stylesheet" type="text/css" href="scripts/browse-styles.css"/>
        <script type="text/javascript" src="https://code.jquery.com/jquery-latest.min.js"></script>
        <script src="https://kit.fontawesome.com/7a99b4a29c.js" crossorigin="anonymous"></script> <!-- adds easy icons like the magnifying glass in the search button -->
    </head>
    <body>
        <div id="navbar"></div>
        <div id="main-flex-container">
            <div class="main-text-background">
                <div id="title-and-image">
                    <img src="<?php echo $cover; ?>">
                    <div id="book-title-details-container">
                        <h1 id="book-title-details"><?php echo $title; ?></h1>
                    </div>
                </div>
                
            </div>                 
        </div>
        <script>
            $(document).ready(function() {
                $("#navbar").load("navbar/navbar.html");
            });
        </script>
    </body>
</html>