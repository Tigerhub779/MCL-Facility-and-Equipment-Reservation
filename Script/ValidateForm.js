//global form variables
const arrayTabForm=[$("#terms-conditionBox"),$(".profileBox"),$(".orgBox"),$(".venueBox"),$(".equipmentForm"),$(".finalBox")];
var orgRadio,reserveType,equipments,indexForm=0,prevForm=$("#terms-conditionBox"),prevIndex=[],goingBack=false,isSubmit=false;
let hashMapInfo=[];
let hashMapTypeInfo=[];
let errorMsg=[];

//
let test;

//class global
let profileInfo,orgInfo,reserveInfo,signatureInfo;

//class of declaring variable
class FormProfileInfo{
    constructor(requesterName,dateFilling,department,natureActivity,isOrg,reservationType){
        this._name=requesterName;
        this._dateFill=dateFilling;
        this._department=department;
        this._natureActivity=natureActivity;
        this._isOrg=isOrg
        this._reservationType=reservationType;
    }

    get FullName(){
        return this._name;
    }

    get DateFill(){
        return this._dateFill;
    }

    get Department(){
        return this._department;
    }

    get NatureActivity(){
        return this._natureActivity;
    }
    get IsOrg(){
        return this._isOrg;
    }
    get ReservationType(){
        return this._reservationType;
    }

    printAll(){
        console.log("Accepted info");
        console.log(this.FullName);
        console.log(this._dateFill);
        console.log(this._department);
        console.log(this._natureActivity);
        console.log(this._isOrg);
        console.log(this._reservationType);
    }

}

class OrganizationFormInfo{
    constructor(orgName,activityName,objectives,details,orgPres,orgSec,orgTres){
            this._orgName = orgName;
            this._activityName = activityName;
            this._objectives =objectives;
            this._details =details;
            this._orgPres = orgPres;
            this._orgSec = orgSec;
            this._orgTres = orgTres;
    }

    get orgName(){
        return this._orgName;
    }

    get activityName(){
        return this._activityName;
    }

    get objectives(){
        return this._objectives;
    }

    get details(){
        return this._details;
    }

    get orgPres(){
        return this._orgPres;
    }

    get orgSec(){
        return this._orgSec;
    }

    get orgTres(){
        return this._orgTres;
    }

    printAll(){
        console.log("Accepted Info Organizations")
        console.log(this._orgName);
        console.log(this._activityName);
        console.log(this._objectives);
        console.log(this._details);
        console.log("Members:")
        console.log(this._orgPres);
        console.log(this._orgSec);
        console.log(this._orgTres);
    }
}

class ReservationFormInfo{
    constructor(dateStart,dateEnd,timeStart,timeEnd){
        this._dateStart = dateStart;
        this._dateEnd = dateEnd;
        this._timeStart = timeStart;
        this._timeEnd = timeEnd;
        this._venue=null;
        this._venueRoom=null;
        this._equipmentsListName=null;
        this._equipmentsListQty=null;
        this._venueRemarks=null;
        this._equipmentRemarks=null;
    }

    set venueProposed(venue){
        this._venue = venue;
    }

    set venueRoomProposed(venueRoom){
        this._venueRoom=venueRoom;
    }


    set equipmentsNameProposedAO(equipmentsName){
        this._equipmentsListNameAO=equipmentsName;
    }

    set equipmentsNameProposedLMO(equipmentsName){
        this._equipmentsListNameLMO=equipmentsName; 
    }

    set equipmentsQtyProposedAO(equipmentsQty){
        this._equipmentsListQtyAO=equipmentsQty;
    }
    set equipmentsQtyProposedLMO(equipmentsQty){
        this._equipmentsListQtyLMO=equipmentsQty;
    }
    set instructionVenueProposed(venueRemarks){
        this._venueRemarks=venueRemarks;
    }

    set instructionEquipmentsProposed(equipmentsRemarks){
        this._equipmentsRemarks=equipmentsRemarks;
    }

    get dateStart(){
        return this._dateStart;
    }
    get dateEnd(){
        return this._dateEnd;
    }
    get timeStart(){
        return this._timeStart;
    }
    get timeEnd(){
        return this._timeEnd;
    }
    get venue(){
        return this._venue;
    }
    get venueRoom(){
        return this._venueRoom;
    }
    get equipmentsQtyAO(){
        return this._equipmentsListQtyAO;
    }
    get equipmentsNameAO(){
        return this._equipmentsListNameAO;
    }
    get equipmentsQtyLMO(){
        return this._equipmentsListQtyLMO;
    }
    get equipmentsNameLMO(){
        return this._equipmentsListNameLMO;
    }
    get remarksVenue(){
        return this._venueRemarks;
    }
    get remarksEquipment(){
        return this._equipmentsRemarks;
    }

     printAll(){
        console.log("Accepted Reservation Info");
        console.log("Date: "+this._dateStart+" - "+this._dateEnd);
        console.log("Time: "+this._timeStart+" - "+this._timeEnd);

        if(this._venue!=null){
        console.log("Proposed Venue: "+this._venue+"("+this._venueRoom+")");
        console.log("Remarks Venue:"+this._venueRemarks);
        }
        if(this._equipmentsListName!=null){
            this.printItems();
            console.log("Remarks Equipments:"+this._equipmentRemarks);
        }
    }

    printItems(){
        console.log("Proposed Items");
        for(var i=0; i<this._equipmentsListName.length; i++){
            console.log("   "+this._equipmentsListName[i]+" - x"+this._equipmentsListQty[this._equipmentsListName[i]]);
        }
    }

