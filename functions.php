<?php

add_action('wp_enqueue_scripts', 'include_style');
function include_style()
{
    wp_enqueue_style('style', get_stylesheet_uri());
    wp_enqueue_style('main', get_template_directory_uri() . '/assets/css/main.css');
    wp_enqueue_style('layout', get_template_directory_uri() . '/assets/css/layout.css');
    wp_enqueue_style('single', get_template_directory_uri() . '/assets/css/single.css');
    wp_enqueue_style('sidebar', get_template_directory_uri() . '/assets/css/sidebar.css');
    wp_enqueue_style('page', get_template_directory_uri() . '/assets/css/page.css');
    wp_enqueue_style('player', get_template_directory_uri() . '/assets/css/player.css');
    wp_enqueue_style('comments', get_template_directory_uri() . '/assets/css/comments.css');
    wp_enqueue_style('forms-styles', get_template_directory_uri() . '/assets/css/forms-styles.css');
    wp_enqueue_style('account-styles', get_template_directory_uri() . '/assets/css/account-styles.css');
    wp_enqueue_style('404', get_template_directory_uri() . '/assets/css/404.css');
    wp_enqueue_style('footer', get_template_directory_uri() . '/assets/css/footer.css');
}

add_action('after_setup_theme', 'myMenu');
function myMenu()
{
    register_nav_menu('top_nav', 'top navigation bar');
}

add_action('wp_footer', 'include_script');
function include_script()
{
    wp_enqueue_script('main', get_template_directory_uri() . '/assets/js/main.js', null, null, false);
    wp_enqueue_script('player', get_template_directory_uri() . '/assets/js/player.js', null, null, false);
    //wp_enqueue_script('nav', get_template_directory_uri() . '/assets/js/nav.js', null, null, false);
    //wp_enqueue_script('film-rate', get_template_directory_uri() . '/assets/js/film-rate.js', null, null, false);
}

add_action('widgets_init', 'register_my_widgets');
function register_my_widgets()
{
    register_sidebar([
        'name' => 'Main Sidebar',
        'id' => 'main-sidebar',
        'description' => 'Main archive sidebar',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => ''
    ]);
}

function get_the_href($href, $text)
{
    echo '<a href="' . $href . '">' . $text . '</a>';
}


function add_episodes_shortcode($atts)
{
    $atts = shortcode_atts(
        array(
            'text' => 'url',
            'series' => '1',
        ),
        $atts,
        'add_episodes'
    );

    global $post;
    $post_id = get_the_ID();

    if ($post_id) {
        $formatted_text = do_shortcode($atts['text']);

        $existing_array = get_post_meta($post_id, 'add_episodes', true);

        if (!is_array($existing_array)) {
            $existing_array = array();
        }

        $episode_exists = false;
        foreach ($existing_array as $existing_episode) {
            if ($existing_episode['text'] === $formatted_text && $existing_episode['series'] === $atts['series']) {
                $episode_exists = true;
                break;
            }
        }

        if (!$episode_exists) {
            $existing_array[] = array(
                'text' => $formatted_text,
                'series' => $atts['series'],
            );

            update_post_meta($post_id, 'add_episodes', $existing_array);
        }
    }

    return '';
}
add_shortcode('add_episodes', 'add_episodes_shortcode');


function display_episodes_shortcode($atts)
{
    $post_id = get_the_ID();

    $episodes_array = get_post_meta($post_id, 'add_episodes', true);

    if (is_array($episodes_array) && !empty($episodes_array)) {
        $output = '<ul>';

        foreach ($episodes_array as $episode) {
            $output .= '<li>';
            $output .= '<strong>Series ' . esc_html($episode['series']) . '</strong>: ' . do_shortcode($episode['text']);
            $output .= '</li>';
        }

        $output .= '</ul>';

        return $output;
    }

    return '';
}
add_shortcode('display_episodes', 'display_episodes_shortcode');

function clear_episodes_shortcode()
{
    global $post;
    $post_id = get_the_ID();

    if ($post_id) {
        delete_post_meta($post_id, 'add_episodes');
    }

    return 'Масив епізодів очищено';
}
add_shortcode('clear_episodes', 'clear_episodes_shortcode');


