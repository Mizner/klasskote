(function () {
    var shopButton = document.getElementById("theShop");
    var megaMenu = document.getElementById("megaMenu");
    shopButton.addEventListener("click", function () {
        megaMenu.classList.toggle("show");
    })
})();