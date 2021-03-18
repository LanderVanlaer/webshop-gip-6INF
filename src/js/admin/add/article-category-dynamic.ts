interface ISpecification {
    id: number,
    nameD: string,
    nameF: string,
    nameE: string,
}

interface ICategory {
    id: number,
    nameD: string,
    nameF: string,
    nameE: string,
    specs: Array<ISpecification>,
}

const categorySearch: HTMLInputElement = document.querySelector<HTMLInputElement>("#categorySearch");
const categoryBodySearch: HTMLTableSectionElement = document.querySelector<HTMLTableSectionElement>("#categoryBodySearch");
const categoryBodyAdded: HTMLTableSectionElement = document.querySelector<HTMLTableSectionElement>("#categoryBody");
const specificationBody: HTMLTableSectionElement = document.querySelector<HTMLTableSectionElement>("#specificationBody");

const SPEC_INPUT_NAME = 'specification_';
let selectedCategories: Array<ICategory> = [];

const toggleCategory: (category: ICategory) => boolean = (category: ICategory) => {
    const selected: boolean = selectedCategories.some((v: ICategory) => v.id === category.id);

    if (selected) {
        if (!confirm(`Wilt u de categorie{${category.id},${category.nameD},${category.nameF},${category.nameE}} verwijderen van het artikel?`))
            return true;
        else {
            selectedCategories = selectedCategories.filter(({id}: ICategory) => id !== category.id);
        }
    } else
        selectedCategories.push(category);

    updateDOM();
    return !selected;
};

const updateDOM: () => void = () => {
    updateAddedCategoriesDOM();
    updateSpecificationsDOM();
};

const updateAddedCategoriesDOM: () => void = () => {
    categoryBodyAdded.innerHTML = null;

    selectedCategories.forEach((c: ICategory) => {
        const tr: HTMLTableRowElement = generateTabloRowCategories(generateTableRowTableDataCategories(c.id, c.nameD, c.nameF, c.nameE));

        tr.addEventListener('click', () => {
            if (!toggleCategory(c)) {
                categorySearch.value = null;
                //Trigger input event
                categorySearch.dispatchEvent(new Event('input'));
            }
        });

        categoryBodyAdded.appendChild(tr);
    });
};

const updateSpecificationsDOM: () => void = () => {
    const allSpecs: Array<ISpecification> = getAllSpecifications();

    allSpecs.forEach((s: ISpecification) => {
        const inputElement: HTMLInputElement = specificationBody.querySelector<HTMLInputElement>(`input[name="${SPEC_INPUT_NAME}${s.id}"]`);
        if (!inputElement) {
            const tr: HTMLTableRowElement = document.createElement<"tr">("tr");
            tr.innerHTML = `<td>${s.nameD}</td><td>${s.nameF}</td><td>${s.nameE}</td><td><input type="text" name="${SPEC_INPUT_NAME}${s.id}"></td>`;
            specificationBody.appendChild(tr);
        }
    });

    const specificationInputs: Array<HTMLInputElement> = [...specificationBody.querySelectorAll<HTMLInputElement>('input')];
    specificationInputs.forEach((i: HTMLInputElement) => {
        const ID: number = Number(i.name.substring(SPEC_INPUT_NAME.length));

        if (!allSpecs.some(({id}: ISpecification) => id === ID)) {
            i.parentElement.parentElement.remove();
        }
    });
};

const getAllSpecifications: () => Array<ISpecification> = () => {
    return selectedCategories.reduce((a: Array<ISpecification>, c: ICategory) => [...a, ...c.specs], []);
};

const generateTableRowTableDataCategories: (id: number, nameD: string, nameF: string, nameE: string) => string =
    (id, nameD, nameF, nameE) => `<td>${nameD}</td><td>${nameF}</td><td>${nameE}</td><td>${id}</td>`;

const generateTabloRowCategories: (tds) => HTMLTableRowElement = (tds) => {
    const tr: HTMLTableRowElement = document.createElement<"tr">("tr");
    tr.innerHTML = tds;
    return tr;
};

categorySearch.addEventListener('input', async () => {
    const value = categorySearch.value.trim();
    if (!value)
        return categoryBodySearch.innerHTML = null;

    try {
        const res: Response = await fetch(`/api/admin/add/category.php?q=${value}`);
        const data: Array<ICategory> = await res.json();

        categoryBodySearch.innerHTML = null;
        data.forEach((c: ICategory) => {
                const regex: RegExp = new RegExp(`(.*)(${value})(.*)`, 'gi');
                const bold: (s: string) => string = (s: string) => s.replaceAll(regex, "$1<b>$2</b>$3");

                const tr: HTMLTableRowElement = generateTabloRowCategories(generateTableRowTableDataCategories(c.id, bold(c.nameD), bold(c.nameF), bold(c.nameE)));

                if (selectedCategories.some((v: ICategory) => v.id === c.id))
                    tr.classList.add('selected');

                tr.addEventListener('click', () => {
                    if (toggleCategory(c))
                        tr.classList.add('selected');
                    else
                        tr.classList.remove('selected');
                });

                categoryBodySearch.appendChild(tr);
            }
        );
    } catch (e) {
        console.warn(e);
    }
});