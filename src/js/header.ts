//------------------- SEARCH -------------------//
const searchDiv: Element = document.querySelector("#search > div");
const searchBar: HTMLElement = document.getElementById("search-bar");

searchBar.addEventListener('input', (): void => {
    //TODO FETCH
});

searchBar.addEventListener('focusin', (): void => searchDiv.classList.add("active"));
searchBar.addEventListener('focusout', (): void => searchDiv.classList.remove("active"));