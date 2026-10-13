<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:jpfaq/Resources/Private/Language/locallang_db.xlf:tx_jpfaq_domain_model_questioncomment',
        'label' => 'name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'sortby' => 'sorting',
        'default_sortby' => 'crdate DESC',
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
            'default' => 'tx_jpfaq_domain_model_questioncomment',
        ],
    ],
    'types' => [
        '1' => ['showitem' => 'name,email,comment,question,ip,--div--;core.form.tabs:language,sys_language_uid,l10n_parent,l10n_diffsource,--div--;core.form.tabs:access,hidden,starttime,endtime'],
    ],
    'columns' => [
        'name' => [
            'exclude' => true,
            'label' => 'LLL:EXT:jpfaq/Resources/Private/Language/locallang_db.xlf:tx_jpfaq_domain_model_questioncomment.name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'email' => [
            'exclude' => true,
            'label' => 'LLL:EXT:jpfaq/Resources/Private/Language/locallang_db.xlf:tx_jpfaq_domain_model_questioncomment.email',
            'config' => [
                'type' => 'email',
                'size' => 30,
            ],
        ],
        'comment' => [
            'exclude' => true,
            'label' => 'LLL:EXT:jpfaq/Resources/Private/Language/locallang_db.xlf:tx_jpfaq_domain_model_questioncomment.comment',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
                'required' => true,
            ],
        ],
        'finfo' => [
            'exclude' => true,
            'label' => 'LLL:EXT:jpfaq/Resources/Private/Language/locallang_db.xlf:tx_jpfaq_domain_model_questioncomment.finfo',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'question' => [
            'exclude' => true,
            'label' => 'LLL:EXT:jpfaq/Resources/Private/Language/locallang_db.xlf:tx_jpfaq_domain_model_questioncomment.question',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_jpfaq_domain_model_question',
                'foreign_field' => 'question',
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],
        'crdate' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.creationDate',
            'config' => [
                'type' => 'datetime',
                'default' => 0,
                'searchable' => false,
            ],
        ],
        'ip' => [
            'exclude' => true,
            'label' => 'LLL:EXT:jpfaq/Resources/Private/Language/locallang_db.xlf:tx_jpfaq_domain_model_categorycomment.ip',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'searchable' => false,
            ],
        ],
    ],
];
