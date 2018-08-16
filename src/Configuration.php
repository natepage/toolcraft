<?php
declare(strict_types=1);

namespace NatePage\ToolCraft;

use NatePage\ToolCraft\Exceptions\InvalidConfigurationOptionException;
use NatePage\ToolCraft\Interfaces\ConfigurationInterface;
use NatePage\ToolCraft\Interfaces\ConfigurationOptionInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * @var mixed[]|null
     */
    private $cache;

    /**
     * @var mixed[]
     */
    private $config = [];

    /**
     * @var \NatePage\ToolCraft\Interfaces\ConfigurationOptionInterface[]
     */
    private $options = [];

    /**
     * @var mixed[]
     */
    private $override;

    /**
     * Config constructor.
     *
     * @param mixed[]|null $override
     */
    public function __construct(?array $override = null)
    {
        $this->override = $override ?? [];
    }

    /**
     * {@inheritdoc}
     */
    public function addOption(ConfigurationOptionInterface $option, ?string $tool = null): ConfigurationInterface
    {
        $index = $tool ?? 0;

        if (isset($this->options[$index]) === false) {
            $this->options[$index] = [];
        }

        $this->options[$index][] = $option;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addOptions(array $options, ?string $tool = null): ConfigurationInterface
    {
        foreach ($options as $option) {
            $this->addOption($option, $tool);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function dump(): array
    {
        return $this->getCache();
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $option)
    {
        $this->optionExists($option);

        return $this->getCache()[$option];
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * {@inheritdoc}
     */
    public function merge(array $config): ConfigurationInterface
    {
        $this->config = \array_merge($this->config, $config);

        return $this->invalidateCache();
    }

    /**
     * {@inheritdoc}
     */
    public function set(string $option, $value): ConfigurationInterface
    {
        $this->optionExists($option);

        $this->config[$option] = $value;

        return $this->invalidateCache();
    }

    /**
     * Build cache array and return it.
     *
     * @return mixed[]
     */
    private function buildCache(): array
    {
        $cache = [];

        /**
         * @var int|string $tool
         * @var \NatePage\ToolCraft\Interfaces\ConfigurationOptionInterface $option
         */
        foreach ($this->options as $tool => $options) {
            foreach ($options as $option) {
                $key = \is_int($tool) ? $option->getName() : \sprintf('%s.%s', $tool, $option->getName());

                if (\is_bool($option->getDefault())) {
                    $cache[$key] = $this->getBoolValue($key);

                    continue;
                }

                // If config is default, then try to use override else fallback to default
                if (($this->config[$key] ?? null) === $option->getDefault()) {
                    $cache[$key] = $this->override[$key] ?? $option->getDefault();

                    continue;
                }

                $cache[$key] = $this->config[$key] ?? $option->getDefault();
            }
        }

        \ksort($cache);

        return $cache;
    }

    /**
     * Get bool value for given config key.
     *
     * @param string $key
     *
     * @return bool
     */
    private function getBoolValue(string $key): bool
    {
        return \array_key_exists($key, $this->config)
            && $this->config[$key] !== false
            && $this->config[$key] !== 'false';
    }

    /**
     * Get cache.
     *
     * @return mixed
     */
    private function getCache(): array
    {
        if ($this->cache !== null) {
            return $this->cache;
        }

        return $this->cache = $this->buildCache();
    }

    /**
     * Invalidate cache.
     *
     * @return self
     */
    private function invalidateCache(): self
    {
        $this->cache = null;

        return $this;
    }

    /**
     * If given option doesn't exist, throw exception.
     *
     * @param string $option
     *
     * @throws \NatePage\ToolCraft\Exceptions\InvalidConfigurationOptionException
     */
    private function optionExists(string $option): void
    {
        if (\array_key_exists($option, $this->getCache()) === false) {
            throw new InvalidConfigurationOptionException(\sprintf('Configuration option %s does not exist', $option));
        }
    }
}
