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
        storagePid >
        recursive >
    }
}

page {
    #First include jQuery! Disable jpfaq_jquery if you have your own jQuery lib
    includeJSFooter {
        tx_jpfaq_jquery = EXT:jpfaq/Resources/Public/Javascript/jquery-3.2.1.min.js
        tx_jpfaq = EXT:jpfaq/Resources/Public/Javascript/jpFaq.js
    }

    includeCSS {
        tx_jpfaq = EXT:jpfaq/Resources/Public/Styles/jpfaq.css
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
