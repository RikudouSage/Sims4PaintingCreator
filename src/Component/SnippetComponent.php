<?php

namespace Rikudou\Sims4\Paintings\Component;

use InvalidArgumentException;
use LogicException;
use Rikudou\Sims4\Paintings\Enums\ContentGroup;
use Rikudou\Sims4\Paintings\Enums\ContentType;
use SimpleXMLElement;

final class SnippetComponent extends AbstractComponent
{
    private const XML_PATH = __DIR__ . '/../../dataTemplates/snippet.xml';

    /**
     * @var string
     */
    private $namespace;

    /**
     * @var int
     */
    private $paintingStyle;

    /**
     * @var RecipeComponent[]
     */
    private $recipes;

    /**
     * @param string            $namespace
     * @param int               $paintingStyle
     * @param RecipeComponent[] $recipes
     *
     * @internal
     */
    public function __construct(string $namespace, int $paintingStyle, array $recipes)
    {
        $this->namespace = $namespace;
        $this->paintingStyle = $paintingStyle;
        $this->recipes = $recipes;
    }

    /**
     * @inheritDoc
     */
    public function getType(): int
    {
        return ContentType::SNIPPET;
    }

    /**
     * @inheritDoc
     */
    public function getGroup(): int
    {
        return ContentGroup::SNIPPET;
    }

    /**
     * @inheritDoc
     */
    protected function getUniqueName(): string
    {
        return $this->namespace . ':Snippet';
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
            if ((string) $item['n'] === 'painting_styles') {
                $item->T = $this->paintingStyle;
            } else {
                foreach ($this->recipes as $recipe) {
                    $child = $item->addChild('U');
                    /** @var SimpleXMLElement $child->T */
                    $child->T = $recipe->getFullInstanceId();
                    $child->T['n'] = 'texture';
                }
            }
        }

        $output = $xml->asXML();
        if ($output === false) {
            throw new LogicException('The generated XML file is not valid');
        }

        return $output;
    }
}
