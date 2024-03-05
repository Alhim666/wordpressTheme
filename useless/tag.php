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

        <div class="card-container">
            <?php
            global $wp_query;

            $save_wpq = $wp_query;

            // Отримати потрібний тег з URL
            $tag_slug = get_query_var('tag');

            // ваш запит і код виводу з пагінацією
            $wp_query = new WP_Query(array(
                'paged' => get_query_var('paged') ?: 1, // сторінка пагінації
                'tag' => $tag_slug, // потрібний тег
            ));

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
<?php get_footer(); ?>
