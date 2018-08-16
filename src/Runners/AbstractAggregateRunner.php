<?php
declare(strict_types=1);

namespace NatePage\ToolCraft\Runners;

use NatePage\ToolCraft\Interfaces\AggregateRunnerInterface;
use NatePage\ToolCraft\Interfaces\ConsoleAwareInterface;
use NatePage\ToolCraft\Interfaces\RunnerInterface;

abstract class AbstractAggregateRunner extends AbstractRunner implements AggregateRunnerInterface
{
    /**
     * @var \NatePage\ToolCraft\Interfaces\RunnerInterface[]
     */
    protected $runners;

    /**
     * AbstractAggregateRunner constructor.
     *
     * @param \NatePage\ToolCraft\Interfaces\RunnerInterface[]|null $runners
     */
    public function __construct(?array $runners = null)
    {
        $this->runners = $runners ?? [];
    }

    /**
     * {@inheritdoc}
     */
    public function addRunner(RunnerInterface $runner): AggregateRunnerInterface
    {
        $this->runners[] = $runner;

        return $this;
    }

    /**
     * Close runner instance.
     *
     * @return void
     */
    public function close(): void
    {
        foreach ($this->runners as $runner) {
            $runner->close();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setRunners(array $runners): AggregateRunnerInterface
    {
        foreach ($runners as $runner) {
            $this->addRunner($runner);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function start(): RunnerInterface
    {
        // Take care of console aware runners
        foreach ($this->runners as $runner) {
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
