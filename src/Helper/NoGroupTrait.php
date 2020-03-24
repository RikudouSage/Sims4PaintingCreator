<?php

namespace Rikudou\Sims4\Paintings\Helper;

use Rikudou\Sims4\Paintings\Enums\ContentGroup;

trait NoGroupTrait
{
    public function getGroup(): int
    {
        return ContentGroup::NONE;
    }
}
