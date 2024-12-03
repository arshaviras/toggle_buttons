<?php

return [

    'label' => 'Էջավորման նավիգացիա',

    'overview' => 'Ցուցադրվում են :first-ից :last-ը՝ :total արդյունքներից',

    'fields' => [

        'records_per_page' => [
            'label' => 'Մեկ էջում',
            'options' => [
                'all' => 'Բոլորը',
            ],
        ],

    ],

    'actions' => [

        'go_to_page' => [
            'label' => 'Գնալ էջ :page',
        ],

        'next' => [
            'label' => 'Հաջորդը',
        ],

        'previous' => [
            'label' => 'Նախորդ',
        ],

    ],

];