    concatItems(equipmentsListName,equipmentsListQty){
        if(equipmentsListName.length==0)
             return str="None";

        var str="";
        for(var i=0; i<equipmentsListName.length; i++){
            let acceptedItem=equipmentsListName[i].replace(/\s+/g,'-');
            //equipmentsListName[i].replace(/\s+/g,'-')
            str+=("   "+acceptedItem+" - Qty: "+equipmentsListQty[equipmentsListName[i]])+",\n";
        }
        return str;
    }

}

class signatureFormInfo{
    constructor(signature){
        this._signature=signature;
    }

    get(){
        return this._signature;
    }
}

//add info to class
function addInfoProfile(firstName,dateFilling,department,natureActivity,isOrg,reservationType){

    profileInfo=new FormProfileInfo(firstName,dateFilling
    ,department,natureActivity,isOrg,reservationType);
}

function turnIdInfo(){
    hashMapTypeInfo['departmentSelect']="Department";
    hashMapTypeInfo['natureActivity']="Nature of Activity";
    hashMapTypeInfo['orgName']="Name of the Organization";
    hashMapTypeInfo['presName']="Name of the President";
    hashMapTypeInfo['secName']="Name of the Secretary";
    hashMapTypeInfo['tresName']="Name of the Treasurer";
    hashMapTypeInfo['activityName']= "Name of the activity";
    hashMapTypeInfo['orgObjectives']="the objectives";
    hashMapTypeInfo['orgDetails']="the details";
    hashMapTypeInfo['dateStart']="Date Start";
    hashMapTypeInfo['dateEnd']="Date End";
    hashMapTypeInfo['Other']="Other";
    hashMapTypeInfo['LMO-Other']="Other";
}

