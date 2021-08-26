<?php
function encodeUrl(string $input)
{
    $output = "";
    foreach (str_split($input) as $char) {
        if (rand(0, 1) === 1)
            $output .= '&#'.ord($char).';';
        else
            $output .= $char;
    }
    return $output;
}
function pt($param){
    return htmlspecialchars($param);
}