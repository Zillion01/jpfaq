<?php
return [
    'ctrl' => [
        'title'	=> 'LLL:EXT:jpfaq/Resources/Private/Language/locallang_db.xlf:tx_jpfaq_domain_model_question',
        'label' => 'question',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => 1,
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
        'searchFields' => 'question,answer,additional_content_answer,categories,',
        'typeicon_classes' => [
            'default' => 'mimetypes-x-tx_jpfaq_domain_model_question'
        ],
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, question, answer, additional_content_answer, categories',
    ],
    'types' => [
        '1' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, question, answer, additional_content_answer, categories, --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, starttime, endtime'],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'special' => 'languages'
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_jpfaq_domain_model_question',
                'foreign_table_where' => 'AND tx_jpfaq_domain_model_question.pid=###CURRENT_PID### AND tx_jpfaq_domain_model_question.sys_language_uid IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        't3ver_label' => [
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.versionLabel',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
            ],
        ],
        'hidden' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
                'items' => [
                    '1' => [
                        '0' => 'LLL:EXT:lang/locallang_core.xlf:labels.enabled'
                    ]
                ],
            ],
        ],
        'starttime' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime',
                'range' => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ],
                'allowLanguageSynchronization' => true,
            ],
        ],
        'endtime' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime',
                'range' => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ],
                'allowLanguageSynchronization' => true,
            ],
        ],

        'question' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:jpfaq/Resources/Private/Language/locallang_db.xlf:tx_jpfaq_domain_model_question.question',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],

        ],
        'answer' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:jpfaq/Resources/Private/Language/locallang_db.xlf:tx_jpfaq_domain_model_question.answer',
            'config' => [
                'type' => 'text',
                'eval' => 'trim,required',
                'enableRichtext' => true
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
                                'default' => 'textmedia'
                            ]
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
                    'showRemovedLocalizationRecords' => 1,
                    'showAllLocalizationLink' => 1,
                    'showSynchronizationLink' => 1,
                    'enabledControls' => [
                        'info' => false,
                    ]
                ],
                'allowLanguageSynchronization' => true,
            ]
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

    ],
];
