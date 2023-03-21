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
                <div id="trending-background"></div>
                <div id="trending-background"></div>
                <div id="trending-background"></div>
            </div>                 
        </div>
        <script>
            $(document).ready(function() {
                $("#navbar").load("navbar/navbar.html");
            });
        </script>
    </body>
</html>