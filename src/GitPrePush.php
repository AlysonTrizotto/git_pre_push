<?php

namespace GitPrePush;

use GitPrePush\Config\Config;
use GitPrePush\Listener\HookListenerInterface;
use GitPrePush\Event\HookEvent;
use GitPrePush\Listener\PrePushListener;
use GitPrePush\Service\TestService;

class GitPrePush
{
    private Config $config;
    private array $listeners = [];

    public function __construct(?Config $config = null)
    {
        $this->config = $config ?? new Config();
    }

    public function addListener(HookListenerInterface $listener): void
    {
        $this->listeners[] = $listener;
    }

    public function run(): void
    {
        $env = $this->detectEnvironment();
        if ($env === 'production') {
            echo "[INFO] APP_ENV=production detectado. Pulando testes e permitindo push.\n";
            return;
        }

        // Auto-wire default listener if none was provided
        if (empty($this->listeners)) {
            $this->addListener(new PrePushListener(new TestService($this->config)));
        }

        $event = new HookEvent('pre-push', ['env' => $env]);
        foreach ($this->listeners as $listener) {
            $listener->handle($event);
        }
    }

    private function detectEnvironment(): string
    {
        $env = getenv('APP_ENV');
        if ($env) {
            return strtolower($env);
        }
        if (file_exists('.env')) {
            $lines = file('.env');
            foreach ($lines as $line) {
                if (preg_match('/^APP_ENV=(.*)/', trim($line), $matches)) {
                    return strtolower(trim($matches[1], '"\r'));
                }
            }
        }
        return '';
    }
}
