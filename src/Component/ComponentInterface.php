<?php

namespace Rikudou\Sims4\Paintings\Component;

interface ComponentInterface
{
    /**
     * Returns the component content gzip compressed
     *
     * @return string
     */
    public function getCompressedContent(): string;

    /**
     * Returns the size in bytes of the compressed content
     *
     * @return int
     */
    public function getSize(): int;

    /**
     * Returns size in bytes of the uncompressed content
     *
     * @return int
     */
    public function getOriginalSize(): int;

    /**
     * Returns a type from \Rikudou\Sims4\Paintings\Enums\ContentType
     *
     * @see \Rikudou\Sims4\Paintings\Enums\ContentType
     *
     * @return int
     */
    public function getType(): int;

    /**
     * Returns a group from \Rikudou\Sims4\Paintings\Enums\ContentGroup
     *
     * @see \Rikudou\Sims4\Paintings\Enums\ContentGroup
     *
     * @return int
     */
    public function getGroup(): int;

    /**
     * Returns the first part of instance id (8 bytes)
     *
     * @return int
     */
    public function getInstanceId1(): int;

    /**
     * Returns the second part of instance id (8 bytes)
     *
     * @return int
     */
    public function getInstanceId2(): int;

    /**
     * Returns the full instance id (16 bytes)
     *
     * @return string
     */
    public function getFullInstanceId(): string;

    /**
     * Returns the full instance ID as a hexadecimal string
     *
     * @return string
     */
    public function getFullInstanceIdAsHex(): string;
}
