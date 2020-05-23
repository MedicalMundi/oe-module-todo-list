<?php declare(strict_types=1);


namespace MedicalMundi\TodoList;

class isModuleStandAlone
{
    public function __invoke()
    {
        return !file_exists($this->getGlobalsFile());
    }

    private function getGlobalsFile(): string
    {
        $interfaceRootDirectory = \dirname(__DIR__, 5);

        return $interfaceRootDirectory . DIRECTORY_SEPARATOR . "globals.php";
    }
}
