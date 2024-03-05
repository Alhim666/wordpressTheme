<footer>
    <div class="footer">
        <hr class="hr-dashed-gradient">
        <div class="row">
            <a target="_blank" href="https://t.me/Alhim616"><i class="fa fa-telegram"></i></a>
            <a target="_blank" href="https://twitter.com/Alhim616"><i class="fa fa-twitter"></i></a>
        </div>

        <div class="row">
            Made by Dmytro Yanushevych
        </div>
    </div>
</footer>

<img id="scrollToTopButton" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/svg/icons/backtotop.svg" alt="Scroll to Top">

<script>
    // Отримуємо кнопку та встановлюємо обробник кліку
    const scrollToTopButton = document.getElementById("scrollToTopButton");
    scrollToTopButton.addEventListener("click", scrollToTop);

    // Показуємо кнопку при прокрутці до певної відстані
    window.addEventListener("scroll", toggleScrollToTopButton);

    function toggleScrollToTopButton() {
        if (window.pageYOffset > 200) { // Змініть цей поріг на ваш вибір
            scrollToTopButton.style.display = "block";
        } else {
            scrollToTopButton.style.display = "none";
        }
    }

    function scrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: "smooth" // Плавна анімація прокрутки
        });
    }

    jQuery(document).ready(function($) {
        // Function to set iframe height
        function setIframeHeight() {
            // Find all iframes on the page
            var iframes = $('iframe');

            // Loop through each iframe and set its height in 16:9 proportion
            iframes.each(function() {
                var width = $(this).width();
                var height = (width / 16) * 9;
                this.style.height = height + 'px';
            });
        }

        // Initial call to set iframe height
        setIframeHeight();

        // Update iframe height on window resize
        $(window).on('resize', function() {
            setIframeHeight();
        });
    });
</script>

<?php wp_footer(); ?>