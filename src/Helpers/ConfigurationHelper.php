<?php
declare(strict_types=1);

namespace NatePage\ToolCraft\Helpers;

use NatePage\ToolCraft\Interfaces\ConfigurationInterface;
use NatePage\ToolCraft\Interfaces\Runners\RunnerInterface;
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
     * @return self
     */
    public function addCommandOptions(Command $command): self
    {
        // Add config options to input options
        foreach ($this->configuration->getOptions() as $prefix => $options) {
            /** @var \NatePage\ToolCraft\Interfaces\ConfigurationOptionInterface $option */
            foreach ($options as $option) {
                if ($option->isExposed() === false) {
                    continue;
                }

                $key = \is_int($prefix) ? $option->getName() : \sprintf('%s.%s', $prefix, $option->getName());

                $command->addOption(
                    $key,
                    null,
                    InputOption::VALUE_OPTIONAL,
                    $option->getDescription(),
                    $option->getDefault()
                );
            }
        }

        return $this;
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
     * Pass configuration to given runner if it is aware of the configuration.
     *
     * @param \NatePage\ToolCraft\Interfaces\Runners\RunnerInterface $runner
     *
     * @return \NatePage\ToolCraft\Helpers\ConfigurationHelper
     */
    public function handleRunnerOptions(RunnerInterface $runner): self
    {
        if (($runner instanceof ConfigurationInterface) === false) {
            return $this;
        }

        /** @var \NatePage\ToolCraft\Interfaces\ConfigurationAwareInterface $runner */
        $runner->setConfiguration($this->configuration);

        return $this;
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
