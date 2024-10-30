<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit375ddb73af33df3fd96c3ccdc043b069
{
    public static $classMap = array (
        'Isizer_Main' => __DIR__ . '/../..' . '/inc/class-isizer-main.php',
        'Isizer_Settings' => __DIR__ . '/../..' . '/inc/class-isizer-settings.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInit375ddb73af33df3fd96c3ccdc043b069::$classMap;

        }, null, ClassLoader::class);
    }
}
