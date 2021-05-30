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
            <?php if ($_SERVER['PHP_SELF'] != "/admin/login/index.php" && $_SERVER['PHP_SELF'] != "/admin/login/index.php/") { ?>
                <li id="login-logout">
                    <a class="btn-blue" href="/admin/logout">Logout</a>
                </li>
            <?php } ?>
            <li id="add">
                <a class="btn-blue" href="/admin/add">Add</a>
            </li>
            <li id="update">
                <a class="btn-blue" href="/admin/update">Update</a>
            </li>
        </ul>
    </nav>
</header>