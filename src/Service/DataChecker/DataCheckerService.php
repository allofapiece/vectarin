<?php
/**
 * Created by PhpStorm.
 * User: Listratsenka Stas
 * Date: 05.04.2018
 * Time: 17:34
 */

namespace App\Service\DataChecker;


abstract class DataCheckerService
{
    private $message;
    private $isError;
    private $entity;

    public function __construct(object $entity = null)
    {
        $this->entity = $entity;
    }

    abstract public function checkData(object $entity);

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getIsError()
    {
        return $this->isError;
    }

    /**
     * @param mixed $isError
     */
    public function setIsError($isError)
    {
        $this->isError = $isError;
    }

    /**
     * @return object
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param object $entity
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
    }


}