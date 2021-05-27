const sameAddressCheckbox = document.querySelector<HTMLInputElement>("#same-address");
const deliveryAddressTable = document.querySelector("#delivery-address-table");

sameAddressCheckbox.addEventListener('input', () => {
    if (sameAddressCheckbox.checked) {
        deliveryAddressTable.classList.add("disabled");
        deliveryAddressTable.querySelectorAll<HTMLInputElement>('input')
            .forEach((e: HTMLInputElement) => e.disabled = true);
    } else {
        deliveryAddressTable.classList.remove("disabled");
        deliveryAddressTable.querySelectorAll<HTMLInputElement>('input')
            .forEach((e: HTMLInputElement) => e.disabled = false);
    }
});