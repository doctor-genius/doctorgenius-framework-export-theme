//This is a global function called remotely by the Google Translate library 
function googleTranslateElementInit() {
    
    //php_vars populated by WP
    var enabledLanguages = php_vars['enabled_languages'];
    gt = new google.translate.TranslateElement({pageLanguage: 'en', includedLanguages: enabledLanguages.join(), layout: google.translate.TranslateElement.FloatPosition.TOP_LEFT}, 'google_translate_element');
}

(function($) {
    $(document).ready(function() {
        /*
         * Google Translate Module
         *
         */
        $('#translation_links a').click(function() {
            var lang = this.dataset.lang;
            var lang_select = document.getElementById(':0.targetLanguage').getElementsByTagName('select');

            if (lang_select.length > 0) {
                lang_select = lang_select[0];
            } else {
                return false;
            }

            $('#current_lang').removeClass().addClass($(this).attr('class'));

            if (lang === "en" && document.getElementById(':1.container') ) {
                var button = document.getElementById(':1.container').contentDocument.getElementById(':1.close');
                if(typeof button !== 'undefined') {
                    button.click();
                }
            } else {
                lang_select.value = lang;
            }

            if (document.createEvent) {
                var evt = document.createEvent("HTMLEvents");
                evt.initEvent("change", false, true);
                lang_select.dispatchEvent(evt);
            } else {
                lang_select.fireEvent("onchange");
            }

            return false;
        });

    });
})(jQuery);
