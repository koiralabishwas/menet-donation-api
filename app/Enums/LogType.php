<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class LogType extends Enum
{
    const FATAL = 'FATAL';

    const ERROR = 'ERROR';

    const WARNING = 'WARNING';

    const INFO = 'INFO';

    const DEBUG = 'DEBUG';
}
