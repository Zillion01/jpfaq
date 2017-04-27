# Choose in backend sysfolder with FAQ articles "Contains Plugin" and set on jpFAQ!

[page|module = jpfaq]
    TCEFORM {
        tt_content {
            # Hide columns
            colPos.disabled = 1

            # Allow only Ctype's, see for a list of Fluid Styled Content Ctypes:
            # /typo3/sysext/fluid_styled_content/Configuration/TypoScript/ContentElement/
            # CType.keepItems = textmedia
        }
    }

    # Hide the tt_content records in list view
    mod.web_list.hideTables = tt_content
[end]