<?php

namespace Umbrella\AdminBundle\Maker\Utils;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Bundle\MakerBundle\Util\ClassNameDetails;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class MakeHelper
{
    // TODO : Use root_namespace config from symfony/maker
    private const ROOT_NAMESPACE = 'App';

    public const VIEW_MODAL = 'modal';
    public const VIEW_PAGE = 'page';
    private string $rootNamespace;
    private string $entityNamespace;
    private string $baseTemplatePath;

    public function __construct(private ManagerRegistry $doctrine, private string $rootDirectory)
    {
        $this->rootNamespace = trim(self::ROOT_NAMESPACE, '\\');
        $this->entityNamespace = $this->rootNamespace . '\\Entity';
        $this->baseTemplatePath = __DIR__ . '/../../../skeleton/';
    }

    /**
     * Ask for entity class
     */
    public function askEntityClass(ConsoleStyle $io, ?string $default = null): string
    {
        if (null === $default) {
            $question = new Question(sprintf('Class name of the entity to create (e.g. <fg=yellow>%s</>)', Str::asClassName(Str::getRandomTerm())));
        } else {
            $question = new Question('Class name of the entity to create', $default);
        }

        $question->setAutocompleterValues($this->getEntitiesForAutocomplete());
        $question->setValidator([MakeValidator::class, 'notBlank']);

        return $io->askQuestion($question);
    }

    /**
     * Get default Controller class from Entity class
     */
    public function getDefaultControllerClassFromEntityClass(string $entityClass): string
    {
        return 'Admin\\' . Str::asClassName(sprintf('%s Controller', $entityClass));
    }

    /**
     * Ask for controller class
     */
    public function askControllerClass(ConsoleStyle $io, string $defaultControllerClass): string
    {
        $question = new Question('Class name of the controller to create', $defaultControllerClass);
        $question->setValidator([MakeValidator::class, 'notBlank']);

        return $io->askQuestion($question);
    }

    /**
     * Ask for edit view type
     */
    public function askEditViewTypeClass(ConsoleStyle $io): string
    {
        $question = new ChoiceQuestion('Edit view type', [self::VIEW_MODAL, self::VIEW_PAGE], self::VIEW_MODAL);
        return $io->askQuestion($question);
    }

    /**
     * Ask entity searchable
     */
    public function askEntitySearchable(ConsoleStyle $io): bool
    {
        $question = new ConfirmationQuestion('Is your entity full-text searchable on table view', true);
        return $io->askQuestion($question);
    }

    public function template(string $name): string
    {
        return $this->baseTemplatePath . DIRECTORY_SEPARATOR . ltrim($name, DIRECTORY_SEPARATOR);
    }

    public function getRouteConfig(ClassNameDetails $controller, ?string $basePath = null): array
    {
        return [
            'base_path' => $basePath ?? Str::asRoutePath($controller->getRelativeNameWithoutSuffix()),
            'name_prefix' => $this->asRouteName($controller)
        ];
    }

    private function asRouteName(ClassNameDetails $controller): string
    {
        // App\Foo\Bar\Controller\BazController => App\Foo\Bar\Baz
        $s = str_replace('Controller\\' . $controller->getRelativeName(), '', $controller->getFullName()) . $controller->getRelativeNameWithoutSuffix();

        // App\Foo\Bar\Baz => App_Foo_Bar_Baz
        $s = str_replace('\\', '_', $s);

        // App\Foo\Bar\Baz => app_foo_bar_baz
        return strtolower($s);
    }

    /**
     * Return relative app Entities class
     */
    private function getEntitiesForAutocomplete(): array
    {
        $entities = [];

        foreach ($this->doctrine->getManagers() as $em) {
            foreach ($em->getMetadataFactory()->getAllMetadata() as $metaData) {
                if (0 === strpos($metaData->getName(), $this->rootNamespace . '\\')) {
                    $entityClassDetails = new ClassNameDetails($metaData->getName(), $this->entityNamespace);
                    $entities[] = $entityClassDetails->getRelativeName();
                }
            }
        }
        sort($entities);

        return $entities;
    }

    private function absolutizePath(string $path): string
    {
        return sprintf('%s/%s', $this->rootDirectory, $path);
    }

    public function fileExists(string $path): bool
    {
        return file_exists($this->absolutizePath($path));
    }

    public function getFileContents(string $path): string
    {
        if (!$this->fileExists($path)) {
            throw new \InvalidArgumentException(sprintf('Cannot find file "%s"', $path));
        }

        return file_get_contents($this->absolutizePath($path));
    }

    public function writeFileContents(string $path, string $content): void
    {
        file_put_contents($this->absolutizePath($path), $content);
    }
}
