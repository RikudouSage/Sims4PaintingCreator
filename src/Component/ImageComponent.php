<?php

namespace Rikudou\Sims4\Paintings\Component;

use Imagick;
use Rikudou\Sims4\Paintings\Enums\ContentType;
use Rikudou\Sims4\Paintings\Helper\NoGroupTrait;

final class ImageComponent extends AbstractComponent
{
    use NoGroupTrait;

    /**
     * @var string
     */
    private $namespace;

    /**
     * @var string
     */
    private $imageName;

    /**
     * @var string
     */
    private $filePath;

    /**
     * @var int
     */
    private $minLevel = 1;

    /**
     * @var int
     */
    private $maxLevel = 10;

    /**
     * @var string
     */
    private $canvasType;

    /**
     * @internal
     *
     * @param string $namespace
     * @param string $imageName
     * @param string $filePath
     * @param string $canvasType
     */
    public function __construct(
        string $namespace,
        string $imageName,
        string $filePath,
        string $canvasType
    ) {
        $this->namespace = $namespace;
        $this->imageName = $imageName;
        $this->filePath = $filePath;
        $this->canvasType = $canvasType;
    }

    public function getType(): int
    {
        return ContentType::BITMAP;
    }

    /**
     * @return int
     */
    public function getMinLevel(): int
    {
        return $this->minLevel;
    }

    /**
     * @param int $minLevel
     *
     * @return ImageComponent
     */
    public function setMinLevel(int $minLevel): ImageComponent
    {
        $this->minLevel = $minLevel;

        return $this;
    }

    /**
     * @return int
     */
    public function getMaxLevel(): int
    {
        return $this->maxLevel;
    }

    /**
     * @param int $maxLevel
     *
     * @return self
     */
    public function setMaxLevel(int $maxLevel): self
    {
        $this->maxLevel = $maxLevel;

        return $this;
    }

    public function getUniqueName(): string
    {
        return $this->namespace . ':' . $this->imageName;
    }

    /**
     * @return string
     */
    public function getCanvasType(): string
    {
        return $this->canvasType;
    }

    protected function getRawContent(): string
    {
        $imagick = new Imagick($this->filePath);

        // not sure which of these two is needed, documentation is lacking
        $imagick->setFormat('dds');
        $imagick->setImageFormat('dds');

        // same as above
        $imagick->setCompression(Imagick::COMPRESSION_DXT5);
        $imagick->setImageCompression(Imagick::COMPRESSION_DXT5);

        return $imagick->getImageBlob();
    }
}
