<?php
declare(strict_types=1);

namespace NatePage\ToolCraft\Helpers;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class OutputHelper
{
    /**
     * @var \Symfony\Component\Console\Style\SymfonyStyle
     */
    private $style;

    /**
     * OutputHelper constructor.
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    public function __construct(InputInterface $input, OutputInterface $output)
    {
        $this->style = new SymfonyStyle($input, $output);
    }

    /**
     * Write config into output if display-config enabled.
     *
     * @param \NatePage\ToolCraft\Helpers\ConfigurationHelper $configurationHelper
     *
     * @return void
     *
     * @throws \NatePage\ToolCraft\Exceptions\InvalidConfigurationOptionException If option doesn't exist
     */
    public function config(ConfigurationHelper $configurationHelper): void
    {
        if ($configurationHelper->get('display-config') === false) {
            return;
        }

        $this->style->section('Config');

        $dump = $configurationHelper->dump();
        $toolsId = $configurationHelper->getToolsId();
        $rows = [];

        foreach ($dump as $key => $value) {
            $toolId = \explode('.', $key)[0] ?? null;

            // Skip config for disabled tools
            if (\in_array($toolId, $toolsId, true) && ($dump[\sprintf('%s.enabled', $toolId)] ?? false) === false) {
                continue;
            }

            $rows[] = [$key, $this->toString($value)];
        }

        $this->style->table(['Config', 'Value'], $rows);
    }

    /**
     * Write error for given message.
     *
     * @param string $message
     *
     * @return void
     */
    public function error(string $message): void
    {
        $this->style->error($message);
    }

    /**
     * Write success for given message.
     *
     * @param string $message
     *
     * @return void
     */
    public function success(string $message): void
    {
        $this->style->success($message);
    }

    /**
     * Write warning for given message.
     *
     * @param string $message
     *
     * @return void
     */
    public function warning(string $message): void
    {
        $this->style->warning($message);
    }

    /**
     * Convert given value to string.
     *
     * @param mixed $value
     *
     * @return string
     */
    private function toString($value): string
    {
        if ($value === null) {
            return 'NULL';
        }

        if (\is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        return (string)$value;
    }
}
