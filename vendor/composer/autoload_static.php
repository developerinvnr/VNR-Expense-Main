<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitbda663c5798f4f3890e12254f9819a81
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitbda663c5798f4f3890e12254f9819a81::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitbda663c5798f4f3890e12254f9819a81::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitbda663c5798f4f3890e12254f9819a81::$classMap;

        }, null, ClassLoader::class);
    }
}
