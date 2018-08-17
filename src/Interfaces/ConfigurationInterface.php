<?php
declare(strict_types=1);

namespace NatePage\ToolCraft\Interfaces;

interface ConfigurationInterface
{
    /**
     * Add configuration option.
     *
     * @param \NatePage\ToolCraft\Interfaces\ConfigurationOptionInterface $option
     * @param null|string $prefix
     *
     * @return \NatePage\ToolCraft\Interfaces\ConfigurationInterface
     */
    public function addOption(ConfigurationOptionInterface $option, ?string $prefix = null): self;

    /**
     * Add multiple configuration options.
     *
     * @param \NatePage\ToolCraft\Interfaces\ConfigurationOptionInterface[] $options
     * @param null|string $prefix
     *
     * @return \NatePage\ToolCraft\Interfaces\ConfigurationInterface
     */
    public function addOptions(array $options, ?string $prefix = null): self;

    /**
     * Get flat representation of config.
     *
     * @return mixed[]
     */
    public function dump(): array;

    /**
     * Get value for given option.
     *
     * @param string $option
     *
     * @return mixed
     *
     * @throws \NatePage\ToolCraft\Exceptions\InvalidConfigurationOptionException If given option doesn't exist
     */
    public function get(string $option);

    /**
     * Get configuration options.
     *
     * @return \NatePage\ToolCraft\Interfaces\ConfigurationOptionInterface[]
     */
    public function getOptions(): array;

    /**
     * Merge current config with given one.
     *
     * @param mixed[] $config
     *
     * @return \NatePage\ToolCraft\Interfaces\ConfigurationInterface
     */
    public function merge(array $config): self;

    /**
     * Set value for given option.
     *
     * @param string $option
     * @param $value
     *
     * @return \NatePage\ToolCraft\Interfaces\ConfigurationInterface
     *
     * @throws \NatePage\ToolCraft\Exceptions\InvalidConfigurationOptionException If given option doesn't exist
     */
    public function set(string $option, $value): self;
}