//event listener
$(document).ready(function () {

    // for error message clear identification
    turnIdInfo();

    // next-btn tab
   $("#next-btn").on('click',function (e) { 
       e.preventDefault();
       goingBack=false;
       if(!validateForm(indexForm))
         return;

         //output
        monitorForm(indexForm+1);
        if(indexForm<6)
        $(document).scrollTop(0);
    });

    //prev-btn-tab
    $("#prev-btn").on('click',function(e){
        e.preventDefault();
        if(prevForm.length>0)
        prevForm.css("display","none");
        goingBack=true;
        indexForm=prevIndex.pop();
        monitorPrevForm(indexForm);

        //remove errors message
        var error=$("#errorsMsg");
        error.empty();
        $("#validationBox").css("display","none");
    })


    const loading=$("#loading");
    //submit button
    $("#submit-btn").on("click",function(e){
        
            if($("#signatureId").attr("src")==""){
                e.preventDefault();
                errorMsg['signature']="Please provide your signature";
                let idError=['signature'];
                generateErrorsMessage(idError);
            }
            else{
                loading.show();

                isSubmit=true;
                $("input[type='radio'][name='radio-review']").prop('disabled',false);
               const checkboxReserve= $("input[type='checkbox'][name='review-reserve-type']");
               checkboxReserve.each(function(index,value) {
                    value.prop("disabled",false);
               })
                
                if(checkboxReserve.length==2){
                    checkboxReserve.val("Both");
                }
            }
    });

    
    $(window).on("beforeunload",function(){
        if(!isSubmit)
             return confirm("Are you sure you want to refresh? Note: All inputs will be lost");
    })

    // validate signature
    $("#signatureId").on('load',function(e){
        removeErrorMessage('signature');
    })

    //vaidation on terms and condition
    $("#terms-condition").click(function () {
        if($(this).prop("checked")){
            $("#terms-conditionMsg").removeClass("d-block");
        }
      })
    

    // validation on profile & organization
    $("input[type='radio'][name='orgRadio']",).click(function () {
        if($(this).prop("checked")){
            orgRadio=$(this);
            $("#org-Msg").attr('style',"display:none;");
            var idRadio='orgRadio';
           removeErrorMessage(idRadio);
        }
      })
    
    $("input[type='checkbox'][name='reserve-type']").click(function(){
        reserveType=$("input[type='checkbox'][name='reserve-type']:checked");
        if($(this).prop("checked")){
            $("#reserve-Msg").attr('style',"display:none;");
            var idReserve='reserveType';
            if($(this).length>=1){
                removeErrorMessage(idReserve);
            }
        }
    });

    $("#presName,#secName,#tresName").on("keydown input",function(e){
           var key = e.keyCode;
           if(key==8||key==37||key==39)
            return true;
           if(key==8||(key>=60&&key<=90)||(key>=97&&key<=122)||key==32){
                return true;
           }
            else{
                return false;
            }

    });

    $("#presName,#secName,#tresName").on("keyup input",function(e){
            let msg=$("#"+this.id+"Msg");
            let valText=$(this).val();
            var valAlpha=String(modifyCharsAlpha(valText));
            var validVal=true;

            if($(this).val()==""){
                $(this).addClass("is-invalid");
                msg.text("Required to provide "+hashMapTypeInfo[this.id]);
                errorMsg[this.id]=msg.text();
                validVal=false;
            }
            else if(valAlpha.length<2){
                $(this).addClass("is-invalid");
                msg.text(hashMapTypeInfo[this.id]+" must be a Full Name");
                errorMsg[this.id]=msg.text();
                validVal=false;
            }
            else if($(this).hasClass("is-invalid")){
                $(this).removeClass("is-invalid");
                $(this).addClass("is-valid");
                errorMsg[this.id]=msg.text();
                validVal=false;
            }
            else{
                $(this).addClass("is-valid");
                removeErrorMessage(this.id);
            }

            hashMapInfo[this.id]=validVal;
    })

    $("#natureActivity,#orgName,#activityName,#orgObjectives,#orgDetails,#AO-Other,#LMO-Other").on("keyup input",function () {
        let msg=$("#"+this.id+"Msg");
        let valText=$(this).val();
        const valAlpha=String(modifyCharsAlpha(valText))+"";
        var validVal=true;

         if(valText==""){
             $(this).addClass("is-invalid");
             msg.text("Required to fill "+hashMapTypeInfo[this.id]);
            // errorMsg.push(msg.text());
             if((this.id=="AO-Other"||this.id=="LMO-Other")){
                 errorMsg['venuePick']=msg.text();
             }
             else
             errorMsg[this.id]=msg.text();
             validVal=false;
         }
         else if((this.id=="AO-Other"||this.id=="LMO-Other")&&valAlpha.length<5){
            $(this).addClass("is-invalid");
            msg.text("It must contain "+(5-valAlpha.length)+" more alphabet characters");
             errorMsg['venuePick']= hashMapTypeInfo[this.id]+": "+msg.text();
             validVal=false;
         }
         else if((this.id=="natureActivity"||this.id=="orgName"||this.id=="activityName")&&valAlpha.length<5){
             $(this).addClass("is-invalid");
             msg.text("It must contain "+(5-valAlpha.length)+" more alphabet characters");
             errorMsg[this.id]= hashMapTypeInfo[this.id]+": "+msg.text();
             validVal=false;
         }
         else if((this.id=="orgObjectives"||this.id=="orgDetails")&&valAlpha.length<20){
            $(this).addClass("is-invalid");
            msg.text("It must contain at "+(20-valAlpha.length)+" more alphabet characters");
            errorMsg[this.id]= hashMapTypeInfo[this.id]+": "+msg.text();
            validVal=false;
        }
        else{
            if((this.id=="AO-Other"||this.id=="LMO-Other"))
            removeErrorMessage('venuePick');
            else
            removeErrorMessage(this.id);
            $(this).removeClass("is-invalid");
            $(this).addClass("is-valid");
         }
        

         if((this.id=="AO-Other"||this.id=="LMO-Other")){
             hashMapInfo['venuePick']=validVal;
         }
         else{
             hashMapInfo[this.id]=validVal;
         }
      });
    
      $("#departmentSelect").on("change",function(){

          if($(this).val()=="Choose a Department"){
            $(this).addClass("is-invalid");
          }
          else{
             $(this).removeClass("is-invalid");
             $(this).addClass("is-valid");
             removeErrorMessage(this.id);
          }
      });

       //dynamic validation on date
      var dateUseStart=$("#dateStart");
      var dateEndStart=$("#dateEnd");
      initializeDate();
      $("#dateStart,#dateEnd").change(function(){
            var isAccepted=true;
            if(checkCurrentDate($(this))&&checkDate(dateUseStart.val(),dateEndStart.val(),this.id)){
                dateUseStart.removeClass("is-invalid");
                dateEndStart.removeClass("is-invalid");
                dateUseStart.addClass("is-valid");
                dateEndStart.addClass("is-valid");
                errorMsg['dateStart']='';
                errorMsg['dateEnd']='';
                hashMapInfo['dateStart']=true;
                hashMapInfo['dateEnd']=true;

                removeErrorMessage(dateUseStart.attr("id"));
                removeErrorMessage(dateEndStart.attr("id"))
            }
            else{
                isAccepted=false;
                $(this).addClass("is-invalid");
                $("#"+this.id+"Msg").text(errorMsg[this.id]);
            }
            hashMapInfo[this.id]=isAccepted;
      });

      //dynamic validation on time
      var timeStart=$("#startTime");
      var timeEnd=$("#endTime");
      $("#startTime,#endTime").change(function(){
          var isAccepted2=true;
          if(checkTime(timeStart,timeEnd,this.id)){
            $(timeStart).removeClass("is-invalid");
            $(timeEnd).removeClass("is-invalid");
            $(timeStart).addClass("is-valid");
            $(timeEnd).addClass("is-valid");
            hashMapInfo['startTime']=true;
            hashMapInfo['endTime']=true;
            errorMsg['startTime']='';
            errorMsg['endTime']='';

            //remove error
            removeErrorMessage(timeStart.attr("id"));
            removeErrorMessage(timeEnd.attr("id"))
          }
          else{
            isAccepted2=false;
            $(this).addClass("is-invalid");
            $("#"+this.id+"Msg").text(errorMsg[this.id]);
          }
          hashMapInfo[this.id]=isAccepted2;
      })


      //val of radio venues
      var selectedVenue=null;
      $("input[type='radio'][name='venue']").on("change",function (){
           hashMapInfo['venuePick']=true;
            if(selectedVenue!=null){
             $("#"+selectedVenue.val()+"ModalHelp").fadeOut();
            }
            
            var selectInput=$("#"+$(this).val());
            if(selectInput.length>0){
                if(selectInput.attr("type")!='text')
                   selectInput.addClass("is-valid");
            }
             selectedVenue=$(this);
             removeErrorMessage('venuePick');
      })

      //room validation
      $(".roomInput").on("keypress input",function(e){
        var keyCode=e.which;

        if(keyCode==8||keyCode==37||keyCode==39)
        return true;

        if($(this).val().length+1>4)
            return false;

        if(keyCode==69||keyCode==82||(keyCode>=48&&keyCode<=57)){
            return true;
        }
        return false;
      })

      $(".roomInput").on("keyup input", function(){
        var isAccepted=true;
        var selectVenue=selectedVenue;
        var modalHelp= $("#"+selectVenue.val()+"ModalHelp");
          if($(this).val().length<4){
              $(this).addClass("is-invalid");
             showAndHideFormat(modalHelp);
              isAccepted=false;
          }
          else if(!verifyRoomNumber($(this).val(),'venuePick')){
            $(this).addClass("is-invalid");
            showAndHideFormat(modalHelp);
            isAccepted=false;
          }
          else{
            modalHelp.fadeOut();
            removeErrorMessage('venuePick');
            $(this).removeClass("is-invalid");
            $(this).addClass("is-valid");
          }

        hashMapInfo['venuePick']=isAccepted;
      })

     //equipments validation
     $("input[name='equipments']").on("change",function (){
        removeErrorMessage('equipment');
     });

    //  //qty input
     
     $(".equipments").delegate(".qty-input","keyup input",function () {
        if($(this).val()==""||parseInt($(this).val())<1){
            $(this).addClass("is-invalid");
        }
        else{
            removeErrorMessage(this.id);
            $(this).removeClass("is-invalid");
            $(this).addClass("is-valid");
        }
    });

    //specify equipments
    $(".equipments").delegate(".Input-Equip","keyup input",function(){
       var modal= $("#"+this.id+"ModalHelp");
       if($(this).val()==""||modifyCharsAlpha($(this).val()).length<2){
           $(this).addClass("is-invalid")
          showAndHideFormat(modal);
       }
       else{
           removeErrorMessage('equipmentName');
           if(modal.is(":visible"))
             modal.css("display","none");
           $(this).removeClass("is-invalid");
           $(this).addClass("is-valid");
       }
    })


});


