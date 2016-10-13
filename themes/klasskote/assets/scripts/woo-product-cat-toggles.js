(function () {
    var parentItems = document.querySelectorAll("#secondary .cat-parent");

    function buttonToggle() {
        var buttons = document.querySelectorAll(".wooToggle");
        for (var i = 0; i < buttons.length; i++) {
            buttons[i].addEventListener("click", function (event) {
                this.parentElement.classList.toggle("toggled");
            })
        }
        return buttons;
    }

    function insertButtons(object) {
        object.forEach(function (item) {
            var newElement = document.createElement("button");
            newElement.className = "wooToggle";
            var newContent = document.createTextNode("+");
            newElement.appendChild(newContent);
            item.insertBefore(newElement, item.firstChild);
        });
        return object;
    }

    if (parentItems.length > 0) {
        Promise
            .resolve(parentItems)
            .then(insertButtons)
            .then(buttonToggle)
            .catch();
    }

})();