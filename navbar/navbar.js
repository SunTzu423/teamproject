console.log("navbar.js loaded");

//vars
let selectBarWidth = "--select-bar-factor";

//functions
function RedirectWindow(elemId) { //takes ID of the nav-item clicked and redirects to the correct page
    switch(elemId) {
        case "nav-1":
            window.location.href = "index.html";
            break;
        case "nav-2":
            window.location.href = "page2.html";
            break;
        case "nav-3":
            window.location.href = "page3.html";
            break;
        default:
            console.log("redirectWindow error");
            break;
    }
}

//initial values based on width of the window
document.documentElement.style.setProperty(selectBarWidth, window.innerWidth/35); //initial select bar width
$(".nav-text").css("font-size", "" + window.innerHeight/38 + "px"); //initial text size
$(".select-bar").css("height", "" + window.innerHeight/400.5 + "px"); //initial select bar height

//hover text color change
$(".nav-item").hover(function() {
    $(this).children(".nav-text").css("color", "#e74c3c");//red text when hovered
}, function() {
    $(this).children(".nav-text").css("color", "#848484"); //grey text when not hovered
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
$(".nav-item").click(function() { RedirectWindow(this.id) });