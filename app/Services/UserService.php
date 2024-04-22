<?php

namespace App\Services;

use App\Models\User;
use Resend\Laravel\Facades\Resend;

class UserService
{
    /**
     * @throws \ReflectionException
     */
    public function sendSuccessRegisterEmail(User $user): void
    {
        Resend::emails()->send([
            'to' => $user->getEmail(),
            'from' => 'Registro <onboarding@mail.jdfs.tech>',
            'subject' => 'Bienvenido/a',
            'html' =>
            '
            <body>
               <h1 style="font-family: Calibri monospace">¡Bienvenido/a, <strong>'. $user->getName() .'!</strong></h1>
               <p style="font-family: Arial, sans-serif; font-size: 16px; line-height: 1.5;">Nos complace darte la bienvenida a nuestro equipo. Esperamos que te sientas cómodo/a y entusiasmado/a de unirte a nosotros.</p>
               <p style="font-family: Arial, sans-serif; font-size: 16px; line-height: 1.5;">Con tu nueva cuenta, tendrás acceso a recursos y herramientas que te ayudarán a desempeñarte en tu rol de la mejor manera como '. $user->getRol() .'. No dudes en explorar y familiarizarte con nuestras políticas, procedimientos y sistemas internos.</p>
               <p style="font-family: Arial, sans-serif; font-size: 16px; line-height: 1.5;">Si necesitas alguna orientación o tienes preguntas, no dudes en comunicarte con el equipo de recursos humanos o con tu supervisor directo. Estamos aquí para ayudarte y asegurarnos de que tu transición sea lo más fluida posible.</p>
               <p style="font-family: Arial, sans-serif; font-size: 16px; line-height: 1.5;">¡Gracias por unirte a nuestro equipo y esperamos tener una colaboración exitosa juntos!</p>
               <p style="font-family: Arial, sans-serif; font-size: 16px; line-height: 1.5;">Para acceder a la aplicación, ingresa tu correo ('. $user->getEmail() .') y tu contraseña.</p>
               <p style="font-family: Arial, sans-serif; font-size: 16px; line-height: 1.5;">Saludos,<br>Jose Fernandez<br>Webmaster</p>
            </body>
            '
        ]);
    }
}
