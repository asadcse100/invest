<?php

namespace App\Services\Exports;

/**
 * Class CsvExport
 */
class CsvExport
{

    /**
     * @var string|null
     */
    protected $fileName = null;

    /**
     * @var resource|null
     */
    protected $file = null;

    /**
     * Set Row for csv export
     * @param array $row
     */
    public function setRow(array $row)
    {
        $file = $this->getFile();
        fputcsv($file, $row);
    }

    /**
     * Get File name
     * @return string|null
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Csv headers
     * @return void
     */
    protected function getCsvHeaders()
    {
        header('Content-Type: text/csv; charset=utf-8');
        header("Content-Disposition: attachment; filename={$this->getFileName()}");
    }

    /**
     * Open file
     * @param string $fileName
     * @return void
     */
    public function setFile($fileName)
    {
        $this->fileName = $fileName;
        $this->getCsvHeaders();
        $file = fopen('php://output', 'wb');
        fputs($file, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));

        $this->file = $file;
    }

    /**
     * Get file
     * @return resource $file
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Download csv
     * @param resource $file
     * @return void
     */
    public function download($file)
    {
        fclose($file);
    }
}
