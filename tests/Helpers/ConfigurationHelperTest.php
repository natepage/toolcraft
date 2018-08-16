<?php
declare(strict_types=1);

namespace Tests\NatePage\ToolCraft\Helpers;

use NatePage\ToolCraft\Configuration;
use NatePage\ToolCraft\ConfigurationOption;
use NatePage\ToolCraft\Exceptions\InvalidConfigurationOptionException;
use NatePage\ToolCraft\Helpers\ConfigurationHelper;
use NatePage\ToolCraft\Interfaces\ConfigurationInterface;
use Symfony\Component\Console\Command\Command;
use Tests\NatePage\ToolCraft\TestCase;

class ConfigurationHelperTest extends TestCase
{
    /**
     * Helper should add only exposed options to command.
     *
     * @return void
     */
    public function testAddCommandOptions(): void
    {
        $command = new Command();
        $helper = new ConfigurationHelper($this->getConfiguration());

        $helper->addCommandOptions($command);

        self::assertCount(1, $command->getDefinition()->getOptions());
    }

    /**
     * Helper should dump configuration values.
     *
     * @return void
     */
    public function testDump(): void
    {
        $helper = new ConfigurationHelper($this->getConfiguration());
        $dump = [
            'hidden' => 'hidden',
            'exposed' => 'exposed'
        ];

        self::assertEquals($dump, $helper->dump());
    }

    /**
     * Helper should return an empty array if no tool prefix provided with options in configuration.
     *
     * @return void
     */
    public function testGetToolsIdWithNoToolPrefix(): void
    {
        self::assertEmpty((new ConfigurationHelper($this->getConfiguration()))->getToolsId());
    }

    /**
     * Helper should return an array with all tool prefixes provided with options in configuration.
     *
     * @return void
     */
    public function testGetToolsIdWithToolsPrefix(): void
    {
        $configuration = $this->getConfiguration();
        $configuration->addOption(new ConfigurationOption('with_tool'), 'tool');

        self::assertEquals(['tool'], (new ConfigurationHelper($configuration))->getToolsId());
    }

    /**
     * Helper should throw invalid configuration option exception when trying to get option which doesn't exist.
     *
     * @return void
     *
     * @throws \NatePage\ToolCraft\Exceptions\InvalidConfigurationOptionException
     */
    public function testGetWithInvalidOption(): void
    {
        $this->expectException(InvalidConfigurationOptionException::class);

        (new ConfigurationHelper($this->getConfiguration()))->get('invalid');
    }

    /**
     * Helper should return configuration option value.
     *
     * @return void
     *
     * @throws \NatePage\ToolCraft\Exceptions\InvalidConfigurationOptionException
     */
    public function testGetWithValidOption(): void
    {
        $helper = new ConfigurationHelper($this->getConfiguration());

        self::assertEquals('hidden', $helper->get('hidden'));
    }

    /**
     * Get options for tests.
     *
     * @return \NatePage\ToolCraft\ConfigurationOption[]
     */
    private function getConfiguration(): ConfigurationInterface
    {
        return (new Configuration())->addOptions([
            new ConfigurationOption('hidden', 'hidden', null, false),
            new ConfigurationOption('exposed', 'exposed')
        ]);
    }
}
