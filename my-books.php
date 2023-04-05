<?php

    include('config/db_connect.php');

    $sql = 'SELECT * FROM books';

    $result = mysqli_query($connection, $sql);

    $books = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result); //free result from memory
    mysqli_close($connection); //close connection

    function simplifyData($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
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
                <div class="title">
                    <h1 id="add-books">My Books</h1>
                </div>
                <p class="main-text">
                    Your personal collection of books. To save books to your collection go to our <a href="add-books.php">Save Books</a> page and enter an ISBN number, or save the books from a <a href="browse.php">Browse</a> search by title.
                </p>
                <div id="title-and-search">
                </div>
                <div id="home-trending-background">
                    <?php if($books != []): ?>
                        <?php foreach($books as $book): ?>
                            <div class="home-trending-book">
                                <img src="<?php echo $book["cover"]; ?>">
                                <h1 id="book-title"><?php echo $book["title"]; ?></h1>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div id="no-results">No Books Saved</div>
                    <?php endif; ?>
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