<?php

/**
 * Function that converts a json to a csv file
 *
 * @param String $json Description
 * @param String $filePath Description
 * @return void
 * @throws Exception
 **/
function jsonToCsv(String $json, String $filepath)
{
    try {
        $array = json_decode($json, true);
        $f = fopen($filepath, 'w');
    
        $header = [];

        foreach ($array as $line) {
            $header = array_merge($header, array_keys($line));
        }
    
        $header = array_values(array_unique($header));
        fputcsv($f, $header);
    
        foreach ($array as $line) {
            $rowData = [];
            foreach ($header as $field) {
                $value = $line[$field];
                if (!empty($value)) {
                    if (is_array($value)) {
                        $rowData[] = implode(', ', $value);
                    } else {
                        $rowData[] = $value;
                    }
                } else {
                    //necessary because fputcsv treats false as empty
                    $rowData[] = !is_bool($value) ? "" : "0";
                }
            }
            fputcsv($f, $rowData);
        }
    
        fclose($f);
    } catch (Throwable $th) {
        throw new Exception("Error occurred while parsing the json to csv. Error code XH001");
    }

}

/**
 * Helper function to encode error messages.
 *
 * @param String $message
 * @return mixed
 **/
function setErrorResponse($message)
{
    return json_encode(['error' => $message]);
}
