<?php
declare(strict_types=1);

 

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class TdetailsController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        //
    }

    /**
     * Searches for tdetails
     */
    public function searchAction()
    {
        $numberPage = $this->request->getQuery('page', 'int', 1);
        $parameters = Criteria::fromInput($this->di, 'Tdetails', $_GET)->getParams();
        $parameters['order'] = "transaction";

        $tdetails = Tdetails::find($parameters);
        if (count($tdetails) == 0) {
            $this->flash->notice("The search did not find any tdetails");

            $this->dispatcher->forward([
                "controller" => "tdetails",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $tdetails,
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
     * Edits a tdetail
     *
     * @param string $transaction
     */
    public function editAction($transaction)
    {
        if (!$this->request->isPost()) {
            $tdetail = Tdetails::findFirstBytransaction($transaction);
            if (!$tdetail) {
                $this->flash->error("tdetail was not found");

                $this->dispatcher->forward([
                    'controller' => "tdetails",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->transaction = $tdetail->transaction;

            $this->tag->setDefault("transaction", $tdetail->transaction);
            $this->tag->setDefault("category", $tdetail->category);
            $this->tag->setDefault("amount", $tdetail->amount);
            
        }
    }

    /**
     * Creates a new tdetail
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "tdetails",
                'action' => 'index'
            ]);

            return;
        }

        $tdetail = new Tdetails();
        $tdetail->transaction = $this->request->getPost("transaction", "int");
        $tdetail->category = $this->request->getPost("category", "int");
        $tdetail->amount = $this->request->getPost("amount", "int");
        

        if (!$tdetail->save()) {
            foreach ($tdetail->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "tdetails",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("tdetail was created successfully");

        $this->dispatcher->forward([
            'controller' => "tdetails",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a tdetail edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "tdetails",
                'action' => 'index'
            ]);

            return;
        }

        $transaction = $this->request->getPost("transaction");
        $tdetail = Tdetails::findFirstBytransaction($transaction);

        if (!$tdetail) {
            $this->flash->error("tdetail does not exist " . $transaction);

            $this->dispatcher->forward([
                'controller' => "tdetails",
                'action' => 'index'
            ]);

            return;
        }

        $tdetail->transaction = $this->request->getPost("transaction", "int");
        $tdetail->category = $this->request->getPost("category", "int");
        $tdetail->amount = $this->request->getPost("amount", "int");
        

        if (!$tdetail->save()) {

            foreach ($tdetail->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "tdetails",
                'action' => 'edit',
                'params' => [$tdetail->transaction]
            ]);

            return;
        }

        $this->flash->success("tdetail was updated successfully");

        $this->dispatcher->forward([
            'controller' => "tdetails",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a tdetail
     *
     * @param string $transaction
     */
    public function deleteAction($transaction)
    {
        $tdetail = Tdetails::findFirstBytransaction($transaction);
        if (!$tdetail) {
            $this->flash->error("tdetail was not found");

            $this->dispatcher->forward([
                'controller' => "tdetails",
                'action' => 'index'
            ]);

            return;
        }

        if (!$tdetail->delete()) {

            foreach ($tdetail->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "tdetails",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("tdetail was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "tdetails",
            'action' => "index"
        ]);
    }
}
