<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Anime">
    <meta name="keywords" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PurpleSanity</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vollkorn:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <?php wp_head(); ?>

</head>

<body>

    <header>
        <nav class="navbar">
            <div class="logo">
                <a href="<?php bloginfo('url') ?>"><?php bloginfo('name') ?><span>.</span></a>
            </div>


            <ul class="nav-links">
                <?php wp_nav_menu(array(
                    'theme_location' => 'top_nav',
                    'container' => null,
                    'menu_class' => 'nav',
                    'menu_id' => 'nav',
                )) ?>
            </ul>
            <script>
                var wpLoggedIn = <?php echo is_user_logged_in() ? 'true' : 'false'; ?>;
                var wpCurrentUserName = "<?php echo is_user_logged_in() ? esc_js(wp_get_current_user()->user_login) : ''; ?>";
            </script>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    var currentWebsiteURL = window.location.href;
                    var thirdSlashIndex = currentWebsiteURL.indexOf('/', currentWebsiteURL.indexOf('/', currentWebsiteURL.indexOf('/') + 1) + 1);
                    if (thirdSlashIndex !== -1) {
                        currentWebsiteURL = currentWebsiteURL.substring(0, thirdSlashIndex + 1);
                    }

                    var accountLink = document.querySelector('a[href="' + currentWebsiteURL + 'account"]');

                    if (accountLink) {
                        var wpLoggedIn = <?php echo is_user_logged_in() ? 'true' : 'false'; ?>;
                        var wpCurrentUserName = "<?php echo is_user_logged_in() ? esc_js(wp_get_current_user()->user_login) : ''; ?>";

                        if (wpLoggedIn) {
                            var currentUserName = wpCurrentUserName;

                            if (currentUserName.length > 7) {
                                currentUserName = currentUserName.substring(0, 7);
                            }

                            accountLink.textContent = currentUserName;
                        }
                    }
                });
            </script>
        </nav>
    </header>