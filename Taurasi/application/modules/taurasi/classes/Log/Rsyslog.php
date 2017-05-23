<?php

/**
 * @author guoquan
 */
class Log_Rsyslog extends Log_Writer
{
    protected $_rsyslog_level = array(
        LOG_EMERG   => 'EMERG',
        LOG_ALERT   => 'ALERT',
        LOG_CRIT    => 'CRIT',
        LOG_ERR     => 'ERR',
        LOG_WARNING => 'WARN',
        LOG_NOTICE  => 'NOTIC',
        LOG_INFO    => 'INFO',
        LOG_DEBUG   => 'DEBUG',
    );

    public function write(array $messages)
    {
        foreach ($messages as $message) {
            $e = $message['additional']['exception'];
            $previous = $e->getPrevious();
            if ($previous instanceof Error) {
                $previousMsg = [
                    'previous' => [
                        'code'   => $previous->getCode(),
                        'level'  => $this->getLevelName($previous->getCode()),
                        'msg'    => $previous->getMessage(),
                        'file'   => $previous->getFile(),
                        'line'   => $previous->getLine(),
                    ]
                ];
            } else {
                $previousMsg = [];
            }
            $msg = [
                'code'   => $e->getCode(),
                'level'  => $this->getLevelName($e->getCode()),
                'msg'    => $e->getMessage(),
                'file'   => $e->getFile(),
                'line'   => $e->getLine(),
                'trace'  => $e->getTraceAsString(),
            ] + $previousMsg;
            Rsyslog::write($msg, $this->getLevel($e->getCode()));
        }
    }

    public function getLevel($code)
    {
        switch ($code) {
            case E_ERROR:
            case E_CORE_ERROR:
            case E_COMPILE_ERROR:
            case E_PARSE:
            case E_USER_ERROR:
            case E_RECOVERABLE_ERROR:
                $level = 'ERR';
                break;
            case E_WARNING:
            case E_CORE_WARNING:
            case E_COMPILE_WARNING:
            case E_USER_WARNING:
                $level = 'WARN';
                break;
            case E_NOTICE:
            case E_USER_NOTICE:
            case E_DEPRECATED:
            case E_USER_DEPRECATED:
            case E_STRICT:
                $level = 'NOTIC';
                break;
            default:
                $level = 'ERR';
        }
        return $level;
    }

    public function getLevelName($code)
    {
        $maps = [
            E_ERROR             => 'E_ERROR',
            E_CORE_ERROR        => 'E_CORE_ERROR',
            E_COMPILE_ERROR     => 'E_COMPILE_ERROR',
            E_PARSE             => 'E_PARSE',
            E_USER_ERROR        => 'E_USER_ERROR',
            E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR',
            E_WARNING           => 'E_WARNING',
            E_CORE_WARNING      => 'E_CORE_WARNING',
            E_COMPILE_WARNING   => 'E_COMPILE_WARNING',
            E_USER_WARNING      => 'E_USER_WARNING',
            E_NOTICE            => 'E_NOTICE',
            E_USER_NOTICE       => 'E_USER_NOTICE',
            E_DEPRECATED        => 'E_DEPRECATED',
            E_USER_DEPRECATED   => 'E_USER_DEPRECATED',
            E_STRICT            => 'E_STRICT'
        ];
        return $maps[$code] ?? 'Unkown';
    }
}