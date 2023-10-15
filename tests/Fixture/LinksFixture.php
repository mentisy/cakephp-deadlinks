<?php
declare(strict_types=1);

namespace Avolle\Deadlinks\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * LinksFixture
 */
class LinksFixture extends TestFixture
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
                'name' => 'An Alive Link',
                'link' => 'https://google.no',
            ],
            [
                'id' => 2,
                'name' => 'Null url, should not be scanned',
                'link' => null,
            ],
        ];
        parent::init();
    }
}
