<?php
declare(strict_types=1);

namespace NatePage\ToolCraft\Interfaces\Runners;

interface RunnerInterface
{
    public const EXIT_CODE_ERROR = 1;
    public const EXIT_CODE_SUCCESS = 0;
    public const STATUS_ERROR = false;
    public const STATUS_SUCCESS = true;

    /**
     * Close runner instance.
     *
     * @return void
     */
    public function close(): void;

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
     * Check if running.
     *
     * @return bool
     */
    public function isRunning(): bool;

    /**
     * Check if successful.
     *
     * @return bool
     */
    public function isSuccessful(): bool;

    /**
     * Start runner instance.
     *
     * @return self
     */
    public function start(): self;

    /**
     * Wait for runner to finish and return exit code.
     *
     * @return int
     */
    public function wait(): int;
}
