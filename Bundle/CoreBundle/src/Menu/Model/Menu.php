<?php

namespace Umbrella\CoreBundle\Menu\Model;

/**
 * Class Menu
 */
class Menu
{
    public const MATCH_BY_REQUEST = 1;
    public const MATCH_BY_RULE = 2;

    public const BY_ROUTE = 1;
    public const BY_PATH = 2;
    public const BY_FULL_PATH = 3;

    protected MenuItem $root;

    protected string $id;

    private array $pathEntries = [];

    private int $matchStrategy = self::MATCH_BY_REQUEST;
    private array $matchRule = [];

    /**
     * Menu constructor.
     */
    public function __construct()
    {
        $this->root = new MenuItem($this, 'root');
        $this->id = uniqid('', true);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getRoot(): MenuItem
    {
        return $this->root;
    }

    public function search(string $search, int $by = self::BY_PATH): ?MenuItem
    {
        return $this->root->searchNested($search, $by);
    }

    public function setMatchRule(string $search, int $by = self::BY_PATH): self
    {
        $this->matchStrategy = self::MATCH_BY_RULE;
        $this->matchRule = [
            'search' => $search,
            'by' => $by
        ];

        return $this;
    }

    public function getMatchStrategy(): int
    {
        return $this->matchStrategy;
    }

    public function getMatchRule(): array
    {
        return $this->matchRule;
    }

    // alias (method used on Umbrella v1)
    public function setCurrent(string $search, int $by = self::BY_PATH): self
    {
        return $this->setMatchRule($search, $by);
    }
}