function get_film($post_id)
{
    $apiKey = '---------------------------------------------';
    $movieTitle = get_the_title($post_id);

    $categories = get_the_category($post_id);
    $categoryLink = '';
    foreach ($categories as $category) {
        $categoryLink .= $category->name . ' ';
    }

    $apiUrl = "https://api.themoviedb.org/3/search/movie?api_key=$apiKey&query=" . urlencode($movieTitle);
    var_dump($apiUrl);
    if (strpos($categoryLink, 'Series') !== false) {
        $apiUrl = "https://api.themoviedb.org/3/search/tv?api_key=$apiKey&query=" . urlencode($movieTitle);
    }

    $response = file_get_contents($apiUrl);

    // if ($response === false) {
    //     $error = error_get_last();
    //     if ($error) {
    //         // Виводимо деталі помилки
    //         var_dump($error);
    //     }
    //     return false;
    // }

    $data = json_decode($response, true);
    if (!empty($data['results'][0])) {
        $film = $data['results'][0];
        if ($film['vote_average'] == 0) {
            $film = $data['results'][1];
        }
    }
    return $film;
}

// add_action('save_post', 'update_rating_field_on_new_post');
// function update_rating_field_on_new_post($post_id)
// {
//     $categories = get_the_category($post_id);
//     $categoryLink = '';
//     foreach ($categories as $category) {
//         $categoryLink .= $category->name . ' ';
//     }

//     $film = get_film($post_id);

//     $voteAverage = $film['vote_average'];
//     update_field('rating_field', $voteAverage, $post_id);
//     $description = $film['overview'];
//     update_field('description', $description, $post_id);

//     if (strpos($categoryLink, 'Series') !== false) {
//     } else {
//         $releaseDate = $film['release_date'];
//         update_field('release_datetime', $releaseDate, $post_id);
//     }
// }

add_action('custom_limited_short_desc', 'custom_limited_short_desc');
function custom_limited_short_desc()
{
    $short_desc = get_field('description');

    $words = explode(' ', $short_desc);

    $limited_words = array_slice($words, 0, 25);

    $limited_short_desc = implode(' ', $limited_words);

    echo $limited_short_desc;

    if (count($words) > 37) {
        echo '...'; 
    }
}

add_action('custom_limited_title', 'custom_limited_title');
function custom_limited_title()
{
    $title = get_the_title();

    $words = explode(' ', $title);

    $limited_words = array_slice($words, 0, 4);

    $limited_title = implode(' ', $limited_words);

    echo $limited_title;

    if (count($words) > 4) {
        echo '...'; 
    }
}


add_action('show_access', 'show_access');
function show_access($post_id)
{
    $categories = get_the_category($post_id);
    $categoryLink = '';
    foreach ($categories as $category) {
        $categoryLink .= $category->name . ' ';
    }

    $film = get_film($post_id);
    var_dump($film);
    $description = $film['overview'];


    if (strpos($categoryLink, 'Series') !== false) {
    } else {
        $releaseDate = $film['release_date'];
        var_dump($releaseDate);
    }
}

function custom_comment_form_defaults_edit($defaults)
{
    $current_user = wp_get_current_user();
    $name = $current_user->user_login;

    $user_profile_url = home_url("/user/$name");
    $edit_profile_url = add_query_arg('um_action', 'edit', $user_profile_url);

    $defaults['logged_in_as'] = sprintf(
        __('You are signed in as <a href="%1$s">%2$s</a>. <a href="%3$s">Edit your account</a>. %4$s.'),
        $user_profile_url,
        $name,
        $edit_profile_url,
        __('Mandatory fields are marked with *')
    );

    return $defaults;
}
add_filter('comment_form_defaults', 'custom_comment_form_defaults_edit');


function custom_comment_form_defaults($defaults)
{
    $login = home_url("/login"); 

    $defaults['must_log_in'] = '<p class="must-log-in">' .
        sprintf(
            __('In order to leave a comment, you need to <a href="%1$s">log in</a>.'),
            $login
        ) . '</p>';

    return $defaults;
}
add_filter('comment_form_defaults', 'custom_comment_form_defaults');

