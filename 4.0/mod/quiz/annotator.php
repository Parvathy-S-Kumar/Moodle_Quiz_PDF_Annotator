<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * This page allows the teacher to annotate file of a particular question.
 *
 * @author Tausif Iqbal and Vishal Rao
 * @updatedby Asha Jose and Parvathy S Kumar
 * @package   mod_quiz
 * @copyright gustav delius 2006
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once('locallib.php');

//The $tempPath is the path to the subdirectory EssayPDF created in moodle's temp directory 
$tempPath = $CFG->tempdir ."/EssayPDF";
$dummyFile= $tempPath ."/dummy.pdf";

$attemptid = required_param('attempt', PARAM_INT);
$slot = required_param('slot', PARAM_INT); // The question number in the attempt.
$fileno = required_param('fileno', PARAM_INT);
$cmid = optional_param('cmid', null, PARAM_INT);

$PAGE->set_url('/mod/quiz/annotator.php', array('attempt' => $attemptid, 'slot' => $slot, 'fileno' => $fileno));

$attemptobj = quiz_create_attempt_handling_errors($attemptid, $cmid);
$attemptobj->preload_all_attempt_step_users();

$que_for_commenting = $attemptobj->render_question_for_commenting($slot);

// we need $qa and $options to get all files submitted by student
$qa = $attemptobj->get_question_attempt($slot);
$options = $attemptobj->get_display_options(true);

// get all the files
$files = $qa->get_last_qt_files('attachments', $options->context->id);

// select the "$fileno" file
$fileurl = "";
$currfileno = 0;
foreach ($files as $file) {
    $currfileno = $currfileno + 1;
    if($currfileno == $fileno)              // this is the file we want
    {
        $out = $qa->get_response_file_url($file);
        $url = (explode("?", $out))[0];     // remove ?forcedownload=1 from the end of the url
        $fileurl = $url;
        $originalFile = $file;             // storing it; in case the file is not PDF, we need the original file to create PDF from it
        break;
    }
}

// variable required to check if annotated file already exists 
// if exists, then render this file only (i.e. update the $fileurl)
$attemptid = $attemptobj->get_attemptid();
$contextid = $options->context->id;
$filename = explode("/", $fileurl);
$filename = end($filename);     //Changed
$filename = urldecode($filename);
$component = 'question';
$filearea = 'response_attachments';
$filepath = '/';
$itemid = $attemptobj->get_attemptid();

$canProceed=true;
// checking if file is not pdf
$format = explode(".", $filename);
$format = end($format);     //Changed
$ispdf = true;
if($format !== 'pdf')
{
    $ispdf = false;
    $filename = (explode(".", $filename))[0] . "_topdf.pdf";
}

$fs = get_file_storage();

// check if the annotated pdf exists or not in database
$doesExists = $fs->file_exists($contextid, $component, $filearea, $itemid, $filepath, $filename);
if($doesExists === true)   // if exists then update $fileurl to the url of this file
{
    // the file object
    $file = $fs->get_file($contextid, $component, $filearea, $itemid, $filepath, $filename);
    // create url of this file
    $file->copy_content_to($dummyFile);

    $url = file_encode_url(new moodle_url('/pluginfile.php'), '/' . implode('/', array(
        $file->get_contextid(),
        $file->get_component(),
        $file->get_filearea(),
        $qa->get_usage_id(),
        $qa->get_slot(),
        $file->get_itemid())) .
        $file->get_filepath() . $file->get_filename(), true);
    
    $url = (explode("?", $url))[0];     // remove '"forcedownload=1' from the end of the url
    $fileurl = $url;                    // now update $fileurl
} else if($ispdf == false)
{
    // annotated PDF doesn't exists and the original file is not a PDF file
    // so we need to create PDF first and update fileurl to this PDF file

    // copy non-pdf file to the temp directory of moodledata
    $fileToConvert=$tempPath . "/" . $originalFile->get_filename();
    $originalFile->copy_content_to($fileToConvert);
    
    // get the mime-type of the original file
    $mime = mime_content_type($originalFile->get_filename());
    $mime = (explode("/", $mime))[0];

    
    //Updated
    // convert that file into PDF, based on mime type (NOTE: this will be created in the cwd)
    try
    {
        if($mime === "image")
            $command = "convert " . $fileToConvert ." " .$dummyFile;
        else if($mime=="text")
        {
            $command = "convert TEXT:" . $fileToConvert ." " .$dummyFile;
        }
        else
        {
            $canProceed=false;
            throw new Exception("Unsupported File Type");
        }
    }
    catch(Exception $e)
    {
        echo 'Message: ' .$e->getMessage();
    }
    //Updation ends

    if($canProceed == true)
    {
        //Execute the commands of imagemagick(Convert texts and images to PDF)
        shell_exec($command);

        // now delete that non-pdf file from tempPath; because we don't need it anymore
        $command = "rm " . $fileToConvert;
        shell_exec($command);

        // create a PDF file in moodle database from the above created PDF file
        $temppath = $dummyFile;
        $fileinfo = array(
            'contextid' => $contextid,
            'component' => $component,
            'filearea' => $filearea,
            'itemid' => $itemid,
            'filepath' => $filepath,
            'filename' => $filename);

        $fs->create_file_from_pathname($fileinfo, $temppath);   // create file

        // now update fileurl to this newly created PDF file
        $file = $fs->get_file($contextid, $component, $filearea, $itemid, $filepath, $filename);
        // create url of this file
        $url = file_encode_url(new moodle_url('/pluginfile.php'), '/' . implode('/', array(
            $file->get_contextid(),
            $file->get_component(),
            $file->get_filearea(),
            $qa->get_usage_id(),
            $qa->get_slot(),
            $file->get_itemid())) .
            $file->get_filepath() . $file->get_filename(), true);
        $url = (explode("?", $url))[0];     // remove '"forcedownload=1' from the end of the url
        $fileurl = $url;                    // now update $fileurl
    }
}
else
{
    $originalFile->copy_content_to($dummyFile);
}

//Checking if dummyfile was successfully created
if(!(file_exists($dummyFile)))
{
    $canProceed=false;
    throw new Exception("Permission  Denied");
}  //Changed

if($canProceed == true) //Changed
{
    // include the html file; It has all the features of annotator
    include "./myindex.html";
}
?>
<!-- assigning php variable to javascript variable so that
     we can use these in javascript file
 -->
<script type="text/javascript">
    var fileurl = "<?= $fileurl ?>"
    var furl = "<?= $fileurl ?>"
    var contextid = "<?= $contextid ?>";
    var attemptid = "<?= $attemptid ?>";
    var filename = "<?= $filename ?>"; 
    var filearea = "<?= $filearea ?>"; 
    var filepath = "<?= $filepath ?>"; 
    var component = "<?= $component ?>"; 
</script>
<script type="text/javascript" src="./myscript.js"></script>
