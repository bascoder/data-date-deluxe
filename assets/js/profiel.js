(function () {
    "use strict";

    function deleteProfielConfirm() {
        $('.form-delete').submit(function (e) {
            var answer = window.confirm('Weet u zeker dat u uw profiel wilt verwijderen?');

            if (answer === false) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
        });
    }

    function bindEditFunctions() {
        if (!is_ingelogd) {
            $('button.edit-button').hide();
        } else {
            $('button.edit-button').click(buttonHandler);
            deleteProfielConfirm();
            fetchMerken();
        }
    }

    function buttonHandler() {
        if (this.value === "editing") {
            this.value = "static";
            this.innerHTML = "Edit";
            finalize(this);
        }
        else {
            this.value = "editing";
            this.innerHTML = "Save";
            makeEditable(this);
        }
    }

    function makeEditable(that) {
        if (that.id === "editDescription") {
            var elem = $("#Description");
            var oldDescript = elem.html();
            var newFilling = "<textarea rows='4' id='DescriptionBox'>" + oldDescript + "</textarea>";
            elem.html(newFilling);
        } else if (that.id === "editSexPref") {
            var elem = $('#SexPref');
            var oldPref = elem.attr('editval');
            var newFilling = "<select id='sex-preference' name='sex_preference' required><option value ='m'>mannen</option><option value ='v'>vrouwen</option><option value ='bi'>mannen en vrouwen</option></select>"
            elem.html(newFilling);
            switch (oldPref) {
                case "1":
                    $("select option[value|='m']", elem).prop("selected", 's');
                    break;
                case "2":
                    $("select option[value|='v']", elem).prop("selected", 's');
                    break;
                case "3":
                    $("select option[value|='bi']", elem).prop("selected", 's');
                    break;
            }
        } else if (that.id === "editBrands") {
            var td = $('#merken-td');

            var merkInput = $('#new-merk');
            merkInput.show('fade');
            $('#new-merk-button').show('fade').click(merkenUtil.onAdd);
        }
    }

    function getHuidigeMerkVoorkeuren() {
        return $('#merken-td').data('merken') || [];
    }

    function fetchMerken() {
        merkenUtil.init('#new-merk');
        merkenUtil.load();
        merkenUtil.onAdd = function () {
            var merk_voorkeur_input = merkenUtil.input;
            var merk = merk_voorkeur_input.val();
            if (!merk || merk === '') {
                merkenUtil.onError();
            } else {
                var fields = merk.split(merkenUtil.fieldSeparater);
                if (fields.length !== 2) {
                    merkenUtil.onError();
                }
                var merkInstance = new Merk(fields[1], fields[0]);
                var merken_array = getHuidigeMerkVoorkeuren();
                merken_array.push(merkInstance);
                $('#merken-td').data('merken', merken_array);
                $(' <span class="merk-label"> ' + fields[0] + '</span>')
                    .appendTo($('#merken-spans'));

                merk_voorkeur_input.val('');
            }
        }
    }

    function finalize(that) {
        if (that.id === "editDescription") {
            var value = document.getElementById('DescriptionBox').value;
            doAjaxUpdate('beschrijving', value, $('#Description'));
        } else if (that.id === "editSexPref") {
            var preference = $("#sex-preference").val();
            doAjaxUpdate('sex_preference', preference, $('#SexPref'));
            var newPref = 0;
            switch (preference) {
                case "m":
                    newPref = 1;
                    break;
                case "v":
                    newPref = 2;
                    break;
                case "bi":
                    newPref = 3;
                    break;
            }
            $('#SexPref').attr('editval', newPref);
        } else if (that.id === "editBrands") {
            doAjaxUpdate('brand_preference', JSON.stringify(getHuidigeMerkVoorkeuren()), null);
        }
    }

    function doAjaxUpdate(field, value, elem) {
        $.ajax({
                url: base_url + 'index.php/profile/edit/update_' + field,
                type: 'POST',
                data: {'ci_session': document.cookie, value: value, plain: 1},
                success: function (response) {
                    if (!!elem)
                        elem.html(response);
                }
            })
            .done(function () {
                console.log("success");
            })
            .fail(function () {
                console.log("error");
            })
            .always(function () {
                console.log("complete");
            });
    }

    $(document).ready(bindEditFunctions);
})();