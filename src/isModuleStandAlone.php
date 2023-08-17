<?php declare(strict_types=1);

namespace OpenEMR\Modules\MedicalMundiTodoList;

class isModuleStandAlone
{
    public function __invoke(): bool
    {
        return ! file_exists($this->getGlobalsFile());
    }

    private function getGlobalsFile(): string
    {
        $interfaceRootDirectory = \dirname(__DIR__, 4);

        return $interfaceRootDirectory . DIRECTORY_SEPARATOR . "globals.php";
    }
}
