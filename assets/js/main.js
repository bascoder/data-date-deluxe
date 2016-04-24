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

    var ajaxTool = {
        loadingCount: 0,
        progressbar: null,
        init: function (progressbarSelector) {
            ajaxTool.progressbar = $(progressbarSelector);

            // init progressbar
            ajaxTool.progressbar.progressbar({
                value: false
            }).hide('fade');

            //register ajax event handlers
            $(document).ajaxSend(ajaxTool.onSend);
            $(document).ajaxComplete(ajaxTool.onComplete);
        },
        onSend: function () {
            ajaxTool.loadingCount++;
            ajaxTool.updateProgressbar();
        },
        onComplete: function () {
            ajaxTool.loadingCount--;
            ajaxTool.updateProgressbar();
        },
        updateProgressbar: function () {
            var isLoading = !!ajaxTool.loadingCount;
            console.log('Update progressbar: ' + isLoading);
            if (isLoading) {
                ajaxTool.progressbar.show('fade');
            } else {
                ajaxTool.progressbar.hide('fade');
            }
        }
    };

    var responsiveMenu = {
        ul: null,
        init: function (ul, toggleAnchor) {
            $(toggleAnchor).click(responsiveMenu.onClick);
            $(window).resize(responsiveMenu.onResize);
            responsiveMenu.ul = $(ul);
        },
        onClick: function (e) {
            var that = this;
            $(responsiveMenu.ul).toggle({
                effect: 'blind',
                complete: function () {
                    console.log('complete');
                    $(that).toggleClass('active');
                }
            });


            e.preventDefault();
        },
        onResize: function () {
            if (window.innerWidth >= 1080) {
                $(responsiveMenu.ul).show('blind');
            }
        }
    };

    $(document).ready(function () {
        messages.hideEmptyMessages();
        ajaxTool.init('#progressbar-ajax');
        responsiveMenu.init('ul.nav', '.toggle-nav');
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
