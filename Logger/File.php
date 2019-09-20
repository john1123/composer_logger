<?php

namespace Logger;

class File {
    /** ��� ����� � ������ */
    protected $filename;
    protected $dateFormat = 'd.m.Y H:i:s';

    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    /**
     * ����� ������ � ���� ����.
     * @param $message String ��������� ��� �������
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
     * ����� ��������� � ���� ���� "��� ����". ��������� ������ � ������ �������� �� ���������.
     * @param $message String ��������� ��� �������
     */
    public function write($message)
    {
        file_put_contents($this->filename, $message, FILE_APPEND | LOCK_EX);
    }
}