//set values to review
function setValuesReview(){

    //signature
    $("#signatureName").val(profileInfo.FullName);
    // name
    $("#review-name").val(profileInfo.FullName);

    //date fill
    $("#review-date-filling").val(profileInfo.DateFill);

    //department
    $("#review-department").val(profileInfo.Department);

    //nature activity
    $("#review-natureActivity").val(profileInfo.NatureActivity);

    //orgRadio
    $("input[type='radio'][name='radio-review'").filter("[value='"+orgRadio.val()+"']")
    .prop("checked", true);
    
    //reserve type
    var reservetypeReview=$("input[type='checkbox'][name='review-reserve-type']");

    reserveType.each(function(index, obj){
        reservetypeReview.filter("[value='"+obj.value+"']")
            .prop("checked",true);
    })

    //date of use
    $("#review-date-useStart").val(reserveInfo.dateStart);
    $("#review-date-useEnd").val(reserveInfo.dateEnd);

    //time of use
    $("#review-time-useStart").val(reserveInfo.timeStart);
    $("#review-time-useEnd").val(reserveInfo.timeEnd);

    //
    var equipmentsListReviewAO= $("#review-equipments-AO");
    var equipmentsListReviewLMO=$("#review-equipments-LMO");
    var FacilityReview=$("#review-facility");
    var RoomReview=$("#review-room");

   if(reserveType.length==2||reserveType.val()=="equipment"){
            // is equipments
            equipmentsListReviewAO.val(reserveInfo.concatItems(reserveInfo.equipmentsNameAO,reserveInfo.equipmentsQtyAO));
            equipmentsListReviewLMO.val(reserveInfo.concatItems(reserveInfo.equipmentsNameLMO,reserveInfo.equipmentsQtyLMO));
            if(reserveType.length==1){
                FacilityReview.val("none");
                RoomReview.val("none");
            }
            else{
                FacilityReview.val(reserveInfo.venue);
                if(reserveInfo.venueRoom==null){
                    RoomReview.val("none");
                }
                else{
                    RoomReview.val(reserveInfo.venueRoom);
                }
            }

    }
    else{
        equipmentsListReviewAO.val("None");
        equipmentsListReviewLMO.val("None");
        FacilityReview.val(reserveInfo.venue);
        if(reserveInfo.venueRoom==null){
            RoomReview.val("none");
        }
        else{
            RoomReview.val(reserveInfo.venueRoom);
        }
    }
    
    var reviewVenueRemarks=$("#review-venue-remarks");
    if(reserveInfo.remarksVenue==null||reserveInfo.remarksVenue==""){
                //remarks
        reviewVenueRemarks.val("None");
    }
    else{
        reviewVenueRemarks.val(reserveInfo.remarksVenue);
    }

    var reviewEquipmentRemarks=$("#review-equipments-remarks");
    if(reserveInfo.remarksEquipment==null||reserveInfo.remarksEquipment==""){
        reviewEquipmentRemarks.val("None");
    }
    else{
        reviewEquipmentRemarks.val(reserveInfo.remarksEquipment);
    }
}

function setValuesReviewOrganization(){
    if(orgRadio.val()=="Yes"){
        if(!$("#organization-review-form").is(":visible")){
            $("#organization-none").css("display", "none");
            $("#organization-review-form").css("display","block");
        }
        $("#review-orgFullName").val(orgInfo.orgName);
        $("#review-pres-name").val(orgInfo.orgPres);
        $("#review-sec-name").val(orgInfo.orgSec);
        $("#review-tres-name").val(orgInfo.orgTres);
        $("#review-actvity").val(orgInfo.activityName);
        $("#review-objective").val(orgInfo.objectives);
        $("#review-details").val(orgInfo.details)
    }
    else{
        $("#organization-none").css("display", "block");
        $("#organization-review-form").css("display","none");
    }
}

