<?php
use PHPUnit\Framework\TestCase;
use GitPrePush\GitPrePush;
use GitPrePush\Listener\HookListenerInterface;
use GitPrePush\Event\HookEvent;
use GitPrePush\Service\TestService;
use GitPrePush\Config\Config;

class GitPrePushTest extends TestCase
{
    protected function tearDown(): void
    {
        // Clean APP_ENV after tests
        putenv('APP_ENV');
    }

    public function testRunSkipsTestsInProduction(): void
    {
        putenv('APP_ENV=production');
        $hook = new GitPrePush();
        ob_start();
        $hook->run();
        $output = ob_get_clean();
        $this->assertStringContainsString('Pulando testes', $output);
    }

    public function testDispatchesRegisteredListener(): void
    {
        putenv('APP_ENV=development');

        $hook = new GitPrePush();

        $listener = $this->createMock(HookListenerInterface::class);
        $listener->expects($this->once())
            ->method('handle')
            ->with($this->isInstanceOf(HookEvent::class));

        $hook->addListener($listener);

        ob_start();
        $hook->run();
        ob_end_clean();
    }

    public function testTestServiceRunTestsSuccessAndFailure(): void
    {
        // Success case (exit 0)
        $configSuccess = new class extends Config {
            public function __construct() {}
            public function get(string $key, $default = null) { return 'php -r "exit(0);"'; }
        };
        $serviceSuccess = new TestService($configSuccess);
        $this->assertTrue($serviceSuccess->runTests());

        // Failure case (exit 1)
        $configFail = new class extends Config {
            public function __construct() {}
            public function get(string $key, $default = null) { return 'php -r "exit(1);"'; }
        };
        $serviceFail = new TestService($configFail);
        $this->assertFalse($serviceFail->runTests());
    }
}
