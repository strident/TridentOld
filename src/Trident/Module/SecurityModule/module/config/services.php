<?php

use Trident\Component\HttpKernel\KernelEvents;

return function($container) {
    // Paremeters
    $container['security.aegis.provider.delegating.class'] = 'Aegis\\Authentication\\Provider\\DelegatingAuthenticationProvider';
    $container['security.aegis.storage.session.class']     = 'Trident\\Bridge\\Strident\\Aegis\\Storage\\SessionStorage';
    $container['security.listener.boot.class']             = 'Trident\\Module\\SecurityModule\\Listener\\BootListener';
    $container['security.class']                           = 'Aegis\\Aegis';


    // Services
    $container->set('security.aegis.provider.delegating', function($c) {
        return new $c['security.aegis.provider.delegating.class']();
    });

    $container->set('security.aegis.storage.session', function($c) {
        $sessionKey = $c->get('configuration')->get('security.session.key', 'trident.session');

        return new $c['security.aegis.storage.session.class']($c->get('session'), $sessionKey);
    });

    $container->set('security.listener.boot', function($c) {
        return new $c['security.listener.boot.class']();
    });

    $container->set('security', function($c) {
        $security = new $c['security.class']();
        $security->setProvider($c->get('security.aegis.provider.delegating'));
        $security->setStorage($c->get('security.aegis.storage.session'));

        return $security;
    });


    // Extensions
    $container->extend('event_dispatcher', function($dispatcher, $c) {
        $dispatcher->addListener(KernelEvents::BOOT, [$c->get('security.listener.boot'), 'onBoot']);

        return $dispatcher;
    });

    $container->extend('security.aegis.provider.delegating', function($provider, $c) {
        $provider->addProvider(new \Aegis\Authentication\Provider\FakeUserProvider($c->get('request')));

        return $provider;
    });
};