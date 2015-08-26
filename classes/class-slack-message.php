<?php

class SlackNotification {

  public $title = '';
  public $room = 'general';
  public $icon = ':longbox;';
  public $attachments = array();
  public $username = 'Super Interactive';
  public $link = '';

  public function __construct()
  {

  }

  public function send()
  {

    $data = "payload=" . json_encode( array(
      "channel"       =>  "#{$this->room}",
      "icon_url"      =>  $this->icon,
      "attachments"   =>  array( $this->attachments ),
      "username"      =>  $this->username
    ));

    // You can get your webhook endpoint from your Slack settings
    $ch = curl_init( SLACK_WEBHOOK );
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);

    if( 'ok' != $result )
    {
      var_dump( $result );
    }

    exit;

    return $result;

   }

}
