<?php
declare(strict_types=1);

namespace NatePage\ToolCraft\Exceptions;

use NatePage\ToolCraft\Interfaces\ToolCraftExceptionInterface;

abstract class ToolCraftException extends \Exception implements ToolCraftExceptionInterface
{
    // No body needed
}
