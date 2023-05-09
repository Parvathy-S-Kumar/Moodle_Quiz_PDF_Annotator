<?php
/**
 * @author Asha Jose, Parvathy S
 * This page contains functions that annotates using fpdf
 * 
 * The data stored as suitable arrays by the parser.php file is utilized here.
 * Depending on the data, different objects can be drawn on top of a pdf using these functions.
 */


define("BRUSHSIZE",0.60);
define("FONTTYPE",'Times');
define("OPACITY",0.20);
define("FULLOPACITY",1);
define("FONTRATIO",1.6);

// Function to draw free hand drawing
function draw_path($arr, $pdf) 
{
    $list = parser_path($arr);
    $stroke = process_color(end($list));
    $pdf->SetDrawColor($stroke[0], $stroke[1], $stroke[2]);   // r g b of stroke color
    $pdf->SetLineWidth(BRUSHSIZE);
    for($k = 0; $k < sizeof($list) - 2; $k++) {
        $pdf->Line($list[$k][0],                      // x1
        $list[$k][1],                                 // y1
        $list[$k + 1][0],                             // x2
        $list[$k + 1][1]);                            // y2
    } 
}

//Function to insert text
function insert_text($arr,$pdf)
{
    $list = parser_text($arr);
    $color = process_color($list[5]);
    $pdf->SetTextColor($color[0], $color[1], $color[2]);       // r g b
    $pdf->SetFont(FONTTYPE);                                  
    // converting fabricjs font size to that of fpdf
    $pdf->SetFontSize($list[6]/FONTRATIO);                                         
    $pdf->text($list[0],                                       // x
    $list[1] + $list[3],                                       // y  ( base + height)
    $list[4]);                                                 // text content
}

//Function to draw a rectangle
function draw_rect($arr,$pdf)
{
    $list = parser_rectangle($arr);
    $fill = process_color($list[4]);
    $pdf->SetFillColor($fill[0],$fill[1],$fill[2]); // r g b
    $pdf->SetAlpha(OPACITY);                     // for highlighting
    $pdf->Rect($list[0],                      // x
    $list[1],                                 // y
    $list[2],                                 // width
    $list[3],'F');                            // height
    // F refers to syle fill
    $pdf->SetAlpha(FULLOPACITY);
}
?>