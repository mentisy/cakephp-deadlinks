<?php
declare(strict_types=1);

namespace Avolle\Deadlinks\Mailer;

use Cake\Core\Configure;
use Cake\I18n\Date;
use Cake\Mailer\Mailer;

/**
 * SendReport mailer.
 */
class ScanResultMailer extends Mailer
{
    /**
     * Mailer's name.
     *
     * @var string
     */
    public static string $name = 'ScanResult';

    /**
     * ScanResultMailer constructor.
     *
     * @param array|null $config Mailer Config
     */
    public function __construct(?array $config = null)
    {
        parent::__construct($config);
        $this->setTransport('default');
    }

    /**
     * Send result from a Dead Links scan
     *
     * @param array $result Scan results
     * @return void
     */
    public function sendResult(array $result): void
    {
        $this
            ->setTo(Configure::readOrFail('Deadlinks.mailRecipient'))
            ->setSubject('Dead Links Report - ' . Date::now()->format('Y-m-d'))
            ->setEmailFormat('text')
            ->setViewVars(compact('result'))
            ->viewBuilder()
            ->setTemplate('Avolle/Deadlinks.send_result');
    }
}
