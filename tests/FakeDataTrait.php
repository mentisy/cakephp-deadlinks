<?php
declare(strict_types=1);

namespace Avolle\Deadlinks\Test;

use Avolle\Deadlinks\Deadlinks\DeadLink;
use Avolle\Deadlinks\Deadlinks\ResultSet;

/**
 * Trait FakeDataTrait
 */
trait FakeDataTrait
{
    /**
     * Generates fake data to use while developing, as to not scan real links.
     *
     * @return \Avolle\Deadlinks\Deadlinks\ResultSet[]
     */
    protected function fakeData(): array
    {
        $files = new ResultSet('Files');
        $files->add(new DeadLink('linkTwo', 'https://a-dead-link-here.no', 'id', 1));
        $files->add(new DeadLink('linkOne', 'https://a-dead-link-here.no', 'id', 2));
        $files->add(new DeadLink('linkTwo', 'https://a-dead-link-here-too.no', 'id', 2));

        $links = new ResultSet('Links');

        $resources = new ResultSet('Resources');
        $resources->add(new DeadLink('link', 'https://a-dead-link-here.no', 'id', 1));

        return [
            'Files' => $files,
            'Links' => $links,
            'Resources' => $resources,
        ];
    }
}
