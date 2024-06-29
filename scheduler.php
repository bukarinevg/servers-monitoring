
<?php 
/**
 * This is the script that will be executed by the cron job.
 */
require_once 'vendor/autoload.php';
use GO\Scheduler;


// $scheduler = new Scheduler();
// $scheduler->php('/var/www/html/script.php')->everyMinute();
// $scheduler->run();


// $scheduler = new Scheduler();
// $scheduler
//   ->raw("echo ".date('Y-m-d H:i')." >> /var/log/cron.log")
//   ->everyMinute();
// $scheduler->run();