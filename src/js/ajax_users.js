$(document).ready(function ()
{
    $('#menu_btn').on('click', function () {
        $('#sidebar').toggleClass('active');
    });

    $('#loc_href').on('click',function(){
        $('#search_mat_form').css("display","block");
        $('#search_mat_result').css("display","block");
        $('#search_pro_form').css("display","none");
        $('#search_pro_result').css("display","none");
        $('#div_info_perso').css("display","none");
    });

    $('#rdv_href').on('click',function(){
        $('#search_pro_form').css("display","block");
        $('#search_pro_result').css("display","block");
        $('#search_mat_form').css("display","none");
        $('#search_mat_result').css("display","none");
        $('#div_info_perso').css("display","none");
    }); 

    $('#info_href').on('click',function(){
        $('#div_info_perso').css("display","block");
        $('#search_mat_form').css("display","none");
        $('#search_mat_result').css("display","none");
        $('#search_pro_form').css("display","none");
        $('#search_pro_result').css("display","none");
    });

    $("input[type='image']").click(function() {
        if($("#upload_fields").css("display") == 'none'){
            $("#upload_fields").css("display","block");
        }
        else{
            $("#upload_fields").css("display","none");
        }
    });
  
  	$("#search_mat").autocomplete({
        source: "../services/load_data.php"
    });

    $("#search_mat_form").submit(function(){
        $.ajax({
            type: "get",
            url: "../services/load_data.php",
            data :{
                'search_mat':$('#search_mat').val(),
            },
            datatype : 'html',
            success: function(result){
                if(result!="NOT_FOUND"){
                    $('#search_mat_result').html(result);
                    $('#mat_not_found').html('');
                }
                else{
                    $('#mat_not_found').html("Aucun résultat trouvé!");
                    $('#search_mat_result').html('');
                }                      
            }
        });        
    });

    $("#job_list").click(function(){
        $.ajax({
            type: "post",
            url: "../services/load_data.php",
            data :{
                'search_pro':$('#job_list').val(),
            },
            datatype : 'html',
            success: function(result){
                $('#search_pro_result').html(result);                      
            }
        });        
    });

});