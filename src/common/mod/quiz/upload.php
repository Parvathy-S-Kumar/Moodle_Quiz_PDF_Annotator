<?php

/**
 * @author Tausif Iqbal, Vishal Rao
 * @updatedby Asha Jose, Parvathy S Kumar
 * All parts of this file excluding preparing the file record object and adding file to the database is modified by us
 *
 * This page saves annotated pdf to database.
 * 
 * It gets the annotation data from JavaScript through POST request. Then annotate the file using FPDI and FPDF
 * Then save it temporarily in this directory.
 *
 * Then create new file in databse using this temporary file.
 *
 */

require_once('../../config.php');
require_once('locallib.php');
require __DIR__ . '/annotatedfilebuilder.php';
require __DIR__ . '/parser.php';
require __DIR__ . '/alphapdf.php';

//To convert PDF versions to 1.4 if the version is above it since FPDI parser will only work for PDF versions upto 1.4
function convert_pdf_version($file, $path)
{
    $filepdf = fopen($file,"r");
    if ($filepdf) 
    {
        $line_first = fgets($filepdf);
        preg_match_all('!\d+!', $line_first, $matches);	
        // save that number in a variable
        $pdfversion = implode('.', $matches[0]);
        if($pdfversion > "1.4")
        {
            $srcfile_new=$path."/newdummy.pdf";
            $srcfile=$file;
            //Using GhostScript convert the pdf version to 1.4
            $shellOutput = shell_exec('gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dNOPAUSE \
            -dBATCH -sOutputFile="'.$srcfile_new.'" "'.$srcfile.'"'); 
            if(is_null($shellOutput))
            {
                throw new Exception("PDF conversion using GhostScript failed");
            }
            $file=$srcfile_new;
            unlink($srcfile);          // to remove original dummy.pdf
        }   
        fclose($filepdf);
    }

    return $file;
}

//Getting all the data from mypdfannotate.js
$value = $_POST['id'];
$contextid = $_POST['contextid'];
$attemptid = $_POST['attemptid'];
$filename = $_POST['filename'];
$component = 'question';
$filearea = 'response_attachments';
$filepath = '/';
$itemid = $attemptid;

$fs = get_file_storage();
// Prepare file record object
$fileinfo = array(
    'contextid' => $contextid,
    'component' => $component,
    'filearea' => $filearea,
    'itemid' => $itemid,
    'filepath' => $filepath,
    'filename' => $filename);

//Get the serialisepdf value contents and convert into php arrays
$json = json_decode($value,true);

//Referencing the file from the temp directory 
$path= $CFG->tempdir . '/EssayPDF';
$file = $path . '/dummy.pdf'; 
$tempfile= $path . '/outputmoodle.pdf';

if(file_exists($file))
{
    //Calling function to convert the PDF version above 1.4 to 1.4 for compatibility with fpdf
    $file=convert_pdf_version($file, $path);

    //Using FPDF and FPDI to annotate
    if(file_exists($file))
    {
        $pdf=build_annotated_file($file,$json);
        // Deleting dummy.pdf
        unlink($file);
        // creating output moodle file for loading into database
        $pdf->Output('F', $tempfile);
    }
    else
        throw new Exception('\nPDF Version incompatible'); 
}
else
    throw new Exception('\nSource PDF not found!'); 

//Untouched 
$fs = get_file_storage();
// Prepare file record object
$fileinfo = array(
    'contextid' => $contextid,
    'component' => $component,
    'filearea' => $filearea,
    'itemid' => $itemid,
    'filepath' => $filepath,
    'filename' => $filename);

//check if file already exists, then first delete it.
$doesExists = $fs->file_exists($contextid, $component, $filearea, $itemid, $filepath, $filename);
if($doesExists === true)
{
    $storedfile = $fs->get_file($contextid, $component, $filearea, $itemid, $filepath, $filename);
    $storedfile->delete();
}
// finally save the file (creating a new file)
$fs->create_file_from_pathname($fileinfo, $tempfile);
//Untouched portion ends

// Deleting outputmoodle.pdf
unlink($tempfile);  
?>
