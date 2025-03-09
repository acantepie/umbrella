<?php

namespace Umbrella\AdminBundle\Utils;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Translation\Loader\PhpFileLoader;
use Symfony\Component\Translation\Translator;

class TranslationDumper
{
    private Translator $translator;
    private string $translationDir;
    private string $domain;
    private array $locales = [];

    public function __construct(string $domain = 'UmbrellaAdmin')
    {
        $this->domain = $domain;
        $this->translator = new Translator('en');
        $this->translator->addLoader('php', new PhpFileLoader());
        $this->translationDir = __DIR__ . '/../../translations';
    }

    public function dumpTranslationsToJson(): string
    {
        $this->loadResources();

        $dump = [];

        foreach ($this->locales as $locale) {
            $catalogue = $this->translator->getCatalogue($locale);
            $dump[$locale] = [
                $catalogue->all($this->domain),
            ];
        }

        return json_encode($dump, \JSON_THROW_ON_ERROR | \JSON_PRETTY_PRINT | \JSON_UNESCAPED_UNICODE);
    }

    private function loadResources(): void
    {
        $finder = new Finder();
        $finder->name(\sprintf('%s.*.php', $this->domain));
        $finder->in($this->translationDir);
        $finder->files();

        /** @var SplFileInfo $file */
        foreach ($finder as $file) {
            $parts = explode('.', $file->getBasename());

            if (3 !== \count($parts)) {
                throw new \RuntimeException(\sprintf('Unexpected file name "%s".', $file->getBasename()));
            }

            $locale = $parts[1];

            $this->translator->addResource('php', $file->getRealPath(), $locale, $this->domain);
            if (!\in_array($locale, $this->locales, true)) {
                $this->locales[] = $locale;
            }
        }
    }
}
