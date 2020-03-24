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

    public function createImage(string $filePath, string $imageName, string $canvasType): ImageComponent
    {
        $image = new ImageComponent($this->getNamespace(), $imageName, $filePath, $canvasType);
        $hash = spl_object_hash($image);
        $this->images[$hash] = $image;

        return $image;
    }

    public function removeImage(ImageComponent $image): self
    {
        $hash = spl_object_hash($image);
        if (isset($this->images[$hash])) {
            unset($this->images[$hash]);
        }

        return $this;
    }

    /**
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

    private function getNamespace(): string
    {
        return sprintf('%s:%d:%s', $this->author, $this->paintingStyle, $this->packageName);
    }
}
