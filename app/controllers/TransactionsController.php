<?php
declare(strict_types=1);

 

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class TransactionsController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        //
    }

    /**
     * Searches for transactions
     */
    public function searchAction()
    {
        $numberPage = $this->request->getQuery('page', 'int', 1);
        $parameters = Criteria::fromInput($this->di, 'Transactions', $_GET)->getParams();
        $parameters['order'] = "id";

        $transactions = Transactions::find($parameters);
        if (count($transactions) == 0) {
            $this->flash->notice("The search did not find any transactions");

            $this->dispatcher->forward([
                "controller" => "transactions",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $transactions,
            'limit'=> 10,
            'page' => $numberPage,
        ]);

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Displays the creation form
     */
    public function newAction()
    {
        //
    }

    /**
     * Edits a transaction
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {
            $transaction = Transactions::findFirstByid($id);
            if (!$transaction) {
                $this->flash->error("transaction was not found");

                $this->dispatcher->forward([
                    'controller' => "transactions",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $transaction->id;

            $this->tag->setDefault("id", $transaction->id);
            $this->tag->setDefault("name", $transaction->name);
            $this->tag->setDefault("tanggal", $transaction->tanggal);
            
        }
    }

    /**
     * Creates a new transaction
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "transactions",
                'action' => 'index'
            ]);

            return;
        }

        $transaction = new Transactions();
        $transaction->name = $this->request->getPost("name", "int");
        $transaction->tanggal = $this->request->getPost("tanggal", "int");
        

        if (!$transaction->save()) {
            foreach ($transaction->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "transactions",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("transaction was created successfully");

        $this->dispatcher->forward([
            'controller' => "transactions",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a transaction edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "transactions",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $transaction = Transactions::findFirstByid($id);

        if (!$transaction) {
            $this->flash->error("transaction does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "transactions",
                'action' => 'index'
            ]);

            return;
        }

        $transaction->name = $this->request->getPost("name", "int");
        $transaction->tanggal = $this->request->getPost("tanggal", "int");
        

        if (!$transaction->save()) {

            foreach ($transaction->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "transactions",
                'action' => 'edit',
                'params' => [$transaction->id]
            ]);

            return;
        }

        $this->flash->success("transaction was updated successfully");

        $this->dispatcher->forward([
            'controller' => "transactions",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a transaction
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $transaction = Transactions::findFirstByid($id);
        if (!$transaction) {
            $this->flash->error("transaction was not found");

            $this->dispatcher->forward([
                'controller' => "transactions",
                'action' => 'index'
            ]);

            return;
        }

        if (!$transaction->delete()) {

            foreach ($transaction->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "transactions",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("transaction was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "transactions",
            'action' => "index"
        ]);
    }
}
