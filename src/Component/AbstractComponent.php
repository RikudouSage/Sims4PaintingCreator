<?php

namespace Rikudou\Sims4\Paintings\Component;

use Rikudou\Sims4\Paintings\Helper\MathUtils;
use RuntimeException;

abstract class AbstractComponent implements ComponentInterface
{
    /**
     * @var string|null
     */
    private $rawContent = null;

    /**
     * @var string
     */
    private $instanceId;

    /**
     * @inheritDoc
     */
    public function getFullInstanceId(): string
    {
        return MathUtils::hexToDec($this->getFullInstanceIdAsHex());
    }

    /**
     * @inheritDoc
     */
    public function getFullInstanceIdAsHex(): string
    {
        if (!$this->instanceId) {
            $this->assignInstanceIds();
        }

        return strtoupper($this->instanceId);
    }

    /**
     * @inheritDoc
     */
    public function getInstanceId1(): int
    {
        return (int) hexdec(str_split($this->getFullInstanceIdAsHex(), 8)[0]);
    }

    /**
     * @inheritDoc
     */
    public function getInstanceId2(): int
    {
        return (int) hexdec(str_split($this->getFullInstanceIdAsHex(), 8)[1]);
    }

    /**
     * @inheritDoc
     */
    public function getCompressedContent(): string
    {
        return $this->compress($this->getCachedRawContent());
    }

    /**
     * @inheritDoc
     */
    public function getSize(): int
    {
        return strlen($this->getCompressedContent());
    }

    /**
     * @inheritDoc
     */
    public function getOriginalSize(): int
    {
        return strlen($this->getCachedRawContent());
    }

    /**
     * Returns the unique name of the component, used for generating IDs
     * Should be unique among all packages ever generated
     *
     * @return string
     */
    abstract protected function getUniqueName(): string;

    /**
     * Returns the raw content of the component as a string
     *
     * @return string
     */
    abstract protected function getRawContent(): string;

    /**
     * Gzip compresses the input string
     *
     * @param string $content
     *
     * @return string
     */
    protected function compress(string $content): string
    {
        $result = gzcompress($content, 9);
        if ($result === false) {
            throw new RuntimeException('Gzip compressing failed');
        }

        return $result;
    }

    /**
     * Creates the instance IDs based on the unique name
     */
    private function assignInstanceIds(): void
    {
        $hash = MathUtils::FNV1($this->getUniqueName());
        $hash = MathUtils::decToHex($hash);
        $hash = str_pad($hash, 16, '8', STR_PAD_LEFT);
        $this->instanceId = $hash;
    }

    /**
     * Returns the same raw content for every call
     *
     * @return string
     */
    private function getCachedRawContent(): string
    {
        if ($this->rawContent === null) {
            $this->rawContent = $this->getRawContent();
        }

        return $this->rawContent;
    }
}
