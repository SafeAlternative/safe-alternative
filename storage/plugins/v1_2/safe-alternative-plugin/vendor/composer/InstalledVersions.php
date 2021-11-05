<?php

namespace SafeAlternative\Composer;

use SafeAlternative\Composer\Autoload\ClassLoader;
use SafeAlternative\Composer\Semver\VersionParser;
class InstalledVersions
{
    private static $installed = array('root' => array('pretty_version' => 'dev-master', 'version' => 'dev-master', 'aliases' => array(), 'reference' => '99a72bf6878e5b07902d45415c96e798bac800e5', 'name' => 'safealternative/plugin'), 'versions' => array('cebe/markdown' => array('pretty_version' => '1.2.1', 'version' => '1.2.1.0', 'aliases' => array(), 'reference' => '9bac5e971dd391e2802dca5400bbeacbaea9eb86'), 'clegginabox/pdf-merger' => array('pretty_version' => 'dev-master', 'version' => 'dev-master', 'aliases' => array(0 => '9999999-dev'), 'reference' => 'ca9367d0d4e21faa686414961d3e096973ecef14'), 'safealternative/plugin' => array('pretty_version' => 'dev-master', 'version' => 'dev-master', 'aliases' => array(), 'reference' => '99a72bf6878e5b07902d45415c96e798bac800e5'), 'sameday-courier/php-sdk' => array('pretty_version' => 'v1.8.3', 'version' => '1.8.3.0', 'aliases' => array(), 'reference' => '5e1ebdc9c960b4c2cd85338d3694e8a1ff1516ca'), 'setasign/fpdf' => array('pretty_version' => '1.8.3', 'version' => '1.8.3.0', 'aliases' => array(), 'reference' => '6a83253ece0df1c5b6c05fe7a900c660ae38afc3'), 'setasign/fpdi' => array('pretty_version' => 'v2.3.6', 'version' => '2.3.6.0', 'aliases' => array(), 'reference' => '6231e315f73e4f62d72b73f3d6d78ff0eed93c31'), 'symfony/polyfill-mbstring' => array('pretty_version' => 'v1.23.0', 'version' => '1.23.0.0', 'aliases' => array(), 'reference' => '2df51500adbaebdc4c38dea4c89a2e131c45c8a1'), 'symfony/polyfill-php80' => array('pretty_version' => 'v1.23.0', 'version' => '1.23.0.0', 'aliases' => array(), 'reference' => 'eca0bf41ed421bed1b57c4958bab16aa86b757d0'), 'symfony/var-dumper' => array('pretty_version' => 'v5.2.8', 'version' => '5.2.8.0', 'aliases' => array(), 'reference' => 'd693200a73fae179d27f8f1b16b4faf3e8569eba'), 'tightenco/collect' => array('pretty_version' => 'v7.26.1', 'version' => '7.26.1.0', 'aliases' => array(), 'reference' => '5e460929279ad806e59fc731e649e9b25fc8774a')));
    private static $canGetVendors;
    private static $installedByVendor = array();
    public static function getInstalledPackages()
    {
        $packages = array();
        foreach (self::getInstalled() as $installed) {
            $packages[] = \array_keys($installed['versions']);
        }
        if (1 === \count($packages)) {
            return $packages[0];
        }
        return \array_keys(\array_flip(\call_user_func_array('array_merge', $packages)));
    }
    public static function isInstalled($packageName)
    {
        foreach (self::getInstalled() as $installed) {
            if (isset($installed['versions'][$packageName])) {
                return \true;
            }
        }
        return \false;
    }
    public static function satisfies(VersionParser $parser, $packageName, $constraint)
    {
        $constraint = $parser->parseConstraints($constraint);
        $provided = $parser->parseConstraints(self::getVersionRanges($packageName));
        return $provided->matches($constraint);
    }
    public static function getVersionRanges($packageName)
    {
        foreach (self::getInstalled() as $installed) {
            if (!isset($installed['versions'][$packageName])) {
                continue;
            }
            $ranges = array();
            if (isset($installed['versions'][$packageName]['pretty_version'])) {
                $ranges[] = $installed['versions'][$packageName]['pretty_version'];
            }
            if (\array_key_exists('aliases', $installed['versions'][$packageName])) {
                $ranges = \array_merge($ranges, $installed['versions'][$packageName]['aliases']);
            }
            if (\array_key_exists('replaced', $installed['versions'][$packageName])) {
                $ranges = \array_merge($ranges, $installed['versions'][$packageName]['replaced']);
            }
            if (\array_key_exists('provided', $installed['versions'][$packageName])) {
                $ranges = \array_merge($ranges, $installed['versions'][$packageName]['provided']);
            }
            return \implode(' || ', $ranges);
        }
        throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
    }
    public static function getVersion($packageName)
    {
        foreach (self::getInstalled() as $installed) {
            if (!isset($installed['versions'][$packageName])) {
                continue;
            }
            if (!isset($installed['versions'][$packageName]['version'])) {
                return null;
            }
            return $installed['versions'][$packageName]['version'];
        }
        throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
    }
    public static function getPrettyVersion($packageName)
    {
        foreach (self::getInstalled() as $installed) {
            if (!isset($installed['versions'][$packageName])) {
                continue;
            }
            if (!isset($installed['versions'][$packageName]['pretty_version'])) {
                return null;
            }
            return $installed['versions'][$packageName]['pretty_version'];
        }
        throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
    }
    public static function getReference($packageName)
    {
        foreach (self::getInstalled() as $installed) {
            if (!isset($installed['versions'][$packageName])) {
                continue;
            }
            if (!isset($installed['versions'][$packageName]['reference'])) {
                return null;
            }
            return $installed['versions'][$packageName]['reference'];
        }
        throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
    }
    public static function getRootPackage()
    {
        $installed = self::getInstalled();
        return $installed[0]['root'];
    }
    public static function getRawData()
    {
        @\trigger_error('getRawData only returns the first dataset loaded, which may not be what you expect. Use getAllRawData() instead which returns all datasets for all autoloaders present in the process.', \E_USER_DEPRECATED);
        return self::$installed;
    }
    public static function getAllRawData()
    {
        return self::getInstalled();
    }
    public static function reload($data)
    {
        self::$installed = $data;
        self::$installedByVendor = array();
    }
    private static function getInstalled()
    {
        if (null === self::$canGetVendors) {
            self::$canGetVendors = \method_exists('SafeAlternative\\Composer\\Autoload\\ClassLoader', 'getRegisteredLoaders');
        }
        $installed = array();
        if (self::$canGetVendors) {
            foreach (ClassLoader::getRegisteredLoaders() as $vendorDir => $loader) {
                if (isset(self::$installedByVendor[$vendorDir])) {
                    $installed[] = self::$installedByVendor[$vendorDir];
                } elseif (\is_file($vendorDir . '/composer/installed.php')) {
                    $installed[] = self::$installedByVendor[$vendorDir] = (require $vendorDir . '/composer/installed.php');
                }
            }
        }
        $installed[] = self::$installed;
        return $installed;
    }
}