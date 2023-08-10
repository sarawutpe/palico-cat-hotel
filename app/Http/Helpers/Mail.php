<?php

namespace App\Http\Helpers;

use \Mailjet\Resources;

class Mail
{
  public static function sendMail($from = 'mr.sarawutpe@gmail.com', $to = '', $message = '')
  {
    $api_key = getenv('MAILJET_API_KEY');
    $api_secret = getenv('MAILJET_SECRET_KEY');
    $mailjet = new \Mailjet\Client($api_key, $api_secret, true, ['version' => 'v3.1']);;
    $body = [
      'Messages' => [
        [
          'From' => [
            'Email' => 'mr.sarawutpe@gmail.com',
            'Name' => "Me"
          ],
          'To' => [
            [
              'Email' => 'mr.sarawutpe@gmail.com',
              'Name' => "You"
            ]
          ],
          'Subject' => "My first Mailjet Email!",
          'TextPart' => "Greetings from Mailjet!",
          'HTMLPart' => "<h3>Dear passenger 1, welcome to <a href=".$message.">Link</a>!</h3>
            <br />May the delivery force be with you!"
        ]
      ]
    ];

    // 'HTMLPart' => "<h3>ยืนยันการกู้รหัสผ่าน <a href=\"""\">Recovery Link</a>!</h3>"
    $response = $mailjet->post(Resources::$Email, ['body' => $body]);
    return $response->success();
  }
}
