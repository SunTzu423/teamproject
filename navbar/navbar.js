console.log("navbar.js loaded");

//vars
let selectBarWidth = "--select-bar-factor";

let idPagePair = 
{
    "nav-1": "index.php",
    "nav-2": "my-books.php",
    "nav-3": "browse.php",
    "nav-4": "add-books.php",
    "nav-5": "page-5.html"
};

function RedirectWindow(elemId) {
    window.location.href = idPagePair[elemId];
}

//initial values based on width of the window
document.documentElement.style.setProperty(selectBarWidth, window.innerWidth/35); //initial select bar width
$(".nav-text").css("font-size", "" + window.innerHeight/38 + "px"); //initial text size
$(".select-bar").css("height", "" + window.innerHeight/400.5 + "px"); //initial select bar height

//hover text color change
$(".nav-item").hover(function() {
    $(this).children(".nav-text").css("color", "#00704d");//color text when hovered
}, function() {
    $(this).children(".nav-text").css("color", "#00A362"); //color text when not hovered
});

//text and navbar scales with window
window.onresize = function() {
    //text resize with window
    if(window.innerHeight/38 > 15) { //sets minimum text size
        $(".nav-text").css({
            "font-size": "" + window.innerHeight/38 + "px", 
            "top": "25%"
        });
    } 
    //select bar height scales with window
    $(".select-bar").css("height", "" + window.innerHeight/400.5 + "px");

    //select bar scales with window width
    document.documentElement.style.setProperty(selectBarWidth, window.innerWidth/35);
};

//navbar buttons redirect window
$(".nav-item").click(function() { 
    RedirectWindow(this.id) 
});