function exclude_pages_from_search_results($query)
{
    if (!is_admin() && $query->is_search) {
        $query->set('post_type', 'post');
    }
    return $query;
}
add_filter('pre_get_posts', 'exclude_pages_from_search_results');

function custom_episode_player($episodes_array, $cat_name)
{
    if (!empty($episodes_array)) {
        if ($cat_name == "Series") { ?>
            <div class="player-container-main">
                <div class="player-container">
                    <div class="player-left">
                        <div class="custom-nav">
                            <input id="custom-toggle" type="checkbox" checked>
                            <h2 id="episode-num">Drop Down Menu</h2>
                            <ul id="chapter-list">
                                <?php foreach ($episodes_array as $episode) { ?>
                                    <li data-target="<?= $episode['series'] ?>" data-src="<?= $episode['text'] ?>">Chapter <?= $episode['series'] ?></li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                    <div class="player-right">
                        <?php echo do_shortcode('[iframe src="' . $episode['text'] . '" width="100%" id="content-iframe" allowfullscreen] '); ?>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <div class="movie">
                <?= do_shortcode('[iframe src="' . $episodes_array[0]['text'] . '" width="100%" allowfullscreen] ') ?>
            </div>
        <?php }
    } else { ?>
        <div class="nf">
            <h2 class="nf-box">It seems that there are not enough episodes yet</h2>
            <img class="nf-img" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/episodesnf.png" alt="nf">
        </div>
<?php }
}

function display_category_and_episodes_count()
{
    $category = get_the_category()[0];
    $category_link = get_category_link($category->cat_ID);
    $category_name = $category->cat_name;

    echo '<p id="cat">Category<span>: </span>';
    echo '<a href="' . get_home_url() . '/archive?category=' . urlencode($category_name) . '">' . $category_name . '</a>';
    echo '</p>';

    if ($category_name == "Series") {
        $episodes_count = get_field('episodes_count');
        echo '<p>Episodes Count<span>: </span>' . esc_html($episodes_count) . '</p>';
    }
}

function get_unique_studio_values()
{
    $args = array(
        'post_type' => 'post', 
        'posts_per_page' => -1, 
        'meta_query' => array(
            array(
                'key' => 'studio', 
                'compare' => 'EXISTS', 
            ),
            array(
                'key' => 'studio',
                'value' => '',
                'compare' => '!='
            ),
        ),
    );

    $custom_field_values = array();

    // Виконуємо запит
    $query = new WP_Query($args);

    // Перебираємо результати запиту
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            $studio_value = get_post_meta(get_the_ID(), 'studio', true);

            if (!in_array($studio_value, $custom_field_values)) {
                $custom_field_values[] = $studio_value;
            }
        }
        wp_reset_postdata();
    }

    echo '<h4>Studio:</h4>';
    echo '<p class="wp-block-tag-cloud">';
    sort($custom_field_values);

    foreach ($custom_field_values as $value) {
        $current_url = esc_url($_SERVER['REQUEST_URI']);
        $url_parts = parse_url($current_url);

        if (isset($url_parts['path'])) {
            $path = $url_parts['path'];

            if (strpos($path, 'archive') === false) {
                $path = get_home_url() . '/archive';
                echo '<a data-studio="' . $value . '" href="' . $path . '?studio=' . $value . '">' . $value . '</a>';
            } else {
                if (strpos($path, 'page') === false) {
                    echo '<a data-studio="' . $value . '" href="' . esc_url($path) . '?studio=' . $value . '">' . $value . '</a>';
                } else {
                    $path = get_home_url() . '/archive';
                    echo '<a data-studio="' . $value . '" href="' . $path . '?studio=' . $value . '">' . $value . '</a>';
                }
            }
        }
    }
    echo '</p>';
}
function get_unique_studio_values_shortcode()
{
    ob_start();
    get_unique_studio_values();
    return ob_get_clean();
}
add_shortcode('unique_studio_values', 'get_unique_studio_values_shortcode');

