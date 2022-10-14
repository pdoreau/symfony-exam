<?php

namespace App\Service;

class File
{
    public function __construct(
        public readonly string $name,
    ) {
    }
}

class Directory
{
    /**
     * @param string $name
     * @param (File|Directory)[] $children
     */
    public function __construct(
        public readonly string $name,
        public readonly array $children,
    ) {
    }
}

class VisitFiles
{
    /**
     * Traverse Files & Directories.
     *
     * Return a list of every files filtered by given function.
     *
     * @param Directory $root
     * @param callable $filterFn
     *
     * @return array
     */
    public function visitFiles(Directory $root, callable $filterFn): array
    {
        $result = [];

        foreach ($root->children as $child)
        {
            if ($child instanceof File && $filterFn($child)) {
                $result[] = $child;
            }
            if ($child instanceof Directory) {
                $result = [...$result, ...$this->visitFiles($child, $filterFn)];
            }
        }

        return $result;
    }

    public function usageExemple(Directory $root): array
    {
        return $this->visitFiles(
            $root,
            function ($file) {
                $name = $file->name;
                for ($i = 0; $i < floor(strlen($name)); $i++) {
                    if ($name[$i] != $name[strlen($name) - $i - 1]) {
                        return false;
                    }
                }
                return true;
            }
        );
    }
}
