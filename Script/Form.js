let isDrawing=false;
const noticeCanvas=$(".noticeCanvas");
let isClear=false;
var boxSignature;
const modalBox=$(".box-canvas");
const signatureDestBox=$(".signatureBox");
const canvas = document.getElementById("canvas");
const contextDraw = canvas.getContext("2d");
const signatureImg = $("#signatureId")[0];
let venueCapacity=[];

$(document).ready(function () {
   initalizeInputs();
   radioVenues();
     //additems

     addItems("#AO-itemAdd-btn","AO-");
     addItems("#LMO-itemAdd-btn","LMO-");
    equipmentCheckBoxFunc();


    //the container
    const modalSignature=$(".modal-sign");
     boxSignature=$(".signatureCanva");

    //get signature
    $("#btn-sign").on('click',function(e){
        e.preventDefault();
        modalSignature.attr("style","display:block;");
        resize();
        boxSignature.animate({
            opacity: 1,
            top: "15%"
        },900);
    });


    //save signature
    $("#btn-sign-save").on('click',function(e){
        e.preventDefault();
            const dataUrl=canvas.toDataURL();
            signatureImg.src=dataUrl;
            signatureImg.height=signatureDestBox.height();
            signatureImg.width = signatureDestBox.width();
            $("#signatureImg").val(signatureImg.src);
            $("#uploadSignature").val('');
            closeSignature();
    });

    //close signature
    $("#btn-clear").on('click',function (e) {
         e.preventDefault();
        contextDraw.clearRect(0,0,canvas.width,canvas.height);
        noticeCanvas.attr("style","display:block;");
    })

    //upload signature
    $("#uploadSignature").change(function (e) {
        e.preventDefault();
        if(this.files&&this.files[0]){
            var reader=new FileReader();
            reader.onload = function(){
                signatureImg.src=reader.result;
                signatureImg.height=signatureDestBox.height();
                signatureImg.width = signatureDestBox.width();
            }
            reader.readAsDataURL(this.files[0]);
        }
    })

    function closeSignature(){
        modalSignature.attr("style","display:none;");
        boxSignature.attr("style","top: 0;");
        noticeCanvas.attr("style","display:block;");
        isClear=false;
    }



    $(".closeModal").on('click',function(e){
        e.preventDefault();
        closeSignature();
    });
    window.addEventListener("resize",resize);
    canvas.addEventListener("mousedown",startDrawing);
    canvas.addEventListener("mouseup",endDrawing);
    canvas.addEventListener("mouseleave",endDrawing);
    canvas.addEventListener("mousemove",draw); 

    function resize(){
        canvas.height=modalBox.height();
        canvas.width=modalBox.width();
        signatureImg.height=signatureDestBox.height();
        signatureImg.width = signatureDestBox.width();
    }

    var reservationReviewForm =$("#Reservation-Form");
    var organizationReviewForm =$("#Organization-Form");
    $(".review-nav-tab").on("click",function(){
        if(this.id=="review-org"){
            if(!organizationReviewForm.is(":visible")){
                $(this).addClass("on-review-tab");
                $("#review-reservation").removeClass("on-review-tab");
                reservationReviewForm.css("display","none");
                organizationReviewForm.css("display","block");
            }
        }
        else if(this.id=="review-reservation"){
            if(!reservationReviewForm.is(":visible")){
                $(this).addClass("on-review-tab");
                $("#review-org").removeClass("on-review-tab");
                reservationReviewForm.css("display","block");
                organizationReviewForm.css("display","none");
            }
        }
        else
            alert("error");
    })

});
   //intialization
   function initalizeInputs() {
        var date=new Date();
        var dateToday=getDateToday(date);
        $("#dateTodayFilling").val(dateToday);
        $("#dateStart").val(dateToday);
        $("#dateEnd").val(dateToday);

        //time
        $("#startTime").val("12:00");
        $("#endTime").val("12:01");
  }

function getDateToday(date){
    var month=date.getMonth()+1;
    var todayMonth=month;
    var today=date.getDate();

        if(month<10){
            todayMonth="0"+month;
        }
        if(today<10)
            today = "0"+today;
    return date.getFullYear()+"-"+todayMonth+"-"+today;
}

