<?php

declare(strict_types=1);

namespace Wedding\Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ManagerRegistry;

abstract class AbstractRepository
{
    private ManagerRegistry $doctrine;

    private bool $hasBeenReset;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
        $this->hasBeenReset = false;
    }

    abstract protected function getEntityClassName(): string;

    protected function getEntityRepository(): EntityRepository
    {
        /** PHPstan is slightly retarded here picking up the psalm-param */
        /** @phpstan-ignore-next-line */
        $repository = $this->getEntityManager()->getRepository($this->getEntityClassName());

        if (!$repository instanceof EntityRepository) {
            throw new \RuntimeException(
                sprintf('Doctrine EntityRepository not found for %s', $this->getEntityClassName())
            );
        }

        return $repository;
    }

    protected function getEntityManager(): EntityManager
    {
        $manager = $this->doctrine->getManagerForClass($this->getEntityClassName());

        if (!$manager instanceof EntityManager) {
            throw new \RuntimeException(
                sprintf('Doctrine entity manager not found for %s', $this->getEntityClassName())
            );
        }

        if (!$manager->isOpen()) {
            if ($this->hasBeenReset) {
                throw new \RuntimeException('Database connection issue, attempted reset but failed');
            }

            foreach ($this->doctrine->getManagerNames() as $name) {
                $this->doctrine->resetManager($name);
            }

            $this->hasBeenReset = true;

            return $this->getEntityManager();
        }

        $this->hasBeenReset = false;

        return $manager;
    }
}
