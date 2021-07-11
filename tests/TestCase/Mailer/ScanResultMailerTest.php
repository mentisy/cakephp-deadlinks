<?php
declare(strict_types=1);

namespace Mailer;

use Avolle\Deadlinks\Mailer\ScanResultMailer;
use Avolle\Deadlinks\Test\FakeDataTrait;
use Cake\Core\Configure;
use Cake\TestSuite\EmailTrait;
use PHPUnit\Framework\TestCase;

class ScanResultMailerTest extends TestCase
{
    use FakeDataTrait;
    use EmailTrait;

    /**
     * Scan Result Mailer
     *
     * @var \Avolle\Deadlinks\Mailer\ScanResultMailer
     */
    protected ScanResultMailer $ScanResultMailer;

    /**
     * set up method
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->ScanResultMailer = new ScanResultMailer();
        Configure::load('deadlinks');
        parent::setUp();
    }

    /**
     * Test sendResult method
     *
     * @return void
     */
    public function testSendResult(): void
    {
        $result = $this->fakeData();
        $this->ScanResultMailer->sendResult($result);
        $this->assertSame(['cakephp-plugins@avolle.com' => 'cakephp-plugins@avolle.com'], $this->ScanResultMailer->getTo());
        $this->assertSame('Dead Links Report - 2021-07-10', $this->ScanResultMailer->getSubject());
        $this->assertSame('text', $this->ScanResultMailer->getEmailFormat());
        $this->assertArrayHasKey('result', $this->ScanResultMailer->viewBuilder()->getVars());
        $this->assertArrayHasKey('Files', $this->ScanResultMailer->viewBuilder()->getVar('result'));
        $this->assertArrayHasKey('Links', $this->ScanResultMailer->viewBuilder()->getVar('result'));
        $this->assertArrayHasKey('Resources', $this->ScanResultMailer->viewBuilder()->getVar('result'));
    }
}
