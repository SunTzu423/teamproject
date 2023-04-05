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
        $filteredSearch = implode("", explode("'", $filteredSearch));
        $filteredSearch = implode("", explode('"', $filteredSearch));
        
        $url = "https://openlibrary.org/search.json?q=".$filteredSearch;

        $response = file_get_contents($url);
        $data = json_decode($response, true);

        $results = [];
        if (isset($data["docs"])) {
            foreach ($data["docs"] as $doc) {
                if(isset($doc["cover_i"])) {
                    $book = [
                        "title" => isset($doc["title"]) ? $doc["title"] : "",
                        "cover" => "https://covers.openlibrary.org/b/id/{$doc["cover_i"]}-M.jpg",
                    ];

                    array_push($results, $book);
                }
            }
        }

        return $results;
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
                <?php if($_SERVER['REQUEST_METHOD'] == 'GET' && empty($_GET['search'])): ?>
                    <div class="title">
                        <h1 id="add-books">Browse</h1>
                    </div>
                    <p class="main-text">
                        Browse a large collection of books based on keywords or title. 
                    </p>
                <?php endif; ?>
                <div id="title-and-search">
                    <div id="search-bar-background"> <!--USE CSS CLAMP()-->
                        <form class="search" action="" method="POST">
                            <div id="search-container">
                                <input type="text" placeholder="Search Books" name="search" autocomplete="off" value="<?php echo $search; ?>">
                                <span id="search-error"><?php echo $searchError;?></span>
                            </div>
                            <!-- <select id="drop-down" name="drop-down">
                                <option value="test">Title</option>
                                <option value="test2">Author</option>
                                <option value="test3">Thing</option>
                            </select> -->
                            <button type="submit" id="search-button" name="submit">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                Search 
                            </button>
                        </form>
                    </div>
                </div>
                <div id="home-trending-background">
                    <?php if($_SERVER['REQUEST_METHOD'] == 'GET' || $search == ''): ?> <!-- if visiting page from Home page or from navbar -->
                        <?php if(empty($_GET['search'])): ?> <!-- if visiting page from navbar -->
                            <!-- <?php foreach($books as $book): ?>
                                <div class="home-trending-book">
                                    <img src="<?php echo $book["cover"]; ?>">
                                    <h1 id="book-title"><?php echo $book["title"]; ?></h1>
                                </div>
                            <?php endforeach; ?> -->
                            <div id="no-results">No Search Results</div>
                        <?php else: ?> <!-- if visiting page from Home page (search) -->
                            <?php $bookResults = getSearchResults($search); ?>
                            <?php if($bookResults != []): ?>
                                <?php foreach($bookResults as $book): ?>
                                    <div class="home-trending-book">
                                        <img src="<?php echo $book["cover"]; ?>">
                                        <h1 id="book-title"><?php echo $book["title"]; ?></h1>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div id="no-results">No Search Results</div>
                            <?php endif; ?>
                        <?php endif; ?> 
                    <?php else: ?> <!-- if visiting page from Browse (search) -->
                        <?php $bookResults = getSearchResults($search); ?>
                        <?php if($bookResults != []): ?>
                            <?php foreach($bookResults as $book): ?>
                                <div class="home-trending-book">
                                    <img src="<?php echo $book["cover"]; ?>">
                                    <h1 id="book-title"><?php echo $book["title"]; ?></h1>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div id="no-results">No Search Results</div>
                        <?php endif; ?>
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