<?php
declare(strict_types=1);

namespace NatePage\ToolCraft\Traits;

use NatePage\ToolCraft\Exceptions\RequiredPropertyMissingException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

trait ConsoleAwareTrait
{
    /**
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    private $input;

    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    private $output;

    /**
     * Set console input.
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return void
     */
    public function setInput(InputInterface $input): void
    {
        $this->input = $input;
    }

    /**
     * Set console output.
     *
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    public function setOutput(OutputInterface $output): void
    {
        $this->output = $output;
    }

    /**
     * Get input.
     *
     * @return \Symfony\Component\Console\Input\InputInterface
     *
     * @throws \NatePage\ToolCraft\Exceptions\RequiredPropertyMissingException If $input not set
     */
    protected function getInput(): InputInterface
    {
        if ($this->input !== null) {
            return $this->input;
        }

        throw new RequiredPropertyMissingException(\sprintf(
            '%s $input on %s must be set',
            InputInterface::class,
            \get_class($this)
        ));
    }

    /**
     * Get output.
     *
     * @return \Symfony\Component\Console\Output\OutputInterface
     *
     * @throws \NatePage\ToolCraft\Exceptions\RequiredPropertyMissingException If $output not set
     */
    protected function getOutput(): OutputInterface
    {
        if ($this->output !== null) {
            return $this->output;
        }

        throw new RequiredPropertyMissingException(\sprintf(
            '%s $output on %s must be set',
            OutputInterface::class,
            \get_class($this)
        ));
    }
}
