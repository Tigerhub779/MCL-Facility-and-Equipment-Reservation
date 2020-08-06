$(document).ready(function () {  
    var scrollBottom =0;
       var isDown=false;
       var isWidthResize=false;
       var onDropdown=false;
   
       //scroll function with header
       var header=$(".main-header header");
       $(window).scroll(function () { 
           var current=$(this).scrollTop();
           
               if(current<300){
                   $("#up-button").fadeOut();
               }
               
               if(current>scrollBottom&&current>300){
                   if(!isDown&&!onDropdown)
                   header.slideUp();
                   $("#up-button").fadeIn();
               }
               else{
                   header.slideDown();
               }
           scrollBottom=current;
       });

       const modal=$("#modal-content");
       const overlay=$("#details-overlay");
       const loading=$("#loading");
       $("#view-status-box").delegate(".btn-view","click",function(){
           loading.show();
            let valueRef=$(this).val();

           modal.load("Includes/ViewStatusData.php",{
               refNo: valueRef
           },function(){
            loading.fadeOut();  
            overlay.fadeIn();
           });
       });

       modal.delegate("#search-close","click",function(){
            overlay.fadeOut();
       });

       const loadingBox=$("#loading-box");
       const box=$("#view-status-box");
       const searchInput = $('#ref_no-search');
    $("#btn-search").on("click", function(){
        let val=searchInput.val();
        if(val[0]!='#')
             val='#'+val;
         
         loadingBox.show();
         box.empty();
 
         box.load("Includes/SearchForm.php",{
             reset:false, 
            refNo:val
         },function () {
             loadingBox.fadeOut();
           });
     });
 
     box.delegate("#cancel-search","click",function(){
         loadingBox.show();
         box.empty()
         box.load("Includes/CancelSearchForm.php",{
             reset:true
         },function () {
             loadingBox.fadeOut();
           });
     });
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

    const commentBox=$("#details-comment");
    const commentText=$("#comment-text");
    $("#close-comment").on("click",function(){
    
        commentBox.fadeOut();
    })

    $("#modal-content").delegate(".btn-comment", "click",function(){
        commentBox.fadeIn();
        commentText.text($(this).val());
    })

   })