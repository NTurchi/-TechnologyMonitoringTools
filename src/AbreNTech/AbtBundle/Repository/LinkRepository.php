<?php

namespace AbreNTech\AbtBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AbreNTech\AbtBundle\Entity\Link;
use Doctrine\ORM\Tools\Pagination\Paginator;

class LinkRepository extends EntityRepository
{
    /**
     * @param $id integer
     * @return Link | null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneWithRelation($id)
    {
        return $this->createQueryBuilder('l')
            ->addSelect('t', 'c')
            ->leftJoin('l.type', 't')
            ->leftJoin('l.category', 'c')
            ->andWhere('l.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return array
     */
    public function findAllWithRelation()
    {
        return $this->createQueryBuilder('l')
            ->addSelect('t', 'c')
            ->leftJoin('l.type', 't')
            ->leftJoin('l.category', 'c')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $idtype integer
     *
     * @return array
     */
    public function findAllWithRelationByType($idtype)
    {
        return $this->createQueryBuilder('l')
            ->addSelect('t', 'c')
            ->leftJoin('l.type', 't')
            ->leftJoin('l.category', 'c')
            ->andWhere('t.id = :id')
            ->setParameter('id', $idtype)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $idcategory
     * @return array
     */
    public function findAllWithRelationByCategory($idcategory)
    {
        return $this->createQueryBuilder('l')
            ->addSelect('t', 'c')
            ->leftJoin('l.type', 't')
            ->leftJoin('l.category', 'c')
            ->andWhere('c.id = :id')
            ->setParameter('id', $idcategory)
            ->getQuery()
            ->getResult();
    }

}