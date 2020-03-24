<?php

namespace Rikudou\Sims4\Paintings;

use Rikudou\Sims4\Paintings\Component\ComponentInterface;
use Rikudou\Sims4\Paintings\Component\ImageComponent;
use Rikudou\Sims4\Paintings\Enums\ComponentFlag;
use Rikudou\Sims4\Paintings\Helper\BinaryDataWriter;

final class PaintingPackage
{
    /**
     * @var ImagePack[]
     */
    private $packs = [];

    /**
     * @var array<string, int>
     */
    private $styles = [];

    /**
     * @var string
     */
    private $author;

    /**
     * @var string
     */
    private $packageName;

    public function __construct(string $author, string $packageName)
    {
        $this->author = $author;
        $this->packageName = $packageName;
    }

    public function createPack(int $paintingStyle): ImagePack
    {
        $pack = new ImagePack($this->author, $this->packageName, $paintingStyle);
        $hash = spl_object_hash($pack);
        $this->packs[$hash] = $pack;
        $this->styles[$hash] = $paintingStyle;

        return $pack;
    }

    public function removePack(ImagePack $pack): self
    {
        $hash = spl_object_hash($pack);
        if (isset($this->packs[$hash])) {
            unset($this->packs[$hash]);
        }
        if (isset($this->styles[$hash])) {
            unset($this->styles[$hash]);
        }

        return $this;
    }

    public function createImage(string $filePath, string $imageName, string $canvasType, int $paintingStyle): ImageComponent
    {
        if (in_array($paintingStyle, $this->styles, true)) {
            $hash = array_search($paintingStyle, $this->styles, true);
            $pack = $this->packs[$hash];
        } else {
            $pack = $this->createPack($paintingStyle);
        }

        return $pack->createImage($filePath, $imageName, $canvasType);
    }

    public function write(string $targetPath): void
    {
        $body = '';
        $index = BinaryDataWriter::createUnsignedLong(0);

        $indexOffset = 96;

        $indexCount = 0;
        foreach ($this->packs as $pack) {
            foreach ($pack->getComponents() as $component) {
                $index .= $this->createIndex($component, $indexOffset);
                $body .= $component->getCompressedContent();
                $indexOffset += $component->getSize();
                ++$indexCount;
            }
        }

        $header = $this->createHeader(strlen($index), $indexOffset, $indexCount);
        $output = $header . $body . $index;
        file_put_contents($targetPath, $output);
    }

    private function createIndex(ComponentInterface $component, int $position): string
    {
        $index = '';
        $index .= BinaryDataWriter::createUnsignedLong($component->getType());
        $index .= BinaryDataWriter::createUnsignedLong($component->getGroup());
        $index .= BinaryDataWriter::createUnsignedLong($component->getInstanceId1());
        $index .= BinaryDataWriter::createUnsignedLong($component->getInstanceId2());
        $index .= BinaryDataWriter::createUnsignedLong($position);
        $index .= BinaryDataWriter::createUnsignedLong($component->getSize() + 0x80000000);
        $index .= BinaryDataWriter::createUnsignedLong($component->getOriginalSize());
        $index .= BinaryDataWriter::createUnsignedShort(ComponentFlag::FLAG_COMPRESSED);
        $index .= BinaryDataWriter::createSignedShort(ComponentFlag::COMPRESSION_FLAG_COMPRESSED);

        return $index;
    }

    private function createHeader(int $indexSize, int $indexOffset, int $indexCount): string
    {
        $header = 'DBPF';
        $header .= BinaryDataWriter::createUnsignedLong(2); // major version
        $header .= BinaryDataWriter::createUnsignedLong(1); // minor version
        $header .= BinaryDataWriter::createUnsignedLong(0); // unknown
        $header .= BinaryDataWriter::createUnsignedLong(0); // unknown
        $header .= BinaryDataWriter::createUnsignedLong(0); // unknown
        $header .= BinaryDataWriter::createUnsignedLong(0); // unknown
        $header .= BinaryDataWriter::createUnsignedLong(0); // unknown
        $header .= BinaryDataWriter::createUnsignedLong(0); // unknown
        $header .= BinaryDataWriter::createUnsignedLong($indexCount); // index entry count
        $header .= BinaryDataWriter::createUnsignedLong(0); // unknown
        $header .= BinaryDataWriter::createUnsignedLong($indexSize);
        $header .= BinaryDataWriter::createUnsignedLong(0); // unknown
        $header .= BinaryDataWriter::createUnsignedLong(0); // unknown
        $header .= BinaryDataWriter::createUnsignedLong(0); // unknown
        $header .= BinaryDataWriter::createUnsignedLong(3); // index minor version
        $header .= BinaryDataWriter::createUnsignedLong($indexOffset);
        $header .= BinaryDataWriter::createUnsignedLong(0); // unknown
        $header .= str_repeat(' ', 24); // unused

        return $header;
    }
}
