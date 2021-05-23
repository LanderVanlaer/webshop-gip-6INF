const languageLinks: NodeListOf<HTMLAnchorElement> = document.querySelectorAll<HTMLAnchorElement>("body footer div.languages > a");
languageLinks.forEach((link: HTMLAnchorElement): void =>
    link.addEventListener('click', (e) => {
            e.preventDefault();
            document.cookie = `lang=${link.dataset.languageLetter}; expires=${Date.now() + 1000 * 60 * 60 * 24 * 7}; path=/`;
            location.reload();
        }
    )
);
