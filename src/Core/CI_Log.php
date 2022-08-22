<?php

namespace Fluent\Legacy\Core;

class CI_Log
{
    /**
     * Write Log File.
     *
     * Generally this function will be called using the global log_message() function
     *
     * @param string $level The error level: 'error', 'debug' or 'info'
     * @param string $msg   The error message
     *
     * @return bool
     */
    public function write_log($level, $msg)
    {
        return logger()->{$level}($msg);
    }
}
