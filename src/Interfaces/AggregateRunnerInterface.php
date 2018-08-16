<?php
declare(strict_types=1);

namespace NatePage\ToolCraft\Interfaces;

interface AggregateRunnerInterface extends RunnerInterface
{
    /**
     * Add runner.
     *
     * @param \NatePage\ToolCraft\Interfaces\RunnerInterface $runner
     *
     * @return \NatePage\ToolCraft\Interfaces\AggregateRunnerInterface
     */
    public function addRunner(RunnerInterface $runner): self;

    /**
     * Set runners.
     *
     * @param \NatePage\ToolCraft\Interfaces\RunnerInterface[] $runners
     *
     * @return \NatePage\ToolCraft\Interfaces\AggregateRunnerInterface
     */
    public function setRunners(array $runners): self;
}