//validate forms
function validateForm(index){
    if(index==0){
        return agreeTermsValid();
    }
    else if(index==1){
      return  validateProfile($("#requesterFNameId"),$("#dateTodayFilling")
        ,$("#departmentSelect"),$("#natureActivity"),orgRadio,reserveType);
    }
    else if(index==2){
        return validateOrganization($("#orgName"),$("#presName"),$("#secName"),$("#tresName"),$("#activityName"),
         $("#orgObjectives"),$("#orgDetails"));
        }
    else if(index==3){
        return validateFacility($("input[type='radio'][name='venue']:checked"),$("#venueRemarks"),$("#dateStart"),$("#dateEnd"),$("#startTime"),$("#endTime"));
    }
    else if(index==4){
        return validateEquipments($("#dateStart"),$("#dateEnd"),$("#startTime"),$("#endTime"),$("input[type='checkbox'][name='equipments']:checked"),$("#equipmentRemarks"));
    }
    else return true;
}
//validations subfunction
function agreeTermsValid(){
    
    if(!$("#terms-condition").prop("checked")){
        $("#terms-conditionMsg").addClass("d-block");
    }
    return $("#terms-condition").prop("checked");
}


function validateProfile(name,dateFilling,department,natureActivity,isOrg,reservationType){
    let validatedSuccess=true
    //department
    if(department.val()=="Choose a Department"){
        department.addClass("is-invalid");
        errorMsg[department.attr("id")]="Please choose a Department";
        validatedSuccess=false;
    }

    //nature activity
    if(natureActivity.val()==""){
        natureActivity.addClass("is-invalid");
        $("#natureActivityMsg").text("Require to Fill "+hashMapTypeInfo[natureActivity.attr("id")]);
        errorMsg[natureActivity.attr("id")]=$("#natureActivityMsg").text();
        validatedSuccess=false;
    }
    else if(!hashMapInfo[natureActivity.attr('id')]){
        validatedSuccess=false;
    }
    else{
        errorMsg[natureActivity.attr('id')]="";
    }

    if(orgRadio==null||reserveType==null||orgRadio.length==0||reserveType.length==0){
        if(orgRadio==null||orgRadio.length==0){
            $("#org-Msg").css("display","block");
            errorMsg['orgRadio']="Please choose Yes/No if this is for Organization Purposes";
        }
        if(reserveType==null||reserveType.length==0){
           $("#reserve-Msg").css("display","block");
           errorMsg['reserveType']="Please choose if your are reserving on Facility/Equipment or Both";
        }
       validatedSuccess=false;
    }

    if(validatedSuccess){
        if($("#validationBox").attr("display")!="none")
        $("#validationBox").fadeOut();
        var reservationType;
        if(reserveType.length==2)
        reservationType="Both"
        else
        reservationType=reserveType.val();
        addInfoProfile(name.val(),dateFilling.val(),department.val(),natureActivity.val(),isOrg.val(),reservationType);
    }
    else{
        let idProfile=[department.attr("id"),natureActivity.attr("id"),"orgRadio","reserveType"];

        generateErrorsMessage(idProfile);
    }
    return validatedSuccess;
}

function validateOrganization(orgName,presName,secName,tresName,activityOrg,objective,details){
    var validatedSuccess=true;
    let valOrgs=[orgName,presName,secName,tresName,activityOrg,objective,details];
    let idOrganization= [orgName.attr("id"),presName.attr('id'),secName.attr('id'),tresName.attr('id')
            ,activityOrg.attr('id'),objective.attr('id'),details.attr('id')];

    for(var i=0; i<valOrgs.length; i++){
        if(valOrgs[i].val()==""){
            valOrgs[i].addClass("is-invalid");
            var errorCurrentMsg=$("#"+idOrganization[i]+"Msg");
            errorCurrentMsg.text("Require to provide "+hashMapTypeInfo[idOrganization[i]]);
            errorMsg[idOrganization[i]]=errorCurrentMsg.text();
            validatedSuccess=false;
        }
        else if(!hashMapInfo[idOrganization[i]]){
            validatedSuccess=false;
        }

    }
    if(validatedSuccess){
        orgInfo=new OrganizationFormInfo(orgName.val(),
             activityOrg.val(),objective.val(),details.val(),presName.val(),secName.val(),tresName.val());
    }
    else{
        generateErrorsMessage(idOrganization);
    }
    return validatedSuccess;
}

