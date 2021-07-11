<?php

return [
    'Deadlinks' => [
        /*
         * Mail Recipient. Person to receive results when mail output is selected
         */
        'mailRecipient' => 'cakephp-plugins@avolle.com',

        /*
         * Tables to scan and their fields
         */
        'tables' => [
            'Files' => [
                'fields' => [
                    'linkOne',
                    'linkTwo',
                ],
            ],
            'Links' => [
                'fields' => [
                    'link',
                ],
            ],
            'Resources' => [
                'fields' => [
                    'link',
                ],
            ],
        ],
    ],
];
