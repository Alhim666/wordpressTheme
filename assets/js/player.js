jQuery(document).ready(function ($) {
    const episodeNumber = $('#episode-num');
    const videoIframe = $('#content-iframe');
    let currentIframe;

    // Get the selected episode from Local Storage or use default
    const selectedEpisode = localStorage.getItem("selectedEpisode") || 1;

    episodeNumber.text('Episode ' + selectedEpisode);
    videoIframe.attr('src', $('#chapter-list li[data-target="' + selectedEpisode + '"]').attr('data-src'));
    currentIframe = videoIframe;

    $('#chapter-list li').click(function () {
        const targetId = $(this).attr('data-target');
        const src = $(this).attr('data-src');

        if (currentIframe) {
            currentIframe.attr('src', 'about:blank');
        }

        episodeNumber.text('Episode ' + targetId);

        videoIframe.attr('src', src);
        currentIframe = videoIframe;

        localStorage.setItem("selectedEpisode", targetId); // Store selected episode in Local Storage
    });
});
