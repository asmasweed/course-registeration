var buttons = document.querySelectorAll("button.status");
console.log(buttons);
var state = true;

buttons.forEach((button) => {
    button.addEventListener('click', () => {
      if( button.innerText === 'Not Completed'){
        button.innerText = 'Course Completed';
      }
      else {
        button.innerText = 'Not Completed';
      }       
      //console.log(button.dataset.id);
      studentUpdate(button);
    })
  })
  
  function studentUpdate(button){
    var complete = button.innerText;
    var gf_id = button.dataset.id;
    //console.log(button.dataset.id);
    //console.log(complete);
    
    jQuery.ajax({
        url : test.ajax_url,
        type : 'post',
        data : {
            action : 'update_student_status',  //php function that runs        
            complete : complete,  //variable passed to php and its name
            gf_id :   gf_id,   //variable passed to php and its name
        },
        success : function( response ) {
           // alert('update success') //tells you it worked
           
           
        }
    });
}


//certificate

//https://parall.ax/products/jspdf

function demoFromHTML() {

  var HTML_Width = jQuery("#certificate-content").width();
  var HTML_Height = jQuery("#certificate-content").height();
  var top_left_margin = 15;
  var PDF_Width = HTML_Width+(top_left_margin*1);
  var PDF_Height = (PDF_Width*1.5)+(top_left_margin*2);
  var canvas_image_width = HTML_Width;
  var canvas_image_height = HTML_Height;
  
  var totalPDFPages = Math.ceil(HTML_Height/PDF_Height)-1;
  

  html2canvas(jQuery("#certificate-content")[0],{
    allowTaint:true
  }).then(function(canvas) {
    canvas.getContext('2d');
    
    //console.log(canvas.height+"  "+canvas.width);
    
    
    var imgData = canvas.toDataURL("image/png");
    var pdf = new jsPDF('p', 'in ', [612, 792]);
      pdf.addImage(imgData, 'PNG', top_left_margin, top_left_margin,canvas_image_width,canvas_image_height);
    
    
    for (var i = 1; i <= totalPDFPages; i++) { 
      pdf.addPage(PDF_Width, PDF_Height);
      pdf.addImage(imgData, 'PNG', top_left_margin, -(PDF_Height*i)+(top_left_margin*4),canvas_image_width,canvas_image_height);
    }
    
      pdf.save("HTML-Document.pdf");
      window.open(imgData);
      });
};

jQuery("#cmd").click(function () {    
demoFromHTML();
});






//get the number and display
let secret = document.querySelectorAll('.secret');
let secretDisplay = document.querySelectorAll('.display-secret');
secret.forEach((input) => {
  input.addEventListener('input', function (evt) {
    secretDisplay.forEach((id) => {
    id.innerHTML = this.value
    });
    
    });
    
  });


//********************************************************************************** */
//display the certificate of corrsponding course title


let courseButtons = document.querySelectorAll('.certificate-button');
let courses = document.querySelectorAll('.course');
courseButtons.forEach((cButton) => {
  cButton.addEventListener('click', () => {
    let id = cButton.dataset.course; 
    let course = document.getElementById(id)
    courses.forEach((course) => {
      course.classList.add('hide')
    });
    course.classList.toggle("hide");
    
     
  });
});

