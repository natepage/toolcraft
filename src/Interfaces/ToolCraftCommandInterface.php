<?php
declare(strict_types=1);

namespace NatePage\ToolCraft\Interfaces;

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
     * Set tools.
     *
     * @param \NatePage\ToolCraft\Interfaces\ToolCollectionInterface $tools
     *
     * @return \NatePage\ToolCraft\Interfaces\ToolCraftCommandInterface
     */
    public function setTools(ToolCollectionInterface $tools): self;

    /**
     * Set runner.
     *
     * @param \NatePage\ToolCraft\Interfaces\RunnerInterface $runner
     *
     * @return \NatePage\ToolCraft\Interfaces\ToolCraftCommandInterface
     */
    public function setRunner(RunnerInterface $runner): self;
}
