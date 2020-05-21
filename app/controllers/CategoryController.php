<?php
declare(strict_types=1);

 

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class CategoryController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        //
    }

    /**
     * Searches for category
     */
    public function searchAction()
    {
        $numberPage = $this->request->getQuery('page', 'int', 1);
        $parameters = Criteria::fromInput($this->di, 'Category', $_GET)->getParams();
        $parameters['order'] = "id";

        $category = Category::find($parameters);
        if (count($category) == 0) {
            $this->flash->notice("The search did not find any category");

            $this->dispatcher->forward([
                "controller" => "category",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $category,
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
     * Edits a category
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {
            $category = Category::findFirstByid($id);
            if (!$category) {
                $this->flash->error("category was not found");

                $this->dispatcher->forward([
                    'controller' => "category",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $category->id;

            $this->tag->setDefault("id", $category->id);
            $this->tag->setDefault("category_name", $category->category_name);
            
        }
    }

    /**
     * Creates a new category
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "category",
                'action' => 'index'
            ]);

            return;
        }

        $category = new Category();
        $category->categoryName = $this->request->getPost("category_name", "int");
        

        if (!$category->save()) {
            foreach ($category->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "category",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("category was created successfully");

        $this->dispatcher->forward([
            'controller' => "category",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a category edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "category",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $category = Category::findFirstByid($id);

        if (!$category) {
            $this->flash->error("category does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "category",
                'action' => 'index'
            ]);

            return;
        }

        $category->categoryName = $this->request->getPost("category_name", "int");
        

        if (!$category->save()) {

            foreach ($category->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "category",
                'action' => 'edit',
                'params' => [$category->id]
            ]);

            return;
        }

        $this->flash->success("category was updated successfully");

        $this->dispatcher->forward([
            'controller' => "category",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a category
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $category = Category::findFirstByid($id);
        if (!$category) {
            $this->flash->error("category was not found");

            $this->dispatcher->forward([
                'controller' => "category",
                'action' => 'index'
            ]);

            return;
        }

        if (!$category->delete()) {

            foreach ($category->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "category",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("category was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "category",
            'action' => "index"
        ]);
    }
}
