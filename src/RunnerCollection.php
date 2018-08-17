<?php
declare(strict_types=1);

namespace NatePage\ToolCraft\Runners;

use NatePage\ToolCraft\Interfaces\RunnerCollectionInterface;
use NatePage\ToolCraft\Interfaces\Runners\RunnerInterface;

class RunnerCollection implements RunnerCollectionInterface
{
    /**
     * @var \NatePage\ToolCraft\Interfaces\Runners\RunnerInterface[]
     */
    private $runners = [];

    /**
     * RunnerCollection constructor.
     *
     * @param \NatePage\ToolCraft\Interfaces\Runners\RunnerInterface[]|null $runners
     */
    public function __construct(?array $runners = null)
    {
        $this->addRunners($runners ?? []);
    }

    /**
     * Add runner to collection.
     *
     * @param \NatePage\ToolCraft\Interfaces\Runners\RunnerInterface $runner
     * @param int|null $priority
     *
     * @return \NatePage\ToolCraft\Interfaces\RunnerCollectionInterface
     */
    public function addRunner(RunnerInterface $runner, ?int $priority = null): RunnerCollectionInterface
    {
        $priority = $priority ?? 0;

        if (isset($this->runners[$priority]) === false) {
            $this->runners[$priority] = [];
        }

        $this->runners[$priority][] = $runner;

        return $this;
    }

    /**
     * Add multiple runners to collection with the same priority.
     *
     * @param \NatePage\ToolCraft\Interfaces\Runners\RunnerInterface[] $runners
     * @param int|null $priority
     *
     * @return \NatePage\ToolCraft\Interfaces\RunnerCollectionInterface
     */
    public function addRunners(array $runners, ?int $priority = null): RunnerCollectionInterface
    {
        foreach ($runners as $runner) {
            $this->addRunner($runner, $priority);
        }

        return $this;
    }

    /**
     * Get all runners sorted by priority.
     *
     * @return \NatePage\ToolCraft\Interfaces\Runners\RunnerInterface[]
     */
    public function all(): array
    {
        // If not runners, no need to sort and merge
        if ($this->isEmpty()) {
            return $this->runners;
        }

        \krsort($this->runners);

        return \array_merge(...$this->runners);
    }

    /**
     * Check if collection is empty.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->runners);
    }
}
