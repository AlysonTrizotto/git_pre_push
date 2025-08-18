<?php
namespace GitPrePush\Event;

class HookEvent
{
    private string $type;
    private array $context;

    public function __construct(string $type, array $context = [])
    {
        $this->type = $type;
        $this->context = $context;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getContext(): array
    {
        return $this->context;
    }
}
