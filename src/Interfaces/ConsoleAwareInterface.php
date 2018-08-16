<?php
declare(strict_types=1);

namespace NatePage\ToolCraft\Interfaces;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface ConsoleAwareInterface
{
    /**
     * Set input.
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return void
     */
    public function setInput(InputInterface $input): void;

    /**
     * Set output.
     *
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    public function setOutput(OutputInterface $output): void;
}
