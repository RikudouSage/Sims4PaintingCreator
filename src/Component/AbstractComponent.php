<?php

namespace Rikudou\Sims4\Paintings\Component;

use RuntimeException;

abstract class AbstractComponent implements ComponentInterface
{
    /**
     * @var int
     */
    private $instanceId1;

    /**
     * @var int
     */
    private $instanceId2;

    /**
     * @var string|null
     */
    private $rawContent = null;

    /**
     * @inheritDoc
     */
    public function getFullInstanceId(): int
    {
        return intval($this->getInstanceId1() . $this->getInstanceId2());
    }

    /**
     * @inheritDoc
     */
    public function getFullInstanceIdAsString(): string
    {
        return strtoupper(dechex($this->getInstanceId1()) . dechex($this->getInstanceId2()));
    }

    /**
     * @inheritDoc
     */
    public function getInstanceId1(): int
    {
        if (!$this->instanceId1) {
            $this->assignInstanceIds();
        }

        return $this->instanceId1;
    }

    /**
     * @inheritDoc
     */
    public function getInstanceId2(): int
    {
        if (!$this->instanceId1) {
            $this->assignInstanceIds();
        }

        return $this->instanceId2;
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
        $hash = hash('fnv164', $this->getUniqueName());
        $parts = str_split($hash, 8);
        $this->instanceId1 = (int) hexdec($parts[0]);
        $this->instanceId2 = (int) hexdec($parts[1]);
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
