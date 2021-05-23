<?php
    include_once __DIR__ . "/../includes/basicFunctions.inc.php";
?>
<footer>
    <div class="copy">&copy; 2020 - <?php echo date('Y') ?> | GigaCam</div>
    <div>
        <div>
            <img src="/images/Icon_shield.svg" alt="Shield Icon">
            <a href="#">Privacy</a>
            <a href="#">Cookies</a>
        </div>
        |
        <div class="languages">
            <?php if (language() != 'e'): ?>
                <a href="#" data-language-letter="e">English</a>
            <?php endif; ?>
            <?php if (language() != 'f'): ?>
                <a href="#" data-language-letter="f">Français</a>
            <?php endif; ?>
            <?php if (language() != 'd'): ?>
                <a href="#" data-language-letter="d">Nederlands</a>
            <?php endif; ?>
        </div>
        <img src="/images/Icon_world.svg" alt="World Icon Languages">
    </div>
    <div>Alle prijzen zijn incl. BTW</div>
</footer>