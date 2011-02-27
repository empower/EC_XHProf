<?php
/**
 * EC_XHProf
 *
 * @category  EC_XHProf
 * @package   EC
 * @author    Bill Shupp <hostmaster@shupp.org>
 * @copyright 2010 Empower Campaigns
 * @license   New BSD
 * @link      http://github.com/empower/ec_xhprof
 */

/**
 * Helper for parsing xhprof output directory contents.
 *
 * @category  EC_XHProf
 * @package   EC
 * @author    Bill Shupp <hostmaster@shupp.org>
 * @copyright 2010 Empower Campaigns
 * @license   New BSD
 * @link      http://github.com/empower/ec_xhprof
 */
class EC_XHProf
{
    /**
     * The output directory
     *
     * @var string
     */
    protected $_outputDirectory = '/tmp/xhprof';

    /**
     * The namepsace to filter on (vhost)
     *
     * @var string
     */
    protected $_namespace = null;

    /**
     * The list of runs found in this namespace.  The key is the run id, the
     * value is a unix timestamp based on the atime of the run.
     *
     * @var array
     */
    protected $_runs = array();

    /**
     * Sets the namespace and output directory, then looks up the runs
     *
     * @param string $namespace       The namespace to filter on.  (vhost)
     * @param string $outputDirectory Optional output directory, used for
     *                                testing
     *
     * @return void
     */
    public function __construct($namespace, $outputDirectory = null)
    {
        if ($outputDirectory !== null) {
            $this->_outputDirectory = $outputDirectory;
        }

        $this->_namespace = $namespace;
        $this->_lookupRuns();
    }

    /**
     * Looks up the runs in this namespace
     *
     * @return void
     */
    protected function _lookupRuns()
    {
        if (!is_dir($this->_outputDirectory)) {
            return;
        }

        $skip = array('.', '..');
        $d    = dir($this->_outputDirectory);
        $path = $d->path;
        $list = array();

        for ($entry = $d->read(); $entry !== false; $entry = $d->read()) {
            if (in_array($entry, $skip)) {
                continue;
            }

            if (!preg_match("!{$this->_namespace}$!", $entry)
                && !preg_match("!media-{$this->_namespace}$!", $entry)) {
                continue;
            }

            $entryParts = explode('.', $entry);
            $run        = array_shift($entryParts);

            $stat       = stat($path . '/' . $entry);
            $list[$run] = $stat['mtime'];
        }
        asort($list);
        $this->_runs = $list;
    }

    /**
     * Returns an array of runs in this namespace.  The key is the run id, the
     * value is string formatted time.
     *
     * @return array
     */
    public function getRuns()
    {
        return array_map(array($this, 'formatTime'), $this->_runs);
    }

    /**
     * Converts the unix timestamp of the run as to a readable format
     *
     * @param int $stamp The atime stamp from stat()
     *
     * @return string
     */
    public function formatTime($stamp)
    {
        return strftime('%c', $stamp);
    }

    /**
     * Clears out existing runs from the output directory
     *
     * @return void
     */
    public function clear()
    {
        foreach ($this->_runs as $key => $value) {
            $f = $this->_outputDirectory . '/' . $key . '.' . $this->_namespace;
            if (!is_readable($f)) {
                $f = $this->_outputDirectory . '/' . $key . '.media-'
                     . $this->_namespace;
            }
            unlink($f);
            unset($this->_runs[$key]);
        }
    }
}
