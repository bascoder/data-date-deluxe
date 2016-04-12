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
