<?php
declare(strict_types=1);

namespace NatePage\ToolCraft\Interfaces;

use NatePage\ToolCraft\Interfaces\Runners\RunnerInterface;

interface RunnerCollectionInterface
{
    /**
     * Add runner to collection.
     *
     * @param \NatePage\ToolCraft\Interfaces\Runners\RunnerInterface $runner
     * @param int|null $priority
     *
     * @return \NatePage\ToolCraft\Interfaces\RunnerCollectionInterface
     */
    public function addRunner(RunnerInterface $runner, ?int $priority = null): self;

    /**
     * Add multiple runners to collection with the same priority.
     *
     * @param \NatePage\ToolCraft\Interfaces\Runners\RunnerInterface[] $runners
     * @param int|null $priority
     *
     * @return \NatePage\ToolCraft\Interfaces\RunnerCollectionInterface
     */
    public function addRunners(array $runners, ?int $priority = null): self;

    /**
     * Get all runners sorted by priority.
     *
     * @return \NatePage\ToolCraft\Interfaces\Runners\RunnerInterface[]
     */
    public function all(): array;

    /**
     * Check if collection is empty.
     *
     * @return bool
     */
    public function isEmpty(): bool;
}
