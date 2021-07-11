<?php
declare(strict_types=1);

namespace Avolle\Deadlinks\Test\TestCase\Deadlinks;

use Avolle\Deadlinks\Deadlinks\UrlScanner;
use Cake\TestSuite\TestCase;

/**
 * Class UrlScannerTest
 */
class UrlScannerTest extends TestCase
{
    /**
     * Test isAlive method
     *
     * @return void
     * @uses \Avolle\Deadlinks\Deadlinks\UrlScanner::isAlive()
     */
    public function testScanLinkAlive()
    {
        $scanner = new UrlScanner();

        $this->assertTrue($scanner->isAlive('https://google.com'));
        $this->assertTrue($scanner->isAlive('https://google.co')); // A redirected Google Link
        $this->assertFalse($scanner->isAlive('https://absolute-gibberish-that-will-never-be-a-link-erwhirhww.com'));
    }

    /**
     * Test isDead method
     *
     * @return void
     * @uses \Avolle\Deadlinks\Deadlinks\UrlScanner::isDead()
     */
    public function testScanLinkDead()
    {
        $scanner = new UrlScanner();

        $this->assertTrue($scanner->isDead('https://absolute-gibberish-that-will-never-be-a-link-erwhirhww.com'));
        $this->assertFalse($scanner->isDead('https://google.com'));
    }
}
