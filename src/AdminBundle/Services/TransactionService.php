<?php

namespace AdminBundle\Services;

use AdminBundle\Form\TransactionType;
use AdminBundle\Interfaces\TransactionServiceInterface;
use AdminBundle\Entity\Transaction;
use AdminBundle\Repository\TransactionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;

/**
 * Class TransactionService
 * @package AdminBundle\Services
 */
class TransactionService implements TransactionServiceInterface
{

    /** @var EntityManagerInterface $em */
    private $em;

    /** @var FormFactoryInterface $formFactory */
    private $formFactory;



    /** @var Environment */
    private $twig;

    /**
     * TransactionService constructor.
     * @param EntityManagerInterface $em
     * @param FormFactoryInterface $formFactory
     * @param Environment $twig
     */
    public function __construct(EntityManagerInterface $em, FormFactoryInterface $formFactory, Environment $twig)
    {
        $this->em = $em;
        $this->formFactory = $formFactory;
        $this->twig = $twig;
    }


    /**
     * @param Request $request
     * @param boolean $ws
     * @param boolean $valid
     * @param string $month
     * @return mixed
     * @throws /\Exception
     */
    public function listTransaction(Request $request, $ws = false, $valid = false, $month= null)
    {
        /** @var TransactionRepository $transactionRepository */
        $transactionRepository = $this->em->getRepository('AdminBundle:Transaction');
        if($valid){
            $transactions = $transactionRepository->getValidTransactionOfTheMonth($month);
            // Last month amounts
            $transactionsOftheLastMonthsValue = $transactionRepository->getValidTransactionOfTheLastMonthsValue($month);
        } else {
            $transactions = $transactionRepository->findAll();
            // Last month amounts
            $transactionsOftheLastMonthsValue = 0;
        }


        // Formatter le retour JSON pour dataTable
        $result = [];
        $result['draw'] = (int)(array_key_exists('draw', $request) ? $request['draw'] : 1);
        $result['recordsTotal'] = count($transactions);
        $result['recordsFiltered'] = count($transactions);
        $result['transactionInValue'] = 0;
        $result['transactionOutValue'] = 0;
        $result['amountStartMonth'] = $transactionsOftheLastMonthsValue;
        $result['amountEndMonth'] = $transactionsOftheLastMonthsValue;
        $result['data'] = [];
        foreach ($transactions as $transaction) {
            /** @var Transaction $transaction */
            $result['data'][] = (object) [
                $transaction->getTitle(),
                $transaction->getDescription(),
                $transaction->getAmount() . ' €',
                $transaction->getCategory() ? $transaction->getCategory()->getTitle() : '-',
                $transaction->getIsInput() ? 'IN' : 'OUT',
                $transaction->getCreatedAt()->format('d-m-Y'),
                $this->twig->render('@Admin/Transaction/action.html.twig', array('id'=>$transaction->getId()))
            ];

            if($transaction->getIsInput()){
                $result['transactionInValue'] += $transaction->getAmount();
                $result['amountEndMonth'] += $transaction->getAmount();
            } else {
                $result['transactionOutValue'] += $transaction->getAmount();
                $result['amountEndMonth'] -= $transaction->getAmount();
            }
        }
        return new JsonResponse($result);
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function addTransaction(Request $request){
        $transaction = new Transaction();
        $TransactionForm = $this->formFactory->create(TransactionType::class, $transaction);

        if ($request->getMethod() === 'POST') {
            $TransactionForm->handleRequest($request);
            if ($TransactionForm->isValid()) {
                $this->em->persist($transaction);
                $this->em->flush();
                return array(
                    'valid' => true,
                );
            } else {
                return $TransactionForm;
            }
        }

        return array(
            'form' => $TransactionForm->createView(),
            'valid' => false,
        );
    }


    /**
     * @param Request $request
     * @param $transactionID
     * @return mixed
     */
    public function editTransaction(Request $request, $transactionID){

        /** @var TransactionRepository $transactionRepository */
        $transactionRepository = $this->em->getRepository(Transaction::class);

        $transaction = $transactionRepository->findOneBy(array('id' => (int) $transactionID));
        $TransactionForm = $this->formFactory->create(TransactionType::class, $transaction);

        if ($request->getMethod() === 'POST') {
            $TransactionForm->handleRequest($request);
            if ($TransactionForm->isValid()) {
                $this->em->persist($transaction);
                $this->em->flush();
                return array(
                    'valid' => true,
                );
            } else {
                return $TransactionForm;
            }
        }

        return array(
            'form' => $TransactionForm->createView(),
            'valid' => false,
        );
    }


    /**
     * @param Request $request
     * @param $transactionID
     * @return mixed
     */
    public function deleteTransaction(Request $request, $transactionID){
        /** @var TransactionRepository $transactionRepository */
        $transactionRepository = $this->em->getRepository(Transaction::class);
        $transaction = $transactionRepository->findOneBy(array('id' => (int) $transactionID));

        $this->em->remove($transaction);
        $this->em->flush();

        return array(
            'valid' => true
        );

    }


    /**
     * @param Request $request
     * @param $transactionID
     * @return mixed
     */
    public function showTransaction(Request $request, $transactionID){
        /** @var TransactionRepository $transactionRepository */
        $transactionRepository = $this->em->getRepository(Transaction::class);
        $transaction = $transactionRepository->findOneBy(array('id' => (int) $transactionID));

        return array(
            'entity' => $transaction,
        );
    }


}