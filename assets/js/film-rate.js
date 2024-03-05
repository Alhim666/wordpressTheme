// Замість 'YOUR_API_KEY' вставте ваш ключ API від TMDb
const apiKey = '';

// Виберіть потрібні елементи за допомогою jQuery
const title = $('#title'); // Select the h2 element by ID

if (title.text().trim() !== '') {
    const rate = $('#rate'); // Select the h2 element by ID
    const categoryLink = $('#cat a').text();
    let apiUrl = '';

    if (categoryLink === 'Series') {
        apiUrl = `https://api.themoviedb.org/3/search/tv?api_key=${apiKey}&query=${encodeURIComponent(title.text())}`;
    } else {
        apiUrl = `https://api.themoviedb.org/3/search/movie?api_key=${apiKey}&query=${encodeURIComponent(title.text())}`;
    }

    // Виконуємо запит за допомогою fetch
    fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
            // Отримуємо перший результат зі списку знайдених фільмів
            let firstResult = data.results[0];
            if(firstResult.vote_average == '0'){
                firstResult = data.results[1];
            }

            if (firstResult) {
                // Отримуємо оцінку фільму з отриманих даних
                const voteAverage = firstResult.vote_average;

                // Додаємо знайдену оцінку до тексту рейтингу
                rate.text(`Rate TMDB: ${voteAverage}`);
            } else {
                rate.text('Rate TMDB: Фільм не знайдено');
            }
        })
        .catch(error => {
            alert(`Сталася помилка під час запиту до API: ${error}`);
        });
}