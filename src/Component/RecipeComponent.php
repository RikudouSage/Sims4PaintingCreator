<?php

namespace Rikudou\Sims4\Paintings\Component;

use InvalidArgumentException;
use LogicException;
use Rikudou\Sims4\Paintings\Enums\ContentType;
use Rikudou\Sims4\Paintings\Helper\NoGroupTrait;
use SimpleXMLElement;

final class RecipeComponent extends AbstractComponent
{
    use NoGroupTrait;

    private const XML_PATH = __DIR__ . '/../../dataTemplates/recipe.xml';

    /**
     * @var string
     */
    private $namespace;

    /**
     * @var string
     */
    private $canvasType;

    /**
     * @var int
     */
    private $minLevel;

    /**
     * @var int
     */
    private $maxLevel;

    /**
     * @var string
     */
    private $textureId;

    /**
     * @var SimpleXMLElement|null
     */
    private $xml = null;

    /**
     * @param string $namespace
     * @param string $canvasType
     * @param int    $minLevel
     * @param int    $maxLevel
     * @param string $textureId
     *
     * @internal
     */
    public function __construct(
        string $namespace,
        string $canvasType,
        int $minLevel,
        int $maxLevel,
        string $textureId
    ) {
        $this->namespace = $namespace;
        $this->canvasType = $canvasType;
        $this->minLevel = $minLevel;
        $this->maxLevel = $maxLevel;
        $this->textureId = $textureId;
    }

    /**
     * @inheritDoc
     */
    public function getType(): int
    {
        return ContentType::RECIPE;
    }

    /**
     * @inheritDoc
     */
    protected function getUniqueName(): string
    {
        return $this->namespace . ':Recipe';
    }

    /**
     * @inheritDoc
     */
    protected function getRawContent(): string
    {
        $content = file_get_contents(self::XML_PATH);
        if ($content === false) {
            throw new InvalidArgumentException(sprintf("The xml file '%s' does not exist", self::XML_PATH));
        }
        $xml = new SimpleXMLElement($content);
        $xml['n'] = $this->getUniqueName();
        $xml['s'] = $this->getFullInstanceId();
        foreach ($xml->L as $item) {
            if ((string) $item['n'] === 'canvas_types') {
                $item->E = $this->canvasType;
            } else {
                $bounds = $item->L->V->U->V->U->U->T;
                foreach ($bounds as $bound) {
                    switch ($bound['n']) {
                        case 'lower_bound':
                            $bound[0] = $this->minLevel;
                            break;
                        case 'upper_bound':
                            $bound[0] = $this->maxLevel;
                            break;
                    }
                }
            }
        }
        $xml->L->E = $this->canvasType;
        $xml->T = sprintf('2f7d0004:00000000:%s', $this->textureId);

        $output = $xml->asXML();
        if ($output === false) {
            throw new LogicException('The generated XML file is not valid');
        }

        return $output;
    }
}
