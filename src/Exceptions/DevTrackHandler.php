<?php

namespace YGThor\LaravelErrorNotify\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Bridge\Google\Smtp\GmailTransport;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use \Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Spatie\Permission\Exceptions\UnauthorizedException;

class DevTrackHandler extends ExceptionHandler
{
    public function report($e)
    {
        // emails.exception is the template of your email
        // it will have access to the $error that we are passing below
        $excluded = [
            $e instanceof AuthenticationException,
            $e instanceof NotFoundHttpException,
            $e instanceof UnauthorizedException,
        ];

        $check_excluded = array_filter($excluded, function ($v) {
            return $v;
        });

        if (count($check_excluded) == 0) {
            try {
                $debugger_email = config('laravel-error-notify.DEBUGGER_EMAIL');
                $mailer = config('laravel-error-notify.DEBUGGER_MAIL_MAILER');
                $host = config('laravel-error-notify.DEBUGGER_MAIL_HOST');
                $port = config('laravel-error-notify.DEBUGGER_MAIL_PORT');
                $username = config('laravel-error-notify.DEBUGGER_MAIL_USERNAME');
                $password = config('laravel-error-notify.DEBUGGER_MAIL_PASSWORD');
                $encryption = config('laravel-error-notify.DEBUGGER_MAIL_ENCRYPTION');
                $app_name = config('app.name');

                if (!empty($debugger_email)) {
                    // Create a Symfony Mailer instance
                    $transport = Transport::fromDsn('smtp://' . $host)  
                    ->setUsername($username)
                    ->setPassword($password);

                    $mailer = new Mailer($transport);

                    // Create an email message
                    $email = (new Email())
                        ->from(new Address($username, $app_name))
                        ->to(new Address($debugger_email))
                        ->subject('[' . \Request::ip() . ']' . $app_name . ': ' . $e->getMessage())
                        ->html(view('ygthor-laravel-error-notify::mails.exception', ['error' => $e])->render());

                    // Send the email
                    $mailer->send($email);
                }
            } catch (\Exception $exc) {
                // dd($exc->getMessage());
            }
        }

        return parent::report($e);
    }
}
