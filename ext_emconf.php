<?php

$EM_CONF[$_EXTKEY] = array(
    'title' => 'jpFAQ',
    'description' => 'Frequently Asked Questions (FAQ) plugin. With categories, on-the-fly search, customer helpfulness tracking and comments.',
    'category' => 'plugin',
    'author' => 'Jacco van der Post',
    'author_email' => 'jacco@id-webdesign.nl',
    'state' => 'stable',
    'uploadfolder' => false,
    'createDirs' => '',
    'clearCacheOnLoad' => 0,
    'version' => '11.5.0',
    'constraints' =>
        array(
            'depends' =>
                array(
                    'typo3' => '11.5.5-11.5.99',
                ),
            'conflicts' =>
                array(),
            'suggests' =>
                array(),
        ),
);
