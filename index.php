<?php get_header(); ?>

<div class="slider-container">
    <h1 class="slider-header">novelty<span>.</span></h1>
    <div class="slider">
        <?php
        $my_posts = get_posts(array(
            'numberposts' => 6,
            'category'    => 0,
            'suppress_filters' => true,
            'meta_key' => 'release_datetime',   // Додайте цей параметр для вказівки на кастомне поле
            'orderby' => 'meta_value',      // Сортування за значенням мета-поля
        ));

        global $post;

        foreach ($my_posts as $post) {
            setup_postdata($post);
        ?>
            <div class="slider-item">
                <a href="<?= the_permalink() ?>">
                    <img class="tilt-effect" src="<?php the_field('banner_img'); ?>" alt="">
                </a>
                <p class="slider-text"><span><?= the_title() ?></span> <?php do_action('custom_limited_short_desc'); ?></p>
            </div>
        <?php
        }

        wp_reset_postdata(); // сброс
        ?>
    </div>
</div>

<hr class="hr-dashed-gradient">

<div class="card-cont">
    <h2 class="slider-header">most rated<span>.</span></h2>

    <div class="card-container">
        <?php
        $my_posts = get_posts(array(
            'numberposts' => 12,
            'category'    => 0,
            'suppress_filters' => true,
            'meta_key' => 'rating_field',   // Додайте цей параметр для вказівки на кастомне поле
            'orderby' => 'meta_value',      // Сортування за значенням мета-поля
        ));

        global $post;

        foreach ($my_posts as $post) {
            setup_postdata($post);
        ?>
            <div class="card">
                <div class="card2">
                    <a href="<?php the_permalink() ?>">
                        <img src="<?php the_field('main_img'); ?>" alt="">
                    </a>
                    <div class="forcard-container">
                        <p class="card-text"><?= the_title() ?></p>
                    </div>
                    <p class="rate">Rate: <?php the_field('rating_field'); ?></p>
                </div>
            </div>
        <?php
        }

        wp_reset_postdata(); // сброс
        ?>
    </div>
    <img id="scrollToTopButton" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/svg/icons/backtotop.svg" alt="Scroll to Top">
</div>

<?php get_footer() ?>