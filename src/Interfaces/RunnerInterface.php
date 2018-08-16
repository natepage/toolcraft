<?php
declare(strict_types=1);

namespace NatePage\ToolCraft\Interfaces;

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
     * @return \NatePage\ToolCraft\Interfaces\RunnerInterface
     */
    public function start(): self;

    /**
     * Wait for runner to finish and return exit code.
     *
     * @return int
     */
    public function wait(): int;
}
