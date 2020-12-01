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
  
  var pdf = new jsPDF('p', 'pt', 'letter');
  // source can be HTML-formatted string, or a reference
  // to an actual DOM element from which the text will be scraped.
  source = $('#content')[0];

  // we support special element handlers. Register them with jQuery-style 
  // ID selector for either ID or node name. ("#iAmID", "div", "span" etc.)
  // There is no support for any other type of selectors 
  // (class, of compound) at this time.
  specialElementHandlers = {
      // element with id of "bypass" - jQuery style selector
      '#bypassme': function (element, renderer) {
          // true = "handled elsewhere, bypass text extraction"
          return true
      }
  };
  margins = {
      top: 80,
      bottom: 60,
      left: 40,
      width: 522
  };
  // all coords and widths are in jsPDF instance's declared units
  // 'inches' in this case
  pdf.fromHTML(
      source, // HTML string or DOM elem ref.
      margins.left, // x coord
      margins.top, { // y coord
          'width': margins.width, // max width of content on PDF
          'elementHandlers': specialElementHandlers
      },

      function (dispose) {
          // dispose: object with X, Y of the last line add to the PDF 
          //          this allow the insertion of new lines after html
          pdf.save('neat-certificate.pdf');
      }, margins
  );
}

jQuery('#cmd').click(function () {    
demoFromHTML();
});



//get the number and display
let secret = document.querySelector('#secret');
let secretDisplay = document.querySelector('#display-secret');
secret.addEventListener('input', function (evt) {
console.log(this.value);
secretDisplay.innerHTML = this.value
});

//********************************************************************************** */
//display the certificate of corrsponding course title

let courseButtons = document.querySelectorAll('.certificate-button');

//document.getElementsByClassName("certificates-list")[0].style.display = "none";



courseButtons.forEach((cButton) => {
  cButton.addEventListener('click', () => {
    let id = cButton.dataset.course; 
    let course = document.getElementById(id)
    //course.classList.toggle("show");
    course.style.display="block";
     
  });
});

