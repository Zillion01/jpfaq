plugin.tx_jpfaq_faq {
    view {
        templateRootPaths.0 = EXT:jpfaq/Resources/Private/Templates/
        templateRootPaths.1 = {$plugin.tx_jpfaq_faq.view.templateRootPath}
        partialRootPaths.0 = EXT:jpfaq/Resources/Private/Partials/
        partialRootPaths.1 = {$plugin.tx_jpfaq_faq.view.partialRootPath}
        layoutRootPaths.0 = EXT:jpfaq/Resources/Private/Layouts/
        layoutRootPaths.1 = {$plugin.tx_jpfaq_faq.view.layoutRootPath}
    }

    persistence {
        storagePid <
        recursive <
    }

    settings {
        excludeAlreadyDisplayedQuestions = 0

        gtag {
            # Google Analytics Event tracking for helpful / unhelpfulresponse
            # First install gtag.js snippet https://developers.google.com/analytics/devguides/collection/gtagjs/
            enable = 0
            event = Response on faq
            category = Faq
            #label will be the question title
            valueHelpful = helpful
            valueUnhelpful = unhelpful
        }

        question {
            comment {
                email {
                    enable = 0
                    subject = New FAQ comment

                    sender {
                        name = Your website FAQ
                        email = no-reply@example.com
                    }

                    receivers {
                        email = info@yoursite.com,marketing@yoursite.com
                    }

                    introText = <p>Hi, you have received a new comment on a FAQ question / answer!</p>
                    closeText = <p>Friendly regards,<br/>Your website</p>
                }
            }
        }

        category {
            comment {
                email {
                    enable = 0
                    subject = New FAQ comment

                    sender {
                        name = Your website FAQ
                        email = no-reply@example.com
                    }

                    receivers {
                        email = info@yoursite.com,marketing@yoursite.com
                    }

                    introText = <p>Hi, you have received a new comment on the FAQ!</p>
                    closeText = <p>Friendly regards,<br/>Your website</p>
                }
            }
        }
    }
}

page {
    # First include jQuery! Unset tx_jpfaq_jquery if you have your own jQuery lib
    includeJSFooter {
        tx_jpfaq_jquery = https://code.jquery.com/jquery-3.4.1.min.js
        tx_jpfaq_jquery {
            disableCompression = 1
            excludeFromConcatenation = 1
        }
        tx_jpfaq = EXT:jpfaq/Resources/Public/Javascript/jpFaq.js
    }

    includeCSS {
        tx_jpfaq = EXT:jpfaq/Resources/Public/Styles/jpfaq.css

        # Unset if you already have Fontawesome included or do not wish to use it in the templates
        tx_jpfaq_fontAwesome = https://use.fontawesome.com/releases/v5.5.0/css/all.css
        tx_jpfaq_fontAwesome {
            disableCompression = 1
            excludeFromConcatenation = 1
        }
    }
}

# Mapping tt_content
config.tx_extbase {
    persistence {
        enableAutomaticCacheClearing = 1
        updateReferenceIndex = 0

        classes {
            Jp\Jpfaq\Domain\Model\TtContent {
                mapping {
                    tableName = tt_content

                    columns {
                        uid.mapOnProperty = uid
                        pid.mapOnProperty = pid
                        sorting.mapOnProperty = sorting
                        CType.mapOnProperty = contentType
                        header.mapOnProperty = header
                    }
                }
            }
        }
    }
}

# Rendering of content elements in answer
lib.tx_jpfaq.contentElementRendering = RECORDS
lib.tx_jpfaq.contentElementRendering {
    tables = tt_content
    source.current = 1
    dontCheckPid = 1
}

# Pagetype for ajaxComment, must be unique
jpfaqAjaxComment = PAGE
jpfaqAjaxComment {
    typeNum = 88188

    config {
        disableAllHeaderCode = 1
        disableCharsetHeader = 1
        disablePrefixComment = 1
        additionalHeaders = Content-type:text/html
        admPanel = 0
        debug = 0
    }

    10 < styles.content.get
    10 {
        stdWrap.trim = 1

        select {
            where = list_type = "jpfaq_faq"
        }

        renderObj < tt_content.list.20.jpfaq_faq
    }
}
