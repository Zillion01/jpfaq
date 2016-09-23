plugin.tx_jpfaq_faq {
    view {=
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
        tx_jpfaq_jquery = EXT:jpfaq/Resources/Public/Javascript/jquery-3.1.1.min.js
        tx_jpfaq = EXT:jpfaq/Resources/Public/Javascript/jpFaq.js
    }

    includeCSS {
        tx_jpfaq = EXT:jpfaq/Resources/Public/Styles/jpfaq.css
    }
}

