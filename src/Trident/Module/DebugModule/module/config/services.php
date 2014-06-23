<?php

use Trident\Component\HttpKernel\KernelEvents;

return function($container) {
    // Parameters
    $container['debug.listener.toolbar_caching.class']         = 'Trident\\Module\\DebugModule\\Listener\\ToolbarCachingListener';
    $container['debug.listener.toolbar_controller.class']      = 'Trident\\Module\\DebugModule\\Listener\\ToolbarControllerListener';
    $container['debug.listener.toolbar_doctrine_query.class']  = 'Trident\\Module\\DebugModule\\Listener\\ToolbarDoctrineQueryListener';
    $container['debug.listener.toolbar_injection.class']       = 'Trident\\Module\\DebugModule\\Listener\\ToolbarInjectionResponseListener';
    $container['debug.listener.toolbar_memory_usage.class']    = 'Trident\\Module\\DebugModule\\Listener\\ToolbarMemoryUsageListener';
    $container['debug.listener.toolbar_runtime.class']         = 'Trident\\Module\\DebugModule\\Listener\\ToolbarRuntimeListener';
    $container['debug.listener.toolbar_security.class']        = 'Trident\\Module\\DebugModule\\Listener\\ToolbarSecurityListener';
    $container['debug.toolbar.extension.caching.class']        = 'Trident\\Module\\DebugModule\\Toolbar\\Extension\\TridentCachingExtension';
    $container['debug.toolbar.extension.controller.class']     = 'Trident\\Module\\DebugModule\\Toolbar\\Extension\\TridentControllerExtension';
    $container['debug.toolbar.extension.doctrine_query.class'] = 'Trident\\Module\\DebugModule\\Toolbar\\Extension\\TridentDoctrineQueryExtension';
    $container['debug.toolbar.extension.memory_usage.class']   = 'Trident\\Module\\DebugModule\\Toolbar\\Extension\\TridentMemoryUsageExtension';
    $container['debug.toolbar.extension.runtime.class']        = 'Trident\\Module\\DebugModule\\Toolbar\\Extension\\TridentRuntimeExtension';
    $container['debug.toolbar.extension.security.class']       = 'Trident\\Module\\DebugModule\\Toolbar\\Extension\\TridentSecurityExtension';
    $container['debug.toolbar.extension.version.class']        = 'Trident\\Module\\DebugModule\\Toolbar\\Extension\\TridentVersionExtension';
    $container['debug.toolbar.class']                          = 'Trident\\Component\\Debug\\Toolbar\\Toolbar';


    // Services
    $container->set('debug.listener.toolbar_caching', function($c) {
        $caching = $c->get('caching');

        return new $c['debug.listener.toolbar_caching.class']($caching->getStack(), $c->get('debug.toolbar.extension.caching'));
    });

    $container->set('debug.listener.toolbar_controller', function($c) {
        return new $c['debug.listener.toolbar_controller.class']($c->get('debug.toolbar.extension.controller'));
    });

    $container->set('debug.listener.toolbar_doctrine_query', function($c) {
        return new $c['debug.listener.toolbar_doctrine_query.class']($c->get('doctrine.orm.sql_logger'), $c->get('debug.toolbar.extension.doctrine_query'));
    });

    $container->set('debug.listener.toolbar_injection', function($c) {
        return new $c['debug.listener.toolbar_injection.class']($c->get('templating.engine.delegating'), $c->get('debug.toolbar'));
    });

    $container->set('debug.listener.toolbar_memory_usage', function($c) {
        return new $c['debug.listener.toolbar_memory_usage.class']($c->get('debug.toolbar.extension.memory_usage'));
    });

    $container->set('debug.listener.toolbar_runtime', function($c) {
        return new $c['debug.listener.toolbar_runtime.class']($c->get('debug.toolbar.extension.runtime'));
    });

    $container->set('debug.listener.toolbar_security', function($c) {
        return new $c['debug.listener.toolbar_security.class']($c->get('debug.toolbar.extension.security'));
    });

    $container->set('debug.toolbar.extension.caching', function($c) {
        return new $c['debug.toolbar.extension.caching.class']();
    });

    $container->set('debug.toolbar.extension.controller', function($c) {
        return new $c['debug.toolbar.extension.controller.class']();
    });

    $container->set('debug.toolbar.extension.doctrine_query', function($c) {
        return new $c['debug.toolbar.extension.doctrine_query.class']();
    });

    $container->set('debug.toolbar.extension.memory_usage', function($c) {
        return new $c['debug.toolbar.extension.memory_usage.class']();
    });

    $container->set('debug.toolbar.extension.runtime', function($c) {
        return new $c['debug.toolbar.extension.runtime.class']($c->get('kernel'));
    });

    $container->set('debug.toolbar.extension.security', function($c) {
        return new $c['debug.toolbar.extension.security.class']($c->get('security'));
    });

    $container->set('debug.toolbar.extension.version', function($c) {
        return new $c['debug.toolbar.extension.version.class']($c->get('kernel'));
    });

    $container->set('debug.toolbar', function($c) {
        return new $c['debug.toolbar.class']();
    });


    // Extensions
    $container->extend('debug.toolbar', function($toolbar, $c) {
        $toolbar->addExtension($c->get('debug.toolbar.extension.version'));
        $toolbar->addExtension($c->get('debug.toolbar.extension.controller'));
        $toolbar->addExtension($c->get('debug.toolbar.extension.runtime'));
        $toolbar->addExtension($c->get('debug.toolbar.extension.memory_usage'));
        $toolbar->addExtension($c->get('debug.toolbar.extension.doctrine_query'));
        $toolbar->addExtension($c->get('debug.toolbar.extension.caching'));
        $toolbar->addExtension($c->get('debug.toolbar.extension.security'));

        return $toolbar;
    });

    $container->extend('event_dispatcher', function($dispatcher, $c) {
        $dispatcher->addListener(KernelEvents::CONTROLLER, [$c->get('debug.listener.toolbar_controller'), 'onController']);
        $dispatcher->addListener(KernelEvents::RESPONSE, [$c->get('debug.listener.toolbar_caching'), 'onResponse']);
        $dispatcher->addListener(KernelEvents::RESPONSE, [$c->get('debug.listener.toolbar_controller'), 'onResponse']);
        $dispatcher->addListener(KernelEvents::RESPONSE, [$c->get('debug.listener.toolbar_doctrine_query'), 'onResponse']);
        $dispatcher->addListener(KernelEvents::RESPONSE, [$c->get('debug.listener.toolbar_memory_usage'), 'onResponse']);
        $dispatcher->addListener(KernelEvents::RESPONSE, [$c->get('debug.listener.toolbar_runtime'), 'onResponse']);
        $dispatcher->addListener(KernelEvents::RESPONSE, [$c->get('debug.listener.toolbar_security'), 'onResponse']);

        // Must be placed after some of the other events
        $dispatcher->addListener(KernelEvents::RESPONSE, [$c->get('debug.listener.toolbar_injection'), 'onResponse']);

        return $dispatcher;
    });
};
