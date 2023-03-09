console.log("navbar.js loaded");

//vars
let selectBarWidth = "--select-bar-factor";
let currPageId = "nav-1";

let idPagePair = 
{
    "nav-1": "index.html",
    "nav-2": "page2.html",
    "nav-3": "page3.html",
    "nav-4": "page4.html"
};

function RedirectWindow(elemId) {
    window.location.href = idPagePair[elemId];
}

for(let id in idPagePair) { //getting the current page id
    if(window.location.href == idPagePair[id]) {
        currPageId = id;
    }
}

//initial values based on width of the window
document.documentElement.style.setProperty(selectBarWidth, window.innerWidth/35); //initial select bar width
$(".nav-text").css("font-size", "" + window.innerHeight/38 + "px"); //initial text size
$(".select-bar").css("height", "" + window.innerHeight/400.5 + "px"); //initial select bar height

//hover text color change
$(".nav-item").hover(function() {
    $(this).children(".nav-text").css("color", "#00704d");//color text when hovered
}, function() {
    if(this.id != currPageId) // check if its already the selected button
        $(this).children(".nav-text").css("color", "#00A362"); //color text when not hovered
});

// setting the current page button to make it look like its selected
$("#" + currPageId).children(".nav-text").css("color", "#00704d");
$("#" + currPageId).children(".select-bar").css({"visibility": "visible", "transform": "scaleX(var(--select-bar-factor))"});


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
    if(this.id != currPageId)
        RedirectWindow(this.id) 
});