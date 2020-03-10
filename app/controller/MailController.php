<?php
namespace App\controller;

Class MailController{

    private $username;
    private $password;
    private $host;
    private $port;
    private $recaptcha;


    public function __construct($username, $password,$recaptchaSecret,$port,$host){
        $this->username = $username;
        $this->password = $password;
        $this->host = $host;
        $this->port = $port;
        $this->recaptcha = new \ReCaptcha\ReCaptcha($recaptchaSecret);
    }

    public function sendMail($data,$response,$slimView,$viewfile, $to){
        $resp = $this->recaptcha->verify($data['g-recaptcha-response']);
        if ($resp->isSuccess()) {
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
            var_dump($result);
        }else{
            $errors = $resp->getErrorCodes();
            var_dump($errors);
        }

        return $response;

    }

}
