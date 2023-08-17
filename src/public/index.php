<?php declare(strict_types=1);

/**
 * This file is required, see official guidelines.
 * The guide not clarifying the content and the supposed intent.
 *
 * @see pag. 15 - https://www.open-emr.org/wiki/images/6/61/ModuleInstaller-DeveloperGuide.pdf - custom module section.
 */

use Laminas\HttpHandlerRunner\Emitter\SapiStreamEmitter;
use MedicalMundi\TodoList\Module;
use OpenEMR\Modules\MedicalMundiTodoList\isModuleStandAlone;

(
    static function (): void {
        require __DIR__ . '/../../src/isModuleStandAlone.php';

        if ((new isModuleStandAlone())()) {
            require __DIR__ . '../../../vendor/autoload.php';
        } else {
            require __DIR__ . '../../../vendor/autoload.php';
        }

        $psr17Factory = new \Nyholm\Psr7\Factory\Psr17Factory();
        $serverRequestFactory = new \Nyholm\Psr7Server\ServerRequestCreator(
            $psr17Factory, // ServerRequestFactory
            $psr17Factory, // UriFactory
            $psr17Factory, // UploadedFileFactory
            $psr17Factory  // StreamFactory
        );

        try {
            $module = Module::bootstrap();

            // TODO CREATE REQUEST FROM GLOBALS
            $request = $serverRequestFactory->fromGlobals();
            if ($request === null) {
                $responseBody = $psr17Factory->createStream(json_encode([
                    'error' => 'malformed request',
                ]));
                $response = $psr17Factory->createResponse(400)->withBody($responseBody);
            } else {
                $response = $module->handle($request);
            }
        } catch (Throwable $e) {
            //logger()->error($e, ['exception' => $e, 'request' => $request ?? null]);
            $responseBody = $psr17Factory->createStream(json_encode([
                'error' => $e->getMessage(),
            ], JSON_THROW_ON_ERROR));
            $response = $psr17Factory->createResponse(500)->withBody($responseBody);
        }

        (new SapiStreamEmitter())->emit($response);
    }
)();
