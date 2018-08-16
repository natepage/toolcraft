<?php
declare(strict_types=1);

namespace NatePage\ToolCraft\Interfaces;

interface ToolCollectionInterface
{
    /**
     * Add tool to collection.
     *
     * @param \NatePage\ToolCraft\Interfaces\ToolInterface $tool
     * @param int|null $priority
     *
     * @return \NatePage\ToolCraft\Interfaces\ToolCollectionInterface
     */
    public function addTool(ToolInterface $tool, ?int $priority = null): self;

    /**
     * Add multiple tools to collection with the same priority.
     *
     * @param \NatePage\ToolCraft\Interfaces\ToolInterface[] $tools
     * @param int|null $priority
     *
     * @return \NatePage\ToolCraft\Interfaces\ToolCollectionInterface
     */
    public function addTools(array $tools, ?int $priority = null): self;

    /**
     * Get all tools sorted by priority.
     *
     * @return \NatePage\ToolCraft\Interfaces\ToolInterface[]
     */
    public function all(): array;

    /**
     * Check if collection is empty.
     *
     * @return bool
     */
    public function isEmpty(): bool;
}
