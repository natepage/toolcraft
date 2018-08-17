<?php
declare(strict_types=1);

namespace NatePage\ToolCraft\Runners;

use NatePage\ToolCraft\Helpers\ConfigurationHelper;
use NatePage\ToolCraft\Helpers\OutputHelper;
use NatePage\ToolCraft\Interfaces\ConfigurationAwareInterface;
use NatePage\ToolCraft\Interfaces\ConfigurationInterface;
use NatePage\ToolCraft\Interfaces\ConsoleAwareInterface;
use NatePage\ToolCraft\Interfaces\RunnerCollectionInterface;
use NatePage\ToolCraft\Interfaces\Runners\AggregateRunnerInterface;
use NatePage\ToolCraft\Interfaces\Runners\RunnerInterface;

abstract class AbstractAggregateRunner extends AbstractRunner implements AggregateRunnerInterface, ConfigurationAwareInterface
{
    /**
     * @var \NatePage\ToolCraft\Interfaces\RunnerCollectionInterface
     */
    protected $runners;

    /**
     * Close runner instance.
     *
     * @return void
     */
    public function close(): void
    {
        foreach ($this->runners->all() as $runner) {
            $runner->close();
        }
    }

    /**
     * Set configuration.
     *
     * @param \NatePage\ToolCraft\Interfaces\ConfigurationInterface $configuration
     *
     * @return void
     */
    public function setConfiguration(ConfigurationInterface $configuration): void
    {
        // If no runners, skip
        if ($this->runners === null) {
            return;
        }

        foreach ($this->runners->all() as $runner) {
            (new ConfigurationHelper($configuration))->handleRunnerOptions($runner);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setRunners(RunnerCollectionInterface $runners): AggregateRunnerInterface
    {
        $this->runners = $runners;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \NatePage\ToolCraft\Exceptions\RequiredPropertyMissingException
     */
    public function start(): RunnerInterface
    {
        // If no runners to run, warning and skip
        if ($this->runners === null) {
            (new OutputHelper($this->getInput(), $this->getOutput()))->warning(sprintf(
                'Runner %s does not have any runners to run, skip',
                $this->getId()
            ));

            return $this;
        }

        // Take care of console aware runners
        foreach ($this->runners->all() as $runner) {
            if (($runner instanceof ConsoleAwareInterface) === false) {
                continue;
            }

            $this->handleConsoleAwareRunner($runner);
        }

        return parent::start();
    }

    /**
     * Make sure to take care of runners aware of the console.
     *
     * @param \NatePage\ToolCraft\Interfaces\ConsoleAwareInterface $runner
     *
     * @return void
     */
    abstract protected function handleConsoleAwareRunner(ConsoleAwareInterface $runner): void;
}
