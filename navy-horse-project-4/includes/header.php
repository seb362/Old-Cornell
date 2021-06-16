<div class="header">
    <div id="logoandname">
        <div id="logopic">
            <img src="images/logo.jpg" alt="Lion Dance Logo" id="logo">
        </div>
        <div id="toptitle">
            <p id="topname">Cornell Lion Dance</p>
        </div>
    </div>

    <div id="navlinks">
        <nav>
            <ul class="navigation">
                <li class="notlist <?php if (isset($activehome)) {
                                        echo $activehome;
                                    } ?>"><a href="index.php" class="navlink">Home</a></li>
                <li class="notlist">||</li>
                <li class="notlist <?php if (isset($activeofficers)) {
                                        echo $activeofficers;
                                    } ?>"><a href="officers.php" class="navlink">Officers</a></li>
                <li class="notlist">||</li>
                <li class="notlist <?php if (isset($activegallery)) {
                                        echo $activegallery;
                                    } ?>"><a href="gallery.php" class="navlink">Gallery</a></li>
                <li class="notlist">||</li>
                <li class="notlist <?php if (isset($activeperformances)) {
                                        echo $activeperformances;
                                    } ?>"><a href="performances.php" class="navlink">Performances</a></li>
                <li class="notlist">||</li>
                <li class="notlist <?php if (isset($activecontact)) {
                                        echo $activecontact;
                                    } ?>"><a href="contact.php" class="navlink">Contact</a></li>
                <li class="notlist">||</li>
                <li class="notlist <?php if (isset($activelogin)) {
                                        echo $activelogin;
                                    } ?>">
                    <?php if (is_user_logged_in()) {
                        echo '<a href="logout.php" class="navlink">Logout</a></li>';
                    } else {
                        echo '<a href="login.php" class="navlink">Login</a></li>';
                    } ?>
            </ul>
        </nav>

    </div>

</div>
