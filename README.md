# Deadlinks CakePHP Plugin

[![Deadlinks CI](https://github.com/mentisy/cakephp-deadlinks/actions/workflows/ci.yml/badge.svg)](https://github.com/mentisy/cakephp-deadlinks/actions/workflows/ci.yml)

Scan links inside your database records to make sure they're not dead.

The links you once inserted into your database may not work in the future. This plugin
makes sure all your links are well and alive.
If any links are found to be dead, the results can be ouput through various methods.

Output methods:
* Terminal
* Log file
* Mail

## Version map:
| Plugin | Branch | Cake | PHP          |
|--------|--------|------|--------------|
| 2.x    | main   | 5.x  | ^8.1         |
| 1.x    | 1.x    | 4.x  | ^7.4 \| ^8.0 |

## Installation
`composer require avolle/cakephp-deadlinks`

## Usage

Add a config file in your app config folder, describing which tables and fields to scan.
Optionally you can insert an email recipient to receive results when selecting the mail ouput method.

Example config file (`/config/deadlinks.php`):
This config file will scan the following tables and fields:
* Files
    * linkOne
    * linkTwo
* Links
    * link
* Resources
    * link

```php
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
```

Add the plugin in your `src/Application.php` file. Put it inside the bootstrapCli method
```php
protected function bootstrapCli(): void
{
    $this->addPlugin('Avolle/Deadlinks');
}
```

To run the scanner, execute this command in your terminal
* Output in terminal: `bin\cake scan -t`
* Output to log file: `bin\cake scan -l`
* Output to an email: `bin\cake scan -m`

You can also ouput several methods. For both terminal and log file, run `bin\cake scan -t -l`.

For help, run `bin\cake scan -h`
