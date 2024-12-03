<?php

return [

    'builder' => [

        'actions' => [

            'add' => [
                'label' => 'Ավելացնել :label',
            ],

            'delete' => [
                'label' => 'Ջնջել',
            ],

            'move_down' => [
                'label' => 'Իջեցնել',
            ],

            'move_up' => [
                'label' => 'Բարձրացնել',
            ],

            'collapse' => [
                'label' => 'Ծալել',
            ],

            'expand' => [
                'label' => 'Ընդարձակել',
            ],

            'collapse_all' => [
                'label' => 'Ծալել բոլորը',
            ],

            'expand_all' => [
                'label' => 'Ընդարձակել բոլորը',
            ],

        ],

    ],

    'file_upload' => [

        'editor' => [

            'actions' => [

                'cancel' => [
                    'label' => 'Cancel',
                ],

                'drag_crop' => [
                    'label' => 'Drag mode "crop"',
                ],

                'drag_move' => [
                    'label' => 'Drag mode "move"',
                ],

                'flip_horizontal' => [
                    'label' => 'Flip image horizontally',
                ],

                'flip_vertical' => [
                    'label' => 'Flip image vertically',
                ],

                'move_down' => [
                    'label' => 'Move image down',
                ],

                'move_left' => [
                    'label' => 'Move image to left',
                ],

                'move_right' => [
                    'label' => 'Move image to right',
                ],

                'move_up' => [
                    'label' => 'Move image up',
                ],

                'reset' => [
                    'label' => 'Reset',
                ],

                'rotate_left' => [
                    'label' => 'Rotate image to left',
                ],

                'rotate_right' => [
                    'label' => 'Rotate image to right',
                ],

                'set_aspect_ratio' => [
                    'label' => 'Set aspect ratio to :ratio',
                ],

                'save' => [
                    'label' => 'Save',
                ],

                'zoom_100' => [
                    'label' => 'Zoom image to 100%',
                ],

                'zoom_in' => [
                    'label' => 'Zoom in',
                ],

                'zoom_out' => [
                    'label' => 'Zoom out',
                ],

            ],

            'fields' => [

                'height' => [
                    'label' => 'Height',
                    'unit' => 'px',
                ],

                'rotation' => [
                    'label' => 'Rotation',
                    'unit' => 'deg',
                ],

                'width' => [
                    'label' => 'Width',
                    'unit' => 'px',
                ],

                'x_position' => [
                    'label' => 'X',
                    'unit' => 'px',
                ],

                'y_position' => [
                    'label' => 'Y',
                    'unit' => 'px',
                ],

            ],

            'aspect_ratios' => [

                'label' => 'Aspect ratios',

                'no_fixed' => [
                    'label' => 'Free',
                ],

            ],

            'svg' => [

                'messages' => [
                    'confirmation' => 'Editing SVG files is not recommended as it can result in quality loss when scaling.\n Are you sure you want to continue?',
                    'disabled' => 'Editing SVG files is disabled as it can result in quality loss when scaling.',
                ],

            ],

        ],

    ],

    'key_value' => [

        'actions' => [

            'add' => [
                'label' => 'Ավելացնել տող',
            ],

            'delete' => [
                'label' => 'Ջնջել տողը',
            ],

        ],

        'fields' => [

            'key' => [
                'label' => 'Բանալի',
            ],

            'value' => [
                'label' => 'Արժեք',
            ],

        ],

    ],

    'markdown_editor' => [

        'toolbar_buttons' => [
            'attach_files' => 'Կցել ֆայլեր',
            'bold' => 'Bold',
            'bullet_list' => 'Bullet list',
            'code_block' => 'Կոդի բլոկ',
            'edit' => 'Խմբագրել',
            'italic' => 'Շեղագիր',
            'link' => 'Հղում',
            'ordered_list' => 'Համարակալված ցուցակ',
            'preview' => 'Նախադիտում',
            'strike' => 'Strikethrough',
        ],

    ],

    'repeater' => [

        'actions' => [

            'add' => [
                'label' => 'Ավելացնել :label',
            ],

            'delete' => [
                'label' => 'Ջնջել',
            ],

            'move_down' => [
                'label' => 'Իջեցնել',
            ],

            'move_up' => [
                'label' => 'Բարձրացնել',
            ],

            'collapse' => [
                'label' => 'Ծալել',
            ],

            'expand' => [
                'label' => 'Ընդարձակել',
            ],

            'collapse_all' => [
                'label' => 'Ծալել բոլորը',
            ],

            'expand_all' => [
                'label' => 'Ընդարձակել բոլորը',
            ],

        ],

    ],

    'rich_editor' => [

        'dialogs' => [

            'link' => [

                'actions' => [
                    'link' => 'Հղում',
                    'unlink' => 'Չեղարկել հղումը',
                ],

                'label' => 'URL',

                'placeholder' => 'Մուտքագրեք URL',

            ],

        ],

        'toolbar_buttons' => [
            'attach_files' => 'Կցել ֆայլեր',
            'blockquote' => 'Արգելափակման մեջբերում',
            'bold' => 'Bold',
            'bullet_list' => 'Bullet list',
            'code_block' => 'Կոդի բլոկ',
            'h1' => 'Անվանում',
            'h2' => 'Վերնագիր',
            'h3' => 'Ենթավերնագիր',
            'italic' => 'Շեղագիր',
            'link' => 'Հղում',
            'ordered_list' => 'Համարակալված ցուցակ',
            'redo' => 'Կրկնել',
            'strike' => 'Strikethrough',
            'undo' => 'Չեղարկել',
        ],

    ],

    'select' => [

        'actions' => [

            'create_option' => [

                'modal' => [

                    'heading' => 'Ստեղծել',

                    'actions' => [

                        'create' => [
                            'label' => 'Ստեղծել',
                        ],

                    ],

                ],

            ],

        ],

        'boolean' => [
            'true' => 'Այո',
            'false' => 'Ոչ',
        ],

        'loading_message' => 'Բեռնվում է...',

        'no_search_results_message' => 'Ոչ մի տարբերակ չի համապատասխանում ձեր որոնմանը։',

        'placeholder' => 'Ընտրեք տարբերակ',

        'searching_message' => 'Որոնում...',

        'search_prompt' => 'Սկսեք մուտքագրել որոնման համար...',

    ],

    'tags_input' => [
        'placeholder' => 'Նոր հատկորոշում',
    ],

    'wizard' => [

        'actions' => [

            'previous_step' => [
                'label' => 'Ետ',
            ],

            'next_step' => [
                'label' => 'Հաջորդը',
            ],

        ],

    ],

];
