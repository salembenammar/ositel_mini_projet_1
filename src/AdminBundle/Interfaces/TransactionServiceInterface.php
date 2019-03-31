<?php

namespace AdminBundle\Interfaces;

use Symfony\Component\HttpFoundation\Request;


/**
 * Interface TransactionServiceInterface
 * @package AdminBundle\Interfaces
 */
interface TransactionServiceInterface
{
    /**
     * @param Request $request
     * @param boolean $ws
     * @param boolean $valid
     * @param string $month
     * @return mixed
     */
    public function listTransaction(Request $request, $ws = false, $valid = false, $month= null);


    /**
     * @param Request $request
     * @return mixed
     */
    public function addTransaction(Request $request);


    /**
     * @param Request $request
     * @param $transactionID
     * @return mixed
     */
    public function editTransaction(Request $request, $transactionID);


    /**
     * @param Request $request
     * @param $transactionID
     * @return mixed
     */
    public function deleteTransaction(Request $request, $transactionID);


    /**
     * @param Request $request
     * @param $transactionID
     * @return mixed
     */
    public function showTransaction(Request $request, $transactionID);

}