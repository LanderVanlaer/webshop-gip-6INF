interface ICountry {
    id: number;
    name: string
}

const fetchCategory = async (q: string): Promise<Array<ICountry>> => {
    const res: Response = await fetch(`/api/address/country/index.php?q=${q}`);
    return await res.json();
};

const countryInput: HTMLInputElement = document.querySelector("#country");
const countryInputId: HTMLInputElement = document.querySelector("#country_id");
const countryOutputTable: HTMLTableElement = document.querySelector("#country-dynamic");

const addCountriesDynamic = async () => {
    countryInputId.value = null;

    const value: string = countryInput.value.trim();
    if (!value)
        return countryOutputTable.innerHTML = null;

    const data: Array<ICountry> = await fetchCategory(value);

    countryOutputTable.innerHTML = null;

    for (const item of data) {
        if (item.name.toLowerCase() == value.toLowerCase()) {
            countryInputId.value = String(item.id);
            break;
        }
    }

    for (let i = 0; i < data.length && i < 6; i++) {
        const country = data[i];
        const tr: HTMLTableRowElement = document.createElement<'tr'>('tr');
        tr.innerHTML = `<td>${country.id}</td><td>${country.name}</td>`;

        tr.addEventListener('click', () => {
            countryInput.value = country.name;
            countryInputId.value = String(country.id);
            countryOutputTable.innerHTML = null;
        });

        countryOutputTable.appendChild<HTMLTableRowElement>(tr);
    }
};

countryInput.addEventListener("input", addCountriesDynamic);
countryInput.addEventListener("focusin", addCountriesDynamic);
document.querySelector<HTMLFormElement>("main > form").addEventListener("submit", addCountriesDynamic);

window.addEventListener("keydown", e => {
    if (e.target != countryInput)
        countryOutputTable.innerHTML = null;
});