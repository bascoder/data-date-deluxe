(function () {
    "use strict";

    var merken = {
        fieldSeparater: '--',
        init: function () {
            $('#btn-add-merk').click(merken.onAdd);
        },
        load: function () {
            $.getJSON(base_url + 'index.php/ajax/merkAjax/all', null, function (json) {
                var values = json.map(function (current) {
                    return current.naam + merken.fieldSeparater + current.mid;
                });
                merken.populateAutoComplete(values);
            });
        },
        onAdd: function () {
            var merk_voorkeur_input = $('#merk-voorkeur');
            var merk = merk_voorkeur_input.val();
            if (!merk || merk === '') {
                merken.onError();
            } else {
                merken.addMerkVoorkeur(merk);
                merk_voorkeur_input.val('');
            }
        },
        onError: function () {
            window.alert('Voer een merk in');
        },
        addMerkVoorkeur: function (merk) {
            $('<br /><input type="text" readonly disabled class="listed-merk" value="' + merk + '" />')
                .appendTo($('#merk-voorkeuren'));
        },
        populateAutoComplete: function (values) {
            $('#merk-voorkeur').autocomplete({
                source: values
            });
        }
    };

    var persoonlijkheid = {
        validValues: [],
        load: function () {
            $.getJSON(base_url + 'index.php/ajax/persoonlijkheidAjax/all', null, function (json) {
                var values = json.map(function (self) {
                    return self.name;
                });
                persoonlijkheid.validValues = values;
                $('#persoonlijkheids-voorkeur').autocomplete({
                    source: values
                });
            });
        }
    };

    var form = {
        element: null,
        init: function (element) {
            form.element = $(element);
            form.element.submit(form.maySubmit);
        },
        isFormValid: function () {
            return $(form.element)[0].checkValidity();
        },
        maakMerkenObject: function () {
            var listed_merken = $('.listed-merk');
            if (listed_merken.size() > 0) {
                var merkenArr = [];
                listed_merken.each(function () {

                    var fields = $(this).val().split(merken.fieldSeparater);
                    var merk = $(this).val();
                    if (fields.length === 2) {
                        merk = new Merk(fields[1], fields[0]);
                    }
                    merkenArr.push(merk);
                });
                $('#merken').val(JSON.stringify(merkenArr));
                return true;
            }

            window.alert('Voeg ten minste 1 merk voorkeur toe');
            return false;
        },
        heeftPersoonlijkheid: function () {
            var selected = $('#persoonlijkheids-voorkeur').val();
            var isValid = persoonlijkheid.validValues.indexOf(selected) !== -1;
            if(!isValid) {
                window.alert('Selecteer een persoonlijkheids voorkeur uit de lijst');
            }

            return isValid;
        },
        maySubmit: function () {
            return form.isFormValid() && form.maakMerkenObject() && form.heeftPersoonlijkheid();
        }
    };

    $(document).ready(function () {
        merken.init();
        merken.load();
        persoonlijkheid.load();
        form.init($('form'));
    });
})();
