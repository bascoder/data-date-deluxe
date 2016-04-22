"use strict";

var merkenUtil = {
    fieldSeparater: '--',
    merkenArray: [],
    input: null,
    init: function (inputSelector) {
        merkenUtil.input = $(inputSelector);
    },
    load: function () {
        $.getJSON(base_url + 'index.php/ajax/merkAjax/all', null, function (json) {
            var values = json.map(function (current) {
                return current.naam + merkenUtil.fieldSeparater + current.mid;
            });
            merkenUtil.populateAutoComplete(values);
        });
    },
    onAdd: function () {
        var merk_voorkeur_input = merkenUtil.input;
        var merk = merk_voorkeur_input.val();
        if (!merk || merk === '') {
            merkenUtil.onError();
        } else {
            merkenUtil.addMerkVoorkeur(merk);
            merk_voorkeur_input.val('');
        }
    },
    onError: function () {
        window.alert('Voer een merk in');
    },
    addMerkVoorkeur: function (merk) {
        $('<br /><input type="text" readonly disabled class="listed-merk" value="' + merk + '" />')
            .appendTo(merkenUtil.input);
    },
    populateAutoComplete: function (values) {
        merkenUtil.input.autocomplete({
            source: values
        });
    }
};
