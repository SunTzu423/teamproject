<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Temporary Title</title>
        <link rel="stylesheet" type="text/css" href="scripts/general-styles.css"/>
        <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
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
                        <form class="search" action="" method="GET">
                            <input type="text" placeholder="Search Books" name="search" autocomplete="off"> 
                            <button type="button" id="drop-down-button" name="drop-down">
                                <i class="fa-solid fa-caret-down"></i>
                                T
                            </button>
                            <button type="submit" id="search-button" name="submit">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                Search 
                            </button>
                        </form>
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