<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit104d38afc029ee4990ce7f81e1e2d490
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInit104d38afc029ee4990ce7f81e1e2d490', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInit104d38afc029ee4990ce7f81e1e2d490', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInit104d38afc029ee4990ce7f81e1e2d490::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
