<?php declare(strict_types=1);

/**
 * This file is required, see official guidelines.
 * The guide not clarifying the content and the supposed intent.
 *
 * @see pag. 15 - https://www.open-emr.org/wiki/images/6/61/ModuleInstaller-DeveloperGuide.pdf - custom module section.
 */

use Laminas\HttpHandlerRunner\Emitter\SapiStreamEmitter;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use OpenEMR\Modules\MedicalMundiTodoList\Module;

(
    static function () : void {

        require __DIR__ . '/src/Module.php';

        if (Module::isStandAlone()) {
            require __DIR__ . '/vendor/autoload.php';
        } else {
            require __DIR__ . '/../../../../vendor/autoload.php';

            /**
             * Il seguente if non è richiesto in produzione,
             * ma abilita la modalità di sviluppo con installazione
             * da directory (vedi makefile: local-module-install)
             * eseguita con l'ausilio di composer-merge-plugin
             * senza questo if il modulo cerca le classi di test e genera un errore
             */
            if(file_exists(__DIR__ . '/vendor/autoload.php')){
                require __DIR__ . '/vendor/autoload.php';
            }
        }


        $psr17Factory = new Psr17Factory();
        $serverRequestFactory = new ServerRequestCreator(
            serverRequestFactory: $psr17Factory,
            uriFactory: $psr17Factory,
            uploadedFileFactory: $psr17Factory,
            streamFactory: $psr17Factory
        );

        try {
            $module = Module::bootstrap();

            // TODO CREATE REQUEST FROM GLOBALS
            $request = $serverRequestFactory->fromGlobals();
            if ($request === null) {
                $responseBody = $psr17Factory->createStream(json_encode([ 'error' => 'malformed request' ]));
                $response = $psr17Factory->createResponse(400)->withBody($responseBody);
            } else {
                $response =  $module->handle($request);
            }
        } catch (Throwable $e) {
            //logger()->error($e, ['exception' => $e, 'request' => $request ?? null]);
            $responseBody = $psr17Factory->createStream(json_encode([ 'error' => $e->getMessage() ]));
            $response = $psr17Factory->createResponse(500)->withBody($responseBody);
        }

        (new SapiStreamEmitter())->emit($response);
    }
)();
