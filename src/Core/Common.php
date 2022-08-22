<?php

declare(strict_types=1);

/**
 * Copyright (c) 2021 Kenji Suzuki
 * Copyright (c) 2022 Agung Sugiarto.
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/kenjis/ci3-to-4-upgrade-helper
 * @see https://github.com/agungsugiarto/legacy-to-laravel
 */

use Fluent\Legacy\Exception\NotSupportedException;
use Fluent\Legacy\Exception\RuntimeException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

if (! function_exists('show_error')) {
    /**
     * Error Handler.
     *
     * This function lets us invoke the exception class and
     * display errors using the standard error template located
     * in application/views/errors/error_general.php
     * This function will send the error page directly to the
     * browser and exit.
     *
     * @param   string
     * @param   int
     * @param   string
     *
     * @return void
     */
    function show_error($message, $status_code = 500, $heading = '')
    {
        if ($heading !== '') {
            throw new NotSupportedException(
                '$heading is not supported.'
                    .'Please write your view file `app/Views/errors/html/error_500.php`.'
            );
        }

        throw new RuntimeException($message, $status_code);
    }
}

if (! function_exists('show_404')) {
    /**
     * @param string $page      Page URI
     * @param bool   $log_error Whether to log the error
     */
    function show_404(string $page = '', bool $log_error = true): void
    {
        if (is_cli()) {
            $heading = 'Not Found';
            $message = 'The controller/method pair you requested was not found.';
        } else {
            $heading = '404 Page Not Found';
            $message = 'The page you requested was not found.';
        }

        // By default we log this, but allow a dev to skip it
        if ($log_error) {
            log_message('error', $heading.': '.$page);
        }

        throw new NotFoundHttpException($heading.': '.$message);
    }
}

if (! function_exists('log_message')) {
    /**
     * Error Logging Interface.
     *
     * We use this as a simple mechanism to access the logging
     * class and send messages to be logged.
     *
     * @param	string	the error level: 'error', 'debug' or 'info'
     * @param	string	the error message
     *
     * @return void
     */
    function log_message($level, $message)
    {
        get_instance()->log->write_log($level, $message);
    }
}

if (! function_exists('html_escape')) {
    /**
     * Returns HTML escaped variable.
     *
     * @param mixed $var           the input string or array of strings to be escaped
     * @param bool  $double_encode $double_encode set to FALSE prevents escaping twice
     *
     * @return mixed the escaped string or array of strings as a result
     */
    function html_escape($var, bool $double_encode = true)
    {
        if ($double_encode === false) {
            throw new NotSupportedException(
                '$double_encode = false is not supported.'
            );
        }

        return esc($var, 'html');
    }
}

// ------------------------------------------------------------------------

if (! function_exists('is_php')) {
    /**
     * Determines if the current version of PHP is equal to or greater than the supplied value.
     *
     * @param	string
     *
     * @return bool TRUE if the current version is $version or higher
     */
    function is_php($version)
    {
        static $_is_php;
        $version = (string) $version;

        if (! isset($_is_php[$version])) {
            $_is_php[$version] = version_compare(PHP_VERSION, $version, '>=');
        }

        return $_is_php[$version];
    }
}

// ------------------------------------------------------------------------

if (! function_exists('is_really_writable')) {
    /**
     * Tests for file writability.
     *
     * is_writable() returns TRUE on Windows servers when you really can't write to
     * the file, based on the read-only attribute. is_writable() is also unreliable
     * on Unix servers if safe_mode is on.
     *
     * @see	https://bugs.php.net/bug.php?id=54709
     *
     * @param	string
     *
     * @return bool
     */
    function is_really_writable($file)
    {
        // If we're on a Unix server with safe_mode off we call is_writable
        if (DIRECTORY_SEPARATOR === '/' && (is_php('5.4') or ! ini_get('safe_mode'))) {
            return is_writable($file);
        }

        /* For Windows servers and safe_mode "on" installations we'll actually
         * write a file then read it. Bah...
         */
        if (is_dir($file)) {
            $file = rtrim($file, '/').'/'.md5(mt_rand());
            if (($fp = @fopen($file, 'ab')) === false) {
                return false;
            }

            fclose($fp);
            @chmod($file, 0777);
            @unlink($file);

            return true;
        } elseif (! is_file($file) or ($fp = @fopen($file, 'ab')) === false) {
            return false;
        }

        fclose($fp);

        return true;
    }
}

// ------------------------------------------------------------------------

