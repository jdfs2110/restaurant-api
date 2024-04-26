<?php

namespace App\Services;

use App\Exceptions\IncorrectLoginException;
use App\Exceptions\ModelNotFoundException;
use App\Exceptions\NoContentException;
use App\Exceptions\UserIsNotWaiterException;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;
use Resend\Laravel\Facades\Resend;

class UserService
{
    public function __construct(
        public UserRepository $repository
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
            'html' =>
            '
            <body>
               <h1 style="font-family: Inter monospace">¡Bienvenido/a, <strong>'. $user->getName() .'!</strong></h1>
               <p style="font-family: system-ui, sans-serif; font-size: 16px; line-height: 1.5;">Nos complace darte la bienvenida a nuestro equipo. Esperamos que te sientas cómodo/a y entusiasmado/a de unirte a nosotros.</p>
               <p style="font-family: system-ui, sans-serif; font-size: 16px; line-height: 1.5;">Con tu nueva cuenta, tendrás acceso a recursos y herramientas que te ayudarán a desempeñarte en tu rol de la mejor manera. No dudes en explorar y familiarizarte con nuestras políticas, procedimientos y sistemas internos.</p>
               <p style="font-family: system-ui, sans-serif; font-size: 16px; line-height: 1.5;">Si necesitas alguna orientación o tienes preguntas, no dudes en comunicarte con el equipo de recursos humanos o con tu supervisor directo. Estamos aquí para ayudarte y asegurarnos de que tu transición sea lo más fluida posible.</p>
               <p style="font-family: system-ui, sans-serif; font-size: 16px; line-height: 1.5;">¡Gracias por unirte a nuestro equipo y esperamos tener una colaboración exitosa juntos!</p>
               <p style="font-family: system-ui, sans-serif; font-size: 16px; line-height: 1.5;">Para acceder a la aplicación, ingresa tu correo ('. $user->getEmail() .') y tu contraseña.</p>
               <p style="font-family: system-ui, sans-serif; font-size: 16px; line-height: 1.5;">Saludos,<br>Jose Fernandez<br>Webmaster</p>
            </body>
            '
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
            'html' =>
            '
            <body>
                <h1 style="font-family: Inter monospace; text-decoration: none;">Hola, <strong>'. $user->getName() .'</strong></h1>
                <p style="font-family: system-ui, sans-serif; font-size: 16px; line-height: 1.5;">Te escribimos para confirmar que hemos recibido y procesado los cambios de datos en tu cuenta. Todo está actualizado y listo para que sigas adelante sin preocupaciones.</p>
                <p style="font-family: system-ui, sans-serif; font-size: 16px; line-height: 1.5;">Si necesitas algo más, no dudes en volver a actualizar tu información o en contactar con el equipo de soporte.</p>
                <p style="font-family: system-ui, sans-serif; font-size: 16px; line-height: 1.5;">Saludos,<br>Jose Fernandez<br>Webmaster</p>
            </body>
            '
        ]);
    }

    public function sendGoodByeEmail(User $user): void
    {
        Resend::emails()->send([
            'to' => $user->getEmail(),
            'from' => 'Soporte <soporte@mail.'. self::DOMAIN_NAME .'>',
            'subject' => 'Borrado de cuenta',
            'html' =>
            '
            <body>
                <h1 style="font-family: Inter monospace; text-decoration: none;">Hola, <strong>'. $user->getName() .'</strong></h1>
                <p style="font-family: system-ui, sans-serif; font-size: 16px; line-height: 1.5;">Te escribimos para informarte que tu cuenta ha sido eliminada por la culminación de tu estancia en el puesto de trabajo.</p>
                <p style="font-family: system-ui, sans-serif; font-size: 16px; line-height: 1.5;">Queremos expresar nuestro más sincero agradecimiento por habernos permitido ser parte de tu viaje. Si en algún momento decides regresar, estaremos aquí para recibirte con los brazos abiertos.</p>
                <p style="font-family: system-ui, sans-serif; font-size: 16px; line-height: 1.5;">Te deseamos todo lo mejor en tus futuros proyectos y esperamos que nuestros caminos se crucen de nuevo en el futuro.</p>
                <p style="font-family: system-ui, sans-serif; font-size: 16px; line-height: 1.5;">Sinceramente,<br>Jose Fernandez<br>Webmaster</p>
            </body>
            '
        ]);
    }

    /**
     * @param int $id ID del usuario
     * @throws ModelNotFoundException cuando no se encuentra el usuario
     * @throws UserIsNotWaiterException cuando el usuario introducido no tiene el rol 'mesero'
     */
    public function checkIfMesero(int $id): void
    {
        $user = $this->repository->findOrFail($id);

        if ($user->getRol() !== 'mesero') {
            throw new UserIsNotWaiterException('El usuario no es mesero.');
        }
    }

    /**
     * @param mixed $user El usuario a revisar
     * @param string $passwordToCheck La contraseña introducida en la petición HTTP
     * @throws IncorrectLoginException cuando el usuario o la contraseña son incorrectos
     */
    public function checkEmailAndPassword(mixed $user, string $passwordToCheck): void
    {
        if (is_null($user) || !Hash::check($passwordToCheck, $user->getPassword())) {
            throw new IncorrectLoginException('Usuario o contraseña incorrectos.');
        }
    }

    private const PAGINATION_LIMIT = 15;
    /**
     * @param int $pagina Número de página que se desea obtener
     * @throws NoContentException cuando la página está vacía
     * @return Collection Los usuarios de la página deseada
     */
    public function paginated(int $pagina): Collection
    {
        $users = $this->repository->all()->forPage($pagina, self::PAGINATION_LIMIT);

        if ($users->isEmpty()) {
            throw new NoContentException('No hay usuarios.');
        }

        return $users;
    }

    /**
     * @return int La cantidad de páginas que tienen los usuarios
     */
    public function getAmountOfPages(): int
    {
        $paginas = $this->repository->all()->count();

        return ceil($paginas / self::PAGINATION_LIMIT);
    }

    /**
     * @param int $id ID del rol
     * @throws NoContentException cuando el rol no tiene usuarios
     * @return Collection Los usuarios de ese rol
     */
    public function findAllByIdRol(int $id): Collection
    {
        $users = $this->repository->findAllByIdRol($id);

        if ($users->isEmpty()) {
            throw new NoContentException('No hay usuarios.');
        }

        return $users;
    }
}
