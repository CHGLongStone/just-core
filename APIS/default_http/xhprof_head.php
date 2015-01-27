<?
//xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
if (extension_loaded('xhprof')) {
    echo '<b>RUN</b>xhprof';
	include_once '/var/www/JCORE/APIS/default_admin_http/xhprof_lib/utils/xhprof_lib.php';
    include_once '/var/www/JCORE/APIS/default_admin_http/xhprof_lib/utils/xhprof_runs.php';
    xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
}
?>