<?php
/**
 * @author Asha Jose, Parvathy S
 * This page contains functions for parsing
 * The data from serialiser of fabricjs is read and processed.
 * The processed data is then stored in utiliszable manner for fpdf
 */

define("RATIO", 0.238);

// for finding corresponding size of fpdf object given a fabricjs object
function normalize($val)
{
    return RATIO * $val;
}

//Parser for free hand drawings 
function parser_path($arr) 
{
    // stored as a set of points (x and y coordinates)
    $list = array();
    $temp = array();
    $len = count($arr["path"]);
    for($i = 0; $i < $len-1 ; $i++)
    {
        $temp = array();
        if($i == 0 || $i == $len-2)
            continue;

        array_push($temp,normalize($arr["path"][$i][1]));  //x1
        array_push($temp,normalize($arr["path"][$i][2]));  //y1
        array_push($list,$temp);
        $temp = array();
        array_push($temp,normalize($arr["path"][$i][3]));  //x2
        array_push($temp,normalize($arr["path"][$i][4]));  //y2
        array_push($list,$temp);
    }
   array_push($list,$arr["stroke"]);                       // stroke color
   return $list;
}


//Parser for text
function parser_text($arr)
{
    $list = array();
    // left and top refers to x and y coordinates of top left corner
    array_push($list,normalize($arr["left"]),normalize($arr["top"]),normalize($arr["width"]),normalize($arr["height"]));
    // text refers to the content and fill is the color of the text
    array_push($list,$arr["text"],$arr["fill"]);
    array_push($list,$arr["fontSize"]);
    return $list;
}


//Parser for rectangle
function parser_rectangle($arr)
{
    $list = array();
    // scaleX and scaleY is 1 if the rectangle is not transformed
    // width and height remains same and the scaleX and scaleY changed during transformation
    $width=(normalize($arr["width"]))*($arr["scaleX"]); 
    $height=(normalize($arr["height"]))*($arr["scaleY"]);
    array_push($list,normalize($arr["left"]),normalize($arr["top"]),$width,$height);
    array_push($list,$arr["fill"]);
    return $list;

}



//Parser for processing color format of fabricjs to fpdf
function process_color($str) {
    if ($str == "null")    
        $val = [0, 0, 0];
    if ($str == "red" || $str == "rgba(255, 0, 0, 0.3)" || $str == "rgb(255, 0, 0)")                 
        $val = [255, 0, 0];              // converting string to rgb
    else if ($str == "green" || $str == "rgba(0, 255, 0, 0.3)" || $str == "rgb(0, 255, 0)")
        $val = [0, 255, 0];
    else if($str == "blue" || $str == "rgba(0, 0, 255, 0.3)" || $str == "rgb(0, 0, 255)")
        $val = [0, 0, 255];
    else if($str == "black" || $str == "rgba(0, 0, 0, 0.3)" || $str == "rgb(0, 0, 0)")
        $val = [0, 0, 0];
        else if($str == "yellow" || $str == "rgba(255, 255, 0, 0.3)" || $str == "rgb(255, 255, 0)")
        $val = [255, 255, 0];
    else {
        $val =array();
        list($r, $g, $b) = sscanf($str, "#%02x%02x%02x"); //hexadecimal format
        $val=[$r, $g, $b];
    }
    return $val;
}
?>