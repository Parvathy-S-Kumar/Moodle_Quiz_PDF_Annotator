# Steps for the installation of PDF Annotator for the moodle quiz module 

### Requirements

- PHP version 3.8 or above  
Follow this [link](https://ubuntu.com/server/docs/programming-php) to install PHP in ubuntu
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

### Installation
- The files added as a part of this project are
	* parser.php
	* test.php
	* alphapdf.php
	
- Files added as a part of the last year's project that we modified are
	* myindex.html
	* mypdfannotate.js
	* annotator.php
	* upload.php

- Files added/modified last year
	* mypdfannotate.css
	* myscript.js
	* mystyles.css
	* comment.php
	* rendered.php
	
- All files except renderer.php is inside mod/quiz. Copy these files in this location. Copy renderer.php inside /question/type/essay/ 	
	


