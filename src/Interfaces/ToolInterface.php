<?php
declare(strict_types=1);

namespace NatePage\ToolCraft\Interfaces;

interface ToolInterface
{
    /**
     * Get tool identifier.
     *
     * @return string
     */
    public function getId(): string;

    /**
     * Get tool name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Get tool runner.
     *
     * @return \NatePage\ToolCraft\Interfaces\RunnerInterface
     */
    public function getRunner(): RunnerInterface;
}
