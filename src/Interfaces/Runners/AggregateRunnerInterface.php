<?php
declare(strict_types=1);

namespace NatePage\ToolCraft\Interfaces\Runners;

use NatePage\ToolCraft\Interfaces\RunnerCollectionInterface;

interface AggregateRunnerInterface extends RunnerInterface
{
    /**
     * Set runners.
     *
     * @param \NatePage\ToolCraft\Interfaces\RunnerCollectionInterface $runners
     *
     * @return \NatePage\ToolCraft\Interfaces\Runners\AggregateRunnerInterface
     */
    public function setRunners(RunnerCollectionInterface $runners): self;
}
