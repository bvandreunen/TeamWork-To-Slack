<?php

  // $fp = fopen( __DIR__ . '/log.txt', 'a');
  // fwrite($fp, var_export($_REQUEST, 1));
  // fwrite($fp, var_export($_SERVER, 1));
  // // fwrite($fp, var_export($log, 1));
  // fwrite($fp, "\n---------------------------\n");
  // fclose($fp);

  if( 'POST' != $_SERVER['REQUEST_METHOD'] || ! strstr( $_SERVER['HTTP_USER_AGENT'], 'TeamworkPM' ) )
  {
    header('HTTP/1.0 403 Forbidden');
    exit;
  }

  require_once __DIR__ . "/../vendor/autoload.php";

  require_once __DIR__ . "/../config.php";
  require_once __DIR__ . "/classes/class-projects.php";
  require_once __DIR__ . "/classes/class-slack-message.php";

  # TASK.COMPLETED
  if( 'TASK.COMPLETED' == $_POST['event'] )
  {
    $project = new Projects;
    $project->task_completed();
  }


