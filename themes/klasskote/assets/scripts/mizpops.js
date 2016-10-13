(function () {

    var triggers = document.getElementsByClassName("miztrigger");

    function triggerShows(arr) {
        for (var i = 0; i < arr.length; i++) {
            arr[i].addEventListener("click", function () {
                document.getElementsByClassName(this.getAttribute("name"))[0].style.display = "block";
            });
        }
        return arr;
    }

    triggerShows(triggers);

    var wrappers = document.getElementsByClassName("mizwrap");

    function theSVG() {
        return "<svg class='closeIcon' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' version='1.1' x='0px' y='0px' width='40px' height='40px' viewBox='0 0 357 357' style='enable-background:new 0 0 357 357;' xml:space='preserve'> <g id='close'> <polygon points='357,35.7 321.3,0 178.5,142.8 35.7,0 0,35.7 142.8,178.5 0,321.3 35.7,357 178.5,214.2 321.3,357 357,321.3 214.2,178.5' fill='#FFFFFF'></polygon> </g> </svg>";
    }

    function triggerHides(modules) {
        for (var i = 0; i < modules.length; i++) {
            modules[i].getElementsByClassName("closeButton")[0].addEventListener("click", function () {
                this.parentElement.style.display = "none";
            });
        }
    }

    function addCloseButtons(arr) {
        for (var i = 0; i < arr.length; i++) {
            var btn = document.createElement("BUTTON");
            btn.innerHTML = theSVG();
            btn.classList.add("closeButton");
            arr[i].insertBefore(btn, arr[i].firstElementChild);
        }
        return arr;
    }

    Promise
        .resolve(wrappers)
        .then(addCloseButtons)
        .then(triggerHides);
})();