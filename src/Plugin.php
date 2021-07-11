<?php
declare(strict_types=1);

namespace Avolle\Deadlinks;

use Avolle\Deadlinks\Command\ScanCommand;
use Avolle\Deadlinks\Exception\MissingConfigException;
use Cake\Console\CommandCollection;
use Cake\Core\BasePlugin;
use Cake\Core\Configure;
use Cake\Core\Exception\CakeException;
use Cake\Log\Engine\FileLog;
use Cake\Log\Log;

/**
 * Plugin for CakePHP Deadlinks
 */
class Plugin extends BasePlugin
{
    /**
     * Plugin constructor.
     *
     * @param array $options Plugin options
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options);

        try {
            Configure::load('deadlinks');
        } catch (CakeException $ex) {
            throw new MissingConfigException(
                'Configuration key `Deadlinks` was not found. Please add a `deadlinks.php` '
                . 'file to your config folder.'
            );
        }

        // Add a logger config for outputting scan results into the log
        if (!Log::getConfig('deadlinks')) {
            Log::setConfig('deadlinks', [
                'className' => FileLog::class,
                'path' => LOGS,
                'file' => 'deadlinks',
                'url' => env('LOG_DEBUG_URL'),
                'scopes' => ['deadlinks'],
            ]);
        }
    }

    /**
     * Add scan command into the plugin commands directory
     *
     * @param \Cake\Console\CommandCollection $commands Commands
     * @return \Cake\Console\CommandCollection
     */
    public function console(CommandCollection $commands): CommandCollection
    {
        $commands->add('scan', ScanCommand::class);

        return $commands;
    }
}
