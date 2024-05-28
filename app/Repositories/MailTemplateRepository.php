<?php

namespace App\Repositories;

class MailTemplateRepository
{
    /**
     * @param string $name El nombre del usuario
     * @param string $email El email del usuario
     * @return string la plantilla de correo electrónico de registro exitoso
     */
    public function renderRegisterTemplate(string $name, string $email): string
    {
        return
        '
        <body>
               <h1 style="font-family: Inter monospace">¡Bienvenid@ a Food Flow, <strong>'. $name .'!</strong></h1>
               <p style="font-family: system-ui, sans-serif; font-size: 16px; line-height: 1.5;">Nos complace darte la bienvenida a nuestro equipo. Esperamos que te sientas cómod@ y entusiasmad@ de unirte a nosotros.</p>
               <p style="font-family: system-ui, sans-serif; font-size: 16px; line-height: 1.5;">Con tu nueva cuenta, tendrás acceso a recursos y herramientas que te ayudarán a desempeñarte en tu rol de la mejor manera. No dudes en explorar y familiarizarte con nuestras políticas, procedimientos y sistemas internos.</p>
               <p style="font-family: system-ui, sans-serif; font-size: 16px; line-height: 1.5;">Si necesitas alguna orientación o tienes preguntas, no dudes en comunicarte con el equipo de recursos humanos o con tu supervisor directo. Estamos aquí para ayudarte y asegurarnos de que tu transición sea lo más fluida posible.</p>
               <p style="font-family: system-ui, sans-serif; font-size: 16px; line-height: 1.5;">¡Gracias por unirte a nuestro equipo y esperamos tener una colaboración exitosa juntos!</p>
               <p style="font-family: system-ui, sans-serif; font-size: 16px; line-height: 1.5;">Para acceder a la aplicación, ingresa tu correo ('. $email .') y tu contraseña.</p>
               <p style="font-family: system-ui, sans-serif; font-size: 16px; line-height: 1.5;">Saludos,<br>Equipo de Food Flow</p>
        </body>
        ';
    }

    /**
     * @param string $name El nombre del usuario
     * @return string la plantilla de correo electrónico de actualización de datos del perfil del usuario
     */
    public function renderUpdateTemplate(string $name): string
    {
        return
        '
        <body>
                <h1 style="font-family: Inter monospace; text-decoration: none;">Hola, <strong>'. $name .'</strong></h1>
                <p style="font-family: system-ui, sans-serif; font-size: 16px; line-height: 1.5;">Te escribimos para confirmar que hemos recibido y procesado los cambios de datos en tu cuenta. Todo está actualizado y listo para que sigas adelante sin preocupaciones.</p>
                <p style="font-family: system-ui, sans-serif; font-size: 16px; line-height: 1.5;">Si necesitas algo más, no dudes en volver a actualizar tu información o en contactar con el equipo de soporte.</p>
                <p style="font-family: system-ui, sans-serif; font-size: 16px; line-height: 1.5;">Saludos,<br>Equipo de Food Flow</p>
        </body>
        ';
    }

    /**
     * @param string $name El nombre del usuario
     * @return string la plantilla de correo electrónico de noticia de cambio de email
     */
    public function renderUpdatedEmailNoticeTemplate(string $name): string
    {
        return
        '
        <body>
            <h1>Hola, <strong>'. $name .'</strong></h1>
            <p style="font-family: system-ui, sans-serif; font-size: 16px; line-height: 1.5;">Te escribimos para notificarte que se ha cambiado la dirección de correo electrónico de tu cuenta.</p>
            <p style="font-family: system-ui, sans-serif; font-size: 16px; line-height: 1.5;">Si no has sido tu, por favor ponte en contacto con nosotros via correo electrónico &lt;soporte@mail.jdfs.dev&gt;</p>
            <p style="font-family: system-ui, sans-serif; font-size: 16px; line-height: 1.5;">En el caso de haber sido tú, simplemente ignora este correo electrónico.</p>
            <p style="font-family: system-ui, sans-serif; font-size: 16px; line-height: 1.5;">Saludos,<br>Equipo de Food Flow</p>
        </body>
        ';
    }

    /**
     * @param string $name El nombre del usuario
     * @return string la plantilla de correo electrónico de borrado de cuenta del usuario
     */
    public function renderGoodByeEmail(string $name): string
    {
        return
        '
        <body>
                <h1 style="font-family: Inter monospace; text-decoration: none;">Hola, <strong>'. $name .'</strong></h1>
                <p style="font-family: system-ui, sans-serif; font-size: 16px; line-height: 1.5;">Te escribimos para informarte que tu cuenta ha sido eliminada por la culminación de tu contrato en el puesto de trabajo.</p>
                <p style="font-family: system-ui, sans-serif; font-size: 16px; line-height: 1.5;">Queremos expresar nuestro más sincero agradecimiento por habernos permitido ser parte de tu viaje. Si en algún momento decides regresar, estaremos aquí para recibirte con los brazos abiertos.</p>
                <p style="font-family: system-ui, sans-serif; font-size: 16px; line-height: 1.5;">Te deseamos todo lo mejor en tus futuros proyectos y esperamos que nuestros caminos se crucen de nuevo en el futuro.</p>
                <p style="font-family: system-ui, sans-serif; font-size: 16px; line-height: 1.5;">Sinceramente,<br>Equipo de Food Flow</p>
        </body>
        ';
    }
}
