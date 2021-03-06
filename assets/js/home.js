(function () {
    "use strict";

    var randomProfielen = {
        url: base_url + "index.php/profile/lookup/random",
        element: '',
        init: function (element) {
            randomProfielen.element = $(element);
            $('#meer-random-profielen').click(randomProfielen.load);
        },
        load: function () {
            $.ajax({
                url: randomProfielen.url,
                type: 'GET',
                dataType: 'json'
            }).done(function (profielen) {
                if (profielen && profielen.length >= 1) {
                    randomProfielen.appendProfielen(profielen);
                }
            }).fail(function (xhr, status) {
                console.error(xhr);
                if (status === 404) {
                    randomProfielen.element.empty();
                    randomProfielen.display404();
                }
            });
        },
        display404: function () {
            $('<div class="message message-error">Er zijn nog geen profielen</div>').appendTo(randomProfielen.element);
        },
        appendProfielen: function (profielen) {
            randomProfielen.element.empty();
            $.each(profielen, function () {
                var profiel = new Profiel(this);
                var container = $('<div class="profiel clickable" data-pid="' + profiel.pid + '"></div>');
                var table = $('<table></table>');

                var profielFoto = new Foto(profiel.profiel_foto);
                $('<img class="profiel-foto-thumb" src="' + base_url + profielFoto.getThumbnail() + '" alt="' + profielFoto.titel + '" />')
                    .appendTo(container);
                $('<tr><td>Nickname</td><td>' + profiel.nickname + '</td></tr>').appendTo(table);
                $('<tr><td>Geslacht</td><td>' + profiel.geslacht + '</td></tr>').appendTo(table);
                $('<tr><td>Leeftijd</td><td>' + profiel.age() + '</td></tr>').appendTo(table);
                $('<tr><td>Beschrijving</td><td>' + profiel.beschrijving + '</td></tr>').appendTo(table);
                //$('<tr><td>Persoonlijkheid</td><td>' + profiel.persoonlijkheids_type + '</td></tr>').appendTo(table);
                $('<tr><td>Merken</td><td>' + profiel.merkenToString(5) + '</td></tr>').appendTo(table);
                table.appendTo(container);
                container.appendTo(randomProfielen.element);
            });
            randomProfielen.attachProfielClick();
        },
        attachProfielClick: function () {
            $('.profiel').click(function () {
                var pid = $(this).data('pid');
                window.location.href = base_url + 'index.php/profile/display/van/' + pid;
            });
        }
    };

    $(document).ready(function () {
        randomProfielen.init('#random-profielen');
        randomProfielen.load();
    });
})();
