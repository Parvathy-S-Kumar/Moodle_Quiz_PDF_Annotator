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

 

2. To switch to Moodle_Quiz_PDF_Annotator directory and copy the required files by executing the following commands.
    * `cd Moodle_Quiz_PDF_Annotator/`
    * `sudo make quiz_annotator MOODLE_VERSION=x.x MOODLE_DATA_DIR=y` 
    * Usage example _sudo make quiz_annotator MOODLE_VERSION=4.1 MOODLE_DATA_DIR='/var/moodledata'_
    * The parameters MOODLE_VERSION and MOODLE_DATA_DIR are optional. 
    * MOODLE_DATA_DIR should be set to the path of moodledata directory.
    * Default MOODLE_VERSION is 4.1 and MOODLE_DATA_DIR is '/var/moodledata'
    
3. To clear the cache
    * `sudo php /var/www/html/moodle/admin/cli/purge_caches.php`
    * It is an optional step, but it is better to do.

__Note you might need to change the variable MOODLE in Makefile according to your folder name if you followed default settings while installing moodle then this is not needed but if you changed the name from moodle to something else then you need to update here the same.__   

### Steps to remove Quiz Annotator from  Moodle
1. Go to the moodle_quiz_qnnotator folder ,open terminal and run 
    * `sudo make restore`

__Note you always need to restore by the above command before making again. Repeated running of 'sudo make quiz_annotator' will copy the wrong files to the backup directory and would make the next restoring process fail. .__   
    
### Support for other versions
- Add in [/mod/quiz/](https://github.com/Parvathy-S-Kumar/Moodle_Quiz_PDF_Annotator/tree/main/src/common/mod/quiz)
	1. parser.php
	2. annotatedfilebuilder.php
	3. alphapdf.php
	4. index.html
	5. pdfannotate.js
	6. annotator.php
	7. upload.php
	8. pdfannotate.css
	9. clickhandlers.js
	10. styles.css
	
- Add in /mod/quiz/ the directory [fpdf-fpdi](https://github.com/Parvathy-S-Kumar/Moodle_Quiz_PDF_Annotator/tree/main/src/common/mod/quiz/fpdi-fpdf)

- User can go through the [changelog](https://github.com/Parvathy-S-Kumar/Moodle_Quiz_PDF_Annotator/blob/main/src/4.1/changelog.md) and modify the following files.
   1. /mod/quiz/comment.php
   2. /question/type/essay/renderer.php
