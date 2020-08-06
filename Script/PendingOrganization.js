$(document).ready(function () {
    const searchInput = $('#ref_no-search');
    const box= $("#org-box");
    const loading= $("#loading-box");

    $("#btn-search").on("click", function(){
       let val=searchInput.val();
       if(val[0]!='#')
            val='#'+val;
        
        loading.show();
        box.empty();

        box.load("Includes/SearchOrg.php",{
            refNo:val
        },function () {
            loading.fadeOut();
          });
    });

    box.delegate("#cancel-search","click",function(){
        loading.show();
        box.empty()
        box.load("Includes/SearchOrg.php",{
            reset:true
        },function () {
            loading.fadeOut();
          });
    });

    const msg=$(".overlay");
    box.delegate(".btn-view","click",function(){
        msg.fadeIn();
    })

    $("#btn-close").on("click",function(){
        msg.fadeOut();
    })



});