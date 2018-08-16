<?php
declare(strict_types=1);

namespace NatePage\ToolCraft\Runners;

use Symfony\Component\Process\Process;

class ProcessRunner extends AbstractRunner
{
    /**
     * @var \Symfony\Component\Process\Process
     */
    private $process;

    /**
     * ProcessRunner constructor.
     *
     * @param \Symfony\Component\Process\Process $process
     */
    public function __construct(Process $process)
    {
        $this->process = $process;
    }

    /**
     * Close runner instance.
     *
     * @return void
     *
     * @throws \NatePage\ToolCraft\Exceptions\RequiredPropertyMissingException
     */
    public function close(): void
    {
        $outputProcess = \trim($this->process->getOutput());
        $successful = '// Successful';

        if ($this->getOutput()->isVerbose()) {
            if (empty($outputProcess)) {
                $this->getOutput()->writeln($successful);
            }

            return;
        }

        $this->getOutput()->writeln(
            $this->isSuccessful() ? $successful : $outputProcess
        );
    }

    /**
     * {@inheritdoc}
     */
    public function isRunning(): bool
    {
        $this->running = $this->process->isRunning();

        return parent::isRunning();
    }

    /**
     * {@inheritdoc}
     */
    public function wait(): int
    {
        $this->process->wait();
        $this->successful = $this->process->isSuccessful();

        return parent::wait();
    }

    /**
     * Execute runner specific logic.
     *
     * @return void
     */
    protected function doStart(): void
    {
        $this->process->start(function (
            /** @noinspection PhpUnusedParameterInspection */
            $type,
            $buffer
        ): void {
            if ($this->getOutput()->isVerbose() === false) {
                return;
            }

            $this->getOutput()->write($buffer);
        });
    }
}
