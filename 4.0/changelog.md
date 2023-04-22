## Changes made in the moodle 4.0 directory
### new files/folder added in directory /mod/quiz/
1. annotator.php
2. upload.php 
3. myindex.html
4. mypdfannotate.js
5. mypdfannotate.css
6. myscript.js
7. mystyles.css
8. parser.php
9. test.php
10. fpdf-fpdi 



### modification made in existing files

- in file /question/type/essay/renderer.php

    - new function from line 115 to 155
        qtype_essay_renderer:: 
          feedback_files_read_only()

    - new function from line 157 to 183
        qtype_essay_renderer::  
          get_filenames()

    - modified function
        qtype_essay_renderer ::  
          formulation_and_controls  
            (line no : 77 to 78)we added new variable $annotatedfiles  
            (line no : 86 to 87)we initialized the variable $annotatedfiles  
            by calling $this->feedback_files_read_only($qa, $options)  
            (line no : 103 to 109) we are rendering the annotatedfiles

    
- in file /mod/quiz/comment.php
    -  line 111 to 116 is removed and  replaced it with new code
        from line no 111 to 157  
        the purpose of this is to create a dropdown to  
        show all the attached files and select one of them  
        after selecting it will open that file into a new   
        annotator by calling the file annotator.php (at iine 123)   

