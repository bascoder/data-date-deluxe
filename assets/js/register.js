(function () {
    "use strict";

    var polyfillDatePicker = {
        geboorteDatumRegex: '^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$',
        element: null,
        init: function (element) {
            polyfillDatePicker.element = $(element);
        },
        polyfill: function () {
            if (!polyfillDatePicker.isDateSupported()) {
                var element = polyfillDatePicker.element;
                $(element).attr('pattern', polyfillDatePicker.geboorteDatumRegex);
                var label = $('label[for=' + element[0].id + ']');
                label.html(label.html() + ' formaat: JJJJ-MM-DD');
            }
        },
        isDateSupported: function () {
            var testElement = document.createElement("input");
            testElement.setAttribute("type", "date");
            return !(testElement.type === "text");
        }
    };

    var postValidator = {
        form: null,
        mailExists: false,
        nicknameExists: false,
        init: function (selector) {
            postValidator.form = $(selector);

            $('#mail').change(postValidator.lookupEmail);
            $('#nickname').change(postValidator.lookupNickname);

            postValidator.form.submit(function (e) {
                var validStatus = postValidator.isValid();
                if (validStatus === true) {
                    return true;
                }
                $('#error').html(validStatus).show('fade');
                e.stopPropagation();
                e.preventDefault();
                return false;
            });
        },
        isValid: function () {
            var errors = '';

            var geboorteDatum = $('#geboorte-datum').val();
            var geboorteDatumDate = new Date(geboorteDatum);
            if (!postValidator.is18jaarEnOuder(geboorteDatumDate)) {
                errors += 'Je moet ouder zijn dan 18<br />';
            }

            if ($('#leeftijd_voorkeur_min').val() < 18) {
                errors += 'Je leeftijd voorkeur moet minstens 18 zijn<br />';
            }
            if ($('#leeftijd_voorkeur_max').val() > 99) {
                errors += 'Je leeftijd voorkeur moet kleiner zijn dan 100<br />';
            }
            if (postValidator.mailExists) {
                errors += 'Dit email adres is al in gebruik';
            }
            if (postValidator.nicknameExists) {
                errors += 'Deze nickname is al in gebruik';
            }

            if (errors != '') return errors;
            return true;
        },
        /**
         *
         * @param {Date} geboorteDatum date object
         * @returns {boolean}
         */
        is18jaarEnOuder: function (geboorteDatum) {
            var date = new Date(geboorteDatum.getFullYear() + 18, geboorteDatum.getMonth(), geboorteDatum.getDate());
            return date <= new Date();
        },
        lookupEmail: function () {
            $.getJSON(base_url + 'index.php/ajax/profielajax/email/' + encodeURIComponent($('#mail').val()), null, function (json) {
                postValidator.mailExists = json.exist;
                if (json.exist)
                    $('#error').html('Dit email adres is al in gebruik').show('fade');
            });
        },
        lookupNickname: function () {
            $.getJSON(base_url + 'index.php/ajax/profielajax/nickname/' + encodeURIComponent($('#nickname').val()), null, function (json) {
                postValidator.nicknameExists = json.exist;
                if (json.exist)
                    $('#error').html('Deze nickname is al in gebruik').show('fade');
            });
        }
    };

    $(document).ready(function () {
        polyfillDatePicker.init($('#geboorte-datum'));
        polyfillDatePicker.polyfill();

        postValidator.init('#register-form');
    });
})();
