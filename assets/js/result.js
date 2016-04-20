$(document).ready(function () {
    $('.profiel').click(function () {
        var pid = $(this).data('pid');
        window.location.href = base_url + 'index.php/profile/display/van/' + pid;
    });
});
