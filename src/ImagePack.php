<?php

namespace Rikudou\Sims4\Paintings;

use Rikudou\Sims4\Paintings\Component\ComponentInterface;
use Rikudou\Sims4\Paintings\Component\ImageComponent;
use Rikudou\Sims4\Paintings\Component\RecipeComponent;
use Rikudou\Sims4\Paintings\Component\SnippetComponent;

final class ImagePack
{
    /**
     * @var string
     */
    private $author;

    /**
     * @var string
     */
    private $packageName;

    /**
     * @var int
     */
    private $paintingStyle;

    /**
     * @var ImageComponent[]
     */
    private $images = [];

    /**
     * @param string $author
     * @param string $packageName
     * @param int    $paintingStyle
     *
     * @internal
     */
    public function __construct(string $author, string $packageName, int $paintingStyle)
    {
        $this->author = $author;
        $this->packageName = $packageName;
        $this->paintingStyle = $paintingStyle;
    }

    /**
     * Creates a new image component inside the pack
     *
     * @param string $filePath
     * @param string $imageName
     * @param string $canvasType
     *
     * @return ImageComponent
     */
    public function createImage(string $filePath, string $imageName, string $canvasType): ImageComponent
    {
        $image = new ImageComponent($this->getNamespace(), $imageName, $filePath, $canvasType);
        $hash = spl_object_hash($image);
        $this->images[$hash] = $image;

        return $image;
    }

    /**
     * Removes existing image component from the pack
     *
     * @param ImageComponent $image
     *
     * @return $this
     */
    public function removeImage(ImageComponent $image): self
    {
        $hash = spl_object_hash($image);
        if (isset($this->images[$hash])) {
            unset($this->images[$hash]);
        }

        return $this;
    }

    /**
     * Returns all components (images, image recipes and snippet)
     *
     * @return ComponentInterface[]
     */
    public function getComponents(): array
    {
        $result = [];
        foreach ($this->images as $image) {
            $result[] = $image;
            $result[] = new RecipeComponent(
                $image->getUniqueName(),
                $image->getCanvasType(),
                $image->getMinLevel(),
                $image->getMaxLevel(),
                $image->getFullInstanceIdAsString()
            );
        }

        $result[] = new SnippetComponent(
            $this->getNamespace(),
            $this->paintingStyle,
            $this->images
        );

        return $result;
    }

    /**
     * Unique namespace
     *
     * @return string
     */
    private function getNamespace(): string
    {
        return sprintf('%s:%d:%s', $this->author, $this->paintingStyle, $this->packageName);
    }
}
