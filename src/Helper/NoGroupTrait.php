<?php

namespace Rikudou\Sims4\Paintings\Helper;

use Rikudou\Sims4\Paintings\Enums\ContentGroup;

trait NoGroupTrait
{
    /**
     * @inheritDoc
     */
    public function getGroup(): int
    {
        return ContentGroup::NONE;
    }
}
