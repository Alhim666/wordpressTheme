<?php get_header(); ?>

<div class="container-page">
    <div class="left-page">
        <?php get_sidebar(); ?>
        <hr class="hr-dashed-gradient dis">
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
            $current_category = get_queried_object(); // Отримати поточну категорію

            if ($current_category) {
                $category_name = $current_category->name;
                $category_id = $current_category->term_id;

                global $wp_query;

                $save_wpq = $wp_query;

                // ваш запит і код виведення з пагінацією
                $wp_query = new WP_Query(array(
                    'paged' => get_query_var('paged') ?: 1, // Сторінка пагінації
                    'cat' => $category_id // Вказуємо ID категорії
                ));

                while ($wp_query->have_posts()) {
                    $wp_query->the_post();

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

                // Пагінація
                the_posts_pagination();

                // Повертаємо глобальний $wp_query
                wp_reset_postdata();
                $wp_query = $save_wpq;
            }
            ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>