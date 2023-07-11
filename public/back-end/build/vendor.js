var fullUrl = window.location.origin;
var FULLURL="vendors";

$('form').submit(function(){
    $(this).children('button[type=submit]').prop('disabled', true);
});

$(document).on('change','.provinces',function(){
    const val= $(this).val();
    const id = $(this).data("id");
    $.get(FULLURL+'/getdistrict',{id:val})
    .done(function(data){
        $('#district'+id).html('<option value="" hidden>กรุณาเลือกอำเภอ</option>')  
        $('#district'+id).append(data)
    })
})
$(document).on('change','.district',function(){
    const val= $(this).val();
    const id = $(this).data("id");

    $.get(FULLURL+'/getsubdistrict',{id:val})
    .done(function(data){
        $('#subdistrict'+id).html('<option value="" hidden>กรุณาเลือกตำบล</option>')
        $('#subdistrict'+id).append(data)
    })
})
$(document).on('change','.subdistrict',function(){
    const id = $(this).data("id");
    const zipcode= $('option:selected',this).data("postcode");
    $('#postcode'+id).html('')
    $('#postcode'+id).val(zipcode)
})
$('#sort').on('click',function(){
    const $this = $(this), text = $this.html(); 
    if(text=='Sort'){ $this.html('Cancel'); }else{ $this.html($this.data('text')) }
    $('.handle').toggle(); $('.no').toggle();
})
if($('#sorted_table').length>0)
{
    var el = document.getElementById('sorted_table');
    var dragger = tableDragger(el, { mode:'row', dragHandler:'.handle', onlyBody: true, animation: 300, });
    dragger.on('drop',function(from,to){
        const id = $('tr[data-row="'+from+'"]').data('id');
        dragsort(id,from,to);
    });
}
function dragsort(id,from,to){
    $.ajax({
        url:fullUrl+'/vendors/dragsort', type:'post', data:{id:id, from:from, to:to, _token:$('input[name="_token"]').val()}, dataType:'json',
        success:function(data){ if(data==true){ if(confirm('Refresh to change the display effect.')==true){ location.reload();} } }
    })
}
$('.status').on('click',function(){
    const $this = $(this), id = $(this).data('id');
    $.ajax({type:'get',url:fullUrl+'/vendors/status/'+id,success:function(res){if(res==false){$(this).prop('checked',false)}}});
})
$('#selectAll').on('click',function(){
    if($(this).is(':checked')){ $('#delSelect').prop('disabled',false);$('.ChkBox').prop('checked',true) }else{ $('#delSelect').prop('disabled',true); $('.ChkBox').prop('checked',false) }
})
$('.ChkBox').click(function(){
    const checked = []; const $this = $(this).prop("checked");
    $('.ChkBox').each(function(){ if($(this).is(':checked')){ checked.push($this) } })
    if(checked.length>0){ $('#delSelect').prop('disabled',false); }else{ $('#delSelect').prop('disabled',true); }
})
$('.deleteItem').on('click',function(){
    const id =[$(this).data('id')];
    if(id.length>0){ destroy(id) }
})
$('#delSelect').on('click',function(){
    const id = $('.ChkBox:checked').map(function(){ return $(this).val() }).get();
    console.log(id);
    if(id.length>0){ destroy(id) }
});
function destroy(id)
{
    Swal.fire({
        title:"ลบข้อมูล",text:"คุณต้องการลบข้อมูลใช่หรือไม่?",icon:"warning",showCancelButton:true,confirmButtonColor:"#DD6B55",showLoaderOnConfirm: true,
        preConfirm: () => {
            return fetch(fullUrl+'/vendors/destroy?id='+id)
            .then(response => response.json())
            .then(data => location.reload())
            .catch(error => { Swal.showValidationMessage(`Request failed: ${error}`)})
        }
    });
}

$('#type').change(function(){
    var val = $(this).val();
    if(val == 'person' || val == 'company')
    {
        $(this).parent().parent().find('div:nth-child(3) input').prop('required',true);
    }else{
        $(this).parent().parent().find('div:nth-child(3) input').removeAttr('required');
    }
})

// CreateForm
if($('#createForm').length>0){

    $('#createForm').validate({
        ignore:[],
        rules:{
            name:'required',
            phone:'required',
            id_card:{ minlength:13 },
            // email:{ required:true, email: true},
            type:'required',
        },
        // errorPlacement : function(error,element){
        //     if(element.parent().hasClass('custom-file'))
        //     { 
        //         error.insertAfter(element.parent()) 
        //     }else{ 
        //         error.insertAfter(element) 
        //     }
        // },
        messages:{
            name:{required:'Please enter name'},
            // id_card:{required:'Please enter ID number for individual'},
            phone:{required:'Please enter phone number'},
            // email:{required:'Please enter email'},
            type:{required:'Please select type'},
        }
    });
}

// EditForm
if($('#editForm').length>0){
    $('#editForm').validate({
        ignore:[],
        rules:{
            name:'required',
            phone:'required',
            id_card:{ minlength:13 },
            // email:{ required:true, email: true},
            type:'required',
        },
        // errorPlacement : function(error,element){
        //     if(element.parent().hasClass('custom-file'))
        //     { 
        //         error.insertAfter(element.parent()) 
        //     }else{ 
        //         error.insertAfter(element) 
        //     }
        // },
        messages:{
            name:{required:'Please enter name'},
            // id_card:{required:'Please enter ID number for individual'},
            phone:{required:'Please enter phone number'},
            // email:{required:'Please enter email'},
            type:{required:'Please select type'},
        }
    });
}