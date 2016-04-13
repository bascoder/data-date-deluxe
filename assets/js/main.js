(function () {
    "use strict";

    var messages = {
        // hide messages if no content is set
        hideEmptyMessages: function () {
            $('.message').each(function () {
                // if message is empty
                if ($(this).is(':empty') || $(this).html().trim() === '') {
                    $(this).hide();
                }
            });
        }
    };

    $(document).ready(function () {
        messages.hideEmptyMessages();
    });
})();

var util = {
    /**
     * Returns leeftijd in jaar
     * @param {number} geboorteDatumTimestamp timestamp in seconden
     * @returns {number} leeftijd in jaar
     */
    calculateAge: function (geboorteDatumTimestamp) {
        var ageDefMilliseconds = Date.now() - (geboorteDatumTimestamp * 1000);
        var ageDate = new Date(ageDefMilliseconds);
        return Math.abs(ageDate.getUTCFullYear() - 1970);
    }
};
