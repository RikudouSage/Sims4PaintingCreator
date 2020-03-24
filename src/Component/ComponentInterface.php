<?php

namespace Rikudou\Sims4\Paintings\Component;

interface ComponentInterface
{
    public function getCompressedContent(): string;

    public function getSize(): int;

    public function getOriginalSize(): int;

    public function getType(): int;

    public function getGroup(): int;

    public function getInstanceId1(): int;

    public function getInstanceId2(): int;

    public function getFullInstanceId(): int;

    public function getFullInstanceIdAsString(): string;
}
