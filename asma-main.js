var buttons = document.querySelectorAll(".status");
console.log(buttons);

buttons.forEach((button) => {
    button.addEventListener('click', () => {
      this.value;
      if(button.innerHTML = 'FINISH THE COURSE'){
      button.innerHTML = 'Completed';
      }
      else{
        document.getElementById("").disabled = true;
      }
      document.getElementById("").disabled = true;  
      console.log(button.dataset.id);
      studentUpdate(button);
      
    });
  });
  
  function studentUpdate(button){
    var complete = button.value;
    var gf_id = button.dataset.id;
    //console.log(button.dataset.id);
    console.log(complete);
    
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
