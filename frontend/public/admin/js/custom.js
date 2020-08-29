$(document).ready(function(){
    setTimeout(function(){
        $('#success').fadeOut('slow');
    },6000);

    setTimeout(function(){
        $('#update').fadeOut('slow');
    },6000);

    setTimeout(function(){
        $('#delete').fadeOut('slow');
    },6000);

    $('#dataTables').DataTable({
        'paging'      : true,
        'lengthChange': true,
        'searching'   : true,
        'ordering'    : true,
        'info'        : true,
        'autoWidth'   : true
    });
    
    $('#purchase_date').datepicker({
        autoclose: true
    });
    $('#tran_date').datepicker({
        autoclose: true
    });
})