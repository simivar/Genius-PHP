<?php

declare(strict_types=1);

namespace Genius\Enum;

use MyCLabs\Enum\Enum;

/**
 * @method static Scope ME()
 * @method static Scope CREATE_ANNOTATION()
 * @method static Scope MANAGE_ANNOTATION()
 * @method static Scope VOTE()
 *
 * @extends Enum<string>
 */
final class Scope extends Enum
{
    private const ME = 'me';
    private const CREATE_ANNOTATION = 'create_annotation';
    private const MANAGE_ANNOTATION = 'manage_annotation';
    private const VOTE = 'vote';
}
