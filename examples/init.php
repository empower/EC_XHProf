<?php

// Example check at the top of your application for enabling profiling
if (isset($_SERVER['WWW_PROFILE']) && extension_loaded('xhprof')) {
    $xproflib = APPLICATION_PATH . '/../public/build/xhprof/xhprof_lib';
    include_once $xproflib . '/utils/xhprof_lib.php';
    include_once $xproflib . '/utils/xhprof_runs.php';
    xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);

    function stop_xhprof_profiling()
    {
        $xhprofData      = xhprof_disable();
        $xhprofNameSpace = $_SERVER['SERVER_NAME'];
        $xhprofRuns      = new XHProfRuns_Default();
        $xhprofRunID     = $xhprofRuns->save_run($xhprofData, $xhprofNameSpace);
    }
    register_shutdown_function('stop_xhprof_profiling');
}
