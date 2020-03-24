<?php

namespace Rikudou\Sims4\Paintings\Helper;

use Imagick;

final class ImageResizer
{
    public const SIZE_LARGE = [290, 512];
    public const SIZE_MEDIUM = [256, 256];
    public const SIZE_SMALL = [128, 128];

    /**
     * @var string
     */
    private $imagePath;

    /**
     * @var array
     */
    private $size;

    /**
     * @var Imagick|null
     */
    private $resized = null;

    /**
     * @param string $imagePath
     * @param array  $size
     */
    public function __construct(string $imagePath, array $size)
    {
        $this->imagePath = $imagePath;
        $this->size = $size;
    }

    public function asString(): string
    {
        return $this->resize()->getImageBlob();
    }

    public function write(string $targetPath): void
    {
        file_put_contents($targetPath, $this->asString());
    }

    private function resize(): Imagick
    {
        if ($this->resized === null) {
            $imagick = new Imagick($this->imagePath);
            $imagick->resizeImage(0, $this->size[1], Imagick::FILTER_GAUSSIAN, 1);
            if ($imagick->getImageWidth() !== $this->size[0]) {
                $imagick->cropThumbnailImage($this->size[0], $this->size[1]);
            }

            if ($imagick->getImageWidth() !== $imagick->getImageHeight()) {
                $background = new Imagick();
                $background->newImage($imagick->getImageHeight(), $imagick->getImageHeight(), 'white', $imagick->getImageFormat());
                $background->compositeImage($imagick, Imagick::COMPOSITE_ATOP, 0, 0);
                $imagick = $background;
            }

            $this->resized = $imagick;
        }

        return $this->resized;
    }
}