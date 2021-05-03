<?php

namespace Modules\Installer\Helpers;

/**
 * Class RequirementsChecker
 *
 * Requirements helper to check permission.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\Installer
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @author    Another Author <another@example.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class RequirementsChecker
{

    /**
     * Minimum PHP Version Supported (Override is in installer.php config file).
     *
     * @var _minPhpVersion
     */
    private $_minPhpVersion = '7.1.3';

    /**
     * Check for the server requirements.
     *
     * @param Array $requirements [Requirements array]
     *
     * @return Array
     */
    public function check(array $requirements)
    {
        $results = [];
        foreach ($requirements as $type => $requirement) {
            switch ($type) {
            // check php requirements
            case 'php':
                foreach ($requirements[$type] as $requirement) {
                    $results['requirements'][$type][$requirement] = true;

                    if (!extension_loaded($requirement)) {
                        $results['requirements'][$type][$requirement] = false;

                        $results['errors'] = true;
                    }
                }
                break;
            // check apache requirements
            case 'apache':
                foreach ($requirements[$type] as $requirement) {
                    // if function doesn't exist we can't check apache modules
                    if (function_exists('apache_get_modules')) {
                        $results['requirements'][$type][$requirement] = true;

                        if (!in_array($requirement, apache_get_modules())) {
                            $results['requirements'][$type][$requirement] = false;

                            $results['errors'] = true;
                        }
                    }
                }
                break;
            }
        }
        return $results;
    }

    /**
     * Check PHP version requirement.
     *
     * @param String $minPhpVersion [Min php version]
     *
     * @return Array
     */
    public function checkPHPversion($minPhpVersion = null)
    {
        $minVersionPhp = $minPhpVersion;
        $currentPhpVersion = $this->_getPhpVersionInfo();
        $supported = false;

        if ($minPhpVersion == null) {
            $minVersionPhp = $this->getMinPhpVersion();
        }

        if (version_compare($currentPhpVersion['version'], $minVersionPhp) >= 0) {
            $supported = true;
        }

        $phpStatus = [
            'full' => $currentPhpVersion['full'],
            'current' => $currentPhpVersion['version'],
            'minimum' => $minVersionPhp,
            'supported' => $supported
        ];

        return $phpStatus;
    }

    /**
     * Get current Php version information.
     *
     * @return Array
     */
    private static function _getPhpVersionInfo()
    {
        $currentVersionFull = PHP_VERSION;
        preg_match("#^\d+(\.\d+)*#", $currentVersionFull, $filtered);
        $currentVersion = $filtered[0];

        return [
            'full' => $currentVersionFull,
            'version' => $currentVersion
        ];
    }

    /**
     * Get minimum PHP version ID.
     *
     * @return String _minPhpVersion
     */
    protected function getMinPhpVersion()
    {
        return $this->_minPhpVersion;
    }
}
