<?php

namespace John1123\Logger;

class File {
    /** Имя файла с логами */
    protected $filename;
    protected $dateFormat = 'd.m.Y H:i:s';

    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    /**
     * Пишет строку в файл лога.
     * @param $message String Сообщение для записи
     * @param $addBacktrace boolean добавлять ли после строки backtrace информацию
     */
    public function log($message, $addBacktrace=false)
    {
        $out = '';
        if (strlen($this->dateFormat) > 0) {
            $out = date($this->dateFormat) . ': ';
        }
        $out .= $message . PHP_EOL;
        if ($addBacktrace == true) {
            $out .= $this->get_debug_print_backtrace() . PHP_EOL;
        }
        $this->write($out);
    }

    /**
     * Пишет сообщение в файл лога "как есть". Времени, переводов строки и других символов не добавляет.
     * @param $message String Сообщение для запииси
     */
    public function write($message)
    {
        file_put_contents($this->filename, $message, FILE_APPEND | LOCK_EX);
    }

    /**
     * Возвращает backtrace информацию
     * @see https://www.php.net/manual/ru/function.debug-print-backtrace.php
     */
    protected function get_debug_print_backtrace($traces_to_ignore=1)
    {
        $traces = debug_backtrace();
        $ret = array();
        foreach($traces as $i => $call){
            if ($i < $traces_to_ignore ) {
                continue;
            }

            $object = '';
            if (isset($call['class'])) {
                $object = $call['class'].$call['type'];
                if (is_array($call['args'])) {
                    foreach ($call['args'] as &$arg) {
                        $this->get_arg($arg);
                    }
                }
            }

            $ret[] = '#'.str_pad($i - $traces_to_ignore, 3, ' ')
                .$object.$call['function'].'('.implode(', ', $call['args'])
                .') called at ['.$call['file'].':'.$call['line'].']';
        }

        return implode("\n",$ret);
    }

    /**
     * Приводит аргументы в читаемый вид
     * @see https://www.php.net/manual/ru/function.debug-print-backtrace.php
     */
    protected function get_arg(&$arg)
    {
        if (is_object($arg)) {
            $arr = (array)$arg;
            $args = array();
            foreach($arr as $key => $value) {
                if (strpos($key, chr(0)) !== false) {
                    $key = '';    // Private variable found
                }
                $args[] =  '['.$key.'] => '.$this->get_arg($value);
            }

            $arg = get_class($arg) . ' Object ('.implode(',', $args).')';
        } elseif (is_bool($arg)) {
            $arg = $arg == true ? 'true' : 'false';
        }
    }
}