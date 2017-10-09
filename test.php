<?php

require '/var/www/conf/keys.php';
# Include the Autoloader (see "Libraries" for install instructions)
require 'vendor/autoload.php';
use Mailgun\Mailgun;

# Instantiate the client.
$mgClient = new Mailgun(MG_KEY);
$domain = MG_DOMAIN;

# Make the call to the client.
$from = "Mailgun Sandbox <postmaster@{$domain}>";
$to = 'Jason Snider <jason@jasonsnider.com>';
$subject = 'Hello Jason Snider';
$text = 'Congratulations Jason Snider, you just sent an email with Mailgun! You are truly awesome!';
$result = $mgClient->sendMessage(
  $domain,
  array('from'    => $from,
        'to'      => $to,
        'subject' => $subject,
        'text'    => $text
    )
);
