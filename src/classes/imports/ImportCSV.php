<?php

namespace TaskForce\classes\imports;

use SplFileObject;
use TaskForce\classes\exceptions\WrongFilenameOrPathException;

class ImportCSV
{
    private string $sourceFile;
    private SplFileObject $preparedFile;

    public function __construct($sourceFile)
    {
        $this->sourceFile = $sourceFile;
    }
    
    /**
     * Подготавливает объект к работе и выбрасывает исключения, если что-то пошло не так
     *
     * @return void
     *
     * @throws WrongFilenameOrPathException
     */
    public function prepare(): void
    {
        if (!file_exists($this->sourceFile)) {
            throw new WrongFilenameOrPathException('Файл не существует.');
        }

        $this->preparedFile = new SplFileObject($this->sourceFile);
    }

    /**
     * Переносит все строки из CSV файла в новый SQL файл, подставляя их в виде INSERT запроса
     *
     * @param string $sqlFile Директория и название выходного файла
     * @param string $table Таблица, в которую будут записываться данные
     * @param array $params Параметры, которые необходимо записать в таблицу
     *
     * @return void
     */
    public function convertToSql(string $sqlFile, string $table, array $params): void
    {
        $outputFile = new SplFileObject($sqlFile, 'c');

        $string = "INSERT INTO $table (" . implode(', ', $params) . ") VALUES ";

        $this->preparedFile->seek(1);

        while ($this->preparedFile->valid()) {
            $data = $this->preparedFile->fgetcsv();
            $values = [];

            foreach ($data as $el) {
                $values[] = is_numeric($el) ? $el : "'$el'";
            }

            $outputFile->fwrite($string . '(' . implode(', ', $values) . ');' . "\n");
        }
    }
}
