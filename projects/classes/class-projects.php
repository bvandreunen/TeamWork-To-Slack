<?php

use GuzzleHttp\Client as Guzzle;
use Rossedman\Teamwork\Client;
use Rossedman\Teamwork\Factory as Teamwork;

class Projects {

  private $teamwork;

  public function __construct()
  {
    $client = new Client( new Guzzle, TEAMWORK_API_KEY, TEAMWORK_URL );
    $this->teamwork = new Teamwork( $client );
  }

  public function task_completed()
  {
    try
    {

      $taskId = (int) $_POST['objectId'];

      $task = $this->teamwork->task( $taskId )->find();

      $completerFirstName = $task['todo-item']['completer_firstname'];

      $projectName = $task['todo-item']['project-name'];//

      $taskListName = $task['todo-item']['todo-list-name'];//

      $taskName = $task['todo-item']['content'];

      $taskURL = TEAMWORK_URL . "/tasks/{$taskId}";

      $taskTitle = ":ballot_box_with_check: $taskName";

      $completerId = (int) $task['todo-item']['completer_id'];

      $completer = $this->teamwork->people( $completerId )->find();

      $completerIcon = $completer['person']['avatar-url'];

      $taskDescription = array(
        'fallback'   => "✔ $taskName",
        "title"      =>  $taskTitle,
        "title_link" =>  $taskURL,
        'color'      => 'good',
        'mrkdwn_in'  => array( 'text', 'pretext', 'fields' )
      );

      $taskDescription['fields'] = array(
        array(
          'title' => 'Project',
          'value' => $projectName,
          'short' => true
        ),
        array(
          'title' => 'Task list',
          'value' => $taskListName,
          'short' => true
        )
      );

      $notification = new SlackNotification;

      $notification->username = $completerFirstName;
      $notification->room = SLACK_CHANNEL;
      $notification->title = $taskName;
      $notification->attachments = $taskDescription;
      $notification->icon = $completerIcon;
      $notification->link = $taskURL;

      $notification->send();

    } catch (Exception $e) {
      error_log( var_export( $e, 1 ) );
    }

  }

  private function log( $log ) {
    $fp = fopen( __DIR__ . '/../log.txt', 'a');
    fwrite($fp, var_export($_REQUEST, 1));
    fwrite($fp, var_export($_SERVER, 1));
    fwrite($fp, var_export($log, 1));
    fwrite($fp, "\n---------------------------\n");
    fclose($fp);
  }

}
