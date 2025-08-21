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

        // Se o test_command especificar --env=..., exija a existência do arquivo .env.<env>
        $testCommand = $this->config->get('test_command');
        if (is_string($testCommand) && preg_match('/--env=([\w\.-]+)/', $testCommand, $m)) {
            $envFromCmd = strtolower($m[1]);
            $expectedFile = '.env.' . $envFromCmd;
            if (!file_exists($expectedFile)) {
                echo "[ERRO] Arquivo de ambiente esperado não encontrado: {$expectedFile}. Configure-o antes do push.\n";
                echo "[ERRO] Testes falharam! Push abortado.\n";
                exit(1);
            }
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
        // 1) Variável de ambiente
        $env = getenv('APP_ENV');
        if ($env) {
            return strtolower($env);
        }

        // 2) Test command configurado (ex.: php artisan test --env=testing)
        $testCommand = $this->config->get('test_command');
        if (is_string($testCommand) && preg_match('/--env=([\w\.-]+)/', $testCommand, $m)) {
            return strtolower($m[1]);
        }

        // 3) Leitura de APP_ENV do .env padrão
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
