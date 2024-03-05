<?php get_header(); ?>

<div class="container-page">
    <div class="left-page">
        <div class="sidebar-content">
            <div class="sort-form">
                <form action="" method="get" id="archive-sort-form">
                    <label for="sort-by" class="sort-title">Sort:</label>
                    <select name="sort" id="sort-by">
                        <option value="release-date-desc" <?php echo ($_GET['sort'] === 'release-date-desc') ? 'selected' : ''; ?>>
                            <p class="sort-p">Release Date (Descending)</p>
                        </option>
                        <option value="release-date-asc" <?php echo ($_GET['sort'] === 'release-date-asc') ? 'selected' : ''; ?>>Release Date (Ascending)</option>
                        <option value="rating-desc" <?php echo ($_GET['sort'] === 'rating-desc') ? 'selected' : ''; ?>>Rating (Descending)</option>
                        <option value="rating-asc" <?php echo ($_GET['sort'] === 'rating-asc') ? 'selected' : ''; ?>>Rating (Ascending)</option>
                    </select>
                    <input type="submit" class="sort" value="Go">
                </form>
            </div>

            <?php get_sidebar() ?>
            <hr class="hr-dashed-gradient dis">
        </div>
    </div>

    <?php
    $wp_query = get_movies();
    ?>

    <div class="right-page">
        <div class="search">
            <h2 class="search-title">Search<span>:</span></h2>
            <div class="srch-div">
                <?php echo get_search_form(); ?>
            </div>
        </div>

        <?php
        if ($wp_query->have_posts()) {
            echo '<p class="results-cont"><span class="results">' . $wp_query->found_posts . ' results found</span></p>';
        }
        ?>

        <div class="card-container">
            <?php

            while ($wp_query->have_posts()) {
                $wp_query->the_post();

                $tags = get_the_tags(); // Отримати список тегів

                if ($tags) {
                    $tag_names = array(); // Масив для зберігання імен тегів

                    foreach ($tags as $tag) {
                        $tag_names[] = '<span>' . $tag->name . '</span>'; // Додати ім'я тегу до масиву
                    }
                }
            ?>
                <a class="card-link" href="<?php the_permalink() ?>">
                    <div class="card-page1">
                        <div class="card-page">
                            <div class="img-container">
                                <img class="img" src="<?php the_field('main_img'); ?>" alt="<?= the_title() ?>">
                            </div>
                            <p class="title-card"><?= do_action('custom_limited_title'); ?></p>
                            <span class="date">Release: <?php the_field('release_datetime'); ?></span>
                            <p class="info"><?php do_action('custom_limited_short_desc'); ?></p>
                            <div class="tags">
                                <?php
                                if ($tag_names) {
                                    echo implode(' | ', $tag_names);
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </a>
            <?php
            }
            ?>

        </div>
        <?php
        the_posts_pagination();

        // вернем global $wp_query
        wp_reset_postdata();
        $wp_query = $save_wpq;

        ?>
    </div>
</div>
<script>
    var currentURL = window.location.href;

    // Функція для підсвічування активних посилань
    function highlightLinks() {
        var categoryLinks = document.querySelectorAll('.wp-block-categories a');
        var tagLinks = document.querySelectorAll('.wp-block-tag-cloud a');

        categoryLinks.forEach(function(link) {
            if (currentURL.includes(link.getAttribute('href'))) {
                link.classList.add('current-cat');
            }
        });

        tagLinks.forEach(function(link) {
            if (currentURL.includes(link.getAttribute('href'))) {
                link.classList.add('current-tag');
            }
        });

        // Підсвічуємо студії
        var studioLinks = document.querySelectorAll('.wp-block-tag-cloud a[data-studio]');

        studioLinks.forEach(function(link) {
            if (currentURL.includes(link.getAttribute('href')) || currentURL.includes(encodeURI(link.getAttribute('data-studio')))) {
                link.classList.add('current-tag');
            }
        });
    }

    // Викликаємо функцію для підсвічування посилань при завантаженні сторінки
    window.onload = highlightLinks;

    document.addEventListener("DOMContentLoaded", function() {
        const form = document.getElementById("archive-sort-form");
        const select = document.getElementById("sort-by");

        form.addEventListener("submit", function(e) {
            e.preventDefault();

            const selectedValue = select.value;

            // Отримати поточну URL-адресу сторінки архіву
            const currentURL = window.location.href;

            // Определить базовий URL (без параметрів)
            const baseURL = currentURL.split("?")[0];

            // Створити об'єкт параметрів URL
            const params = new URLSearchParams(window.location.search);

            // Встановити параметр сортування на вибране значення
            params.set("sort", selectedValue);

            // Перенаправити користувача на новий URL з параметрами сортування
            window.location.href = `${baseURL}?${params.toString()}`;
        });
    });
</script>
<?php get_footer(); ?>