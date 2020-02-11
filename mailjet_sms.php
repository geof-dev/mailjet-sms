<?php

require 'vendor/autoload.php';
use \Mailjet\Resources;

class MailjetSms
{
	private $mj;
	
	public function __construct()
	{
        require 'config.php';
        $this->mj = new \Mailjet\Client($mj_smstoken, NULL, true, ['url' => "api.mailjet.com", 'version' => 'v4', 'call' => false]);
    }

    public function sendSms($from, $to, $text){
        $body = [
        'Text' => $text,
        'To' => $to,
        'From' => $from,
        ];
        $response = $this->mj->post(Resources::$SmsSend, ['body' => $body]);
        return $response;
    }
}