if (! function_exists('get_config')) {
    /**
     * Loads the main config.php file.
     *
     * This function lets us grab the config file even if the Config class
     * hasn't been instantiated yet
     *
     * @param	array
     *
     * @return array
     */
    function &get_config(array $replace = [])
    {
        static $config;

        if (empty($config)) {
            $file_path = APPPATH.'config/config.php';
            $found = false;
            if (file_exists($file_path)) {
                $found = true;
                require $file_path;
            }

            // Is the config file in the environment folder?
            if (file_exists($file_path = APPPATH.'config/'.ENVIRONMENT.'/config.php')) {
                require $file_path;
            } elseif (! $found) {
                set_status_header(503);
                echo 'The configuration file does not exist.';
                exit(3); // EXIT_CONFIG
            }

            // Does the $config array exist in the file?
            if (! isset($config) or ! is_array($config)) {
                set_status_header(503);
                echo 'Your config file does not appear to be formatted correctly.';
                exit(3); // EXIT_CONFIG
            }
        }

        // Are any values being dynamically added or replaced?
        foreach ($replace as $key => $val) {
            $config[$key] = $val;
        }

        return $config;
    }
}

// ------------------------------------------------------------------------

if (! function_exists('config_item')) {
    /**
     * Returns the specified config item.
     *
     * @param	string
     *
     * @return mixed
     */
    function config_item($item)
    {
        static $_config;

        if (empty($_config)) {
            // references cannot be directly assigned to static variables, so we use an array
            $_config[0] = &get_config();
        }

        return isset($_config[0][$item]) ? $_config[0][$item] : null;
    }
}

// ------------------------------------------------------------------------

if (! function_exists('get_mimes')) {
    /**
     * Returns the MIME types array from config/mimes.php.
     *
     * @return array
     */
    function &get_mimes()
    {
        static $_mimes;

        if (empty($_mimes)) {
            $_mimes = file_exists(APPPATH.'config/mimes.php')
                ? include(APPPATH.'config/mimes.php')
                : [];

            if (file_exists(APPPATH.'config/'.ENVIRONMENT.'/mimes.php')) {
                $_mimes = array_merge($_mimes, include(APPPATH.'config/'.ENVIRONMENT.'/mimes.php'));
            }
        }

        return $_mimes;
    }
}

// ------------------------------------------------------------------------

if (! function_exists('is_https')) {
    /**
     * Is HTTPS?
     *
     * Determines if the application is accessed via an encrypted
     * (HTTPS) connection.
     *
     * @return bool
     */
    function is_https()
    {
        if (! empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') {
            return true;
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https') {
            return true;
        } elseif (! empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off') {
            return true;
        }

        return false;
    }
}

// ------------------------------------------------------------------------

if (! function_exists('is_cli')) {
    /**
     * Is CLI?
     *
     * Test to see if a request was made from the command line.
     *
     * @return bool
     */
    function is_cli()
    {
        return PHP_SAPI === 'cli' or defined('STDIN');
    }
}

// ------------------------------------------------------------------------

if (! function_exists('set_status_header')) {
    /**
     * Set HTTP Status Header.
     *
     * @param	int	the status code
     * @param	string
     *
     * @return void
     */
    function set_status_header($code = 200, $text = '')
    {
        if (is_cli()) {
            return;
        }

        if (empty($code) or ! is_numeric($code)) {
            show_error('Status codes must be numeric', 500);
        }

        if (empty($text)) {
            is_int($code) or $code = (int) $code;
            $stati = [
                100 => 'Continue',
                101 => 'Switching Protocols',

                200 => 'OK',
                201 => 'Created',
                202 => 'Accepted',
                203 => 'Non-Authoritative Information',
                204 => 'No Content',
                205 => 'Reset Content',
                206 => 'Partial Content',

                300 => 'Multiple Choices',
                301 => 'Moved Permanently',
                302 => 'Found',
                303 => 'See Other',
                304 => 'Not Modified',
                305 => 'Use Proxy',
                307 => 'Temporary Redirect',

                400 => 'Bad Request',
                401 => 'Unauthorized',
                402 => 'Payment Required',
                403 => 'Forbidden',
                404 => 'Not Found',
                405 => 'Method Not Allowed',
                406 => 'Not Acceptable',
                407 => 'Proxy Authentication Required',
                408 => 'Request Timeout',
                409 => 'Conflict',
                410 => 'Gone',
                411 => 'Length Required',
                412 => 'Precondition Failed',
                413 => 'Request Entity Too Large',
                414 => 'Request-URI Too Long',
                415 => 'Unsupported Media Type',
                416 => 'Requested Range Not Satisfiable',
                417 => 'Expectation Failed',
                422 => 'Unprocessable Entity',
                426 => 'Upgrade Required',
                428 => 'Precondition Required',
                429 => 'Too Many Requests',
                431 => 'Request Header Fields Too Large',

                500 => 'Internal Server Error',
                501 => 'Not Implemented',
                502 => 'Bad Gateway',
                503 => 'Service Unavailable',
                504 => 'Gateway Timeout',
                505 => 'HTTP Version Not Supported',
                511 => 'Network Authentication Required',
            ];

            if (isset($stati[$code])) {
                $text = $stati[$code];
            } else {
                show_error('No status text available. Please check your status code number or supply your own message text.', 500);
            }
        }

        if (strpos(PHP_SAPI, 'cgi') === 0) {
            header('Status: '.$code.' '.$text, true);

            return;
        }

        $server_protocol = (isset($_SERVER['SERVER_PROTOCOL']) && in_array($_SERVER['SERVER_PROTOCOL'], ['HTTP/1.0', 'HTTP/1.1', 'HTTP/2', 'HTTP/2.0'], true))
            ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.1';
        header($server_protocol.' '.$code.' '.$text, true, $code);
    }
}

