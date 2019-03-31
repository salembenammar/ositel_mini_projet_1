<?php

namespace AdminBundle\Controller;

use AdminBundle\Interfaces\TransactionServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="transaction_screen_1")
     */
    public function indexAction()
    {
        return $this->render('@Admin/Transaction/index.html.twig');
    }




    /**
     * @Route("/transaction/add", name="transaction_add")
     * @throws \Exception
     *
     * @return mixed
     */
    public function addAction(Request $request, TransactionServiceInterface $transactionService)
    {
        $data = $transactionService->addTransaction($request);
        if ($data['valid']){
            return $this->redirectToRoute('transaction_screen_1', array());
        }
        return $this->render('@Admin/Transaction/add.html.twig', $data);
    }

    /**
     *
     * @param Request $request
     * @param TransactionServiceInterface $transactionService
     * @param string $id
     *
     * @Route("/transaction/edit/{id}/", name="transaction_edit")
     * @throws \Exception
     *
     * @return mixed
     */
    public function editAction(Request $request, $id,TransactionServiceInterface $transactionService)
    {
        $data = $transactionService->editTransaction($request, $id);
        if ($data['valid']){
            return $this->redirectToRoute('transaction_screen_1', array());
        }
        return $this->render('@Admin/Transaction/add.html.twig', $data);
    }

    /**
     *
     * @param Request $request
     * @param TransactionServiceInterface $transactionService
     * @param string $id
     *
     * @Route("/transaction/show/{id}/", name="transaction_show")
     * @throws \Exception
     *
     * @return mixed
     */
    public function showAction(Request $request, $id,TransactionServiceInterface $transactionService)
    {
        $data = $transactionService->showTransaction($request, $id);
        return $this->render('@Admin/Transaction/delete.html.twig', $data);
    }

    /**
     *
     * @param Request $request
     * @param TransactionServiceInterface $transactionService
     * @param string $id
     *
     * @Route("/transaction/delete/{id}/", name="transaction_delete")
     * @throws \Exception
     *
     * @return mixed
     */
    public function deleteAction(Request $request, $id,TransactionServiceInterface $transactionService)
    {
        $data = $transactionService->deleteTransaction($request, $id);
        if ($data['valid']){
            return $this->redirectToRoute('transaction_screen_1', array());
        }
    }


    /**
     * @Route("/transactions", name="transaction_list")
     * @throws \Exception
     *
     * @return mixed
     */
    public function listAction(Request $request, TransactionServiceInterface $transactionService)
    {
        $data = $transactionService->listTransaction($request, false, false);
        if ($data instanceof JsonResponse) {
            return $data;
        }
        return $this->render('@Admin/Transaction/list.html.twig', $data);
    }



    /**
     * @Route("/transaction/valid", name="transaction_screen_2")
     */
    public function screen2Action()
    {
        return $this->render('@Admin/Transaction/valid.html.twig');
    }

    /**
     * @Route("/transactions/valid/{month}", name="transaction_valid_list")
     * @throws \Exception
     *
     * @return mixed
     */
    public function validListAction(Request $request, $month,TransactionServiceInterface $transactionService)
    {
        $data = $transactionService->listTransaction($request, false, true, $month);
        if ($data instanceof JsonResponse) {
            return $data;
        }
        return $this->render('@Admin/Transaction/list.html.twig', $data);
    }
}