function get_unique_categories()
{
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => -1,
    );

    $custom_categories = array();

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            $post_categories = get_the_category();

            if (!empty($post_categories)) {
                foreach ($post_categories as $category) {
                    if (!in_array($category->name, $custom_categories)) {
                        $custom_categories[] = $category->name;
                    }
                }
            }
        }
        wp_reset_postdata();
    }

    echo '<h4>Category:</h4>';
    echo '<ul class="wp-block-categories-list wp-block-categories">';
    sort($custom_categories);
    foreach ($custom_categories as $category) {
        $current_url = esc_url($_SERVER['REQUEST_URI']);
        $url_parts = parse_url($current_url);


        if (isset($url_parts['path'])) {
            $path = $url_parts['path'];

            if (strpos($path, 'archive') === false) {
                $path = get_home_url() . '/archive';
                echo '<li class="cat-item"><a href="' . $path . '?category=' . urlencode($category) . '">' . $category . '</a></li>';
            } else {
                if (strpos($path, 'page') === false) {
                    echo '<li class="cat-item"><a href="' . esc_url($path) . '?category=' . urlencode($category) . '">' . $category . '</a></li>';
                } else {
                    $path = get_home_url() . '/archive';
                    echo '<li class="cat-item"><a href="' . $path . '?category=' . urlencode($category) . '">' . $category . '</a></li>';
                }
            }
        }
    }
    echo '</ul>';
}

function get_unique_categories_shortcode()
{
    ob_start();
    get_unique_categories();
    return ob_get_clean();
}
add_shortcode('unique_categories', 'get_unique_categories_shortcode');

function get_unique_tags()
{
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => -1,
    );

    $custom_tags = array();

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            $post_tags = get_the_tags();

            if (!empty($post_tags)) {
                foreach ($post_tags as $tag) {
                    if (!in_array($tag->name, $custom_tags)) {
                        $custom_tags[] = $tag->name;
                    }
                }
            }
        }
        wp_reset_postdata();
    }

    echo '<h4>Genres:</h4>';
    echo '<p class="wp-block-tag-cloud">';
    sort($custom_tags);
    foreach ($custom_tags as $tag) {
        $current_url = esc_url($_SERVER['REQUEST_URI']);
        $url_parts = parse_url($current_url);

        if (isset($url_parts['path'])) {
            $path = $url_parts['path'];

            if (strpos($path, 'archive') === false) {
                $path = get_home_url() . '/archive';
                echo '<a href="' . $path . '?tag=' . urlencode($tag) . '">' . $tag . '</a>';
            } else {
                if (strpos($path, 'page') === false) {
                    echo '<a href="' . $path . '?tag=' . urlencode($tag) . '">' . $tag . '</a>';
                } else {
                    $path = get_home_url() . '/archive';
                    echo '<a href="' . esc_url($path) . '?tag=' . urlencode($tag) . '">' . $tag . '</a>';
                }
            }
        }
    }
    echo '</p>';
}
function get_unique_tags_shortcode()
{
    ob_start();
    get_unique_tags();
    return ob_get_clean();
}
add_shortcode('unique_tags', 'get_unique_tags_shortcode');


function get_movies(){
    global $wp_query;

    $save_wpq = $wp_query;

    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'release-date-desc';

    $args = array(
        'paged' => get_query_var('paged') ?: 1,
        'meta_key' => 'release_datetime',
        'orderby' => 'meta_value',
        'order' => ($sort === 'release-date-asc' || $sort === 'rating-asc') ? 'ASC' : 'DESC',
    );

    if ($sort === 'rating-desc' || $sort === 'rating-asc') {
        $args['meta_key'] = 'rating_field';
        $args['orderby'] = 'meta_value_num';
    }

    $studio = isset($_GET['studio']) ? $_GET['studio'] : 'all';
    if ($studio !== 'all') {
        $args['meta_query'] = array(
            array(
                'key' => 'studio',
                'value' => $studio,
            ),
        );
    }

    $category = isset($_GET['category']) ? $_GET['category'] : 'all';
    if ($category !== 'all') {
        $args['category_name'] = $category;
    }

    $tag = isset($_GET['tag']) ? $_GET['tag'] : 'all';
    if ($tag !== 'all') {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'post_tag',
                'field' => 'slug',
                'terms' => $tag,
            ),
        );
    }


    return new WP_Query($args);
}
