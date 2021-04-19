<?php

namespace Umbrella\CoreBundle\Component\Toast;

use Symfony\Component\Translation\TranslatableMessage;

/**
 * Class Toast
 */
class Toast
{
    const INFO = 'info';
    const SUCCESS = 'success';
    const WARNING = 'warning';
    const ERROR = 'error';

    const BOTTOM_LEFT = 'bottom-left';
    const BOTTOM_RIGHT = 'bottom-right';
    const BOTTOM_CENTER = 'bottom-center';
    const TOP_RIGHT = 'top-right';
    const TOP_LEFT = 'top-left';
    const TOP_CENTER = 'top-center';
    const MID_CENTER = 'mid-center';

    protected bool $safeHtml = true;

    protected bool $progressBar = false;

    protected bool $closeButton = true;

    protected int $showDuration = 300;

    protected ?string $title = null;

    protected ?TranslatableMessage $translatableTitle = null;

    protected ?string $text = null;

    protected ?TranslatableMessage $translatableText = null;

    protected string $type = self::INFO;

    protected string $position = self::TOP_RIGHT;

    final public function __construct()
    {
    }

    public static function create($text, $title = null, bool $safeHtml = true, string $type = self::INFO): self
    {
        $toast = new static();
        if (is_a($text, TranslatableMessage::class)) {
            $toast->translatableText = $text;
        } else {
            $toast->text = $text;
        }

        if (is_a($title, TranslatableMessage::class)) {
            $toast->translatableTitle = $title;
        } else {
            $toast->title = $title;
        }

        $toast->safeHtml = $safeHtml;
        $toast->type = $type;

        return $toast;
    }

    public static function createSuccess($text, $title = null, bool $safeHtml = true): self
    {
        return self::create($text, $title, $safeHtml, self::SUCCESS);
    }

    public static function createInfo($text, $title = null, bool $safeHtml = true): self
    {
        return self::create($text, $title, $safeHtml, self::INFO);
    }

    public static function createWarning($text, $title = null, bool $safeHtml = true): self
    {
        return self::create($text, $title, $safeHtml, self::WARNING);
    }

    public static function createError($text, $title = null, bool $safeHtml = true): self
    {
        return self::create($text, $title, $safeHtml, self::ERROR);
    }

    public function isSafeHtml(): bool
    {
        return $this->safeHtml;
    }

    public function setSafeHtml(bool $safeHtml): self
    {
        $this->safeHtml = $safeHtml;

        return $this;
    }

    public function hasProgressBar(): bool
    {
        return $this->progressBar;
    }

    public function setProgressBar(bool $progressBar): Toast
    {
        $this->progressBar = $progressBar;

        return $this;
    }

    public function hasCloseButton(): bool
    {
        return $this->closeButton;
    }

    public function setCloseButton(bool $closeButton): self
    {
        $this->closeButton = $closeButton;

        return $this;
    }

    public function getShowDuration(): int
    {
        return $this->showDuration;
    }

    public function setShowDuration(int $showDuration): self
    {
        $this->showDuration = $showDuration;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTranslatableTitle(): ?TranslatableMessage
    {
        return $this->translatableTitle;
    }

    public function setTranslatableTitle(?TranslatableMessage $translatableTitle): self
    {
        $this->translatableTitle = $translatableTitle;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getTranslatableText(): ?TranslatableMessage
    {
        return $this->translatableText;
    }

    public function setTranslatableText(?TranslatableMessage $translatableText): self
    {
        $this->translatableText = $translatableText;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getPosition(): string
    {
        return $this->position;
    }

    public function setPosition(string $position): self
    {
        $this->position = $position;

        return $this;
    }
}
