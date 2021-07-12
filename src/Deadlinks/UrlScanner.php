<?php
declare(strict_types=1);

namespace Avolle\Deadlinks\Deadlinks;

use Cake\Core\InstanceConfigTrait;

/**
 * Class UrlScanner
 */
class UrlScanner
{
    use InstanceConfigTrait;

    /**
     * Config
     *
     * - timeout - In milliseconds, this setting determines when to give up an URL scan. Defaults to 1000 ms
     *
     * @var int[]
     */
    protected array $_defaultConfig = [
        'timeout' => 1000, // milliseconds
    ];

    /**
     * UrlScanner constructor.
     *
     * @param array $config Configuration
     */
    public function __construct(array $config = [])
    {
        $this->setConfig($config);
    }

    /**
     * Scan an URL to see if it's an "OK" status
     *
     * @param string $url Url to scan
     * @return bool
     */
    public function isAlive(string $url): bool
    {
        $ch = $this->createCurlResource($url);
        curl_exec($ch);

        $isAlive = $this->httpOk($ch);
        curl_close($ch);

        return $isAlive;
    }

    /**
     * Scan an URL to see if it's not an "OK" status
     *
     * @param string $url Url to scan
     * @return bool
     */
    public function isDead(string $url): bool
    {
        return !$this->isAlive($url);
    }

    /**
     * Create a curl resource handler
     *
     * @param string $url Url to create resource for
     * @return false|resource
     */
    protected function createCurlResource(string $url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, $this->getConfig('timeout'));
        curl_setopt($ch, CONNECTION_TIMEOUT, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'User-Agent: Dead Links URL scanner',
        ]);

        return $ch;
    }

    /**
     * Check if the curl resource is "OK"
     *
     * @param resource $ch Curl resource handler
     * @return bool
     */
    protected function httpOk($ch): bool
    {
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        return $httpCode >= 200 && $httpCode < 300;
    }
}
