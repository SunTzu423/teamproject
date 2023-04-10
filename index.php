<?php

    function simplifyData($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $search = "";
    $searchError = "";

    if($_SERVER['REQUEST_METHOD'] == 'POST') { //If search bar submitted
        if(empty($_POST['search'])) {
            $searchError = "Please enter some text.";            
        } else {
            $search = simplifyData($_POST['search']);
            header("Location: browse.php?search=" . urlencode($search));
            exit();
        }
    }

    include('config/db_connect.php');

    $sql = 'SELECT * FROM books';

    $result = mysqli_query($connection, $sql);

    $books = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result); //free result from memory
    mysqli_close($connection); //close connection

    // print_r($books);
    
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Temporary Title</title>
        <link rel="stylesheet" type="text/css" href="scripts/general-styles.css"/>
        <script type="text/javascript" src="https://code.jquery.com/jquery-latest.min.js"></script>
        <script src="https://kit.fontawesome.com/7a99b4a29c.js" crossorigin="anonymous"></script> <!-- adds easy icons like the magnifying glass in the search button -->
    </head>
    <body>
        <div id="navbar"></div> <!-- navbar code from navbar.html gets imported here-->
        <div id="main-flex-container">
            <div class="main-text-background">
                <div id="title-and-search">
                    <h1 id="welcome">Welcome to Website Name</h1>
                    <div id="search-bar-background"> <!--USE CSS CLAMP()-->
                        <form class="search" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                            <div id="search-container">
                                <input type="text" placeholder="Search Books" name="search" autocomplete="off">
                                <span id="search-error"><?php echo $searchError;?></span>
                            </div>
                            <!-- <input type="text" placeholder="Search Books" name="search" autocomplete="off">  -->
                            <button type="submit" id="search-button" name="submit">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                Search 
                            </button>
                        </form>
                    </div>
                </div> 
                <p class="main-text">
                    <!-- Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. -->
                    <!-- <br><br> -->
                    This website allows you to: 
                    <ul class="main-text">                        
                        <li>Browse from a collection of books</li>
                        <li>Keep track of your favorite books</li>
                        <li>Add books to your personal collection by ISBN</li>
                        <li>And more</li>
                    </ul>
                </p>
                <br>
                <!-- <div id="home-trending-background">
                    <?php foreach($books as $book): ?>
                        <div class="home-trending-book">
                            <img src="<?php echo $book["cover"]; ?>">
                            <h1 id="book-title"><?php echo $book["title"]; ?></h1>
                        </div>
                    <?php endforeach; ?>
                </div> -->
            </div>
        </div>
        <!-- <div id="bottom-test"></div> -->
        <script>
            $(document).ready(function(){
                 $("#navbar").load("navbar/navbar.html");
            });
        </script>
    </body>
</html>

<!-- 
const isbn = "0747532699";

const url = `https://openlibrary.org/api/books?bibkeys=ISBN:${isbn}&format=json&jscmd=data`;

fetch(url)
    .then(response => {
        if (response.ok) {
            return response.json();
        } else {
            console.log("book not found");
        }
    })
    .then(data => {
        const bookInfo = data[`ISBN:${isbn}`];
        const { title, authors } = bookInfo;
        console.log(`Title: ${title}`);
        console.log(`Author(s): ${authors.map(author => author.name).join(", ")}`);
    })
    .catch(error => console.log(error));


 -->