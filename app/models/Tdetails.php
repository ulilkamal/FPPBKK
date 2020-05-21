<?php

//namespace ;

class Tdetails extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $transaction;

    /**
     *
     * @var integer
     */
    public $category;

    /**
     *
     * @var integer
     */
    public $amount;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("donasi");
        $this->setSource("tdetails");
        $this->belongsTo('category', '\Category', 'id', ['alias' => 'Category']);
        $this->belongsTo('transaction', '\Transactions', 'id', ['alias' => 'Transactions']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Tdetails[]|Tdetails|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Tdetails|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
