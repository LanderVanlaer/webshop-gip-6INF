/**
 * @return URLSearchParams the search params of the current link
 */
const getUrlSearchParams: () => { [key: number]: string[] } = () => {
    const urlSearchParams = new URLSearchParams(document.location.search.substring(1));
    const params: { [key: number]: string[] } = {};

    for (const param of urlSearchParams.keys()) {
        let [keyString, value] = param.trim().split("_");
        const key: number = Number(keyString);

        value = decodeURIComponent(value);
        value = value.replace(/\+/gi, ' ');

        if (!params[Number(key)])
            params[Number(key)] = [];

        params[Number(key)].push(value);
    }
    return params;
};
/**
 * All the Articles on the page
 */
const articles: HTMLElement[] = [...document.querySelectorAll("body > main > section > article.article")] as HTMLElement[];
/**
 * The properties form
 */
const properties: HTMLFormElement = document.querySelector<HTMLFormElement>("body > main > aside > form[name='properties']");
/**
 * Filters the articles
 */
const updateFilterArticles = (): void => {
    const params = getUrlSearchParams();
    articles.forEach((article: HTMLElement): void => {
        const specifications = JSON.parse(article.dataset.specifications);
        let show = true;

        Object.keys(specifications).forEach((key: string) => {
            const value: string = specifications[key];

            if (params[key]) {
                if (!params[key].includes(value))
                    show = false;
            }
        });

        article.style.display = show ? 'inline-block' : 'none';
    });
};

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
    updateFilterArticles();
};

/**
 * All the {@link HTMLInputElement} in the {@link properties} form.
 */
const ELEMENTS: HTMLInputElement[] = [...properties.querySelectorAll<HTMLInputElement>('input')];
properties.addEventListener('submit', (e: Event) => e.preventDefault());


/**
 * All the query's in the url when loading the page
 */
const queryOnLoadPage: URLSearchParams = new URLSearchParams(location.search);

// check checkboxes when in GET query
queryOnLoadPage.forEach((v: string, k: string): void => {
    if (v === 'on')
        ELEMENTS.forEach((e: HTMLInputElement) => {
            if (e.name === k) e.checked = true;
        });
});

//Submit on check for all elements
ELEMENTS.forEach((e: HTMLInputElement): void => e.addEventListener('change', submit));
updateFilterArticles();