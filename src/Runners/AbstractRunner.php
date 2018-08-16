<?php
declare(strict_types=1);

namespace NatePage\ToolCraft\Runners;

use NatePage\ToolCraft\Exceptions\RequireRunnerToBeStartedException;
use NatePage\ToolCraft\Interfaces\ConsoleAwareInterface;
use NatePage\ToolCraft\Interfaces\RunnerInterface;
use NatePage\ToolCraft\Traits\ConsoleAwareTrait;

abstract class AbstractRunner implements ConsoleAwareInterface, RunnerInterface
{
    use ConsoleAwareTrait;

    /**
     * @var bool
     */
    private $running = false;

    /**
     * @var bool
     */
    private $successful = true;

    /**
     * Check if running.
     *
     * @return bool
     */
    public function isRunning(): bool
    {
        return $this->running;
    }

    /**
     * Check if successful.
     *
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return $this->successful;
    }

    /**
     * Start runner instance.
     *
     * @return \NatePage\ToolCraft\Interfaces\RunnerInterface
     */
    public function start(): RunnerInterface
    {
        $this->startRunning();
        $this->doStart();

        return $this;
    }

    /**
     * Wait for runner to finish and return exit code.
     *
     * @return int
     *
     * @throws \NatePage\ToolCraft\Exceptions\RequireRunnerToBeStartedException
     */
    public function wait(): int
    {
        $this->requireRunnerToBeStarted(__FUNCTION__);
        $this->stopRunning();

        return $this->isSuccessful() ? self::EXIT_CODE_SUCCESS : self::EXIT_CODE_ERROR;
    }

    /**
     * Execute runner specific logic.
     *
     * @return void
     */
    abstract protected function doStart(): void;

    /**
     * Check if runner is started.
     *
     * @return bool
     */
    protected function isStarted(): bool
    {
        return $this->running;
    }

    /**
     * Ensures the runner is running or terminated, throws a LogicException if the process has a not started.
     *
     * @param string $function
     *
     * @return void
     *
     * @throws \NatePage\ToolCraft\Exceptions\RequireRunnerToBeStartedException
     */
    protected function requireRunnerToBeStarted(string $function): void
    {
        if ($this->isStarted()) {
            return;
        }

        throw new RequireRunnerToBeStartedException(\sprintf('Runner must be started before calling %s.', $function));
    }

    /**
     * Start current runner.
     *
     * @return void
     */
    protected function startRunning(): void
    {
        $this->running = true;
    }

    /**
     * Stop current runner.
     *
     * @return void
     */
    protected function stopRunning(): void
    {
        $this->running = false;
    }

    /**
     * Update runner status, is successful or not.
     *
     * @param bool $status
     *
     * @return void
     */
    protected function updateStatus(bool $status): void
    {
        $this->successful = $status;
    }
}