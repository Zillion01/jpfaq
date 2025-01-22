<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider;
use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;

$iconPath = 'EXT:jpfaq/Resources/Public/Icons/';

return [
    'ext-jpfaq-wizard-icon' => [
        'provider' => SvgIconProvider::class,
        'source' => $iconPath . 'ce_wiz.svg',
    ],
    'tx_jpfaq_domain_model_questioncomment' => [
        'provider' => SvgIconProvider::class,
        'source' => $iconPath . 'tx_jpfaq_domain_model_questioncomment.svg',
    ],
    'tx_jpfaq_domain_model_categorycomment' => [
        'provider' => SvgIconProvider::class,
        'source' => $iconPath . 'tx_jpfaq_domain_model_categorycomment',
    ],
    'tx_jpfaq_domain_model_category' => [
        'provider' => BitmapIconProvider::class,
        'source' => $iconPath . 'tx_jpfaq_domain_model_category.gif',
    ],
    'tx_jpfaq_domain_model_question' => [
        'provider' => BitmapIconProvider::class,
        'source' => $iconPath . 'tx_jpfaq_domain_model_question.gif',
    ],
];
