<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit9750d92d43d57a8b26da0d72382f2f76
{
    public static $files = array (
        '6315051930cba42fefa0455c5ed59785' => __DIR__ . '/../..' . '/../../../Repository/RepositoryObject/Panopto/vendor/autoload.php',
    );

    public static $prefixLengthsPsr4 = array (
        's' => 
        array (
            'srag\\LibrariesNamespaceChanger\\' => 31,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'srag\\LibrariesNamespaceChanger\\' => 
        array (
            0 => __DIR__ . '/..' . '/srag/librariesnamespacechanger/src',
        ),
    );

    public static $classMap = array (
        'ilPanoptoPageComponentPlugin' => __DIR__ . '/../..' . '/classes/class.ilPanoptoPageComponentPlugin.php',
        'ilPanoptoPageComponentPluginGUI' => __DIR__ . '/../..' . '/classes/class.ilPanoptoPageComponentPluginGUI.php',
        'ppcoVideoFormGUI' => __DIR__ . '/../..' . '/classes/class.ppcoVideoFormGUI.php',
        'srag\\LibrariesNamespaceChanger\\GeneratePluginPhpAndXml' => __DIR__ . '/..' . '/srag/librariesnamespacechanger/src/GeneratePluginPhpAndXml.php',
        'srag\\LibrariesNamespaceChanger\\LibrariesNamespaceChanger' => __DIR__ . '/..' . '/srag/librariesnamespacechanger/src/LibrariesNamespaceChanger.php',
        'srag\\LibrariesNamespaceChanger\\PHP72Backport' => __DIR__ . '/..' . '/srag/librariesnamespacechanger/src/PHP72Backport.php',
        'srag\\LibrariesNamespaceChanger\\PHP7Backport' => __DIR__ . '/..' . '/srag/librariesnamespacechanger/src/PHP7Backport.php',
        'srag\\LibrariesNamespaceChanger\\RemovePHP72Backport' => __DIR__ . '/..' . '/srag/librariesnamespacechanger/src/RemovePHP72Backport.php',
        'srag\\LibrariesNamespaceChanger\\UpdatePluginReadme' => __DIR__ . '/..' . '/srag/librariesnamespacechanger/src/UpdatePluginReadme.php',
        'srag\\Plugins\\PanoptoPageComponent\\Util\\PermissionUtils' => __DIR__ . '/../..' . '/src/Util/PermissionUtils.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit9750d92d43d57a8b26da0d72382f2f76::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit9750d92d43d57a8b26da0d72382f2f76::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit9750d92d43d57a8b26da0d72382f2f76::$classMap;

        }, null, ClassLoader::class);
    }
}
