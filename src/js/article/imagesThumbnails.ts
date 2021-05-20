const thumbnails: NodeListOf<HTMLLIElement> = document.querySelectorAll<HTMLLIElement>("body > main > div.product-top > div.images > div > ul > li");
const bigImage: HTMLDivElement = document.querySelector<HTMLDivElement>("#big-image");

thumbnails.forEach((li: HTMLLIElement): void => {
    li.addEventListener('click', (): void => {
        //Remove active thumbnail
        thumbnails.forEach((item: HTMLLIElement): void => item.classList.remove('active'));

        //add active to selected item
        li.classList.add('active');

        bigImage.style.backgroundImage = li.style.backgroundImage;
    });
});
