$(document).ready(function () {
    var onDropdown=false;

    $("#dropdown-img").click(function(){
        if(!onDropdown){
           $(this).attr("src","Images/icon(footer)/triangleClick.png");
           $(".dropdown-content").slideDown();
           onDropdown=true;
        }
        else{
            $(this).attr("src","Images/icon(footer)/triangle.png");
            $(".dropdown-content").slideUp();
            onDropdown=false
        }
    });

    //buttons form-content
    $("#form-content").delegate("#equipment-slider,#remarks-slider,#facility-slider,#equipmentR-slider,#obj-slider,#details-slider","click", function(){
        var box=$("#"+this.id+"-box");
        
        if(box.css("display")=="none"){
            $(this).text("-");
            box.slideDown();
        }
        else{
           $(this).text("+");
            box.slideUp();
        }
    });

    //loading
    const loading=$("#loading");

   //show and hide details
    var modal=$("#modal-content");
    const overlayDetails=$("#details-overlay");
    const content= $("#form-content");
    $("#content-box").delegate(".button-view","click",function(){
        loading.show();

        const ref=$(this).val();
        content.load("Includes/Admin-Control/View-data.php",{
            ref_no:ref
        },function(){

            $("#admin-content").load("Includes/Admin-Control/View-data2.php",{ 
                ref_no: ref
            },
            function(){
                $("#approve-form-sign").val(ref);
                $("#button-reject-yes").val(ref);
                //animations
                loading.fadeOut();
                overlayDetails.fadeIn();
                modal.fadeIn();
            });
        });
    });



     //button approve
  


    //button signature
    const rejectConfirm=$("#reject-confirm");
    const rejectError=$("#reject-error");

    // error approve
    const rejectMsg=$("#reject-msg");
    const rejectNote=$("#reject-note");

    $("#admin-content").delegate("#button-reject","click",function(){
        if($("#comment").val()==""){
            rejectMsg.text("Please provide a reason for rejection in the comment section");
            rejectNote.text("Note: Comments are required to reject a form as a justification");
            rejectError.fadeIn();
        }
        else{
          rejectConfirm.fadeIn();
        }
    });

    const memberView=$("#member-sign-view");
    //view signature
    $("#admin-content").delegate(".view-list","click",function(){
        var viewBtn = $(this);
        $("#show-member-sign").load("Includes/Admin-Control/Show-Signature.php",{
            ref_no: viewBtn.val(),
            member: viewBtn.attr("name")
        },function(){
            memberView.fadeIn();
        });

    });

    //close view member sign
    $("#show-member-sign").delegate("#member-close","click",function(){
        memberView.fadeOut();
    });
    //signature
    const overlay2=$(".overlay-2");
    const signature=$("#finalSignatureBox");
    const warningSignature=$("#signature-warning");

    //signature Src
    const signatureId=$("#signatureId");


    //button approve
    $("#admin-content").delegate("#button-approve","click",function(){
        let items=$("input[type=checkbox][name*='equip']");
        let faci=$("#faci-checker");
        

        if(signatureId.attr("src")==""){
            rejectMsg.text("Please provide a signature to approve a form.");
            rejectNote.text("Note: You can add a signature in the Status Information > Your Signature.");
            rejectError.fadeIn();
        }
        else if(items.length||faci.length){
            let itemsChecked=$("input[type=checkbox][name*='equip']:checked");
           let faciChecked=$("#faci-checker:checked");
             if(!itemsChecked.length&&!faciChecked.length){
                rejectMsg.text("Please provide a to check at least one reservation to approve.");
                rejectNote.text("Note: You can check boxes in the Status Information");
                rejectError.fadeIn();
             }
             else{
                $("#approved-sign-container").show();
                $("#modify-signature").hide();
                overlay2.fadeIn();
             }
        }
        else{
            $("#approved-sign-container").show();
            $("#modify-signature").hide();
            overlay2.fadeIn();
        }
    });

    //comment
    let comment;

    $("#admin-content").delegate("#comment","keyup",function (){
        comment =$(this).val();
    });

    let equipments="";


    //button - last approve
    $("#approve-form-sign").on("click",function(){
        loading.show();

        $("input[type=checkbox][name*='equip']:checked").each(function(){
            equipments+=$(this).val()+",";
        });

        let isApprovedFacility=false;
        if($("#faci-checker:checked").length==1){
            isApprovedFacility=true;
        }
        else if(!$("#faci-checker:checked").length){
            isApprovedFacility=null;
        }


        $(this).load("Includes/Admin-Control/Approve-Form.php",{
            ref_no:$(this).val(),
            img_src: signatureId.attr("src"),
            facility: isApprovedFacility,
            equipment: equipments,
            admin_comment: comment
        },function(responseText, textStatus, xhr){
            if(textStatus=="error"){
                alert("Error Found! Please try again.");
                $(document).html("Error Found:"+xhr.status+" "+xhr.statusText);
            }   
            else{
                location.reload();
            }
        });
        equipments="";
     
     });

     //close view button
     
    $("#btn-close").on("click",function(){
        comment="";
        modal.fadeOut();
        overlayDetails.fadeOut();
     });

    $("#close-signature").on("click",function(){
        overlay2.fadeOut();
    });
    
    
    //reject button
    $("#button-okay").on("click",function(){
        rejectError.fadeOut();
    })

    $("#button-reject-no").on("click",function(){
         rejectConfirm.fadeOut();
    });

    $("#button-reject-yes").on("click",function(){
        loading.show();

        let items=$("input[type=checkbox][name*='equip']");
        let faci=$("#faci-checker");

        let hasItems=true;
        if(!items.length)
            hasItems=false;
        
        let hasFaci=true;
        if(!faci.length)
            hasFaci=false;

        $(this).load("Includes/Admin-Control/Reject-Form.php",{
            ref_no:$(this).val(),
            admin_comment: comment,
            items:hasItems,
            facility:hasFaci
        },function(responseText, textStatus, xhr){
            if(textStatus=="error"){
                alert("Error Found! Please try again.");
                $(document).html("Error Found:"+xhr.status+" "+xhr.statusText);
            }   
            else
             location.reload();
        });
    });

    //trash button
    const trash=$("#trash-confirm");
    const trashMsgFormNum=$("#trash-formNo");
    const trashBtnYes=$("#button-trash-yes");
    $("#form-nav").delegate(".trash","click",function () {
        var refNoTrash=$(this).val();
        trashMsgFormNum.text(refNoTrash);
        trashBtnYes.val(refNoTrash);
        trash.fadeIn();
    });

    $("#button-trash-no").on("click",function(){
        trash.fadeOut();
    })

    $("#button-trash-yes").on("click",function(){
        loading.show();
        $(this).load("Includes/Admin-Control/Trash-Form.php",{
            ref_no: $(this).val()
        },function(){

            location.reload();
        }
        );
    })

    //button draw signature
    const signatureCanvaBox=$("#signature-canvas-box");
    $("#button-canva-close").on("click",function(){
        signatureCanvaBox.fadeOut();
    });

 

    //draw signature
    const canvas=$("#canvas")[0];
    var isDrawing=false;
    const contextDraw= canvas.getContext("2d");
    const modalBox=$("#sign-canvas");
    const signatureDestBox=$(".signatureBox");
    const signatureImg = $("#signatureId")[0];
    const noticeCanvas=$(".noticeCanvas");

    window.addEventListener("resize",resize);
    canvas.addEventListener("mousedown",startDrawing);
    canvas.addEventListener("mouseup",endDrawing);
    canvas.addEventListener("mouseleave",endDrawing);
    canvas.addEventListener("mousemove",draw); 
   
  
       //open draw sign
    $("#btn-draw-sign").on("click",function(){
        noticeCanvas.attr("style","display:block;");
       
        signatureCanvaBox.fadeIn();
        resize();
        // drawText();
    });
    function resize(){
        canvas.height=modalBox.height();
        canvas.width=modalBox.width();
        signatureImg.height=signatureDestBox.height();
        signatureImg.width = signatureDestBox.width();
    }
    function startDrawing(e){
        isDrawing=true;
        noticeCanvas.attr("style","display:none;");
        contextDraw.beginPath();
    }
    function endDrawing(){
         isDrawing=false;  
    }


    var drawing = false;            
     function draw(e){
        if(!isDrawing)
            return;
        
        drawing=true;
         //style
         contextDraw.lineWidth=5;
         contextDraw.lineCap="round";
        
        //position
         contextDraw.lineTo(e.offsetX,e.offsetY);
         contextDraw.stroke();
    }


    $("#btn-clear").on('click',function (e) {
       contextDraw.clearRect(0,0,canvas.width,canvas.height);
       noticeCanvas.attr("style","display:block;");
   })

   $("#btn-sign-save").on('click',function(e){
       if(!drawing){
            noticeCanvas.attr("style","display:block;");
       }
       else{
             drawing=false;
            const dataUrl=canvas.toDataURL();
            signatureImg.src=dataUrl;
            $(this).load("Includes/Admin-Control/Save-Signature.php",{
                admin_sign: signatureImg.src

            },
            
            function(){
                signatureImg.height=signatureDestBox.height();
                signatureImg.width = signatureDestBox.width();
                $("#signatureImg").val(signatureImg.src);
                $("#uploadSignature").val('');
                signatureCanvaBox.fadeOut();
            });
        }

    });


    $("#uploadSignature").change(function (e) {
        $(this).load("Includes/Admin-Control/Save-Signature.php",{
            admin_sign: signatureImg.src

        }, function(){
            if(this.files&&this.files[0]){
                var reader=new FileReader();
                reader.onload = function(){
                    signatureImg.src=reader.result;
                    signatureImg.height=signatureDestBox.height();
                    signatureImg.width = signatureDestBox.width();
                }
                reader.readAsDataURL(this.files[0]);
            }
         });
    });

  


    // $("#signatureId").on("load",function(){
    //        resize();
    // });

    $("#admin-content").delegate("#signature-btn","click",function(){
        $("#approved-sign-container").hide();
        $("#modify-signature").show();
        overlay2.fadeIn();
    });


    const loadingBox=$("#loading-box");
    //menu nav
    const navText=  $("#nav-text");
    $(".btn-menu").on("click",function(){
        const valIndicator = $(this).val();

        if(valIndicator==navText.val()){
            return;
        }
        loadingBox.show();
        $("#content-box").load("Includes/Admin-Control/Admin-Menu.php",{
            menu: valIndicator
        },function(){
            loadingBox.fadeOut();
            navText.text(valIndicator[0].toUpperCase() + valIndicator.substring(1).toLowerCase());
            
        });
    });
    

    //sorter by date
    $("#sort-forms-date").on("change",function(){
        loadingBox.show();
        $("#content-box").load("Includes/Admin-Control/Sort-date.php",{
           sortOrder: $(this).val()
        },function(){
            loadingBox.fadeOut();
        });
        
    });

});