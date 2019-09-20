<?php

namespace Logger;

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
     * @param $message String Сообщение для запииси
     */
    public function log($message)
    {
        $out = '';
        if (strlen($this->dateFormat) > 0) {
            $out = date($this->dateFormat) . ': ';
        }
        $out .= $message . PHP_EOL;
        $this->write($out);
    }

    /**
     * Пишет сообщение в файл лога "как есть". Переводов строки и других символов не добавляет.
     * @param $message String Сообщение для запииси
     */
    public function write($message)
    {
        file_put_contents($this->filename, $message, FILE_APPEND | LOCK_EX);
    }
}