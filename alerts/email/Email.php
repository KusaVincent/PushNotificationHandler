<?php
require_once ROOT_PATH . 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;

class Email
{
    
    private object $mailer;

    public function __construct(private string $senderEmail, private string $senderPassword, private array $config)
    {
        $this->mailer  = new PHPMailer(true);

        $this->configureMailer();
    }

    private function configureMailer(): void
    {
        try {
            $this->mailer->isSMTP();
            $this->mailer->SMTPAuth   = true;
            $this->mailer->Username   = $this->senderEmail;
            $this->mailer->Password   = $this->senderPassword;
            $this->mailer->Host       = $this->config['host'];
            $this->mailer->Port       = $this->config['port'];
            $this->mailer->SMTPSecure = $this->config['secure'];
        } catch (\Exception $e) {
            logThis(2, 'Failed to configure mailer for ' . $this->senderEmail . ': ' . $e->getMessage() . json_encode($this->config));
            throw new \Exception('Failed to configure mailer: ' . $e->getMessage());
        }
    }

    public function send(array $emails, string $subject, string $header, string $message, ?string $attachment = null): array
    {
        $results = [];

        foreach ($emails as $email) {
            $results[$email] = false;

            try {
                $this->mailer->isHTML(true);
                $this->mailer->Subject = $subject;
                $this->mailer->SetFrom($this->senderEmail, $header);
                $this->mailer->Body = $message;
                $this->mailer->AddAddress($email);
                if(null !== $attachment) $this->mailer->addAttachment($attachment);

                if ($this->mailer->Send()) $results[$email] = true;
            } catch (\Exception $e) {
                logThis(2, 'Failed to send email to ' . $email . ' from ' . $this->senderEmail . ' : ' . $e->getMessage() . json_encode($this->config));
            } finally {
                $this->mailer->ClearAddresses();
            }
        }

        $this->mailer->smtpClose();

        return $results;
    }
}