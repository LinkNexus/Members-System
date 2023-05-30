<div class="nav-bar">
    <a href="">TheBlog</a>
    <div>
        <?php if (isset($_SESSION['user_infos'])): ?>
            <span>
                <a href="Logout.php">Logout</a>
            </span>
        <?php else: ?>
            <span>
                <a href="Register.php">Sign Up</a>
            </span>
            <span>
                <a href="Login.php">Login</a>
            </span>
        <?php endif; ?>
    </div>
</div>