function validateFacility(venue,venueRemarks,dateStart,dateEnd,timeStart,timeEnd){
    var validateSuccess=true;

    if(!validateDateAndTime(dateStart,dateEnd,timeStart,timeEnd))
        validateSuccess=false;
  //venue
    if(venue.length==0){
        errorMsg['venuePick']="Please pick a facility!";
        validateSuccess=false;
        hashMapInfo['venuePick']=false;
    }
        
    if(venue.val()=="AO-Other"&&($("#AO-Other").val()==""||modifyCharsAlpha($("#AO-Other").val()).length<5)){
        $("#Other").addClass("is-invalid");
        errorMsg['venuePick']="Other is invalid!";
        validateSuccess=false;
        hashMapInfo['venuePick']=false;
    }
    
    if(venue.val()=="LMO-Other"&&($("#LMO-Other").val()==""||modifyCharsAlpha($("#LMO-Other").val()).length<5)){
        $("#LMO-Other").addClass("is-invalid");
        errorMsg['venuePick']="Other is invalid!";
        validateSuccess=false;
        hashMapInfo['venuePick']=false;
    }

    if(venue.length>0&&venue.val().indexOf("Lecture-Room")!=-1){
        var room=$(".roomInput");
        if(!verifyRoomNumber(room.val(),'venuePick')){
            validateSuccess=false;
            hashMapInfo['venuePick']=false;
        }
        else if(!checkRoom(room[0])){
            validateSuccess=false;
            hashMapInfo['venuePick']=false;
        }
        
    }
 
    if(!validateSuccess){
        let idDateTimeFacility=['venuePick',dateStart.attr("id"),dateEnd.attr("id"),timeStart.attr("id"),timeEnd.attr("id")];
        generateErrorsMessage(idDateTimeFacility);
    }
    else{
        reserveInfo= new ReservationFormInfo(dateStart.val(),dateEnd.val(),timeStart.val(),timeEnd.val());
        reserveInfo.venueProposed=venue.val();
        if($("#"+venue.val()).length>0){
            reserveInfo.venueRoomProposed=$("#"+venue.val()).val();
        }
        if(venueRemarks.val()!=""){
            reserveInfo.instructionVenueProposed=venueRemarks.val();
        }
    }
    return validateSuccess;
}

function validateEquipments(dateStart,dateEnd,timeStart,timeEnd,equipmentList,equipmentRemarks){
    var isValid=true;
    var isEquipmentOnly=false;
    let AO_QtyEquipments=[];
    let LMO_QtyEquipments=[];
    let nameOfEquipmentAO=[];
    let nameOfEquipmentLMO=[];
    let idInfo=[];
    if(reserveType.length==1){
        if(!validateDateAndTime(dateStart,dateEnd,timeStart,timeEnd)){
            isValid=false;
            idInfo.push(dateStart.attr('id'),dateEnd.attr('id')),timeStart.attr('id'),timeEnd.attr('id');
        }
        isEquipmentOnly=true;
    }
    

    if(equipmentList.length==0){
        errorMsg['equipment']="Please select an Equipment";
        idInfo.push('equipment');
        isValid=false;
    }
    else{
        for(var i=0;i<equipmentList.length; i++)
        {
            var idEquipment = equipmentList[i].value;
            var equipment="";
            equipment=idEquipment;
            let qty=$("#"+equipment+"-Qty")[0];
            let errorId="";

            if(idEquipment.indexOf("Other")!=-1){
                let id=idEquipment;
                let addId="";
                if(id.indexOf("AO-")!=-1){
                    addId="AO-";
                }
                else if(id.indexOf("LMO-")!=-1){
                    addId="LMO-"
                }

                idEquipment = addId+document.getElementById(idEquipment+'-Name').value;
                if(idEquipment==""||modifyCharsAlpha(idEquipment).length<2){
                   errorMsg['equipmentName']="Please provide a correctly formatted equipment name";
                   idInfo.push('equipmentName');
                    isValid=false;
                }
                qty=document.getElementById(id+'-Qty');
            }
           errorId=idEquipment;

            if(qty.value==""||parseInt(qty)<1){
                if(errorId==""){
                    errorId="Invalid";
                }
                errorMsg[qty.id]=errorId+": Quantity must be greater than 0";
                idInfo.push(qty.id);
                isValid=false;
            }
            else{
                if(idEquipment.indexOf("AO-")!=-1){
                    var equipmentNameId=idEquipment.substring(idEquipment.indexOf("AO-")+3);
                    nameOfEquipmentAO.push(equipmentNameId);
                    AO_QtyEquipments[equipmentNameId]=parseInt(qty.value);
                }
                else if(idEquipment.indexOf("LMO-")!=-1){
                    var equipmentNameId=idEquipment.substring(idEquipment.indexOf("LMO-")+4);
                    nameOfEquipmentLMO.push(equipmentNameId);
                    LMO_QtyEquipments[equipmentNameId]=parseInt(qty.value);
                }
            }
        }
       // test=AO_QtyEquipments;
    }

    if(!isValid){
        generateErrorsMessage(idInfo);
    }
    else{
        if(isEquipmentOnly){
            reserveInfo= new ReservationFormInfo(dateStart.val(),dateEnd.val(),timeStart.val(),timeEnd.val());
        }
        reserveInfo.equipmentsNameProposedAO=nameOfEquipmentAO;
        reserveInfo.equipmentsQtyProposedAO=AO_QtyEquipments;
        
        reserveInfo.equipmentsNameProposedLMO=nameOfEquipmentLMO;
        reserveInfo.equipmentsQtyProposedLMO=LMO_QtyEquipments;

        var equipmentRemarks=$("#equipmentRemarks").val();
        if(equipmentRemarks!=""){
            reserveInfo.instructionEquipmentsProposed=equipmentRemarks;
        }
    }

    return isValid;
}

