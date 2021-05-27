//noinspection DuplicatedCode

interface ICountry {
    id: number;
    name: string
}

const fetchCountry = async (q: string): Promise<Array<ICountry>> => {
    const res: Response = await fetch(`/api/address/country/index.php?q=${q}`);
    return await res.json();
};

const addCountriesDynamicBuy = async (inputID: HTMLInputElement, typingInput: HTMLInputElement, outputTable: HTMLTableElement) => {
    inputID.value = null;

    const value: string = typingInput.value.trim();
    if (!value)
        return outputTable.innerHTML = null;

    const data: Array<ICountry> = await fetchCountry(value);

    outputTable.innerHTML = null;

    for (const item of data) {
        if (item.name.toLowerCase() == value.toLowerCase()) {
            inputID.value = String(item.id);
            break;
        }
    }

    for (let i = 0; i < data.length && i < 6; i++) {
        const country = data[i];
        const tr: HTMLTableRowElement = document.createElement<'tr'>('tr');
        tr.innerHTML = `<td>${country.id}</td><td>${country.name}</td>`;

        tr.addEventListener('click', () => {
            typingInput.value = country.name;
            inputID.value = String(country.id);
            outputTable.innerHTML = null;
        });

        outputTable.appendChild<HTMLTableRowElement>(tr);
    }
};

const purchaseCountryInput: HTMLInputElement = document.querySelector("#purchase_country");
const purchaseCountryInputId: HTMLInputElement = document.querySelector("#purchase_country_id");
const purchaseCountryOutputTable: HTMLTableElement = document.querySelector("#purchase-country-dynamic");

const deliveryCountryInput: HTMLInputElement = document.querySelector("#delivery_country");
const deliveryCountryInputId: HTMLInputElement = document.querySelector("#delivery_country_id");
const deliveryCountryOutputTable: HTMLTableElement = document.querySelector("#delivery-country-dynamic");

const purchaseCountryDynamic = () => addCountriesDynamicBuy(purchaseCountryInputId, purchaseCountryInput, purchaseCountryOutputTable);
const deliveryCountryDynamic = () => addCountriesDynamicBuy(deliveryCountryInputId, deliveryCountryInput, deliveryCountryOutputTable);

purchaseCountryInput.addEventListener("input", purchaseCountryDynamic);
purchaseCountryInput.addEventListener("focusin", purchaseCountryDynamic);
deliveryCountryInput.addEventListener("input", deliveryCountryDynamic);
deliveryCountryInput.addEventListener("focusin", deliveryCountryDynamic);

window.addEventListener("keydown", e => {
    if (e.target != purchaseCountryInput)
        purchaseCountryOutputTable.innerHTML = null;

    if (e.target != purchaseCountryInput)
        purchaseCountryOutputTable.innerHTML = null;
});