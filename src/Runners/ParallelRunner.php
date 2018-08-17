<?php
declare(strict_types=1);

namespace NatePage\ToolCraft\Runners;

use NatePage\ToolCraft\Helpers\OutputHelper;
use NatePage\ToolCraft\Interfaces\ConsoleAwareInterface;
use NatePage\ToolCraft\Outputs\ConsoleSectionOutput;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * This runner runs all the given runners in parallel, starts all the runners at once and finishes when the last
 * runner is done.
 */
class ParallelRunner extends AbstractAggregateRunner
{
    /**
     * @var \NatePage\ToolCraft\Interfaces\Runners\RunnerInterface[]
     */
    private $currentlyRunning = [];

    /**
     * {@inheritdoc}
     */
    public function wait(): int
    {
        while (\count($this->currentlyRunning)) {
            foreach ($this->currentlyRunning as $index => $runner) {
                // If runner still running, skip
                if ($runner->isRunning()) {
                    continue;
                }

                // Close runner
                $runner->close();

                // If at least one runner is not successful, then chain not successful
                if ($runner->isSuccessful() === false) {
                    $this->successful = false;
                }

                // Remove runner from the list of runners currently running
                unset($this->currentlyRunning[$index]);
            }
        }

        return parent::wait();
    }

    /**
     * Execute runner specific logic.
     *
     * @return void
     */
    protected function doStart(): void
    {
        foreach ($this->runners->all() as $runner) {
            // Start each runner and "cache" them
            $this->currentlyRunning[] = $runner->start();
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
        $runner->setInput($this->getInput());
        $runner->setOutput($this->getOutputForRunner());
    }

    /**
     * Get output for runner.
     *
     * @return \Symfony\Component\Console\Output\OutputInterface
     *
     * @throws \NatePage\ToolCraft\Exceptions\RequiredPropertyMissingException
     */
    private function getOutputForRunner(): OutputInterface
    {
        $output = $this->getOutput();

        if ($output instanceof ConsoleOutputInterface) {
            return new ConsoleSectionOutput($output->section());
        }

        $this->getOutputHelper()->warning(\sprintf(
            'Current output does not support sections, no guarantee about the result. 
                    Please prefer using %s for parallel runners.',
            ConsoleOutputInterface::class
        ));

        return $output;
    }

    /**
     * Get output helper.
     *
     * @return \NatePage\ToolCraft\Helpers\OutputHelper
     *
     * @throws \NatePage\ToolCraft\Exceptions\RequiredPropertyMissingException
     */
    private function getOutputHelper(): OutputHelper
    {
        return new OutputHelper($this->getInput(), $this->getOutput());
    }
}
