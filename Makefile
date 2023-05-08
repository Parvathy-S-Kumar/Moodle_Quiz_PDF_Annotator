.PHONY: all generate restore quiz_annotator build backup check_version

MOODLE="moodle"
MOD_QUIZ="mod/quiz"
MOODLE_VERSION=4.0
QUESTION_TYPE_ESSAY="question/type/essay"
TEMP_VERSION=4.0
MOODLE_DATA_DIR="/var/moodledata"

all: quiz_annotator 

quiz_annotator $(MOODLE_VERSION) $(MOODLE_DATA_DIR): check_version  build backup generate
 	
	@echo "quiz annotator is ready to use."

check_version:
	$(eval TEMP_VERSION=${MOODLE_VERSION})
ifeq ($(MOODLE_VERSION),4.0)
	@echo "installing version 4.0 .. "
else ifeq ($(MOODLE_VERSION),4.1)
	@echo "installing version 4.1 .. "
else ifeq ($(MOODLE_VERSION),4.2)
	@echo "installing version 4.2 .. "
else ifeq ($(MOODLE_VERSION),3.11)
	@echo "installing version 3.11 ..."
	$(eval TEMP_VERSION=4.0)
else 
	$(error only MOODLE_VERSION 3.11 - 4.2 are supported)
endif

build:
	@echo "Creating backup and temporary directories"
	@mkdir -p backup/${MOD_QUIZ}
	@mkdir -p backup/${QUESTION_TYPE_ESSAY}
	@mkdir -p -m777 ${MOODLE_DATA_DIR}/temp/EssayPDF

backup:
	@echo "making backup ready"
	@cp -v -p ./../${MOODLE}/${MOD_QUIZ}/comment.php  ./backup/${MOD_QUIZ}/
	@cp -v -p ./../${MOODLE}/${QUESTION_TYPE_ESSAY}/renderer.php ./backup/${QUESTION_TYPE_ESSAY}/ 


generate:
	@echo "Copying php files..."
	@cp -v -p ./${TEMP_VERSION}/${MOD_QUIZ}/comment.php  ./../${MOODLE}/${MOD_QUIZ}/
	@cp -v -p ./${TEMP_VERSION}/${MOD_QUIZ}/annotator.php  ./../${MOODLE}/${MOD_QUIZ}/
	@cp -v -p ./${TEMP_VERSION}/${MOD_QUIZ}/myindex.html  ./../${MOODLE}/${MOD_QUIZ}/
	@cp -v -p ./${TEMP_VERSION}/${MOD_QUIZ}/mypdfannotate.css  ./../${MOODLE}/${MOD_QUIZ}/
	@cp -v -p ./${TEMP_VERSION}/${MOD_QUIZ}/mypdfannotate.js  ./../${MOODLE}/${MOD_QUIZ}/
	@cp -v -p ./${TEMP_VERSION}/${MOD_QUIZ}/myscript.js    ./../${MOODLE}/${MOD_QUIZ}/
	@cp -v -p ./${TEMP_VERSION}/${MOD_QUIZ}/mystyles.css  ./../${MOODLE}/${MOD_QUIZ}/
	@cp -v -p ./${TEMP_VERSION}/${MOD_QUIZ}/upload.php  ./../${MOODLE}/${MOD_QUIZ}/
	@cp -v -p ./${TEMP_VERSION}/${MOD_QUIZ}/parser.php  ./../${MOODLE}/${MOD_QUIZ}/
	@cp -v -p ./${TEMP_VERSION}/${MOD_QUIZ}/parsefunctions.php  ./../${MOODLE}/${MOD_QUIZ}/
	@cp -v -p ./${TEMP_VERSION}/${MOD_QUIZ}/alphapdf.php  ./../${MOODLE}/${MOD_QUIZ}/
	@cp -r -p ./${TEMP_VERSION}/${MOD_QUIZ}/fpdi-fpdf  ./../${MOODLE}/${MOD_QUIZ}/
	@cp -v -p ./${TEMP_VERSION}/${QUESTION_TYPE_ESSAY}/renderer.php  ./../${MOODLE}/${QUESTION_TYPE_ESSAY}/
	@echo "copying done"


restore:
	@echo "removing php files"
	@rm -f ./../${MOODLE}/${MOD_QUIZ}/annotator.php
	@rm -f ./../${MOODLE}/${MOD_QUIZ}/myindex.html
	@rm -f ./../${MOODLE}/${MOD_QUIZ}/mypdfannotate.css
	@rm -f ./../${MOODLE}/${MOD_QUIZ}/mypdfannotate.js
	@rm -f ./../${MOODLE}/${MOD_QUIZ}/myscript.js 
	@rm -f ./../${MOODLE}/${MOD_QUIZ}/mystyles.css
	@rm -f ./../${MOODLE}/${MOD_QUIZ}/parsefunctions.php
	@rm -f ./../${MOODLE}/${MOD_QUIZ}/test.php
	@rm -rf ./../${MOODLE}/${MOD_QUIZ}/fpdi-fpdf
	@rm -f ./../${MOODLE}/${MOD_QUIZ}/upload.php
	@rm -f ./../${MOODLE}/${MOD_QUIZ}/alphapdf.php
	@rm -f ./../${MOODLE}/${QUESTION_TYPE_ESSAY}/renderer.php
	@echo "restoring files"
	@cp -v -p ./backup/${MOD_QUIZ}/comment.php ./../${MOODLE}/${MOD_QUIZ}/  
	@cp -v -p ./backup/${QUESTION_TYPE_ESSAY}/renderer.php ./../${MOODLE}/${QUESTION_TYPE_ESSAY}/
	@echo "removing temporary directory"
	@rm -rf ${MOODLE_DATA_DIR}/temp/EssayPDF
