<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:jpfaq/Resources/Private/Language/locallang_db.xlf:tx_jpfaq_domain_model_question',
        'label' => 'question',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'sortby' => 'sorting',
        'versioningWS' => true,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'typeicon_classes' => [
            'default' => 'tx_jpfaq_domain_model_question',
        ],
        'security' => [
            'ignorePageTypeRestriction' => true,
        ],
    ],
    'types' => [
        '1' => ['showitem' => 'question,answer,additional_content_answer,categories,helpful,nothelpful,questioncomment,--div--;core.form.tabs:language,sys_language_uid,l10n_parent,l10n_diffsource,--div--;core.form.tabs:access,hidden,starttime,endtime'],
    ],
    'columns' => [
        'question' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:jpfaq/Resources/Private/Language/locallang_db.xlf:tx_jpfaq_domain_model_question.question',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'required' => true,
            ],

        ],
        'answer' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:jpfaq/Resources/Private/Language/locallang_db.xlf:tx_jpfaq_domain_model_question.answer',
            'config' => [
                'type' => 'text',
                'eval' => 'trim',
                'enableRichtext' => true,
            ],
        ],
        'additional_content_answer' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:jpfaq/Resources/Private/Language/locallang_db.xlf:tx_jpfaq_domain_model_question.answerAdditional',
            'config' => [
                'type' => 'inline',
                'allowed' => 'tt_content',
                'foreign_table' => 'tt_content',
                'overrideChildTca' => [
                    'columns' => [
                        'CType' => [
                            'config' => [
                                'default' => 'textmedia',
                            ],
                        ],
                    ],
                ],
                'foreign_sortby' => 'sorting',
                'foreign_field' => 'jpfaq',
                'minitems' => 0,
                'maxitems' => 99,
                'appearance' => [
                    'collapseAll' => 1,
                    'expandSingle' => 1,
                    'levelLinksPosition' => 'bottom',
                    'useSortable' => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'showAllLocalizationLink' => 1,
                    'showSynchronizationLink' => 1,
                    'enabledControls' => [
                        'info' => false,
                    ],
                ],
                'allowLanguageSynchronization' => true,
            ],
        ],
        'categories' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:jpfaq/Resources/Private/Language/locallang_db.xlf:tx_jpfaq_domain_model_question.categories',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_jpfaq_domain_model_category',
                'MM' => 'tx_jpfaq_question_category_mm',
                'size' => 10,
                'autoSizeMax' => 30,
                'maxitems' => 9999,
                'multiple' => 0,
                'fieldControl' => [
                    'editPopup' => [
                        'disabled' => false,
                    ],
                    'addRecord' => [
                        'disabled' => false,
                    ],
                ],
            ],
        ],
        'helpful' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:jpfaq/Resources/Private/Language/locallang_db.xlf:tx_jpfaq_domain_model_question.helpful',
            'config' => [
                'type' => 'number',
                'size' => 6,
                'default' => 0,
                'searchable' => false,
            ],

        ],
        'nothelpful' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:jpfaq/Resources/Private/Language/locallang_db.xlf:tx_jpfaq_domain_model_question.nothelpful',
            'config' => [
                'type' => 'number',
                'size' => 6,
                'default' => 0,
                'searchable' => false,
            ],
        ],
        'questioncomment' => [
            'exclude' => true,
            'label' => 'LLL:EXT:jpfaq/Resources/Private/Language/locallang_db.xlf:tx_jpfaq_domain_model_question.questioncomment',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_jpfaq_domain_model_questioncomment',
                'foreign_field' => 'question',
                'foreign_sortby' => 'sorting',
                'maxitems' => 9999,
                'appearance' => [
                    'collapseAll' => 1,
                    'levelLinksPosition' => 'top',
                    'showSynchronizationLink' => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'useSortable' => 1,
                    'showAllLocalizationLink' => 1,
                ],
            ],
        ],
    ],
];
