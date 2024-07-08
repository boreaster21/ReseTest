let hamburger = document.getElementById("hamburger");
let hamburgerMenu = document.getElementById("hamburger-menu");
let close = document.getElementById("close");

hamburger.addEventListener('click', function () {
    hamburgerMenu.classList.add('show');
    hamburger.style.display = "none";
});

close.addEventListener('click', function () {
    hamburgerMenu.classList.remove('show');
    hamburger.style.display = "block";
});