// --------------------------------------------------------------------

if (! function_exists('remove_invisible_characters')) {
    /**
     * Remove Invisible Characters.
     *
     * This prevents sandwiching null characters
     * between ascii characters, like Java\0script.
     *
     * @param	string
     * @param	bool
     *
     * @return string
     */
    function remove_invisible_characters($str, $url_encoded = true)
    {
        $non_displayables = [];

        // every control character except newline (dec 10),
        // carriage return (dec 13) and horizontal tab (dec 09)
        if ($url_encoded) {
            $non_displayables[] = '/%0[0-8bcef]/i';    // url encoded 00-08, 11, 12, 14, 15
            $non_displayables[] = '/%1[0-9a-f]/i';    // url encoded 16-31
            $non_displayables[] = '/%7f/i';    // url encoded 127
        }

        $non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';    // 00-08, 11, 12, 14-31, 127

        do {
            $str = preg_replace($non_displayables, '', $str, -1, $count);
        } while ($count);

        return $str;
    }
}

// ------------------------------------------------------------------------

if (! function_exists('_stringify_attributes')) {
    /**
     * Stringify attributes for use in HTML tags.
     *
     * Helper function used to convert a string, array, or object
     * of attributes to a string.
     *
     * @param	mixed	string, array, object
     * @param	bool
     *
     * @return string
     */
    function _stringify_attributes($attributes, $js = false)
    {
        if (empty($attributes)) {
            return null;
        }

        if (is_string($attributes)) {
            return ' '.$attributes;
        }

        $attributes = (array) $attributes;

        $atts = '';
        foreach ($attributes as $key => $val) {
            $atts .= ($js) ? $key.'='.$val.',' : ' '.$key.'="'.$val.'"';
        }

        return rtrim($atts, ',');
    }
}

// ------------------------------------------------------------------------

if (! function_exists('function_usable')) {
    /**
     * Function usable.
     *
     * Executes a function_exists() check, and if the Suhosin PHP
     * extension is loaded - checks whether the function that is
     * checked might be disabled in there as well.
     *
     * This is useful as function_exists() will return FALSE for
     * functions disabled via the *disable_functions* php.ini
     * setting, but not for *suhosin.executor.func.blacklist* and
     * *suhosin.executor.disable_eval*. These settings will just
     * terminate script execution if a disabled function is executed.
     *
     * The above described behavior turned out to be a bug in Suhosin,
     * but even though a fix was committed for 0.9.34 on 2012-02-12,
     * that version is yet to be released. This function will therefore
     * be just temporary, but would probably be kept for a few years.
     *
     * @see	http://www.hardened-php.net/suhosin/
     *
     * @param string $function_name Function to check for
     *
     * @return bool TRUE if the function exists and is safe to call,
     *              FALSE otherwise
     */
    function function_usable($function_name)
    {
        static $_suhosin_func_blacklist;

        if (function_exists($function_name)) {
            if (! isset($_suhosin_func_blacklist)) {
                $_suhosin_func_blacklist = extension_loaded('suhosin')
                    ? explode(',', trim(ini_get('suhosin.executor.func.blacklist')))
                    : [];
            }

            return ! in_array($function_name, $_suhosin_func_blacklist, true);
        }

        return false;
    }
}
