const slider = document.querySelector('.slider');
if (slider != null) {
    const sliderItems = Array.from(slider.querySelectorAll('.slider-item'));
    const slideCount = sliderItems.length;
    let slideIndex = 0;
    let touchStartX = 0;
    let touchEndX = 0;
    let touchEndTimeout = null; // Змінна для таймера

    slider.addEventListener('touchstart', handleTouchStart);
    slider.addEventListener('touchmove', handleTouchMove);
    slider.addEventListener('touchend', handleTouchEnd);

    function handleTouchStart(event) {
        touchStartX = event.touches[0].clientX;
    }

    function handleTouchMove(event) {
        touchEndX = event.touches[0].clientX;
    }

    function handleTouchEnd() {
        const touchThreshold = 50;

        if (touchStartX - touchEndX > touchThreshold) {
            showNextSlide();
        } else if (touchEndX - touchStartX > touchThreshold) {
            showPreviousSlide();
        }
        
        clearTimeout(touchEndTimeout);
        touchEndTimeout = setTimeout(showNextSlide, 5000);
    }

    function showPreviousSlide() {
        slideIndex = (slideIndex - 1 + slideCount) % slideCount;
        updateSlider();
    }

    function showNextSlide() {
        slideIndex = (slideIndex + 1) % slideCount;
        updateSlider();
    }

    function updateSlider() {
        sliderItems.forEach((slide, index) => {
            let slideWidth = sliderItems[0].getBoundingClientRect().width;
            slide.style.transform = `translateX(${(-slideIndex * slideWidth)}px)`;
        });
    }

    updateSlider();

    const autoSlideInterval = setInterval(showNextSlide, 5000);

    slider.addEventListener('touchstart', () => {
        clearInterval(autoSlideInterval);
    });


    document.addEventListener('DOMContentLoaded', function () {
        const tiltItems = document.querySelectorAll('.tilt-effect');
        const maxTilt = 10; // Максимальний кут нахилу
        let prevAngleX = 0;
        let prevAngleY = 0;

        tiltItems.forEach(function (item) {
            item.addEventListener('mousemove', function (e) {
                const boundingRect = item.getBoundingClientRect();
                const centerX = boundingRect.left + boundingRect.width / 2;
                const centerY = boundingRect.top + boundingRect.height / 2;

                const angleX = (centerX - e.clientX) / 150; // Коефіцієнт нахилу по X
                const angleY = (centerY - e.clientY) / 100; // Коефіцієнт нахилу по Y

                // Обмежуємо та згладжуємо кути нахилу
                const clampedAngleX = Math.max(-maxTilt, Math.min(maxTilt, angleX));
                const clampedAngleY = Math.max(-maxTilt, Math.min(maxTilt, angleY));

                // Згладжуємо перехід між поточними та попередніми значеннями
                const smoothAngleX = prevAngleX * 0.8 + clampedAngleX * 0.2;
                const smoothAngleY = prevAngleY * 0.8 + clampedAngleY * 0.2;

                item.style.transform = `perspective(1000px) rotateX(${smoothAngleY}deg) rotateY(${-smoothAngleX}deg)`;

                prevAngleX = smoothAngleX;
                prevAngleY = smoothAngleY;
            });

            item.addEventListener('mouseleave', function () {
                item.style.transform = '';
                prevAngleX = 0;
                prevAngleY = 0;
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        const sliderTexts = document.querySelectorAll('.card-text');

        sliderTexts.forEach(function (sliderText) {
            const words = sliderText.textContent.split(' ');
            if (words.length > 6) {
                sliderText.classList.add('animate');
            }
        });
    });


}
