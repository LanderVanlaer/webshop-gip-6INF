interface IBrand {
    id: number,
    name: string,
    logo: string,
}

const td: HTMLTableCellElement = document.querySelector<HTMLTableCellElement>("#brand-dynamic");
const divData: HTMLDivElement = td.querySelector<HTMLDivElement>("div.data");
const input: HTMLInputElement = td.querySelector<HTMLInputElement>("input#brand");
const inputId: HTMLInputElement = td.querySelector<HTMLInputElement>("input[name='brand_id']");

const fetchBrand = async (q: string): Promise<Array<IBrand>> => {
    const res: Response = await fetch(`/api/admin/add/brand.php?q=${q}`);
    return await res.json();
};

input.addEventListener<'input'>('input', async () => {
    inputId.value = null;

    const value: string = input.value.trim();
    if (!value)
        return divData.innerHTML = null;

    const data: Array<IBrand> = await fetchBrand(value);

    divData.innerHTML = null;

    for (let i = 0; i < data.length; i++) {
        if (data[i].name.toLowerCase() == value.toLowerCase()) {
            inputId.value = String(data[i].id);
            break;
        }
    }

    data.forEach((brand: IBrand) => {
        const div: HTMLDivElement = document.createElement<'div'>('div');
        div.innerHTML = `${brand.id} ${brand.name}`;

        div.addEventListener('click', () => {
            input.value = brand.name;
            inputId.value = String(brand.id);
        });

        divData.appendChild<HTMLDivElement>(div);
    });
});
