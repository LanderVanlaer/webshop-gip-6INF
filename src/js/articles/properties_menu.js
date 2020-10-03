/**
 * All the query's in the url when loading the page
 */
const query = new URLSearchParams(location.search);
/**
 * @type HTMLFormElement
 */
const properties = document.querySelector("body > main > aside > form[name='properties']");
const ELEMENTS = [...properties.elements];

properties.addEventListener('submit', e => {
    e.preventDefault();
    return false;
});

// check checkboxes when in GET query
query.forEach((v, k) => {
    if (v == 'on')
        ELEMENTS.forEach( /** @type e HTMLInputElement */ e => {
            if (e.name === k) e.checked = true
        });
});

//Submit on check
ELEMENTS.forEach(e => e.addEventListener('change', () => {
    properties.submit();
}));

// TODO don't refresh after clicking checkbox