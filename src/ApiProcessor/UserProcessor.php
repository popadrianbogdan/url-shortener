<?php

namespace App\ApiProcessor;

use App\Entity\User;
use App\Pab\Data\DataHelper;
use App\Pab\Data\DataProcessor;
use App\Repository\UserRepository;

class UserProcessor implements DataProcessor
{
    /**
     * @var User|null
     */
    private $user;
    private $userRepository;


    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke($in)
    {
        $data = new DataHelper($in);

        $email = $data->access('email')->getString();

        if ($this->user) {
            $user = $this->user;
            $changedPassword = $data->maybe()->access('password')->getString();

            if($changedPassword) {
                $user->setPassword($changedPassword);
            }
        } else {
            $user = new User();
            $user->setPassword($data->access('password')->getString());
        }

        $user->setEmail($email);

        return $user;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }
}