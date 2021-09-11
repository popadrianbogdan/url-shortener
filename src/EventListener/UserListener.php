<?php

namespace App\EventListener;

use App\Entity\User;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class UserListener
{
    private $encoder;

    public function __construct(PasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function prePersist(User $user)
    {
        $user->setPassword($this->encodePassword($user));
    }

    public function preUpdate(User $user, PreUpdateEventArgs $eventArgs)
    {
        if ($eventArgs->hasChangedField('password')) {
            $oldEncodedPassword = $eventArgs->getOldValue('password');
            $newPlaintextPassword = $user->getPassword();
            if ($this->encoder->isPasswordValid($oldEncodedPassword, $newPlaintextPassword, $user->getSalt())) {
                $user->setPassword($oldEncodedPassword);

                return;
            }

            $user->setPassword($this->encodePassword($user));
        }
    }

    private function encodePassword(User $user): string
    {
        return $this->encoder->encodePassword($user->getPassword(), $user->getSalt());
    }
}