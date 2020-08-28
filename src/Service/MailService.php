<?php declare(strict_types=1);

namespace App\Service;

use Swift_IoException;
use Swift_Mailer;
use Swift_Message;
use Swift_Attachment;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MailService
{
    /**
     * @var Swift_Mailer
     */
    private $mailer;

    /**
     * Service constructor.
     *
     * @param Swift_Mailer $mailer
     */
    public function __construct(Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param $subject
     * @param $from
     * @param $to
     * @param $body
     * @param UploadedFile|null $attachmentFile
     *
     * @throws Swift_IoException
     */
    public function send($subject, $from, $to, $body, $attachmentFile = null): void
    {
        $message = (new Swift_Message($subject))
            ->setFrom($from)
            ->setTo($to)
            ->setBody(
                $body,
                'text/html'
            );

        if ($attachmentFile) {
            $attachment = (new Swift_Attachment())
                ->setFile(new \Swift_ByteStream_FileByteStream($attachmentFile->getRealPath()))
                ->setFilename($attachmentFile->getClientOriginalName());

            $message->attach($attachment);
        }

        $this->mailer->send($message);
    }
}