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

    /**
     * DataCheckerService constructor.
     */
    public function __construct()
    {
        $this->messages = array();
    }

    /**
     * @return array
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * @param array $messages
     * @return void
     */
    public function setMessages(array $messages): void
    {
        $this->messages = $messages;
    }

    /**
     * @param string $message
     * @return void
     */
    public function addMessage(string $message): void
    {
        $this->messages[] = $message;
    }

    /**
     * @return bool
     */
    public function getIsError(): bool
    {
        return $this->isError;
    }

    /**
     * @param bool $isError
     * @return void
     */
    public function setIsError($isError): void
    {
        $this->isError = $isError;
    }

}