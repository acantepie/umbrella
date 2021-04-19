<?php

namespace Umbrella\AdminBundle\Maker\Generator;

use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Umbrella\AdminBundle\Maker\Utils\MakerUtils;
use Umbrella\AdminBundle\Maker\Utils\MakerValidator;
use Umbrella\AdminBundle\Maker\Utils\MetaClass;

/**
 * Class Generator
 */
class Generator
{
    private Filesystem $fs;

    private string $projectDir;

    private string $rootNs;

    private ?SymfonyStyle $io = null;

    private array $pendingOperations = [];

    /**
     * Generator constructor.
     */
    public function __construct(Filesystem $fs, string $projectDir, string $rootNs)
    {
        $this->fs = $fs;
        $this->projectDir = $projectDir;
        $this->rootNs = $rootNs;
    }

    public function setIO(SymfonyStyle $io)
    {
        $this->io = $io;
    }

    public function createMetaClass(string $name, string $namespacePrefix, string $suffix = '')
    {
        // e.g. Foo\Bar => App\Foo\Bar
        $namespace = $this->rootNs . '\\' . rtrim($namespacePrefix, '\\');

        // e.g (Baz, Repository) => BazRepository
        $shortClassName = MakerUtils::asClassName($name, $suffix);

        // e.g (App\Foo\Bar, BazRepository) => App\Foo\Bar\BazRepository
        $className = $namespace . '\\' . $shortClassName;

        MakerValidator::validateClassName($className);

        // e.g (Foo\Bar, BazRepository) => Foo/Bar/BazRepository
        $filePath = MakerUtils::asFilePath(rtrim($namespacePrefix, '\\')) . '/' . $shortClassName . '.php';

        return new MetaClass($className, $namespace, $suffix, $filePath);
    }

    public function generateClass(string $targetPath, string $templateName, array $variables = [])
    {
        $path = sprintf('%s/src/%s', $this->projectDir, $targetPath);
        $this->addOperation($path, $templateName, $variables);
    }

    public function generateTemplate(string $targetPath, string $templateName, array $variables = [])
    {
        $path = sprintf('%s/templates/%s', $this->projectDir, $targetPath);
        $this->addOperation($path, $templateName, $variables);
    }

    private function addOperation(string $targetPath, string $templateName, array $variables = [])
    {
        $this->pendingOperations[$targetPath] = [
            'template' => $templateName,
            'variables' => $variables,
        ];
    }

    public function hasPendingOperations(): bool
    {
        return !empty($this->pendingOperations);
    }

    public function writeChanges(bool $force = false)
    {
        foreach ($this->pendingOperations as $targetPath => $templateData) {
            $this->dumpFile($targetPath, $this->renderTemplate($templateData['template'], $templateData['variables']), $force);
        }
        $this->pendingOperations = [];
    }

    private function renderTemplate(string $template, array $parameters): string
    {
        ob_start();
        extract($parameters, \EXTR_SKIP);
        include sprintf('%s/../../../skeleton/%s', __DIR__, ltrim($template, '/'));

        return ob_get_clean();
    }

    private function dumpFile(string $path, string $content, bool $force = false)
    {
        $rpath = $this->relativizePath($path);

        if ($this->fs->exists($path)) {
            if ($force) {
                $this->fs->dumpFile($path, $content);
                $this->io->comment(sprintf('<fg=yellow>updated</>: %s', $rpath));
            } else {
                $this->io->comment(sprintf('<fg=green>no change</>: %s', $rpath));
            }
        } else {
            $this->fs->dumpFile($path, $content);
            $this->io->comment(sprintf('<fg=blue>created</>: %s', $rpath));
        }
    }

    private function relativizePath(string $absolutePath): string
    {
        $relativePath = str_replace($this->projectDir, '.', $absolutePath);

        return is_dir($absolutePath) ? rtrim($relativePath, '/') . '/' : $relativePath;
    }
}
