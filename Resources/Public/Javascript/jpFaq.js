//
// Title: jpFaq.js
// Author: Jacco van der Post - www.id-webdesign.nl
// Date: feb 2018
//

var jpFaq = jpFaq || {};

(function ($) {

    var txJpfaq = '.tx-jpfaq';
    var jpFaqToggleTrigger = 'ul.jpfaqList .toggleTrigger';
    var jpFaqListItem = 'ul.jpfaqList li';
    var jpfaqShow = '.jpfaqShowAll';
    var jpfaqHide = '.jpfaqHideAll';
    var jpFaqToggleTriggerContainer = '.toggleTriggerContainer';
    var thisPlugin = '';
    var jpFaqSearch = '#jpfaqSearch';
    var jpFaqSearchForm = '#jpfaqSearch input';
    var jpFaqFilterCount = '#jpfaq-filter-count';
    var jpfaqQuestionCommentContainer = '.jpfaqQuestionCommentContainer';
    var jpfaqAddCommentForm = '.jpfaqAddCommentForm';
    var jpfaqQuestionHelpfulText = '.jpfaqQuestionHelpfulText';
    var jpFaqThankYou = '.jpFaqThankYou';
    var jpfaqQuestionNotHelpful = '.jpfaqQuestionNotHelpful';
    var jpfaqQuestionHelpful = '.jpfaqQuestionHelpful';
    var jpfaqCommentFormClose = '.jpfaqCommentFormClose';
    var jpfaqQuestionCommentForm = '#jpfaqQuestionCommentForm';
    var jpfaqAddCommentCategoryForm = '.jpfaqAddCommentCategoryForm';
    var jpfaqEmailField = '.jpfaqEmailField';
    var jpfaqRequiredField = '.jpfaqRequired';
    var jpfaqCommentFieldWarning = 'jpfaqCommentFieldWarning';
    var jpfaqSubmitComment = '.jpfaqSubmitComment';
    var jpfaqCommentPageType = '&eID=jpfaq_feedback';
    var jpfaqSpinner = '.jpfaqSpinner';
    var jpfaqSpinnerHtml = '<div class="jpfaqSpinner"></div>';
    var jpfaqCatCommentContainerLink = '.jpfaqCatCommentContainerLink';
    var jpfaqCatCommentContainerIntro = '.jpfaqCatCommentContainerIntro';
    var jpfaqCatCommentContainer = '.jpfaqCatCommentContainer';
    var jpfaqCatCommentFormClose = '.jpfaqCatCommentFormClose';
    var jpfaqSubmitCatComment = '.jpfaqSubmitCatComment';
    var jpfaqCatCommentForm = '#jpfaqCatCommentForm';

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

            jpFaq.Main.questionIsHelpful();
            jpFaq.Main.questionIsNotHelpful();
            jpFaq.Main.closeQuestionCommentForm();
            jpFaq.Main.addQuestionComment();
            jpFaq.Main.closeThanks4QuestionComment();

            jpFaq.Main.addCategoryCommentForm();
            jpFaq.Main.closeCategoryCommentForm();
            jpFaq.Main.addCategoryComment();
            jpFaq.Main.closeThanks4CategoryComment();

            // For testing
            // $(jpfaqShow).click();
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

                $(txJpfaq + ' .jpfaqList > li').each(function () {
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
        preventSubmit: function () {
            $(jpFaqSearch).submit(function (e) {
                e.preventDefault();
            });
        },

        /**
         * A question is found helpful
         *
         * Update 'helpful' in database
         * Send to Google event tracking
         *
         * Action: helpfulness
         *
         * @return void
         */
        questionIsHelpful: function () {
            $(jpfaqQuestionHelpful).click(function (event) {
                event.preventDefault();
                $(this).closest(jpfaqQuestionHelpfulText).hide();
                $(jpFaqThankYou).show();

                var loadUri = $(this).attr('href') + jpfaqCommentPageType;
                jpFaq.Main.ajaxPost(loadUri);

                var gtagData = $(this).data();
                if (gtagData['gtagevent']) {
                    jpFaq.Main.sendGtag(gtagData);
                }
            });
        },

        /**
         * A question is not found helpful
         *
         * Via Ajax, load comment form and update property 'nothelpful' in database
         * Send to Google event tracking
         *
         * Action: helpfulness
         *
         * @return void
         */
        questionIsNotHelpful: function () {
            $(jpfaqQuestionNotHelpful).click(function (event) {
                event.preventDefault();
                $(this).closest(jpfaqQuestionHelpfulText).hide();
                $(jpfaqAddCommentForm).slideDown('fast', function () {
                    $(jpfaqAddCommentForm).show();
                })

                var loadUri = $(this).attr('href') + jpfaqCommentPageType;
                jpFaq.Main.ajaxPost(loadUri);

                var gtagData = $(this).data();
                if (gtagData['gtagevent']) {
                    jpFaq.Main.sendGtag(gtagData);
                }
            })
        },

        /**
         * Send gtag to Google analytics events
         * Must be enabled in typoscript settings and gtag.js global tracking snippet must be present
         *
         * @param {array} gtagData
         *
         * @return void
         */
        sendGtag: function (gtagData) {
            gtag('event', gtagData['gtagevent'], {
                'event_category': gtagData['gtagcategory'],
                'event_label': gtagData['gtaglabel'],
                'value': gtagData['gtagvalue']
            });
        },

        /**
         * Close question comment form when clicked on close
         *
         * @return void
         */
        closeQuestionCommentForm: function () {
            $(txJpfaq).on('click', jpfaqCommentFormClose, function () {
                $(this).closest(jpfaqQuestionCommentContainer).find(jpfaqQuestionHelpfulText).show();

                var formContainer = $(this).closest(jpfaqQuestionCommentContainer).find(jpfaqAddCommentForm);
                formContainer.slideUp('fast', function () {
                    formContainer.hide();
                });
            })
        },

        /**
         * Add question comment after submit
         * validate, then send it to postComment
         *
         * @return void
         */
        addQuestionComment: function () {
            $(txJpfaq).on('click', jpfaqSubmitComment, function (event) {
                event.preventDefault();

                var submitButtonId = this.id;
                var questionNumber = submitButtonId.replace('jpfaqSubmitComment', '');
                // Get the correct plugin when multiple plugins with same questions on a page
                var pluginUid = $(this).closest(txJpfaq).attr('id');
                var commentContainer = '#' + pluginUid + ' ' + jpfaqQuestionCommentContainer + questionNumber;
                var form = $(jpfaqQuestionCommentForm + questionNumber);
                var formValidated = jpFaq.Main.validateForm(form);

                if (formValidated) {
                   jpFaq.Main.postComment(event, commentContainer, form);
                }
            });
        },

        /**
         * Close thank you message after posting a question comment
         *
         * @return void
         */
        closeThanks4QuestionComment: function () {
            $(jpfaqQuestionCommentContainer).on('click', '.jpfaqCommentClose', function () {
                $(this).closest('.jpfaqThanks').slideUp('fast', function () {
                    $(this).remove();
                });
            });
        },

        /**
         * Add Category comment form after click link
         *
         * @return void
         */
        addCategoryCommentForm: function () {
            $(jpfaqCatCommentContainerLink).click(function (event) {
                event.preventDefault();
                $(this).closest(jpfaqCatCommentContainerIntro).hide();
                $(jpfaqAddCommentCategoryForm).slideDown('fast', function () {
                    $(jpfaqAddCommentCategoryForm).show();
                })
            })
        },

        /**
         * Close category comment form when clicked on close
         *
         * @return void
         */
        closeCategoryCommentForm: function () {
            $(txJpfaq).on('click', jpfaqCatCommentFormClose, function () {
                $(this).closest(jpfaqCatCommentContainer).find(jpfaqCatCommentContainerIntro).show();

                var formContainer = $(this).closest(jpfaqCatCommentContainer).find(jpfaqAddCommentCategoryForm);
                formContainer.slideUp('fast', function () {
                    formContainer.hide();
                });
            })
        },

        /**
         * Add category comment after submit
         * validate, then send it to postComment
         *
         * @return void
         */
        addCategoryComment: function () {
            $(txJpfaq).on('click', jpfaqSubmitCatComment, function (event) {
                event.preventDefault();

                var submitButtonId = this.id;
                var questionNumber = submitButtonId.replace('jpfaqSubmitCatComment', '');
                // Get the correct plugin when multiple plugins with same questions on a page
                var pluginId = $(this).closest(txJpfaq).attr('id');
                var commentContainer = '#' + pluginId + ' ' + jpfaqCatCommentContainer + questionNumber;
                var pluginUid = pluginId.replace('tx-jpfaq','');
                var form = $(jpfaqCatCommentForm + pluginUid);
                var formValidated = jpFaq.Main.validateForm(form);

                if (formValidated) {
                    jpFaq.Main.postComment(event, commentContainer, form);
                }
            });
        },

        /**
         * Close thank you message after posting a category comment
         *
         * @return void
         */
        closeThanks4CategoryComment: function () {
            $(jpfaqCatCommentContainer).on('click', '.jpfaqCommentClose', function () {
                $(this).closest('.jpfaqThanks').slideUp('fast', function () {
                    $(this).remove();
                });
            });
        },

        /**
         * Validate form on requiredfields and valid email
         *
         *
         * @param {object} form
         * @return {boolean}
         */
        validateForm: function (form) {
            var noFieldErrors = true;

            // Validate form required fields filled, add warning class
            $(form).find(jpfaqRequiredField).each(function () {
                if ($(this).val() === '') {
                    $(this).addClass(jpfaqCommentFieldWarning);
                    noFieldErrors = false;
                }
            });

            // Validate email if filled in, else add warning class
            $(form).find(jpfaqEmailField).each(function () {
                var email = $(this).val();
                if (email) {
                    if (!jpFaq.Main.validateEmail(email)) {
                        $(this).addClass(jpfaqCommentFieldWarning);
                        noFieldErrors = false;
                    }
                }
            });

            // Remove warning class (e.g. red border) on fields with errors
            $('.' + jpfaqCommentFieldWarning).focus(function () {
                $(this).removeClass(jpfaqCommentFieldWarning);
            });

            return noFieldErrors;
        },

        /**
         * Ajax POST
         * Used for helpful / not helpful
         *
         * @param {string} loadUri
         * @param {string} contentContainer
         *
         * @return void
         */
        ajaxPost: function (loadUri) {
            $.ajax({
                type: 'POST',
                url: loadUri,
                data: {},

            });
        },

        /**
         * Post comment with Ajax
         * show thank you text with the comment content
         *
         * Action: addComment
         *
         * @param {object} event
         * @param {string} commentContainer
         * @param {string} form
         *
         * @return void
         */
        postComment: function (event, commentContainer, form) {
            var loadUri = $(form).attr('action') + jpfaqCommentPageType;
            $(commentContainer + ' ' + jpfaqAddCommentForm).fadeOut();
            $(commentContainer).append(jpfaqSpinnerHtml);

            $.ajax({
                type: 'POST',
                url: loadUri,
                data: form.serialize(),

                success: function (response) {
                    $(commentContainer + ' ' + jpfaqAddCommentForm).empty();
                    $(jpfaqSpinner).remove();

                    $(commentContainer).append(response);
                },

                error: function (xhr, thrownError) {
                    $(jpfaqSpinner).remove();
                    $(commentContainer + ' ' + jpfaqAddCommentForm).fadeIn('fast');
                    //console.log(xhr.status + ' ' + xhr.responseText + ' ' + thrownError);
                }
            });
        },

        /**
         * Regex email validation
         *
         * @param email
         *
         * @return boolean
         */
        validateEmail: function (email) {
            var expr = /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))$/i;
            return expr.test(email);
        }
    }
})(jQuery);

/**
 * On ready event
 */
jQuery(document).ready(function () {
    jpFaq.Main.initialize();
});