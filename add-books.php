<?php
    $isbn = '';
    $isbnMessage = '';    

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(empty($_POST['isbn'])) {
            $isbnMessage = "Please enter some text.";
        } else if(validateIsbn($_POST['isbn'])) {
            $isbn = $_POST['isbn'];
            addBook($isbn);
        } else {
            $isbnMessage = "Please enter a valid ISBN number.";
        }
    }

    function validateIsbn($isbn) {
        // $isbnRegex = '/^(?:(?=.{17}$)978[\d-]{14}|(?=.{13}$)(?:\d[\d-]{12}|\d{9})|\d{10})$/';
        $isbnRegex = '/^(\d{10}|\d{13})$/';
        $isbn = implode(explode('-', $_POST['isbn'])); //removes hyphens

        return preg_match($isbnRegex, $isbn);
    }

    function addBook($isbn) {
        global $isbnMessage;

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
    
?>

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
                    <h1 id="add-books">Save Books</h1>
                </div>
                <form action="" method="POST" id="add-books-form">
                    <div class="isbn-container">
                        <label for="isbn" class="label-text">ISBN</label>                        
                        <input type="text" class="add-books-input" name="isbn" placeholder="Enter ISBN Number" autocomplete="off">
                        <span id="search-error"><?php echo $isbnMessage; ?></span>
                    </div>
                </form>
                <p class="main-text">
                   Add books to our online library consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                </p>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                $("#navbar").load("navbar/navbar.html");
            });
        </script>
    </body>
</html>