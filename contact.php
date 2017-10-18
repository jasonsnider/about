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

?>
<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="UTF-8">
      <title>About Jason Snider</title>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="stylesheet" type="text/css" href="css/dist/main.css">
  </head>

  <body>
      
      <div id="Wrapper">
          <nav class="top-nav">
              <a href="index.html" class="pull-left" href="/">Site Logo</a>
              <ul role="navigation">
                  <li><a href="index.html">Home</a></li>
                  <li><a href="about.html">About</a></li>
                  <li><a href="contact.php">Contact</a></li>
              </ul>
          </nav>

          <div class="row">
              <div id="Content" class="col">

                  <?php if(empty($valid->errors) && !empty($input)): ?>
                    <div>Success!</div>
                  <?php else: ?>
                    <div>You page has errors.</div>
                  <?php endif; ?>

                  <form method="post" action="contact.php">

                    <div>
                      <label for="firstName">First Name</label><br>
                      <input type="text" name="first_name" id="firstName" value="<?php echo $valid->userInput('first_name'); ?>">
                      <div style="color: #ff0000;"><?php echo $valid->error('first_name'); ?></div>
                    </div>

                    <div>
                      <label for="lastName" id="lastName">Last Name</label><br>
                      <input type="text" name="last_name" value="<?php echo $valid->userInput('last_name'); ?>">
                      <div style="color: #ff0000;"><?php echo $valid->error('last_name'); ?></div>
                    </div>

                    <div>
                      <label for="email" id="email">Email</label><br>
                      <input type="text" name="email" value="<?php echo $valid->userInput('email'); ?>">
                      <div style="color: #ff0000;"><?php echo $valid->error('email'); ?></div>
                    </div>

                    <div>
                      <label for="subject" id="subject">Subject</label><br>
                      <input type="text" name="subject" value="<?php echo $valid->userInput('subject'); ?>">
                      <div style="color: #ff0000;"><?php echo $valid->error('subject'); ?></div>
                    </div>

                    <div>
                      <label for="message" id="message">Message</label><br>
                      <textarea name="message"><?php echo $valid->userInput('message'); ?></textarea>
                      <div style="color: #ff0000;"><?php echo $valid->error('message'); ?></div>
                    </div>


                    <input type="submit">

                  </form>
              </div>
              <div id="Sidebar" class="col"></div>
          </div>

          <div id="Footer" class="clearfix">
              <small>&copy; 2017 - MyAwesomeSite.com</small>
              <ul role="navigation">
                  <li><a href="terms.html">Terms</a></li>
                  <li><a href="privacy.html">Privacy</a></li>
              </ul>
          </div>
      </div>

  </body>

</html>
