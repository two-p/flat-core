<?php
namespace App\controller;

Class MailController{

    private $username;
    private $password;
    private $host;
    private $port;


    public function __construct($username, $password,$port,$host){
        $this->username = $username;
        $this->password = $password;
        $this->host = $host;
        $this->port = $port;
    }

    public function sendMail($data,$response,$slimView,$viewfile, $to){


            // Create the Transport
            $transport = (new \Swift_SmtpTransport($this->host,  $this->port))
                ->setUsername( $this->username)
                ->setPassword( $this->password);
            // Create the Mailer using your created Transport
            $mailer = new \Swift_Mailer($transport);

            // Create a message
            $message = (new \Swift_Message($data['subject']))
                ->setFrom($this->username)
                ->setTo($to)
                ->setBody($slimView->render($response,"mail/".$viewfile, ['data' => $data])->getBody(),'text/html');

            $result = $mailer->send($message);
     
        return $response;

    }

}
