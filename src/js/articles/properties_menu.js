/**
 * All the query's in the url when loading the page
 */
const query = new URLSearchParams(location.search);
/**
 * All the checkboxes on the page in the form properties
 * @type HTMLFormElement
 */
const properties = document.querySelector("body > main > aside > form[name='properties']");

/**
 * An array of checkboxes
 * @type {Array<HTMLInputElement>}
 * @see {@link properties} for further information
 */
const ELEMENTS = [...properties.elements];

properties.addEventListener('submit', e => e.preventDefault());

// check checkboxes when in GET query
query.forEach((v, k) => {
    if (v == 'on')
        ELEMENTS.forEach( /** @type e HTMLInputElement */ e => {
            if (e.name === k) e.checked = true
        });
});

//Submit on check for all elements
ELEMENTS.forEach(e => e.addEventListener('change', submit));

/**
 * Changing URL by the {@link properties} without reloading the page
 */
function submit() {
    const params = new URLSearchParams();
    ELEMENTS.forEach(e => {
        switch (e.type) {
            case 'checkbox':
                if (e.checked)
                    params.append(e.name, 'on');
                break;
        }
    })
    history.pushState(null, document.title, location.pathname + (params.toString() ? "?" + params.toString() : ''));
}