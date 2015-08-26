<?php

  require_once( __DIR__ . '/../config.php' );
  require_once( __DIR__ . '/../classes/class-slack-message.php' );
  require_once( __DIR__ . '/vendor/autoload.php' );

  if( 'POST' != $_SERVER['REQUEST_METHOD'] ) {
    echo "This is unacceptable.";
    exit;
  }


  try {

    // set your keys
    TeamWorkPm\Auth::set( TW_API_KEY );

    $taskId = (int) $_POST['objectId'];

    $assigneeId = (int) $_POST['userId'];

    $task = TeamWorkPm\Factory::build('task')->get( $taskId );

    $projectName = $task->__get('projectName');

    $taskListName = $task->__get('todoListName');

    $taskName = $task->__get('content');

    $assigneeFirstName = $task->__get('responsiblePartyFirstname');

    $taskURL = "http://projects.superinteractive.com/tasks/{$taskId}";

    $taskTitle = ":ballot_box_with_check: $taskName";

    // $assigneeId = $task->__get('responsiblePartyId');

    $assignee = TeamWorkPm\Factory::build('people')->get( $assigneeId );

    $assigneeIcon = $assignee->__get('avatarUrl');

    $taskDescription = array(
      'fallback'   => "I completed \"$taskName\"",
      "title"      =>  $taskTitle,
      "title_link" =>  $taskURL,
      'color'      => 'good',
      'mrkdwn_in'  => array( 'text', 'pretext', 'fields' )
    );

    $taskDescription['fields'] = array(
      array(
        'title' => 'Task list',
        'value' => $taskListName,
        'short' => true
      ),
      array(
        'title' => 'Project',
        'value' => $projectName,
        'short' => true
      )
    );

    $notification = new SlackNotification;

    $notification->username = $assigneeFirstName;
    $notification->room = 'general';
    $notification->title = $taskTitle;
    $notification->attachments = $taskDescription;
    $notification->icon = $assigneeIcon;
    $notification->link = $taskURL;

    $notification->send();

  } catch (Exception $e) {
    print_r($e);
  }
