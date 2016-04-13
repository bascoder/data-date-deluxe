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
        var merken = this.merken;
        var i;
        for (i = 0; i < merken.length || i === length; i++) {
            merkenString = merkenString + merken[i] + ',';
        }
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

};
