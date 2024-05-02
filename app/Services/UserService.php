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

class UserService
{
    public function __construct(
        public readonly UserRepository $repository,
        public readonly MailService    $mailService
    )
    {
    }

    /**
     * @param User $user El usuario al que se le envía el correo
     */
    public function sendSuccessRegisterEmail(User $user): void
    {
        $this->mailService->sendSuccessRegisterEmail($user);
    }

    /**
     * @param User $user El usuario al que se le envía el correo
     */
    public function sendUpdatedUserEmail(User $user, User $previous): void
    {
        if ($user->getEmail() == $previous->getEmail()) {
            $this->mailService->sendUpdatedUserEmail($user);
            return;
        }

        $this->mailService->sendUpdatedUserEmail($user);
        $this->mailService->sendUpdatedEmailNotice($previous);
    }

    /**
     * @param User $user El usuario al que se le envía el correo
     */
    public function sendGoodByeEmail(User $user): void
    {
        $this->mailService->sendGoodByeEmail($user);
    }

    /**
     * @param int $id ID del usuario
     * @throws ModelNotFoundException cuando no se encuentra el usuario
     * @throws UserIsNotWaiterException cuando el usuario introducido no tiene el rol 'mesero'
     */
    public function checkIfMesero(int $id): void
    {
        $user = $this->repository->findOrFail($id);

        if ($user->getIdRol() !== '1') {
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
     * @return Collection Los usuarios de la página deseada
     * @throws NoContentException cuando la página está vacía
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
     * @return Collection Los usuarios de ese rol
     * @throws NoContentException cuando el rol no tiene usuarios
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
