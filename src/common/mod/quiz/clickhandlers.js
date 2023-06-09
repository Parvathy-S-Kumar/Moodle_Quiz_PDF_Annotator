/**
 * Originally taken from Ravisha Hesh : https://github.com/RavishaHesh/PDFJsAnnotations/tree/master
 * @updatedby Tausif Iqbal and Vishal Rao
 * Changes were made by Asha Jose and Parvathy S Kumar
 * OnClick handlers in index.html are defined here
 * These functions will call functions in pdfannotate.js
 */


// This function calls PDFAnnotate function defined in pdfannotate.js
// fileurl has been assigned its correct value in annotator.php file
var pdf = new PDFAnnotate("pdf-container", fileurl, {
    onPageUpdated(page, oldData, newData) {
    },
    ready() {
    },
    scale: 1.5,
    pageImageCompression: "SLOW", // FAST, MEDIUM, SLOW(Helps to control the new PDF file size)
});

function changeActiveTool(event) {
    event.preventDefault();
    var element = $(event.target).hasClass("tool-button")
    ? $(event.target)
    : $(event.target).parents(".tool-button").first();
    $(".tool-button.active").removeClass("active");
    $(element).addClass("active");
}

function enableSelector(event) {
    event.preventDefault();    
    changeActiveTool(event);
    pdf.enableSelector();    
}

function enablePencil(event) {
    event.preventDefault();    
    changeActiveTool(event);
    pdf.enablePencil();    
}

function enableAddText(event) {
    event.preventDefault();    
    changeActiveTool(event);
    pdf.enableAddText();    
}

function enableRectangle(event) {
    event.preventDefault();
    changeActiveTool(event);
    pdf.enableRectangle();    
}

function deleteSelectedObject(event) {
    event.preventDefault();
    pdf.deleteSelectedObject();   
}

function savePDF(event) {
    event.preventDefault();    
    pdf.savePdf();        //Changes made by Asha and Parvathy: Removed a parameter of the function
}

//Change the color and font size to currently selected color and font size respectively in the index.html UI
$(function () {
    $('.color-tool').click(function () {
        $('.color-tool.active').removeClass('active');
        $(this).addClass('active');
        color = $(this).get(0).style.backgroundColor;
        pdf.setColor(color);
    });

    $('#font-size').change(function () {
        var font_size = $(this).val();
        pdf.setFontSize(font_size);
    });
});
  
