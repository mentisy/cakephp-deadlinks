<?php
declare(strict_types=1);

namespace Avolle\Deadlinks\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ResourcesFixture
 */
class ResourcesFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'name' => 'A Dead Link',
                'link' => 'https://a-dead-link-here.no',
            ],
            [
                'id' => 2,
                'name' => 'Google',
                'link' => 'https://google.com',
            ],
            [
                'id' => 3,
                'name' => 'Google Redirected',
                'link' => 'https://google.co',
            ],
            [
                'id' => 4,
                'name' => 'Empty url, should not be scanned',
                'link' => '',
            ],
        ];
        parent::init();
    }
}
