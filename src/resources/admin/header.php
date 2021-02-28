<header>
    <nav>
        <ul>
            <li id="logo">
                <a href="/">
                    <img src="/images/logo_basic.svg" alt="logo">
                </a>
            </li>
            <li id="home">
                <a class="btn-blue" href="/admin">Admin</a>
            </li>
            <?php if ($_SERVER['REQUEST_URI'] != "/admin/login.php") { ?>
                <li id="login-logout">
                    <a class="btn-blue" href="/admin/logout.php">Logout</a>
                </li>
            <?php } ?>
        </ul>
    </nav>
</header>