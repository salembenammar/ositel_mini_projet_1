<?php

namespace AdminBundle\Repository;
use AdminBundle\Entity\Transaction;

/**
 * TransactionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TransactionRepository extends \Doctrine\ORM\EntityRepository
{

    /**
     * Récupere les transactions valide du mois données
     *
     */
    public function getValidTransactionOfTheMonth($month)
    {
        $startOfMonth = new \DateTime(date("Y").'-' . $month.'-01');
        if ($month < 12){
            $startOfNextMonth = new \DateTime(date("Y").'-' . ($month + 1) .'-01');
        } else {
            $startOfNextMonth = new \DateTime(date("Y").'-' . $month .'-31');
        }

        $qb = $this->createQueryBuilder('t');
        $qb->andWhere('t.isValid = true')
            ->andWhere('t.createdAt >= :start')
            ->andWhere('t.createdAt < :end')
            ->setParameter('start', $startOfMonth)
            ->setParameter('end', $startOfNextMonth);
        $res = $qb->getQuery()->getResult();

        return $res;
    }




    /**
     * Récupere les transactions valide du mois données
     *
     */
    public function getValidTransactionOfTheLastMonthsValue($month)
    {
        $startOfMonth = new \DateTime(date("Y").'-' . $month.'-01');
        $qb = $this->createQueryBuilder('t');
        $qb->andWhere('t.isValid = true')
            ->andWhere('t.createdAt < :start')
            ->setParameter('start', $startOfMonth);
        $res = $qb->getQuery()->getResult();

        $somme = 0;
        dump($res);
        foreach ($res as $transaction) {
            /** @var  Transaction $transaction */
            if($transaction->getIsInput()){
                $somme += $transaction->getAmount();
            } else {
                $somme -= $transaction->getAmount();
            }
        }

        return $somme;
    }

}