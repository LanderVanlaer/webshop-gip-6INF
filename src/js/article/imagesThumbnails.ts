const thumbnails: NodeListOf<HTMLLIElement> = document.querySelectorAll<HTMLLIElement>("body > main > div.product-top > div.images > div > ul > li");
const images: NodeListOf<HTMLImageElement> = document.querySelectorAll("body > main > div.product-top > div.images > ul > li > img");

thumbnails.forEach((li: HTMLLIElement): void => {
    li.addEventListener('click', (): void => {
        //Remove active thumbnail
        thumbnails.forEach((item: HTMLLIElement): void => item.classList.remove('active'));

        //add active to selected item
        li.classList.add('active');

        //Remove active img
        images.forEach((img: HTMLImageElement): void => img.classList.remove('active'));

        //Changing image
        for (let i = 0; i < images.length; i++) {
            const img: HTMLImageElement = images[i];

            if (img.src === li.querySelector<HTMLImageElement>('img').src) {
                img.classList.add('active');
                break;
            }
        }
    });
});
