"use strict";

/**
 * Profiel model zoals in de database
 * @param profiel {object} json object met properties
 * @constructor
 */
var Profiel = function (profiel) {
    // set properties
    for (var property in profiel) {
        if (profiel.hasOwnProperty(property)) {
            this[property] = profiel[property];
        }
    }

    // methods
    this.age = function () {
        if (this.hasOwnProperty('geboorte_datum'))
            return util.calculateAge(this.geboorte_datum);
    };

    /**
     * Return eerste x merken als string
     * @param length
     */
    this.merkenToString = function (length) {
        var merkenString = '';
        var merken = this.merken.map(function (current) {
            return current.naam;
        });
        var i;
        for (i = 0; i < merken.length || i === length; i++) {
            merkenString = merkenString + merken[i] + ', ';
        }

        if (merkenString.charAt(merkenString.length - 2) === ',') {
            merkenString = merkenString.slice(0, -2);
        }
        if (merkenString === '') {
            return 'geen';
        }
        return merkenString;
    };

    // normaliseer attributen
    this.geslacht = profiel.geslacht.geslacht;

    if (!profiel.hasOwnProperty('merken')) {
        this.merken = [];
    }

    if (!profiel.hasOwnProperty('persoonlijkheids_type')) {
        this.persoonlijkheids_type = 'geen';
    } else {
        this.persoonlijkheids_type = profiel.persoonlijkheids_type.type;
    }

    if (!this.beschrijving || this.beschrijving === '') {
        this.beschrijving = 'Geen beschrijving';
    }

    if (!this.profiel_foto) {
        this.profiel_foto = {
            url: base_url + 'assets/img/profiel_fotos/placeholder' + this.geslacht === 'man' ? 'male' : 'female' + '.svg',
            titel: 'Placeholder foto'
        };
    }
};

var Merk = function (mid, naam) {
    this.naam = naam || null;
    this.mid = mid || null;
};

/**
 * @param foto {object} json object
 */
var Foto = function (foto) {
    this.fid = foto['fid'] || 0;
    this.profiel_id = foto['profiel_id'] || 0;
    this.url = foto['url'] || '';
    this.titel = foto['titel'] || '';
    this.beschrijving = foto['beschrijving'] || '';

    function isOverlay(url) {
        return url.indexOf('profile/fototool/overlay/') !== -1;
    }

    this.getThumbnail = function () {
        // clone URL
        var url = this.url.slice(0);
        var extension = url.split('.').pop();
        // overlay url is dynamisch
        if(isOverlay(url)) {
            return url + '?thumb=1';
        }
        if(extension === 'svg') {
            // svg is scalable dus er is geen thumbnail
            return url;
        }
        // replace extension met _small.extension
        return url.replace('.' + extension, '_small.' + extension);
    }
};
