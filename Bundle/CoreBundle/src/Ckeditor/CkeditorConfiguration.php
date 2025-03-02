<?php

namespace Umbrella\CoreBundle\Ckeditor;

class CkeditorConfiguration
{
    public const EXAMPLE_CONFIG = [
        'toolbar' => [
            ['name' => 'clipboard', 'items' => ['Undo', 'Redo']],
        ],
        'uiColor' => '#FEFEFE',
    ];

    public const MINIMAL_CONFIG = [
        'toolbar' => [
            ['name' => 'clipboard', 'items' => ['Undo', 'Redo']],
            ['name' => 'styles', 'items' => ['Format']],
            ['name' => 'basicstyles', 'items' => ['Bold', 'Italic', 'Strike', '-', 'RemoveFormat']],
            ['name' => 'paragraph', 'items' => ['NumberedList', 'BulletedList']],
            ['name' => 'links', 'items' => ['Link', 'Unlink']],
        ],
        'uiColor' => '#FEFEFE',
    ];

    public const FULL_CONFIG = [
        'toolbar' => [
            ['name' => 'clipboard', 'items' => ['Undo', 'Redo']],
            ['name' => 'styles', 'items' => ['Format']],
            ['name' => 'basicstyles', 'items' => ['Bold', 'Italic', 'Strike', '-', 'RemoveFormat']],
            ['name' => 'paragraph', 'items' => ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote']],
            ['name' => 'links', 'items' => ['Link', 'Unlink']],
            ['name' => 'insert', 'items' => ['Image', 'oembed', 'Table']],
            ['name' => 'tools', 'items' => ['Maximize', 'Scayt', 'Source']],
        ],
        'uiColor' => '#FEFEFE',
    ];

    private ?string $asset = null;
    private array $defaultConfig = [];
    private array $configs = [];

    public function __construct(array $bundleConfig)
    {
        $this->resolveConfig($bundleConfig);
    }

    /**
     * Load default configs
     */
    private function resolveConfig(array $bundleConfig): void
    {
        $this->asset = $bundleConfig['asset'];

        $this->configs['minimal'] = self::MINIMAL_CONFIG;
        $this->configs['full'] = self::FULL_CONFIG;

        foreach ($bundleConfig['configs'] as $configName => $config) {
            if (!is_array($config)) {
                throw new \RuntimeException(sprintf('[Ckeditor] Invalid "umbrella_core.configs.%s" config. Ckeditor config must be an array of options. ', $configName));
            }

            $this->configs[$configName] = $config;
        }

        $defaultConfigName = $bundleConfig['default_config'];

        if (!isset($this->configs[$defaultConfigName])) {
            throw new \RuntimeException(sprintf('[Ckeditor] Invalid "umbrella_core.default_config". Config "%s" doesn\'t exist, available configs are %s. ', $defaultConfigName, implode(', ', array_keys($this->configs))));
        }

        $this->defaultConfig = $this->configs[$defaultConfigName];
    }

    public function getConfig(string $name): array
    {
        if (!isset($this->configs[$name])) {
            $configNames = implode(', ', array_keys($this->configs));
            throw new \UnexpectedValueException(sprintf('[Ckeditor] Config "%s" doesn\'t exist, config available are : %s', $name, $configNames));
        }

        return $this->configs[$name];
    }

    public function getDefaultConfig(): array
    {
        return $this->defaultConfig;
    }

    public function getAsset(): ?string
    {
        return $this->asset;
    }
}
