<?php
echo form_open(base_url() . 'index.php/profile/lookup/search', array(
    'method' => 'get'
));
?>
<label for="geslacht-voorkeur">Geslacht voorkeur</label>
<select id="geslacht-voorkeur" name="geslacht_voorkeur" required>
    <option>Mannen</option>
    <option>Vrouwen</option>
    <option>Beide</option>
</select>
<br/>
<label>
    Leeftijd voorkeur
    <input type="number" name="leeftijd_min" id="leeftijd-min" min="18" max="99" required placeholder="min"/>
    <input type="number" name="leeftijd_max" id="leeftijd-max" min="18" max="99" required placeholder="max"/>
</label>
<br/>
<label>
    Persoonlijkheids voorkeur
    <select name="persoonlijkheids voorkeur" required>
        <!--            TODO insert voorkeuren-->
        <option>Geen</option>
    </select>
</label>
<br/>
<label id="merk-voorkeuren">
    Merk voorkeuren
    <input type="text" placeholder="Apple" id="merk-voorkeur"/>
    <button type="button" id="btn-add-merk">Voeg toe</button>
    <input type="hidden" id="merken" name="merken" />
</label>
<br/>
<?php
echo form_submit('', 'Zoek');
echo form_close();
?>

<script>
    (function () {
        "use strict";

        var merken = {
            init: function () {
                $('#btn-add-merk').click(merken.onAdd);
            },
            onAdd: function () {
                var merk = $('#merk-voorkeur').val();
                if (!merk || merk === '') {
                    merken.onError();
                } else {
                    merken.addMerkVoorkeur(merk);
                }
            },
            onError: function () {
                window.alert('Voer een merk in');
            },
            addMerkVoorkeur: function (merk) {
                var count = $('.listed-merk').size();
                var name = 'merk_voorkeur[' + count + ']';
                $('<br /><input type="text" readonly disabled class="listed-merk" name="' + name + '" value="' + merk + '" />')
                    .appendTo($('#merk-voorkeuren'));
            }
        };

        $(document).ready(function () {
            merken.init();
            $('form').submit(function () {
                if (this[0].checkValidity()) {
                    if( $('.listed-merk').size() > 0) {
                        var merken = [];
                        $('.listed-merk').each(function () {
                            merken.push($(this).val());
                        });
                        $('#merken').val(merken.join('|'));
                        return true;
                    }

                    window.alert('Voeg ten minste 1 merk voorkeur toe');
                }

                return false;
            });
        });
    })();
</script>
