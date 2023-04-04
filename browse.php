<?php

    $search = '';
    $searchError = '';

    if($_SERVER['REQUEST_METHOD'] == 'GET') { // search from home page

        if(!empty($_GET['search'])) {
            $search = htmlspecialchars($_GET['search']);
        }

    } else if($_SERVER['REQUEST_METHOD'] == 'POST') { //search from this page

        if(empty($_POST['search'])) {
            $searchError = 'Please enter some text.';            
        } else {
            $search = simplifyData($_POST['search']);
        }        
    }

    include('config/db_connect.php');

    $sql = 'SELECT * FROM books';

    $result = mysqli_query($connection, $sql);

    $books = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result); //free result from memory
    mysqli_close($connection); //close connection

    function getSearchResults($search) {

        $filteredSearch = implode("+", explode(" ", $search)); //replaces " " with "+"
        $filteredSearch = implode("", explode("'", $search));
        $filteredSearch = implode("", explode('"', $search));
        
        $url = "https://openlibrary.org/search.json?q=".$search;

        $response = file_get_contents($url);
        $data = json_decode($response, true);

        $isbnArray = $data["docs"][0]["isbn"];

        return $isbnArray;
    }

    function getBookByIsbn($isbn) {

        $bookData = [];

        $url = "https://openlibrary.org/api/books?bibkeys=ISBN:".$isbn."&format=json&jscmd=data";

        $response = file_get_contents($url);
        $data = json_decode($response, true);

        if(isset($data["ISBN:".$isbn]) && isset($data["ISBN:".$isbn]["cover"])) {
            $bookInfo = $data["ISBN:".$isbn];

            $bookData["title"] = $bookInfo["title"];
            $bookData["cover"] = $bookInfo["cover"]["medium"];
        }

        return $bookData;
    }

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
                <!-- <p class="main-text">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                </p> -->
                <div id="title-and-search">
                    <div id="search-bar-background"> <!--USE CSS CLAMP()-->
                        <form class="search" action="" method="POST">
                            <div id="search-container">
                                <input type="text" placeholder="Search Books" name="search" autocomplete="off" value="<?php echo $search; ?>">
                                <span id="search-error"><?php echo $searchError;?></span>
                            </div>
                            <select id="drop-down" name="drop-down">
                                <option value="test">Title</option>
                                <option value="test2">Author</option>
                                <option value="test3">Thing</option>
                            </select>
                            <button type="submit" id="search-button" name="submit">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                Search 
                            </button>
                        </form>
                    </div>
                </div>
                <div id="home-trending-background">
                    <?php if($_SERVER['REQUEST_METHOD'] == 'GET' || $search == ''): ?> <!-- if visiting page from Home page or from navbar-->
                        <?php if(empty($_GET['search'])): ?> <!-- if visiting page from navbar-->
                            <?php foreach($books as $book): ?>
                                <div class="home-trending-book">
                                    <img src="<?php echo $book["cover"]; ?>">
                                    <h1 id="book-title"><?php echo $book["title"]; ?></h1>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?> <!-- if visiting page from Home page -->
                        <?php endif; ?> 
                    <?php else: ?> <!-- if visiting page from Browse (search) -->
                        <?php $isbnResults = getSearchResults($search); ?>
                        <?php foreach($isbnResults as $isbn): ?>
                            <?php $book = getBookByIsbn($isbn); ?>
                            <?php if($book != []): ?> <!-- if the book can be found (AND has cover)-->
                                <div class="home-trending-book">
                                    <img src="<?php echo $book["cover"]; ?>">
                                    <h1 id="book-title"><?php echo $book["title"]; ?></h1>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
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