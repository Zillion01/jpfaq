<?php

declare(strict_types=1);

return [
    \Jp\Jpfaq\Domain\Model\TtContent::class => [
        'tableName' => 'tt_content',
        'properties' => [
            'contentType' => [
                'fieldName' => 'CType'
            ],
        ],
    ],
];
