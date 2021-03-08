const languageIds: string[] = ['D', 'F', 'E'];
const tbody: HTMLTableSectionElement = document.querySelector<HTMLTableSectionElement>("table#specifications > tbody");

let prevElements: HTMLInputElement[] = [];
let rowNumber: number = 0;

const changeEvent = () => {
    prevElements.forEach(input => input.removeEventListener('change', changeEvent));
    prevElements = [];

    const tr = tbody.insertRow();

    languageIds.forEach(l => {
        const input = document.createElement('input');
        input.type = 'text';
        input.name = `nameSpec${l}${rowNumber}`;
        input.maxLength = 31;

        input.addEventListener('change', changeEvent);
        prevElements.push(input);

        tr.insertCell().appendChild(input);
    });

    rowNumber++;
};

changeEvent();