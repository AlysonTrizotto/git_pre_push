<?php
namespace GitPrePush\Service;

use GitPrePush\Config\Config;

class TestService
{
    private Config $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function runTests(): bool
    {
        $cmd = $this->config->get('test_command', 'php artisan test');
        system($cmd, $exitCode);
        return $exitCode === 0;
    }
}
