## Changes made in the moodle 4.0 directory
### new files/folder added in directory /mod/quiz/
1. annotator.php
2. upload.php 
3. index.html
4. pdfannotate.js
5. pdfannotate.css
6. clickhandlers.js
7. styles.css
8. parser.php
9. annotatedfilebuilder.php
10. alphapdf.php
11. fpdf-fpdi 



### modification made in existing files

___NOTE: Line numbers mentioned below are with respect to Moodle 4.0 and it may change depending on the installed version.___

- in file /question/type/essay/renderer.php

    - new function from line 117 to 157  
        _qtype_essay_renderer::_     
          _feedback_files_read_only()_  

    - new function from line 158 to 186  
        _qtype_essay_renderer::_      
          _get_filenames()_    

    - modified function  
        _qtype_essay_renderer ::_    
          _formulation_and_controls_    
            (line no : 77 to 78) _added new variable $annotatedfiles_      
            (line no : 86 to 87) _initialized the variable $annotatedfiles by calling $this->feedback_files_read_only($qa, $options)_      
            (line no : 103 to 109) _rendering the annotatedfiles_    

    
- in file /mod/quiz/comment.php
    -  line 113 to 115 is removed and  replaced it with new code
        from line no 113 to 161
        _the purpose of this is to create a dropdown to  
        show all the attached files and select one of them  
        after selecting it will open that file into a new   
        annotator by calling the file annotator.php (at line 127)_  

