<?php

$EM_CONF[$_EXTKEY] = array(
    'title' => 'jpFAQ',
    'description' => 'Easy Frequently Asked Questions (FAQ) plugin. With categories and on-the-fly search.',
    'category' => 'plugin',
    'author' => 'Jacco van der Post',
    'author_email' => 'jacco@id-webdesign.nl',
    'state' => 'stable',
    'uploadfolder' => false,
    'createDirs' => '',
    'clearCacheOnLoad' => 0,
    'version' => '8.7.0',
    'constraints' =>
        array(
            'depends' =>
                array(
                    'typo3' => '8.7.0-8.7.99',
                ),
            'conflicts' =>
                array(),
            'suggests' =>
                array(),
        ),
);