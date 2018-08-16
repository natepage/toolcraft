<?php
declare(strict_types=1);

namespace NatePage\ToolCraft;

use NatePage\ToolCraft\Helpers\ConfigurationHelper;
use NatePage\ToolCraft\Helpers\OutputHelper;
use NatePage\ToolCraft\Interfaces\AggregateRunnerInterface;
use NatePage\ToolCraft\Interfaces\ConfigurationInterface;
use NatePage\ToolCraft\Interfaces\ConsoleAwareInterface;
use NatePage\ToolCraft\Interfaces\RunnerInterface;
use NatePage\ToolCraft\Interfaces\ToolCollectionInterface;
use NatePage\ToolCraft\Interfaces\ToolCraftCommandInterface;
use NatePage\ToolCraft\Runners\ChainRunner;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ToolCraftCommand extends Command implements ToolCraftCommandInterface
{
    /**
     * @var \NatePage\ToolCraft\Interfaces\ConfigurationInterface
     */
    private $configuration;

    /**
     * @var \NatePage\ToolCraft\Interfaces\AggregateRunnerInterface
     */
    private $runner;

    /**
     * @var \NatePage\ToolCraft\Interfaces\ToolCollectionInterface
     */
    private $tools;

    /**
     * ToolCraftCommand constructor.
     *
     * @param string $name
     * @param \NatePage\ToolCraft\Interfaces\ConfigurationInterface|null $configuration
     * @param \NatePage\ToolCraft\Interfaces\ToolCollectionInterface|null $tools
     * @param \NatePage\ToolCraft\Interfaces\AggregateRunnerInterface|null $runner
     */
    public function __construct(
        string $name,
        ?ConfigurationInterface $configuration = null,
        ?ToolCollectionInterface $tools = null,
        ?AggregateRunnerInterface $runner = null
    ) {
        $this->configuration = $configuration ?? new Configuration();
        $this->tools = $tools ?? new ToolCollection();
        $this->runner = $runner ?? new ChainRunner();

        parent::__construct($name);
    }

    /**
     * Set configuration.
     *
     * @param \NatePage\ToolCraft\Interfaces\ConfigurationInterface $configuration
     *
     * @return \NatePage\ToolCraft\Interfaces\ToolCraftCommandInterface
     */
    public function setConfiguration(ConfigurationInterface $configuration): ToolCraftCommandInterface
    {
        $this->configuration = $configuration;

        return $this->doConfigure();
    }

    /**
     * Set runner.
     *
     * @param \NatePage\ToolCraft\Interfaces\RunnerInterface $runner
     *
     * @return \NatePage\ToolCraft\Interfaces\ToolCraftCommandInterface
     */
    public function setRunner(RunnerInterface $runner): ToolCraftCommandInterface
    {
        $this->runner = $runner;

        return $this;
    }

    /**
     * Set tools.
     *
     * @param \NatePage\ToolCraft\Interfaces\ToolCollectionInterface $tools
     *
     * @return \NatePage\ToolCraft\Interfaces\ToolCraftCommandInterface
     */
    public function setTools(ToolCollectionInterface $tools): ToolCraftCommandInterface
    {
        $this->tools = $tools;

        return $this->doConfigure();
    }

    /**
     * Configure command.
     *
     * @return void
     */
    protected function configure(): void
    {
        parent::configure();

        // Add global options to config
        $this->configuration->addOptions($this->initConfigurationOptions());

        // Add configuration from tools
        $this->doConfigure();
    }

    /**
     * {@inheritdoc}
     *
     * @throws \NatePage\ToolCraft\Exceptions\InvalidConfigurationOptionException
     */
    protected function execute(InputInterface $input, OutputInterface $output): ?int
    {
        $configurationHelper = $this->getConfigurationHelper();
        $outputHelper = $this->getOutputHelper($input, $output);

        // Update configuration with input from user
        $configurationHelper
            ->merge($input->getOptions())
            ->updateToolsState();

        // Display config if asked
        $outputHelper->config($configurationHelper);

        // If no tools to run, skip
        if ($this->tools->isEmpty()) {
            $outputHelper->warning('No tools to run');

            return RunnerInterface::EXIT_CODE_SUCCESS;
        }

        // If tool is enabled, add its runner to aggregate
        foreach ($this->tools->all() as $tool) {
            if ($configurationHelper->isToolEnabled($tool->getId()) === false) {
                continue;
            }

            $this->runner->addRunner($tool->getRunner());
        }

        // If runner is aware of the console, give it input and output
        if ($this->runner instanceof ConsoleAwareInterface) {
            $this->runner->setInput($input);
            $this->runner->setOutput($output);
        }

        return $this->runner->start()->wait();
    }

    /**
     * Returns list of configuration option for the current command.
     *
     * @return \NatePage\ToolCraft\Interfaces\ConfigurationOptionInterface[]
     */
    protected function initConfigurationOptions(): array
    {
        return [
            new ConfigurationOption('display-config', false, 'Display config'),
            new ConfigurationOption('only', null, 'Run only specified tools')
        ];
    }

    /**
     * Add configuration from tools and set input options on command.
     *
     * @return self
     */
    private function doConfigure(): self
    {
        $this
            ->getConfigurationHelper()
            ->registerTools($this->tools)
            ->addCommandOptions($this);

        return $this;
    }

    /**
     * Get configuration helper.
     *
     * @return \NatePage\ToolCraft\Helpers\ConfigurationHelper
     */
    private function getConfigurationHelper(): ConfigurationHelper
    {
        return new ConfigurationHelper($this->configuration);
    }

    /**
     * Get output helper.
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return \NatePage\ToolCraft\Helpers\OutputHelper
     */
    private function getOutputHelper(InputInterface $input, OutputInterface $output): OutputHelper
    {
        return new OutputHelper($input, $output);
    }
}
