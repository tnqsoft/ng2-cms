<?php

namespace ApiBundle\Service;

use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpFoundation\Request;

use Monolog\Logger as MonoLogger;

/**
 * This class is a log service to handle log
 */
class ClientLogService
{

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * Constructor
     *
     * @param Logger $logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Log the message and context
     *
     * @param integer $code
     * @param string $message
     * @param array $context
     * @return void
     */
    public function log($code, $message, array $context)
    {
        $level = $this->getLevelFromCode($code);
        $this->logger->log($level, $message, $context);
    }

    protected function getLevelFromCode($code)
    {
        $levelNameList = array(
            200 => MonoLogger::INFO,
            400 => MonoLogger::INFO,
            422 => MonoLogger::ERROR,
            424 => MonoLogger::ERROR,
            405 => MonoLogger::ERROR,
            415 => MonoLogger::ERROR,
            500 => MonoLogger::CRITICAL,
            501 => MonoLogger::CRITICAL,
            503 => MonoLogger::CRITICAL,
            401 => MonoLogger::WARNING,
            403 => MonoLogger::WARNING,
            404 => MonoLogger::WARNING,
        );

        $levelName = (isset($levelNameList[$code])) ? $levelNameList[$code] : MonoLogger::NOTICE;

        return MonoLogger::getLevelName($levelName);
    }

}
