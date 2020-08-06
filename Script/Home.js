

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

    // going up button
    $("#up-button").click(function()  { 
        $("html,body").animate({
            scrollTop:0
        },1000);
    });


    //sub menu method
    $("#menu").click(function(){
        if(!isDown){
            isDown=true;
           $(".sub-menu").slideDown();
        }
        else{
            isDown=false;
            $(".sub-menu").slideUp();
        }

    })



    //for cards More info method
    var cards=$(".card").slice(1);
    var buttonMore=$(".more-info");
    var isMoreInfo=false;


    function updateCards(){
        if($(window).width()<=1070){
            if(!isWidthResize){
               removedCards();
               isWidthResize=true;
            }
        } 
        else{
           addCards();  
           isWidthResize=false;
        }
    }

    function removedCards(){
        cards.css("display","none");
        buttonMore.css("display","block");
    }

    function addCards(){
       cards.css("display","block");
       buttonMore.css("display","none");
    }


    updateCards();

    $(window).resize(function () { 
        updateCards();  
        if($(this).width()>1070){
            $(".sub-menu").css("display","none");
            isDown=false;
        }
    });

    buttonMore.click(function(){
        if(!isMoreInfo){
            cards.slideDown();
            buttonMore.text("Less Info");
            isMoreInfo=true;
        }
        else{
            cards.slideUp();
            buttonMore.text("More Info");
            isMoreInfo=false;
        }
    })


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
    })
});

