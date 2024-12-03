<?php

return [

    'column_toggle' => [

        'heading' => 'Սյունակներ',

    ],

    'columns' => [

        'text' => [

            'actions' => [
                'collapse_list' => 'Show :count less',
                'expand_list' => 'Show :count more',
            ],

            'more_list_items' => 'and :count more',

        ],

    ],

    'fields' => [

        'bulk_select_page' => [
            'label' => 'Select/deselect all items for bulk actions.',
        ],

        'bulk_select_record' => [
            'label' => 'Select/deselect item :key for bulk actions.',
        ],

        'bulk_select_group' => [
            'label' => 'Select/deselect group :title for bulk actions.',
        ],

        'search' => [
            'label' => 'Որոնել',
            'placeholder' => 'Որոնել',
            'indicator' => 'Որոնել',
        ],

    ],

    'summary' => [

        'heading' => 'Summary',

        'subheadings' => [
            'all' => 'All :label',
            'group' => ':group summary',
            'page' => 'This page',
        ],

        'summarizers' => [

            'average' => [
                'label' => 'Average',
            ],

            'count' => [
                'label' => 'Count',
            ],

            'sum' => [
                'label' => 'Sum',
            ],

        ],

    ],

    'actions' => [

        'disable_reordering' => [
            'label' => 'Finish reordering records',
        ],

        'enable_reordering' => [
            'label' => 'Reorder records',
        ],

        'filter' => [
            'label' => 'Ֆիլտր',
        ],

        'group' => [
            'label' => 'Խումբ',
        ],

        'open_bulk_actions' => [
            'label' => 'Բացել գործողություններ',
        ],

        'toggle_columns' => [
            'label' => 'Փոխարկել սյունակները',
        ],

    ],

    'empty' => [

        'heading' => ':model չկան',

        'description' => 'Ստեղծեք :model սկսելու համար։',

    ],

    'filters' => [

        'actions' => [

            'apply' => [
                'label' => 'Հաստատել ֆիլտրը',
            ],

            'remove' => [
                'label' => 'Հեռացնել ֆիլտրը',
            ],

            'remove_all' => [
                'label' => 'Հեռացնել բոլոր ֆիլտրերը',
                'tooltip' => 'Հեռացնել բոլոր ֆիլտրերը',
            ],

            'reset' => [
                'label' => 'Վերականգնել ֆիլտրերը',
            ],

        ],

        'heading' => 'Ֆիլտրեր',

        'indicator' => 'Ակտիվ ֆիլտրեր',

        'multi_select' => [
            'placeholder' => 'Բոլորը',
        ],

        'select' => [
            'placeholder' => 'Բոլորը',
        ],

        'trashed' => [

            'label' => 'Ջնջված գրառումները',

            'only_trashed' => 'Միայն ջնջված գրառումները',

            'with_trashed' => 'Ջնջված գրառումներով',

            'without_trashed' => 'Առանց ջնջված գրառումների',

        ],

    ],

    'grouping' => [

        'fields' => [

            'group' => [
                'label' => 'Group by',
                'placeholder' => 'Group by',
            ],

            'direction' => [

                'label' => 'Group direction',

                'options' => [
                    'asc' => 'Ascending',
                    'desc' => 'Descending',
                ],

            ],

        ],

    ],

    'reorder_indicator' => 'Drag and drop the records into order.',

    'selection_indicator' => [

        'selected_count' => '1 գրառում ընտրված է։|:count գրառում ընտրված է։',

        'actions' => [

            'select_all' => [
                'label' => 'Ընտրել բոլոր :count֊ը',
            ],

            'deselect_all' => [
                'label' => 'Ապաընտրել բոլորը',
            ],

        ],

    ],

    'sorting' => [

        'fields' => [

            'column' => [
                'label' => 'Sort by',
            ],

            'direction' => [

                'label' => 'Sort direction',

                'options' => [
                    'asc' => 'Ascending',
                    'desc' => 'Descending',
                ],

            ],

        ],

    ],


];