function validateDateAndTime(dateStart,dateEnd,timeStart,timeEnd){
    var validateSuccess=true;
    if(!hashMapInfo[dateStart.attr('id')]){
        validateSuccess=false;
    }
    else if(!dateStart.hasClass("is-valid")){
        dateStart.addClass("is-valid");
    }

    if(!hashMapInfo[dateEnd.attr('id')]){
        validateSuccess=false;
    }
    else if(!dateEnd.hasClass("is-valid")){
        dateEnd.addClass("is-valid");
    }

    if(!hashMapInfo[timeStart.attr('id')]){
        validateSuccess=false;
    }
    else if(!timeStart.hasClass("is-valid")){
        timeStart.addClass("is-valid");
    }

    if(!hashMapInfo[timeEnd.attr('id')]){
        validateSuccess=false;
    }
    else if(!timeEnd.hasClass("is-valid")){
        timeEnd.addClass("is-valid");
    }


    return validateSuccess;
}


function checkDate(dateStart,dateEnd,idDate){
    dateStart = dateStart.split("-");
    dateEnd = dateEnd.split("-");
    
    //year
    var yearStart=parseInt(dateStart[0]);
    var yearEnd=parseInt(dateEnd[0]);

    //month
    var monthStart=parseInt(dateStart[1]);
    var monthEnd=parseInt(dateEnd[1]);

    //day
    var dayStart=parseInt(dateStart[2]);
    var dateEnd=parseInt(dateEnd[2]);


    if(yearStart>yearEnd){
        dateErrorMsg(idDate);
        return false;
    }
    if(yearStart==yearEnd&&monthStart>monthEnd){
        dateErrorMsg(idDate);
        return false;
    }
    if(yearStart==yearEnd&&monthStart==monthEnd&&dayStart>dateEnd){
        dateErrorMsg(idDate);
        return false;
    }
    return true;
}


function dateErrorMsg(idDate){
    if(idDate=="dateStart"){
        errorMsg[idDate]="Invalid: Should be a lower than End date";
    }
    else{
        errorMsg[idDate]="Invalid: Should be a higher date than Start date";
    }
}

function checkCurrentDate(dateObj){
    var dateTodayFilling=$("#dateTodayFilling").val().split("-");
    var date=dateObj.val().split("-");
    
    var yearStart=parseInt(date[0]);
    var monthStart = parseInt(date[1]);
    var dayStart = parseInt(date[2]);

    var yearCurrent=parseInt(dateTodayFilling[0]);
    var monthCurrent=parseInt(dateTodayFilling[1]);
    var dayCurrent=parseInt(dateTodayFilling[2]); 

    if(yearStart<yearCurrent){
        errorMsg[dateObj.attr("id")]="Invalid: Date is in past";
        return false;
    }
    else if(yearStart==yearCurrent&&monthStart<monthCurrent){
        errorMsg[dateObj.attr("id")]="Invalid: Date is in past";
        return false;
    }
    else if(yearStart==yearCurrent&&monthStart==monthCurrent&&dayStart<dayCurrent){
        errorMsg[dateObj.attr("id")]="Invalid: Date is in past";
        return false;
    }

    return true;
}   

function checkTime(timeStart,timeEnd,timeId){
    if($("#"+timeId).val()==""){
        errorMsg[timeId]="Invalid: Should enter a date"
        return false;
    }
    timeStart=timeStart.val().split(":");
    timeEnd=timeEnd.val().split(":");
    var hourStart=parseInt(timeStart[0]);
    var hourEnd=parseInt(timeEnd[0]);
    var minuteStart=parseInt(timeStart[1]);
    var minuteEnd=parseInt(timeEnd[1]);


    if(hourStart>hourEnd){
        if(timeId=="startTime")
          errorMsg[timeId]="Invalid: Should be lower than end Time";
        else{
            errorMsg[timeId]="Invalid: Should be higher than start Time";
        }
        return false;
    }
    else if(hourStart==hourEnd){
        if(minuteStart>minuteEnd){
            if(timeId=="startTime")
            errorMsg[timeId]="Invalid: Should be lower than end Time";
          else{
            errorMsg[timeId]="Invalid: Should be higher than start Time";
          }
            return false;
        }
        else if(minuteStart==minuteEnd){
            errorMsg[timeId]="Invalid: start and end time should be not equal";
            return false;
        }
    }

    return true;
}


function showAndHideFormat(modalHelp){
    if(!modalHelp.is(":visible")){
        modalHelp.fadeIn();
        setTimeout(function(){
            modalHelp.fadeOut();
        },4500);
    }
}


function generateErrorsMessage(id){
    if(id.length==0)
    location.reload(true);

    //start the template
    $("#validationBox").fadeIn();
    var error=$("#errorsMsg");
    error.empty();

    //generate errors
    for(var i=0; i<id.length; i++){
        if((hashMapInfo[id[i]]==undefined||!hashMapInfo[id[i]])&&errorMsg[id[i]]!=null&&errorMsg[id[i]]!="")
             error.append("<li id="+id[i]+"Error>"+errorMsg[id[i]]+"</li>");
    }
} 

function checkRoom(room){
    var options=$(".select-room");
    var isValid=true;
    for(var i=0; i<options.length; i++){
        var children=$(options[i]).children();
        for(var j=0; j<children.length; j++){
            if(children[j].value==room.value){
                errorMsg['venuePick']= room.value+" is a "+options[i].id+", not a lecture room.";
                return false;
            }
        }
    }
    return true;
}

//move forms
function monitorForm(index){
    if(index==0){
        goToConditions();
        return true;
    }
    else if(index==1){
            prevIndex.push(0);
            indexForm++;
            moveToProfile();
            return true;
    }
    else if(index==2){
       return orgTabHandler();
    }
    else if(index==3)
       return facilityTabHandler();
    else if(index==4)
        return equipmentTabHandler();
    else if(index==5){
        prevIndex.push(indexForm);
        indexForm++;
        moveToFinal();
        return true;
    }
    else if(index==6){
        prevIndex.push(indexForm);
        indexForm++;
        signatureOpen();
        return true;
    }
}

