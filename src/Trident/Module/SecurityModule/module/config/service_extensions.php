<?php

use Trident\Component\HttpKernel\KernelEvents;

return function($container) {
    // Extensions
    $container->extend('debug.toolbar', function($toolbar, $c) {
        $toolbar->addExtension($c->get('security.debug.toolbar.extension'));

        return $toolbar;
    }, false);

    $container->extend('event_dispatcher', function($dispatcher, $c) {
        $dispatcher->addListener(KernelEvents::REQUEST, [$c->get('security.listener.request'), 'onRequest']);

        return $dispatcher;
    });

    $container->extend('security.aegis.authorization_manager', function($authorizationManager, $c) {
        $authorizationManager->addVoter($c->get('security.aegis.authorization.voter.role'));

        return $authorizationManager;
    });
};
