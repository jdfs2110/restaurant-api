<?php

namespace App\Services;

use App\Models\User;
use Resend\Laravel\Facades\Resend;

class MailService
{
    public function __construct(
        public readonly MailTemplateService $templateService
    )
    {
    }

    private const DOMAIN_NAME = 'jdfs.dev';

    /**
     * @param User $user El usuario al que se le envía el correo
     */
    public function sendSuccessRegisterEmail(User $user): void
    {
        Resend::emails()->send([
            'to' => $user->getEmail(),
            'from' => 'Registro <onboarding@mail.'. self::DOMAIN_NAME .'>',
            'subject' => 'Bienvenido/a',
            'html' => $this->templateService->renderRegisterTemplate($user->getName(), $user->getEmail()),
        ]);
    }

    /**
     * @param User $user El usuario al que se le envía el correo
     */
    public function sendUpdatedUserEmail(User $user): void
    {
        Resend::emails()->send([
            'to' => $user->getEmail(),
            'from' => 'Soporte <soporte@mail.'. self::DOMAIN_NAME .'>',
            'subject' => 'Modificaciones en tu cuenta',
            'html' => $this->templateService->renderUpdateTemplate($user->getName())
        ]);
    }

    /**
     * @param User $user El usuario al que se le envía el correo
     */
    public function sendGoodByeEmail(User $user): void
    {
        Resend::emails()->send([
            'to' => $user->getEmail(),
            'from' => 'Soporte <soporte@mail.'. self::DOMAIN_NAME .'>',
            'subject' => 'Borrado de cuenta',
            'html' => $this->templateService->renderGoodByeEmail($user->getName())
        ]);
    }
}
