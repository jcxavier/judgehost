<?php

error_reporting(E_ALL);
ini_set('display_errors','On');

putenv ("NLS_LANG=AMERICAN_AMERICA.AL32UTF8");


$strings['ct_en'] = "The total number of columns between the submission and solution did not match.";
$strings['rt_en'] = "The total number of rows between the submission and solution did not match.";
$strings['rd_en'] = " row(s) were different from the solution.";

// Opens a connection to an Oracle DBMS system
function openConnection($username, $password, $hostname, $port, $sid)
{
    $conn_str = '
    (DESCRIPTION =
        (ADDRESS =
           (PROTOCOL = TCP)
           (HOST = '.$hostname.')
           (PORT = '.$port.')
           (HASH = '.rand(0,99999999).')
         )
         (CONNECT_DATA = (SID = '.$sid.'))
    )';
    
    if ($c = oci_connect($username, $password, $conn_str))
        return $c;
        
    $err = oci_error();
    echo "Oracle Connect Error " . $err['message'];   
        
    return NULL;
}

// Closes the connection with an Oracle DBMS system
function closeConnection($conn)
{
    oci_close($conn);
}

// Executes a query
function execQuery($conn, $sql)
{
    return oci_parse($conn, $sql);
}

// Retrieves the column names
function getColumnArray($stmt)
{
    oci_execute($stmt, OCI_DESCRIBE_ONLY);
    
    for ($i = 1; $i <= oci_num_fields($stmt); ++$i)
        $columns[$i-1] = oci_field_name($stmt, $i);
        
    return $columns;
}

// Retrieves a matrix containing all rows and columns, including the column names
function getRowMatrix($stmt)
{
    $idx = 0;
    $matrix[$idx++] = getColumnArray($stmt);
    
    oci_execute($stmt);
    
    while ($row = oci_fetch_array($stmt, OCI_ASSOC+OCI_RETURN_NULLS))
    {
        $i = 0;
        $newRow = array();
        
        foreach ($row as $item)
            $newRow[$i++] = $item;
        
        $matrix[$idx++] = $newRow;
    }

    return $matrix;
}

// Compares two query matrixes and outputs the differences to $errors
function compareMatrixes($correct, $submission)
{
    $errors = array();
        
    $arr = array_diff($correct[0], $submission[0]);
    $errors['columnNames'] = (sizeof($arr) > 0);
    
    $errors['columnTotal'] = (sizeof($correct[0]) != sizeof($submission[0]));
    $errors['rowTotal'] = (sizeof($correct) != sizeof($submission));
    $errors['rowDifferences'] = 0;
    
    for ($i = 1 ; $i < sizeof($correct) && $i < sizeof($submission); $i++)
    {    
        $arr = array_diff($correct[$i], $submission[$i]);
        $errors['rowDifferences'] += (sizeof($arr) > 0 ? 1 : 0);
    }
    
    if ($errors['rowDifferences'] == sizeof($correct))
        $errors['rowDifferences'] = sizeof($submission);
    
    return $errors;
}

// Prints the query matrix to a HTML table
function printMatrixToHTML($matrix)
{
    echo "<table>\n";
    echo "<table border='1'>\n";
    
    foreach ($matrix as $row)
    {
        echo "<tr>\n";
        
        foreach ($row as $item)
            echo "<td>" . ($item !== null ? $item : "&nbsp;") . "</td>\n";
        
        echo "</tr>\n";
    }

    echo "</table>\n";
}

// Prints the errors in a human readable format
function printPretty($errors)
{ 
    global $strings;
    
    if ($errors['columnTotal'] == true)
        print $strings['ct_en'] . " ";
        
    if ($errors['rowTotal'] == true)
        print $strings['rt_en'] . " ";
        
    if ($errors['rowDifferences'] > 0)
        print $errors['rowDifferences'] . $strings['rd_en'];
}
?>