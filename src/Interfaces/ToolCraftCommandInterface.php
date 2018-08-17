<?php
declare(strict_types=1);

namespace NatePage\ToolCraft\Interfaces;

use NatePage\ToolCraft\Interfaces\Runners\RunnerInterface;

interface ToolCraftCommandInterface
{
    /**
     * Set configuration.
     *
     * @param \NatePage\ToolCraft\Interfaces\ConfigurationInterface $configuration
     *
     * @return \NatePage\ToolCraft\Interfaces\ToolCraftCommandInterface
     */
    public function setConfiguration(ConfigurationInterface $configuration): self;

    /**
     * Set runner.
     *
     * @param \NatePage\ToolCraft\Interfaces\Runners\RunnerInterface $runner
     *
     * @return \NatePage\ToolCraft\Interfaces\ToolCraftCommandInterface
     */
    public function setRunner(RunnerInterface $runner): self;
}
