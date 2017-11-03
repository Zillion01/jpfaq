//
// Title: jpFaq.js
// Author: Jacco van der Post - www.id-webdesign.nl
// Date: sep 2016
//

var jpFaq = jpFaq || {};

(function ($) {

    var txJpfaq = '.tx-jpfaq';
    var jpFaqToggleTrigger = 'ul.listCategory .toggleTrigger';
    var jpFaqListItem = 'ul.listCategory li';
    var jpfaqShow = '.jpfaqShowAll';
    var jpfaqHide = '.jpfaqHideAll';
    var jpFaqToggleTriggerContainer = '.toggleTriggerContainer';
    var thisPlugin = '';
    var jpFaqSearch = '#jpfaqSearch';
    var jpFaqSearchForm = '#jpfaqSearch input';
    var jpFaqFilterCount = '#jpfaq-filter-count';

    /**
     * Plugins
     *
     * @type object
     */
    jpFaq.Main = {

        /**
         * Initialize plugins
         *
         * @return void
         */
        initialize: function () {
            jpFaq.Main.openClose();
            jpFaq.Main.search();
            jpFaq.Main.preventSubmit();
        },

        /**
         * Open and close questions with js
         *
         * @return void
         */
        openClose: function () {
            $(jpFaqToggleTrigger).click(function () {
                thisPlugin = $(this).closest(txJpfaq);
                $(this).next().toggleClass('active').slideToggle('fast');
                $(this).toggleClass('questionUnfolded');
                if ($(thisPlugin).find(jpFaqListItem).children(':first-child').length == $(thisPlugin).find(jpFaqListItem).children(':first-child.questionUnfolded').length) {
                    $(thisPlugin).find(jpfaqShow).hide();
                    $(thisPlugin).find(jpfaqHide).show();
                } else {
                    $(thisPlugin).find(jpfaqHide).hide();
                    $(thisPlugin).find(jpfaqShow).show();
                }
            });
            $(jpfaqShow).click(function () {
                thisPlugin = $(this).closest(txJpfaq);
                $(thisPlugin).find(jpFaqToggleTriggerContainer).removeClass('active');
                $(thisPlugin).find(jpFaqToggleTriggerContainer).addClass('active').slideDown('fast');
                $(thisPlugin).find(jpFaqToggleTrigger).removeClass('questionUnfolded');
                $(thisPlugin).find(jpFaqToggleTrigger).addClass('questionUnfolded');
                $(thisPlugin).find(jpfaqShow).hide();
                $(thisPlugin).find(jpfaqHide).show();
            });
            $(jpfaqHide).click(function () {
                thisPlugin = $(this).closest(txJpfaq);
                $(thisPlugin).find(jpFaqToggleTriggerContainer).removeClass('active').slideUp('fast');
                $(thisPlugin).find(jpFaqToggleTrigger).removeClass('questionUnfolded');
                $(thisPlugin).find(jpfaqHide).hide();
                $(thisPlugin).find(jpfaqShow).show();
            });
        },

        /**
         * Quick search faq's
         *
         * @return void
         */
        search: function () {
            $(jpFaqSearchForm).keyup(function () {
                var searchFilter = $(this).val(), count = 0;

                if (searchFilter.length > 0) {
                    $(jpFaqFilterCount).show();
                } else {
                    $(jpFaqFilterCount).hide();
                }

                $(txJpfaq + ' li').each(function () {
                    if ($(this).text().search(new RegExp(searchFilter, 'i')) < 0) {
                        $(this).fadeOut('fast');
                    } else {
                        $(this).show();
                        count++;
                    }
                });

                $(jpFaqFilterCount + ' span').text(count);
            })
        },

        /**
         * Prevent reload page on submit search
         *
         * @return void
         */
        preventSubmit : function () {
            $(jpFaqSearch).submit(function (e) {
                e.preventDefault();
            });
        }
    }
})(jQuery);

/**
 * On ready event
 */
jQuery(document).ready(function () {
    jpFaq.Main.initialize();
});