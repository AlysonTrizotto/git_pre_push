<?php
use PHPUnit\Framework\TestCase;
use GitPrePush\GitPrePush;

class GitPrePushTest extends TestCase
{
    public function testRunSkipsTestsInProduction()
    {
        $hook = new GitPrePush(environment: 'production');
        ob_start();
        $hook->run();
        $output = ob_get_clean();
        $this->assertStringContainsString('Pulando testes', $output);
    }

    public function testRunFailsWithoutPHP()
    {
        $hook = $this->getMockBuilder(GitPrePush::class)
            ->setMethods(['phpAvailable'])
            ->getMock();
        $hook->method('phpAvailable')->willReturn(false);
        ob_start();
        $hook->run();
        $output = ob_get_clean();
        $this->assertStringContainsString('PHP não encontrado', $output);
    }

    public function testRunFailsWithoutTestCommand()
    {
        $hook = $this->getMockBuilder(GitPrePush::class)
            ->setMethods(['phpAvailable', 'testCommandAvailable'])
            ->getMock();
        $hook->method('phpAvailable')->willReturn(true);
        $hook->method('testCommandAvailable')->willReturn(false);
        ob_start();
        $hook->run();
        $output = ob_get_clean();
        $this->assertStringContainsString('Abortando', $output);
    }

    public function testRunPassesWhenTestsSucceed()
    {
        $hook = $this->getMockBuilder(GitPrePush::class)
            ->setMethods(['phpAvailable', 'testCommandAvailable', 'runTestCommand'])
            ->getMock();
        $hook->method('phpAvailable')->willReturn(true);
        $hook->method('testCommandAvailable')->willReturn(true);
        $hook->method('runTestCommand')->willReturn(0);
        ob_start();
        $hook->run();
        $output = ob_get_clean();
        $this->assertStringContainsString('Todos os testes passaram', $output);
    }

    public function testRunFailsWhenTestsFail()
    {
        $hook = $this->getMockBuilder(GitPrePush::class)
            ->setMethods(['phpAvailable', 'testCommandAvailable', 'runTestCommand'])
            ->getMock();
        $hook->method('phpAvailable')->willReturn(true);
        $hook->method('testCommandAvailable')->willReturn(true);
        $hook->method('runTestCommand')->willReturn(1);
        ob_start();
        $hook->run();
        $output = ob_get_clean();
        $this->assertStringContainsString('Testes falharam', $output);
    }
}