function monitorPrevForm(index){
    if(index==0)
        goToConditions();
    else if(index==1)
        moveToProfile();
    else if(index==2)
        moveToOrg();
    else if(index==3)
        moveToFacilities();
    else if(index==4)
        moveToEquipments();
    else if(index==5)
        moveToFinal();
}

function orgTabHandler(){
  

  //  addInfoProfile();
    prevIndex.push(indexForm);
    if(orgRadio.val()=='Yes')
    {
        indexForm++;
        moveToOrg();
    }
    else if(reserveType.val()=='facility'){
        indexForm+=2;
        moveToFacilities();
        //depends on facility/equipment
    }
    else if(reserveType.val()=='equipment'){
       indexForm+=3;
       moveToEquipments();
    }
    return true;
    
}


function facilityTabHandler(){

    prevIndex.push(indexForm);
    if(reserveType.length==2||reserveType.val()=='facility'){
        indexForm++
        moveToFacilities();
        return true;
    }
    else
    {
        indexForm+=2;
        moveToEquipments();
        return true;
    }
}

function equipmentTabHandler(){
    prevIndex.push(indexForm);
    if(reserveType.length==2){
        indexForm++;
        moveToEquipments();
    }
    else{
        indexForm+=2;
        moveToFinal();
    }
    return true;
}

//move to another form
function goToConditions() {
    $("#img-indicator").attr("src","Images/Indicator/P1.png");
    var current=arrayTabForm[indexForm];
    current.fadeIn();
    prevForm=arrayTabForm[indexForm];
    $("#prev-btn").css("display","none")

}

function moveToProfile() {
    $(".dateBox").css("display","none");
    $("#equipmentBoxRemarks").css("display","none");
    prevForm.css("display","none");
    var current=$(".profileBox");
    prevForm=current;
    $("#img-indicator").attr("src","Images/Indicator/P2.png");
    current.fadeIn();
    $("#prev-btn").css("display","block")
}

function moveToOrg(){
    $(".dateBox").css("display","none");
    prevForm.css("display","none");
    var current=arrayTabForm[indexForm];
    $("#img-indicator").attr("src","Images/Indicator/P2.png");
    current.fadeIn();
    prevForm=current;
}


function moveToFacilities() {
    prevForm.css("display","none");
    var current=arrayTabForm[indexForm];
    $("#img-indicator").attr("src","Images/Indicator/P3.png");
    $(".dateBox").fadeIn();
     current.fadeIn();
     prevForm=current;
  }

function moveToEquipments(){
    if(reserveType.length==2){
        $(".dateBox").css("display","none");
    }
    else
    {
        $(".dateBox").fadeIn();
    }
    prevForm.css("display","none");
    var current=arrayTabForm[indexForm];
    $("#img-indicator").attr("src","Images/Indicator/P4.png");
    current.fadeIn();
    $("#equipmentBoxRemarks").fadeIn();
    prevForm=current;
}

function moveToFinal() {
      //set values for reviews
        setValuesReview();
        setValuesReviewOrganization();

        // next-btn
        var nextBtn=$("#next-btn");
        if(!nextBtn.is(":visible")){
            nextBtn.css("display","block");
            $("#submit-btn").css("display","none");
        }

    $("#progressStatus").text("Your Progress");
    $(".dateBox").css("display","none");
    prevForm.css("display","none");
    var current=arrayTabForm[indexForm];
    $("#img-indicator").attr("src","Images/Indicator/P5.png");
    current.fadeIn();
    prevForm=current;
}

function signatureOpen(){
    $("#progressStatus").text("Almost Complete");
    $("#next-btn").css("display","none");
    $("#submit-btn").css("display","block");
    // prevForm.css("display","none");
    var current = $("#finalSignatureBox");
    $("#img-indicator").attr("src","Images/Indicator/P6.png");
    current.fadeIn();
    prevForm=current;
}
function removeErrorMessage(idElement){

    if($("#"+idElement+"Error").length>=1){
        $("#"+idElement+"Error").remove();
    }
    else{
        return;
    }
    errorMsg[idElement]="";
    if($("#errorsMsg").children().length==0){
            $("#validationBox").fadeOut();
    }
}

function verifyRoomNumber(room,roomId){
    if(room==""){
        errorMsg[roomId]="Please provide a room number";
      return false;
    }
  else if(room.length<4){
      errorMsg[roomId]="Incorrect Format: (Letter-Building)+(Room No) ex: R400,E200";
      return false;
  }
  else if(room.substring(1).replace(/[0-9]/gm,'')!=''){
    errorMsg[roomId]="Incorrect Format: it must be a 3 digit for a room number";
    return false;
  }

  return true;
}

function initializeDate(){
      hashMapInfo["dateStart"]= true;
      hashMapInfo["dateEnd"]= true;
      hashMapInfo["startTime"]=true;
      hashMapInfo["endTime"]=true;
      errorMsg['dateStart']="";
      errorMsg['dateEnd']="";
      errorMsg['startTime']="";
      errorMsg['endTime']="";
}
function modifyCharsAlphaNum(text) {
    return text.replace("/[^a-zA-Z1-9]/gm","");
 }

function modifyCharsAlpha(text){
   return text.replace(/([^a-z])/gi,'');
}
