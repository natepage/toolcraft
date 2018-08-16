<?php
declare(strict_types=1);

namespace NatePage\ToolCraft\Helpers;

use NatePage\ToolCraft\ConfigurationOption;
use NatePage\ToolCraft\Interfaces\ConfigurationAwareInterface;
use NatePage\ToolCraft\Interfaces\ConfigurationInterface;
use NatePage\ToolCraft\Interfaces\ToolCollectionInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;

class ConfigurationHelper
{
    /**
     * @var \NatePage\ToolCraft\Interfaces\ConfigurationInterface
     */
    private $configuration;

    /**
     * ConfigurationHelper constructor.
     *
     * @param \NatePage\ToolCraft\Interfaces\ConfigurationInterface $configuration
     */
    public function __construct(ConfigurationInterface $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * Add input options to given command based on configuration options.
     *
     * @param \Symfony\Component\Console\Command\Command $command
     *
     * @return void
     */
    public function addCommandOptions(Command $command): void
    {
        // Add config options to input options
        foreach ($this->configuration->getOptions() as $tool => $options) {
            /** @var \NatePage\ToolCraft\Interfaces\ConfigurationOptionInterface $option */
            foreach ($options as $option) {
                if ($option->isExposed() === false) {
                    continue;
                }

                $key = \is_int($tool) ? $option->getName() : \sprintf('%s.%s', $tool, $option->getName());

                $command->addOption(
                    $key,
                    null,
                    InputOption::VALUE_OPTIONAL,
                    $option->getDescription(),
                    $option->getDefault()
                );
            }
        }
    }

    /**
     * Get dump of the configuration.
     *
     * @return mixed[]
     */
    public function dump(): array
    {
        return $this->configuration->dump();
    }

    /**
     * Get option value from configuration.
     *
     * @param string $option
     *
     * @return mixed
     *
     * @throws \NatePage\ToolCraft\Exceptions\InvalidConfigurationOptionException If option doesn't exist
     */
    public function get(string $option)
    {
        return $this->configuration->get($option);
    }

    /**
     * Get tool ids.
     *
     * @return string[]
     */
    public function getToolsId(): array
    {
        $ids = [];

        foreach ($this->configuration->getOptions() as $toolId => $options) {
            if (\is_int($toolId)) {
                continue;
            }

            $ids[] = $toolId;
        }

        return $ids;
    }

    /**
     * Pass configuration to tools when aware of it.
     *
     * @param \NatePage\ToolCraft\Interfaces\ToolCollectionInterface $tools
     *
     * @return self
     */
    public function registerTools(ToolCollectionInterface $tools): self
    {
        if ($tools->isEmpty()) {
            return $this;
        }

        foreach ($tools->all() as $tool) {
            // Add enabled option for each tool by default
            $this->configuration->addOption(
                new ConfigurationOption('enabled', true, \sprintf('Enable %s tool', $tool->getName())),
                $tool->getId()
            );

            if (($tool instanceof ConfigurationAwareInterface) === false) {
                continue;
            }

            /** @var \NatePage\ToolCraft\Interfaces\ConfigurationAwareInterface $tool */
            $tool->setConfiguration($this->configuration);
        }

        return $this;
    }

    /**
     * Enable or disable tools based on config.
     *
     * @return self
     *
     * @throws \NatePage\ToolCraft\Exceptions\InvalidConfigurationOptionException If option doesn't exist
     */
    public function updateToolsState(): self
    {
        $only = $this->configuration->get('only');

        if ($only === null || empty($only)) {
            return $this;
        }

        $only = \explode(',', $only);

        foreach ($this->getToolsId() as $toolId) {
            $this->configuration->set(\sprintf('%s.enabled', $toolId), \in_array($toolId, $only, true));
        }

        return $this;
    }

    /**
     * Check if given tool is enabled.
     *
     * @param string $toolId
     *
     * @return bool
     *
     * @throws \NatePage\ToolCraft\Exceptions\InvalidConfigurationOptionException If option doesn't exist
     */
    public function isToolEnabled(string $toolId): bool
    {
        return (bool)$this->configuration->get(\sprintf('%s.enabled', $toolId));
    }

    /**
     * Merge config with given one.
     *
     * @param mixed[] $config
     *
     * @return self
     */
    public function merge(array $config): self
    {
        $this->configuration->merge($config);

        return $this;
    }
}
