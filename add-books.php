<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Temporary Title</title>
        <link rel="stylesheet" type="text/css" href="scripts/general-styles.css"/>
        <link rel="stylesheet" type="text/css" href="scripts/add-books-styles.css"/>
        <script type="text/javascript" src="https://code.jquery.com/jquery-latest.min.js"></script>
        <script src="https://kit.fontawesome.com/7a99b4a29c.js" crossorigin="anonymous"></script> <!-- adds easy icons like the magnifying glass in the search button -->
    </head>
    <body>
        <div id="navbar"></div>
        <div id="main-flex-container">
            <div class="main-text-background">
                <div class="title">
                    <h1 id="add-books">Add Books</h1>
                </div>
                <form action="" method="GET" id="add-books-form">
                    <div class="isbn-container">
                        <label for="isbn" class="label-text">ISBN </label>                        
                        <input type="text" class="add-books-input" name="isbn" placeholder="Enter ISBN Number">
                    </div>
                </form>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                $("#navbar").load("navbar/navbar.html");
            });
        </script>
    </body>
</html>