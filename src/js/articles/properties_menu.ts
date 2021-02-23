/**
 * Changing URL by the {@link properties} without reloading the page
 */
const submit: () => void = (): void => {
    const params: URLSearchParams = new URLSearchParams();

    ELEMENTS.forEach((e: HTMLInputElement) => {
        switch (e.type) {
            case 'checkbox':
                if (e.checked)
                    params.append(e.name, 'on');
                break;
        }
    });

    history.pushState(null, document.title, location.pathname + (params.toString() ? "?" + params.toString() : ''));
};


/**
 * All the query's in the url when loading the page
 */
const query: URLSearchParams = new URLSearchParams(location.search);

/**
 * All the checkboxes on the page in the form properties
 */
const properties: HTMLFormElement = document.querySelector<HTMLFormElement>("body > main > aside > form[name='properties']");

/**
 * An array of checkboxes
 * @see {@link properties} for further information
 */
const ELEMENTS: HTMLInputElement[] = [...properties.elements] as Array<HTMLInputElement>;

properties.addEventListener('submit', (e: Event) => e.preventDefault());

// check checkboxes when in GET query
query.forEach((v: string, k: string): void => {
    if (v === 'on')
        ELEMENTS.forEach((e: HTMLInputElement) => {
            if (e.name === k) e.checked = true;
        });
});

//Submit on check for all elements
ELEMENTS.forEach((e: HTMLInputElement): void => e.addEventListener('change', submit));