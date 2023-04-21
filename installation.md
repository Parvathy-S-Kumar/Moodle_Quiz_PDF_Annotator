# Steps for the installation of PDF Annotator for the moodle quiz module 

### Requirements

- GhostScript  
Follow this [link](https://docs.bitnami.com/google/apps/resourcespace/configuration/install-ghostscript/) to install ghostscript in ubuntu if it is not already installed
- Imagemagick  
    * `sudo apt install imagemagick`
    *  update __rights="none"__ to __rights=read|write__ in __/etc/ImageMagick-6/policy.xml__  for __pattern="pdf"__ , see [this](https://askubuntu.com/questions/1181762/imagemagickconvert-im6-q16-no-images-defined)
    ```xml
        <!-- disable ghostscript format types -->
        <policy domain="coder" rights="none" pattern="PS" />
        <policy domain="coder" rights="none" pattern="EPS" />
        <policy domain="coder" rights="none" pattern="PDF" /> <------- Here!!
        <policy domain="coder" rights="none" pattern="XPS" />

    ```

    * (optional) now to check imagemagick is working , convert a png file to a pdf ,in terminal run 
        * `convert file.png file.pdf`
        
### Steps to add Quiz Annotator to Moodle

1. Download the zip file from this repository, extract in and copy the extracted folder moodle_quiz_annotator to the moodle root folder location `/path/to/moodle`. For example in ubuntu directory structure should be like this-
    ```bash
      .
      ├── /var/www/html/moodle/
      └── /var/www/html/Moodle_Quiz_PDF_Annotator
    ```

<details><summary> :warning: warning </summary>
 
#### after step 3 these files are going to be changed
    * moodle/quesiton/type/essay/renderer.php
    * moodle/mod/quiz/comment.php
</details>

 

2. Go to the moodle_quiz_annotator and in terminal run , as of now it supports MOODLE_VERSION 4.0
    * `sudo make quiz_annotator MOODLE_VERSION=x.x` 
    * for example _sudo make quiz_annotator MOODLE_VERSION=4.0_


### Steps to remove Quiz Annotator from  Moodle
1. Go to the moodle_quiz_qnnotator folder ,open terminal and run 
    * `sudo make restore`
    
### Installation
- The files added as a part of this project are
	1. parser.php
	2. test.php
	3. alphapdf.php
	4. myindex.html
	5. mypdfannotate.js
	6. annotator.php
	7. upload.php
	8. mypdfannotate.css
	9. myscript.js
	10. mystyles.css
	11. comment.php
	12. renderer.phps
	13. fpdf-fpdi folder
	
- Files from 4 to 12 are added/modified from the git repo https://github.com/TausifIqbal/moodle_quiz_annotator
	

- Files from 4 to 8 are modified while 9 to 12 are untouched.
	
	
- An additional folder for fpdf-fpdi is also to be added inside the moodle quiz module
	
- All files except renderer.php is inside mod/quiz. Copy these files in this location. Copy renderer.php inside /question/type/essay/ 	
	


