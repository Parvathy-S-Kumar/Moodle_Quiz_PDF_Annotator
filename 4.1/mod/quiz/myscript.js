/**
 * @updatedby Tausif Iqbal and Vishal Rao
 * No changes made by Asha Jose and Parvathy S Kumar
 */
// fileurl has been assigned its correct value in comment.php file
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
      event.preventDefault();    // changes made
      changeActiveTool(event);
      pdf.enableSelector();
      return false;    // changes made
  }
  
  function enablePencil(event) {
      event.preventDefault();    // changes made
      changeActiveTool(event);
      pdf.enablePencil();
      return false;    // changes made
  }
  
  function enableAddText(event) {
      event.preventDefault();    // changes made
      changeActiveTool(event);
      pdf.enableAddText();
      return false;    // changes made
  }
  

  function enableRectangle(event) {
      event.preventDefault();
      changeActiveTool(event);
      pdf.setColor('rgba(255, 0, 0, 0.3)');
      pdf.setBorderColor('blue');
      pdf.enableRectangle();
      return false;    // changes made
  }
  
  function deleteSelectedObject(event) {
      event.preventDefault();
      pdf.deleteSelectedObject();
      return false;    // changes made
  }
  
  function savePDF(event) {
      event.preventDefault();    // changes made
      pdf.savePdf('sample.pdf'); // save with given file name
      return false;    // changes made
  }
  
  $(function () {
      $('.color-tool').click(function () {
          $('.color-tool.active').removeClass('active');
          $(this).addClass('active');
          color = $(this).get(0).style.backgroundColor;
          pdf.setColor(color);
          return false;
      });
  
      $('#font-size').change(function () {
          var font_size = $(this).val();
          pdf.setFontSize(font_size);
      });
  });
  