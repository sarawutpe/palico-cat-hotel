<?php

namespace App\Http\Helpers;

use \Mailjet\Resources;

class Mail
{
  public static function sendMail($from = '', $to = '', $message = '')
  {
    $sender = getenv('MAILJET_SENDER');
    $api_key = getenv('MAILJET_API_KEY');
    $api_secret = getenv('MAILJET_SECRET_KEY');
    $mailjet = new \Mailjet\Client($api_key, $api_secret, true, ['version' => 'v3.1']);;
    $body = [
      'Messages' => [
        [
          'From' => [
            'Email' => $sender,
            'Name' => "Me"
          ],
          'To' => [
            [
              'Email' => $to,
              'Name' => "You"
            ]
          ],
          'Subject' => "PALICO CAT HOTEL | กู้รหัสผ่าน",
          'TextPart' => "Greetings from Mailjet!",
          'HTMLPart' => "<h3>ยืนยันกู้รหัสผ่าน , <a href=".$message.">".$message."</a>!</h3>"
        ]
      ]
    ];

    $response = $mailjet->post(Resources::$Email, ['body' => $body]);
    return $response->success();
  }
}
