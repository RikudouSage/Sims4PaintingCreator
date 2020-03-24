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

    public function getFullInstanceId(): int
    {
        return intval($this->getInstanceId1() . $this->getInstanceId2());
    }

    public function getFullInstanceIdAsString(): string
    {
        return strtoupper(dechex($this->getInstanceId1()) . dechex($this->getInstanceId2()));
    }

    public function getInstanceId1(): int
    {
        if (!$this->instanceId1) {
            $this->assignInstanceIds();
        }

        return $this->instanceId1;
    }

    public function getInstanceId2(): int
    {
        if (!$this->instanceId1) {
            $this->assignInstanceIds();
        }

        return $this->instanceId2;
    }

    public function getCompressedContent(): string
    {
        return $this->compress($this->getCachedRawContent());
    }

    public function getSize(): int
    {
        return strlen($this->getCompressedContent());
    }

    public function getOriginalSize(): int
    {
        return strlen($this->getCachedRawContent());
    }

    abstract protected function getUniqueName(): string;

    abstract protected function getRawContent(): string;

    protected function compress(string $content): string
    {
        $result = gzcompress($content, 9);
        if ($result === false) {
            throw new RuntimeException('Gzip compressing failed');
        }

        return $result;
    }

    private function assignInstanceIds(): void
    {
        $hash = hash('fnv164', $this->getUniqueName());
        $parts = str_split($hash, 8);
        $this->instanceId1 = (int) hexdec($parts[0]);
        $this->instanceId2 = (int) hexdec($parts[1]);
    }

    private function getCachedRawContent(): string
    {
        if ($this->rawContent === null) {
            $this->rawContent = $this->getRawContent();
        }

        return $this->rawContent;
    }
}
