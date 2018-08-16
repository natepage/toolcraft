<?php
declare(strict_types=1);

namespace NatePage\ToolCraft\Runners;

use NatePage\ToolCraft\Interfaces\ConsoleAwareInterface;

/**
 * This runner runs all the given runners one by one, waits until the runner is finished before starting the next one.
 */
class ChainRunner extends AbstractAggregateRunner
{
    /**
     * Execute runner specific logic.
     *
     * @return void
     */
    protected function doStart(): void
    {
        foreach ($this->runners as $runner) {
            // Run each runners and wait for each of them
            $runner->start()->wait();
            $runner->close();

            // If at least one runner is not successful, then chain not successful
            if ($runner->isSuccessful() === false) {
                $this->updateStatus(self::STATUS_ERROR);
            }
        }
    }

    /**
     * Make sure to take care of runners aware of the console.
     *
     * @param \NatePage\ToolCraft\Interfaces\ConsoleAwareInterface $runner
     *
     * @return void
     *
     * @throws \NatePage\ToolCraft\Exceptions\RequiredPropertyMissingException
     */
    protected function handleConsoleAwareRunner(ConsoleAwareInterface $runner): void
    {
        // Nothing special with chained runners
        $runner->setInput($this->getInput());
        $runner->setOutput($this->getOutput());
    }
}
