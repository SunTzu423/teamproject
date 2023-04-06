<?php

    $isbnMessage = '';
    $isbn = '';

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(!empty($_POST['isbn']) && validateIsbn($_POST['isbn'])) {
            $isbn = $_POST['isbn'];
            addBook($isbn);
        } else {
            $isbnMessage = "Could not save the book.";
        }
    }

    include('config/db_connect.php');

    $sql = 'SELECT * FROM books';

    $result = mysqli_query($connection, $sql);

    $books = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result); //free result from memory
    mysqli_close($connection); //close connection

    function addBook($isbn) {
        global $isbnMessage;

        $url = "https://openlibrary.org/api/books?bibkeys=ISBN:".$isbn."&format=json&jscmd=data";

        $response = file_get_contents($url);
        $data = json_decode($response, true);

        if (isset($data["ISBN:".$isbn])) {
            $bookInfo = $data["ISBN:".$isbn];
            $title = implode(explode('"', $bookInfo["title"])); //removes double quotes for the SQL query

            if(isset($bookInfo["authors"])) {
                $authors = array_map(function ($author) {
                    return $author["name"];
                }, $bookInfo["authors"]);
                $authorsStr = implode(", ", $authors);
            } else {
                $authorsStr = "N/A";
            }
            
            $cover = (isset($bookInfo["cover"])) ? $bookInfo["cover"]["medium"] : "";

            // 0439708184 test isbn
            include('config/db_connect.php');

            $sql = "INSERT INTO books(isbn, title, authors, cover) VALUES (\"$isbn\", \"$title\", \"$authorsStr\", \"$cover\");";
            if($cover != "" && mysqli_query($connection, $sql)) {
                $isbnMessage = "Successfully saved book!";
            } else {
                $isbnMessage = "Could not save the book.";
            }

            //mysqli_free_result($result); //free result from memory
            mysqli_close($connection); //close connection

            // print_r($books);

        } else {
            $isbnMessage = "Could not save the book.";
        }
    }

    function simplifyData($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    function validateIsbn($isbn) {
        $isbnRegex = '/^(\d{10}|\d{13})$/';
        $isbn = implode(explode('-', $_POST['isbn'])); //removes hyphens

        return preg_match($isbnRegex, $isbn);
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
                <div id="title-and-search"></div>
                <div class="main-text" id="search-error"><?php echo $isbnMessage; ?></div>
                <div id="home-trending-background">
                    <?php if($books != []): ?>
                        <?php foreach($books as $book): ?>
                            <div class="home-trending-book">
                                <form action="book-details.php" method="POST">
                                        <input type="hidden" name="isbn" value="<?php echo $book["isbn"]; ?>">
                                        <input type="hidden" name="title" value="<?php echo $book["title"]; ?>">
                                        <input type="hidden" name="cover" value="<?php echo $book["cover"]; ?>">
                                        <button name="image" type="submit" id="cover-images">
                                            <img src="<?php echo $book["cover"]; ?>">
                                        </button>
                                    </form>
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