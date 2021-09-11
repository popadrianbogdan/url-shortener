<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use App\Repository\Traits\ThrowExceptionOnNotFound;

class BaseRepository extends EntityRepository
{
    use ThrowExceptionOnNotFound;

    public function find($id, $lockMode = null, $lockVersion = null)
    {
        $result =  parent::find($id, $lockMode, $lockVersion);

        if ($this->throwExceptionOnNotFound && !$result) {
            throw new $this->exceptionClass($this->getClassName().' with id '.$id.' not found');
        }

        return $result;
    }

    public function findOneBy(array $criteria, array $orderBy = null)
    {
        $result = parent::findOneBy($criteria, $orderBy);
        if ($this->throwExceptionOnNotFound && !$result) {
            throw new $this->exceptionClass($this->getClassName().' not found by '.json_encode($criteria));
        }

        return $result;
    }

    public function findAll()
    {
        return parent::findAll();
    }
}
