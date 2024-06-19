<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Google_sheets {    

    /**
     * Lee un archivo CSV localizado en una URL y lo convierte en un array
     * 2023-05-02
     * @param string $fileId Id del archivo google spreadsheet
     * @param int $gid : Id de la hoja dentro del archivo
     */
    function sheetToArray($fileId, $gid = 0)
    {
        $url = "https://docs.google.com/spreadsheets/d/{$fileId}/export?format=csv&gid={$gid}";
        // Abrir la URL y leer el contenido
        $file = fopen($url, 'r');

        // Leer la primera fila del archivo CSV como encabezados de columna
        $headers = fgetcsv($file);

        // Inicializar un array para almacenar los datos
        $data = array();

        // Leer el resto de las filas y agregarlas al array de datos
        while (($row = fgetcsv($file)) !== false) {
            $data[] = array_combine($headers, $row);
        }

        // Cerrar el archivo
        fclose($file);

        //return $headers;
        return $data;
    }
}