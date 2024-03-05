<?php get_header(); ?>

<div class="container-page">
    <div class="left-page">
        <div class="sidebar-content">
            <?php get_sidebar() ?>
            <hr class="hr-dashed-gradient dis">
        </div>
    </div>
    <div class="right-page">
        <div class="search">
            <h2 class="search-title">Search<span>:</span></h2>
            <div class="srch-div">
                <?php echo get_search_form(); ?>
            </div>
        </div>

        <?php
        global $wp_query;

        $save_wpq = $wp_query;

        // Отримайте термін пошуку користувача
        $search_term = get_search_query();

        // Перевірте, чи є термін пошуку і виведіть пости, якщо так
        if (!empty($search_term)) {
            $args = array(
                's' => $search_term,
                'paged' => get_query_var('paged') ?: 1 // сторінка пагінації
            );

            $wp_query = new WP_Query($args);
        }
        ?>

        <?php
        if ($wp_query->have_posts()) {
            echo '<p class="results-cont"><span class="results">' . $wp_query->found_posts . ' results found</span></p>';
        }
        ?>
        
        <div class="card-container">
            <?php
            if (!empty($search_term)) {
                while ($wp_query->have_posts()) {
                    $wp_query->the_post();

                    $tags = get_the_tags();

                    if ($tags) {
                        $tag_names = array();

                        foreach ($tags as $tag) {
                            $tag_names[] = '<span>' . $tag->name . '</span>';
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
                the_posts_pagination();
                wp_reset_postdata();
            } else {
                // Вивести повідомлення, якщо термін пошуку порожній
                echo '<p>No search results found.</p>';
            }
            ?>

        </div>
        <?php
        // вернем global $wp_query
        $wp_query = $save_wpq;
        ?>
    </div>
</div>
<?php get_footer(); ?>