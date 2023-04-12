<?php
    $bookData = [];
    $title = '';
    $isbn = '';
    $cover = '';
    $hasBook = false;
    $isbnMessage = "";

    include('config/db_connect.php');

    $sql = 'SELECT * FROM books';

    $result = mysqli_query($connection, $sql);

    $books = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result); //free result from memory
    mysqli_close($connection); //close connection

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(!empty($_POST['isbn'])) {
            $isbn = $_POST['isbn'];
            $bookData = getBookInfo($isbn);

            foreach($books as $book) {
                if($book["isbn"] == $bookData["isbn"]) {
                    $hasBook = true;
                    break;
                }
            }
        }

        if(!empty($_POST['save'])) {
            $isbn = $_POST['isbn'];
            if(!$hasBook) {
                addBook($isbn);
            } else {
                deleteBook($isbn);
            }
            
        }
    }

    function getBookInfo($isbn) {

        $bookData = [];

        $url = "https://openlibrary.org/api/books?bibkeys=ISBN:".$isbn."&format=json&jscmd=data";

        $response = file_get_contents($url);
        $data = json_decode($response, true);

        if (isset($data["ISBN:".$isbn])) {
            // $data = json_decode($response, true);
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
            
            $cover = (isset($bookInfo["cover"])) ? $bookInfo["cover"]["large"] : "";

            $bookData["isbn"] = $isbn;
            $bookData["authors"] = $authorsStr;
            $bookData["cover"] = $cover;
            $bookData["title"] = $title;

            return $bookData;
        }
    }

    function addBook($isbn) {
        global $isbnMessage;
        global $hasBook;

        $url = "https://openlibrary.org/api/books?bibkeys=ISBN:".$isbn."&format=json&jscmd=data";

        $response = file_get_contents($url);
        $data = json_decode($response, true);

        if (isset($data["ISBN:".$isbn])) {
            // $data = json_decode($response, true);
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
                $isbnMessage = "Successfully added book!";
                $hasBook = true;
            } else {
                $isbnMessage = "Couldn't find the book you're looking for.";
            }

            // $sql = "SELECT * FROM books;";
            // $result = mysqli_query($connection, $sql);
            // $books = mysqli_fetch_all($result, MYSQLI_ASSOC);

            //mysqli_free_result($result); //free result from memory
            mysqli_close($connection); //close connection

            // print_r($books);

        } else {
            $isbnMessage = "Couldn't find the book you're looking for.";
        }
    }

    function deleteBook($isbn) {
        global $isbnMessage;
        global $hasBook;
        include('config/db_connect.php');

        $sql = 'SELECT * FROM books';

        $result = mysqli_query($connection, $sql);

        $books = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_free_result($result); //free result from memory

        $sql = "DELETE FROM books WHERE isbn = \"$isbn\"";
        if(mysqli_query($connection, $sql)) {
            $isbnMessage = "Successfully deleted book!";
            $hasBook = false;
        } else {
            $isbnMessage = "Couldn't delete the book.";
        }

        mysqli_close($connection);
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>VerbVault</title>
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
                    <?php if($bookData["cover"] != ""): ?>
                        <div id="image-background">
                            <img id="cover-image-details" src="<?php echo $bookData["cover"]; ?>">
                        </div>
                    <?php endif; ?>
                    <div id="book-title-details-container">
                        <h1 id="book-title-details"><?php echo $bookData["title"]; ?></h1>
                        <h1 id="book-data-details">Authors: <?php echo $bookData["authors"]; ?></h1>
                        <h1 id="book-data-details">ISBN: <?php echo $bookData["isbn"]; ?></h1>
                        <div class="main-text" id="search-error"><?php echo $isbnMessage; ?></div>
                        <?php if(!$hasBook):?>
                            <form action="" method="POST" id="save-book">
                                <input type="hidden" name="isbn" value="<?php echo $bookData["isbn"]; ?>">
                                <input type="hidden" name="save" value="save">
                                <button type="submit" id="save-button-details" name="submit">Save</button>
                            </form>
                        <?php else: ?>
                            <form action="" method="POST" id="save-book">
                                <input type="hidden" name="isbn" value="<?php echo $bookData["isbn"]; ?>">
                                <input type="hidden" name="save" value="delete">
                                <button type="submit" id="delete-button-details" name="submit">Delete</button>
                            </form>
                        <?php endif; ?>
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