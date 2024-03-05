    <?php get_header(); ?>

    <div class="container">
        <div class="left">
            <div class="card-single">
                <div class="content">
                    <div class="back">
                        <div class="back-content">
                            <img src="<?php the_field('main_img'); ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="right">
            <h2 class="title-anime" id="title"><?= the_title() ?></h2>
            <p>Genres<span>:</span>
                <?php
                $tags = get_the_tags(); // Отримуємо масив тегів для поточного поста

                if ($tags) { // Перевіряємо, чи є теги
                    $tag_count = count($tags); // Кількість тегів

                    foreach ($tags as $index => $tag) {
                        echo '<a href="' . get_home_url() . '/archive?tag=' . $tag->name . '">' . $tag->name . '</a>';

                        // Додаємо роздільник, якщо не останній тег
                        if ($index < $tag_count - 1) {
                            echo ' | ';
                        }
                    }
                }
                ?>
            </p>
            <?php display_category_and_episodes_count(); ?>
            <p>Duration<span>:</span> <?php the_field('episode_duration'); ?> min</p>
            <p>Release date<span>:</span> <?php the_field('release_datetime'); ?> </p>
            <p>Studio<span>:</span>
                <a href="<?= get_home_url() . '/archive?studio=' . get_field('studio') ?>"> <?php the_field('studio'); ?> </a>
            </p>
            <p id="rate">Rate<span>:</span> <?php the_field('rating_field'); ?></p>
            <hr class="hr-dashed-gradient">
            <p class="desc-box"><?php the_field('description'); ?></p>
        </div>
    </div>

    <?php
    $post_id = get_the_ID();
    //show_access($post_id);
    $episodes_array = get_post_meta($post_id, 'add_episodes', true);
    $cat_name = get_the_category()[0]->cat_name;
    ?>

    <?php custom_episode_player($episodes_array, $cat_name); ?>

    <div class="comments-single">
        <?php comments_template($episodes_array, $cat_name); ?>
    </div>

    <?php get_footer() ?>