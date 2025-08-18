<?php
namespace GitPrePush\Listener;

use GitPrePush\Service\TestService;
use GitPrePush\Event\HookEvent;

class PrePushListener implements HookListenerInterface
{
    private TestService $testService;

    public function __construct(TestService $testService)
    {
        $this->testService = $testService;
    }

    public function handle(HookEvent $event): void
    {
        echo "[INFO] Executando testes antes do push...\n";
        if (!$this->testService->runTests()) {
            echo "[ERRO] Testes falharam! Push abortado.\n";
            exit(1);
        }
        echo "[INFO] Todos os testes passaram. Prosseguindo com o push.\n";
    }
}
