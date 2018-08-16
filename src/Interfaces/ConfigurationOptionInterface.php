<?php
declare(strict_types=1);

namespace NatePage\ToolCraft\Interfaces;

interface ConfigurationOptionInterface
{
    /**
     * Get option default value.
     *
     * @return mixed
     */
    public function getDefault();

    /**
     * Get option description.
     *
     * @return string
     */
    public function getDescription(): string;

    /**
     * Get option name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Check if option is exposed as runtime option.
     *
     * @return bool
     */
    public function isExposed(): bool;
}
