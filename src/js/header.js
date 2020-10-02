//------------------- SEARCH -------------------//
const searchDiv = document.querySelector("#search > div");
const searchBar = document.getElementById("search-bar");

searchBar.addEventListener('input', () => {
    //TODO FETCH
});
searchBar.addEventListener('focusin', () => searchDiv.classList.add("active"))
searchBar.addEventListener('focusout', () => searchDiv.classList.remove("active"))