function getTimeToday(date){
    return date.getHours()+":"+date.getMinutes();
}
function startDrawing(e){
    isDrawing=true;
    noticeCanvas.attr("style","display:none;");
    contextDraw.beginPath();
}
function endDrawing(){
     isDrawing=false;
    
}
            
 function draw(e){
    if(!isDrawing)
        return;
    
     //style
     contextDraw.lineWidth=5;
     contextDraw.lineCap="round";
    
    //position
     contextDraw.lineTo(e.offsetX,e.offsetY);
    contextDraw.stroke();
}
    
 
    
    
    var equipmentsOtherIndex=1;

    function addItems(btnDirectory,type){
        $(btnDirectory).on("click",function (e) { 
            event.preventDefault();
    
            var currentEquip=type+"Other"+equipmentsOtherIndex;
    
            var template=    '<div id="'+(currentEquip+"-Name")+"ModalHelp"+'" class="modal-format-info shadow format-info">'+
            '<div class="pl-3 py-2 modal-format-header">'+
                 '<h4 class="text-dark">Equipments: Format</h4>'+
             '</div>'+
             '<ul class="py-2 pr-2">'+
                ' <li>Must be realistic Equipment</li>'+
                 '<li>It must contain at least 2 alphabet letters</li>'+
            '</ul>'+
            '</div>'+
            '<div class="my-2 row d-flex align-items-center">'+
            '<input name="equipments" type="checkbox" class="col-md-0" value="'+currentEquip+'" checked>'+
             '<div class="col-md-5">'+
             '<input type="text" id="'+(currentEquip+"-Name")+'" class="form-control Input-Equip" placeholder="Specify Equipments">'+
             '<div class="invalid-feedback">Invalid format</div>'+
             '</div>'+
             '<div class="col-md-6">'+
             '<input  id="'+(currentEquip+"-Qty")+'" type="number"  placeholder="Quantity" class="form-control w-100 qty-input" min="1" max="100">'+
             '<div class="invalid-feedback">Please provide a number greater than 0</div>'+
             '</div>'+
             '</div>';
     
        
            if(btnDirectory.substring(0,3)=="#AO"){
                var adminItems= $("#AO-itemAdd");
                adminItems.append(template);
                adminItems.scrollTop(adminItems[0].scrollHeight);
            }
            else if(btnDirectory.substring(0,4)=="#LMO"){
                var labItems= $("#LMO-itemAdd");
                labItems.append(template);
                labItems.scrollTop(labItems[0].scrollHeight);
            }
           
            equipmentsOtherIndex++;
        });
    }
    
    function equipmentCheckBoxFunc(){
        $(".equipments").delegate("input[name='equipments']","click",function () {
    
            var equipmentName=$("#"+$(this).val()+"-Name");
            var equipmentQty= $("#"+$(this).val()+"-Qty");
               if(equipmentName.prop("readonly")){
                   equipmentName.prop("readonly",false);
               }
               else
                  equipmentName.prop("readonly",true);
        
            if(!$(this).is(":checked")){
                equipmentQty.attr("readonly",true);
                return;
            }
        if(equipmentQty.length>0){
            equipmentQty.attr("readonly",false);
        }
      })
    
    }
    
   
var isOpenEquipment=false;
var isOpenVenues=false;
    
    
function remarksEquipment(){
        var equimentsRemarks =  $("#equipmentRemarks");
        if(!isOpenEquipment){
            equimentsRemarks.slideDown();
            isOpenEquipment=true;
            $("#equipmentRemarksIndicator").attr("src","Images/Form/minus.png");
        }
        else{
            equimentsRemarks.slideUp();
            isOpenEquipment=false;
            $("#equipmentRemarksIndicator").attr("src","Images/Form/plus.png");
        }
 }

 function remarksVenues(){
        var venueRemarks=$("#venueRemarks");
        if(!isOpenVenues){
            venueRemarks.slideDown();
            isOpenVenues=true;
            $("#venueRemarksIndicator").attr("src","Images/Form/minus.png");
        }
        else{
            venueRemarks.slideUp();
            isOpenVenues=false;
            $("#venueRemarksIndicator").attr("src","Images/Form/plus.png");
        }
 }
    

 function radioVenues() {
      //radio-buttons for Venues
      var venueInput=null;
      var venueSelect=null;
      $("input[name='venue']").click(function (e) { 
          if(venueSelect!=null){
              venueSelect.attr("disabled",true);
              venueSelect=null;
          }
  
          if(venueInput!=null){
              venueInput.attr('readonly',true);
              if(venueInput.hasClass("is-invalid"))
                 venueInput.removeClass("is-invalid");
              if(venueInput.hasClass("is-valid"))
                 venueInput.removeClass("is-valid");
          }
          if($(this).val()=="Additional-Facilities"){
              venueSelect=$("#AdditionalSelect");
              venueSelect.attr("disabled",false);
          }
          venueInput=$("#"+$(this).val());
          if(venueInput.length>0)
          venueInput.attr('readonly',false);
      });
   }