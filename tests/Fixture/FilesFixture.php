<?php
declare(strict_types=1);

namespace Avolle\Deadlinks\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * FilesFixture
 */
class FilesFixture extends TestFixture
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
                'name' => 'A Dead Link and an Alive Link',
                'linkOne' => 'https://google.com',
                'linkTwo' => 'https://a-dead-link-here.no',
            ],
            [
                'id' => 2,
                'name' => 'Two Dead Links',
                'linkOne' => 'https://a-dead-link-here.no',
                'linkTwo' => 'https://a-dead-link-here-too.no',
            ],
        ];
        parent::init();
    }
}
