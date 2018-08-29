<?php

function pi_poEnviaEmail($correoRemitente, $passwordRemitente, $nombreRemitente, $correoDestinatario, $asunto, $contenidoHTML, $correoCopia = '',$nombreCorreoCopia='')
{

    $transport = (new Swift_SmtpTransport('smtp.gmail.com', 587, 'TLS'))
        ->setUsername($correoRemitente)
        ->setPassword($passwordRemitente)
        ->setStreamOptions(array(
            'ssl' => array(
                'allow_self_signed' => true, 'verify_peer' => false
            )
        ));
    $mailer = new Swift_Mailer($transport);
    if ($correoCopia == '') {
        $message = (new Swift_Message($asunto))
            ->setSubject($asunto)
            ->setFrom('' . $correoDestinatario, $nombreRemitente)
            ->setTo('' . $correoDestinatario);
    } else {
        $message = (new Swift_Message($asunto))
            ->setSubject($asunto)
            ->setFrom('' . $correoDestinatario, $nombreRemitente)
            ->setTo('' . $correoDestinatario)
            ->setCc($correoCopia, $nombreCorreoCopia);
    }


    $message->setBody($contenidoHTML, 'text/html');

    $mailer->send($message);
}


