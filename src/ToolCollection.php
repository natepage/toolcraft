<?php
declare(strict_types=1);

namespace NatePage\ToolCraft;

use NatePage\ToolCraft\Interfaces\ToolCollectionInterface;
use NatePage\ToolCraft\Interfaces\ToolInterface;

class ToolCollection implements ToolCollectionInterface
{
    /**
     * @var \NatePage\ToolCraft\Interfaces\ToolInterface[]
     */
    private $tools = [];

    /**
     * ToolCollection constructor.
     *
     * @param \NatePage\ToolCraft\Interfaces\ToolInterface[]|null $tools
     */
    public function __construct(?array $tools = null)
    {
        $this->addTools($tools ?? []);
    }

    /**
     * Add tool to collection.
     *
     * @param \NatePage\ToolCraft\Interfaces\ToolInterface $tool
     * @param int|null $priority
     *
     * @return \NatePage\ToolCraft\Interfaces\ToolCollectionInterface
     */
    public function addTool(ToolInterface $tool, ?int $priority = null): ToolCollectionInterface
    {
        $priority = $priority ?? 0;

        if (isset($this->tools[$priority]) === false) {
            $this->tools[$priority] = [];
        }

        $this->tools[$priority][] = $tool;

        return $this;
    }

    /**
     * Add multiple tools to collection with the same priority.
     *
     * @param \NatePage\ToolCraft\Interfaces\ToolInterface[] $tools
     * @param int|null $priority
     *
     * @return \NatePage\ToolCraft\Interfaces\ToolCollectionInterface
     */
    public function addTools(array $tools, ?int $priority = null): ToolCollectionInterface
    {
        foreach ($tools as $tool) {
            $this->addTool($tool, $priority);
        }

        return $this;
    }

    /**
     * Get all tools sorted by priority.
     *
     * @return \NatePage\ToolCraft\Interfaces\ToolInterface[]
     */
    public function all(): array
    {
        // If not tools, no need to sort and merge
        if ($this->isEmpty()) {
            return $this->tools;
        }

        \krsort($this->tools);

        return \array_merge(...$this->tools);
    }

    /**
     * Check if collection is empty.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->tools);
    }
}
