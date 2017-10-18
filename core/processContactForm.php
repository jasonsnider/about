<?php
// Include the Autoloader for Composer
require 'vendor/autoload.php';

// Include non-vendor files
require 'core/About/src/Validation/Validate.php';
require 'config/keys.php';

//Declare Namespaces
use Mailgun\Mailgun;

//Mailgun Declarations
$mgClient = new Mailgun(MG_KEY);
$domain = MG_DOMAIN;

//Validate Declarations
$valid = new About\Validation\Validate();
$input = filter_input_array(INPUT_POST);
if(!empty($input)){

    $valid->validation = [
        'first_name'=>[[
            'rule'=>'notEmpty',
            'message'=>'Please enter your first name'
        ]],
        'last_name'=>[[
            'rule'=>'notEmpty',
            'message'=>'Please enter your last name'
        ]],
        'email'=>[[
                'rule'=>'email',
                'message'=>'Please enter a valid email'
            ],[
                'rule'=>'notEmpty',
                'message'=>'Please enter an email'
        ]],
        'subject'=>[[
            'rule'=>'notEmpty',
            'message'=>'Please enter a subject'
        ]],
        'message'=>[[
            'rule'=>'notEmpty',
            'message'=>'Please add a message'
        ]],
    ];


    $valid->check($input);
}

if(empty($valid->errors) && !empty($input)){

    # Make the call to the client.
    $from = "Mailgun Sandbox <postmaster@{$domain}>";
    $to = "Jason Snider <jason@jasonsnider.com>";
    $subject = "Contact Form: {$input['subject']}";
    $text = "{$input['first_name']}  {$input['last_name']} <{$input['email']}>"
        . "\n{$input['subject']}\n{$input['message']}>";

    $result = $mgClient->sendMessage(
      $domain,
      array('from'    => $from,
            'to'      => $to,
            'subject' => $subject,
            'text'    => $text
        )
    );
}

if(empty($valid->errors) && !empty($input)){
    $message = "<div>Success!</div>";
}else{
    $message = "<div>Error!</div>";
}
