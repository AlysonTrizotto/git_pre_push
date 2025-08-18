<?php
namespace GitPrePush\Config;

class Config
{
    private array $settings;

    public function __construct(string $configFile = 'git-pre-push.php')
    {
        $this->settings = file_exists($configFile) ? include $configFile : [];
    }

    public function get(string $key, $default = null)
    {
        return $this->settings[$key] ?? $default;
    }
}
