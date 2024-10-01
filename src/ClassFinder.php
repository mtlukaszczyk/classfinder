<?php

namespace ClassFinder;


class ClassFinder
{
    private static object|null $composer = null;

    /** @var array<int, int|string> */
    private static array $classes = [];

    public function __construct()
    {
        self::$classes  = [];

        self::$composer = require base_path() . '/vendor/autoload.php';

        if (false === empty(self::$composer)) {
            self::$classes = array_keys(self::$composer->getClassMap());
        }
    }

    /**
     * @return string[]
     */
    private function getClasses(): array
    {
        $allClasses = [];

        foreach (self::$classes as $class) {
            $allClasses[] = '\\' . $class;
        }

        return $allClasses;
    }

    /**
     * @return string[]
     */
    public function getClassesByNamespace(string $namespace): array
    {
        if (!str_starts_with($namespace, '\\')) {
            $namespace = '\\' . $namespace;
        }

        $termUpper = strtoupper($namespace);

        $classes = $this->getClasses();

        return array_filter($classes, function ($class) use ($termUpper): bool {
            $className = strtoupper($class);
            return
                str_starts_with($className, $termUpper) &&
                !str_contains($className, strtoupper('Abstract')) &&
                !str_contains($className, strtoupper('Trait')) &&
                !str_contains($className, strtoupper('Base')) &&
                !str_contains($className, strtoupper('Interface'));
        });
    }
}
