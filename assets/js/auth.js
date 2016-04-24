// script voor client side hashing
// aan de server side wordt ook gehashed maar we willen niet weten
// welke wachtwoorden aankomen
(function () {
    "use strict";

    function bindAuthFunctions() {
        // voor het submitten wordt de password gehashed
        $("form.auth").submit(hashAndSubmit);
    }

    function hashAndSubmit() {
        var pwField = $("form input[name=password]");
        var originalPw = pwField.val();
        var username = $("form .email").val();

        if (originalPw && username && originalPw != '' && username != '') {
            var hash = Sha1.hash(originalPw + username);
            $('form input[name=hash]').val(hash);
            // leeg het password veld voor verzenden
            pwField.val('dummy');
            return true;
        } else {
            window.alert('Email en wachtwoord zijn verplicht');
            e.preventDefault();
        }
    }

    $(document).ready(bindAuthFunctions);

})();
