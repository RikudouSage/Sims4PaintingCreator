<?php

namespace Rikudou\Sims4\Paintings\Component;

use Imagick;
use Rikudou\Sims4\Paintings\Enums\CanvasType;
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

    /**
     * @inheritDoc
     */
    public function getType(): int
    {
        return ContentType::BITMAP;
    }

    /**
     * Returns the minimum level required to paint this painting
     *
     * @return int
     */
    public function getMinLevel(): int
    {
        return $this->minLevel;
    }

    /**
     * Sets the minimum level required to paint this painting
     *
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
     * Returns the maximum level allowed to paint this painting
     *
     * @return int
     */
    public function getMaxLevel(): int
    {
        return $this->maxLevel;
    }

    /**
     * Sets the minimum level allowed to paint this painting
     *
     * @param int $maxLevel
     *
     * @return self
     */
    public function setMaxLevel(int $maxLevel): self
    {
        $this->maxLevel = $maxLevel;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getUniqueName(): string
    {
        return $this->namespace . ':' . $this->imageName;
    }

    /**
     * Returns the canvas type for this component
     *
     * @see CanvasType
     *
     * @return string
     */
    public function getCanvasType(): string
    {
        return $this->canvasType;
    }

    /**
     * @inheritDoc
     */
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
