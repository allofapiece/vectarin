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
    private $messages;
    private $isError;

    public function __construct()
    {
        $messages = array();
    }

    /**
     * @return mixed
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @return mixed
     */
    public function setMessages(array $messages)
    {
        $this->messages = $messages;
    }

    /**
     * @param mixed $message
     */
    public function addMessage(string $message)
    {
        $this->messages[] = $message;
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

}