<?php

namespace App\Services\Email;

use \Mailjet\Resources;
use \Mailjet\Client;

class SignupVerification
{

    public static function get_mail_body($sender_email, $recipient_email, $recipient_name, $sub, $body)
    {
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => $sender_email,
                        'Name' => "Hamza Khan"
                    ],
                    'To' => [
                        [
                            'Email' => $recipient_email,
                            'Name' => $recipient_name
                        ]
                    ],
                    'Subject' => $sub,
                    'TextPart' => "http://127.0.0.1:8000/user/verifyAccount/" . $body
                ]
            ]
        ];
        return $body;
    }

    public static function send_email($notification)
    {
        $sender_email = "70102777@student.uol.edu.pk";
        $recipient_email = $notification['email'];
        $recipient_name = $notification['name'];
        $sub = "Account Verification";
        $body = $notification['verificationToken'];

        $mail_body = SignupVerification::get_mail_body($sender_email, $recipient_email, $recipient_name, $sub, $body);

        $mj = new \Mailjet\Client('e56e3a3e223e0029f41c57bbdfd0dd47', 'e86d2a7e0d82e53a519943e2c2d0f944', true, ['version' => 'v3.1']);
        $response = $mj->post(Resources::$Email, ['body' => $mail_body]);
        if ($response->success()) {
            return $response->getData();
        } else {
            return false;
        }
    }
}
