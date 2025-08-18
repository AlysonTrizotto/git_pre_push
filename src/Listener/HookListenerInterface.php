<?php
namespace GitPrePush\Listener;

use GitPrePush\Event\HookEvent;

interface HookListenerInterface
{
    public function handle(HookEvent $event): void;
}
