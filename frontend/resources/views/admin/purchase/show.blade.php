@extends('admin.layouts.template')

@section('style')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
@endsection

@section('template')
<style type="text/css">
    th{
        text-align: center;
    }
</style>
<section class="content-header">
    <h1>
        DETAILS
        
        </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Purchase</li>
        <li class="active"> Details</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<section class="content">
    <div class="">
        <div class="">
            <div class="row">
                <div class="col-md-12">
                    <div class="">
                        <div class="">
                            <div class="panel panel-primary">
                              <div class="panel-heading">Basic Info <a href="" data-toggle="modal" data-target="#myModal" class="btn btn-default btn-sm"><i class="fa fa-edit" ></i> Edit</a></div>
                              <div class="panel-body">
                                  <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for=""> Date: <strong>{{ $purchase_master->purchase_date }}</strong></label>
                                            
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="">Bill No: <strong>{{ $purchase_master->bill_no }}</strong></label>
                                           
                                        </div>
                                    </div>
                                    
                                   <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Supplier: <strong>{{ $purchase_master->sup_name }}</strong></label>
                                        </div>
                                    </div> 
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">User: <strong>{{ $purchase_master->user_name }}</strong></label>
                                          
                                        </div>
                                    </div>
                                    
                                </div>
                              </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <div class="panel panel-primary">
                                          <div class="panel-heading">
                                            Item Details <a href="" data-toggle="modal" data-target="#myModal_2" class="btn btn-default btn-sm"><i class="fa fa-plus-circle" ></i> Add new item</a>
                                           </div>
                                          <div class="panel-body">
                                            <input class="form-control input-sm" id="myInput" type="text" placeholder="Search..">
                                            <br>
                                            <table class="table-bordered" id="" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th height="25">Sl</th>
                                                        <th>Item</th>
                                                        <th>Batch Code</th>
                                                        <th>Particulars</th>
                                                        <th>Quantity</th>                                                   
                                                        <th>Purchase Rate</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>

                                                <tbody id="myTable">
                                                    @php $qty = 0; $total_purchase_rate = 0;$total_sales_rate = 0; @endphp

                                                    @foreach($stock as $key => $data)
                                                    <tr>
                                                        <td height="25">{{ ++$key }}</td>
                                                        <td style="text-align: left;">{{ $data->item_name }} </td>
                                                        <td style="text-align: center;">{{ $data->code }} </td>
                                                        <td style="text-align: center;">{{ $data->particulars }}</td>
                                                        <td style="text-align: center;">{{ $data->stock_in }}</td>
                                                        <td style="text-align: center;">{{ number_format($data->purchase_rate,2) }}</td>
                                                        <td style="text-align: center;">
                                                        @if( Auth::user()->role == 'admin')
                                                                <a title="Edit" href="{{ url('admin/purchase/stock/'.$data->stock_id.'/edit') }}"  class="btn btn-info btn-xs"><i class="fa fa-edit" aria-hidden="true"></i> Edit</a>

                                                                <form action="{{ url('admin/purchase/stock/single/'.$data->stock_id.'/delete') }}" method="post" style="display:inline-block">
                                                                    {{ method_field('DELETE') }} {{ csrf_field() }}
                                                                    <button title="Delete" class="delete btn btn-danger btn-xs"  onclick="return confirm('Are you sure you want to delete this item?');"  >
                                                                        <i class="fa fa-trash-o" aria-hidden="true"></i> Delete
                                                                    </button>
                                                                </form>
                                                        @endif

                                                        </td>
                                                    </tr>
                                                    @php $qty += $data->stock_in; $total_purchase_rate += ($data->purchase_rate * $data->stock_in);
                                               
                                                    @endphp
                                                    @endforeach
                                                    <tr>
                                                        <th colspan="3" style="text-align: center;">TOTAL</th>
                                                        <th></th>
                                                        <th>{{$qty}}</th>
                                                        <th>{{number_format($total_purchase_rate,2)}}</th>
                                                        
                                                    </tr>
                                                </tbody>
                                            </table>
                                          </div>
                                        </div>
                                    </div>
                                </div>    
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Main Content -->

<div class="modal fade in" id="myModal" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">×</button>
          <h4 class="modal-title">Edit Info</h4>
        </div>
        <div class="modal-body">
            <form action="{{url('admin/purchase/info/update')}}" method="post">
                {{ method_field('PUT') }}
                {{ csrf_field() }}
              <input autocomplete="OFF" type="hidden" name="purchase_master_id" value="{{$purchase_master->purchase_master_id}}">
              <div class="form-group">
                <label for="">Date</label>
                <div class="form-group">
                <input autocomplete="OFF" type="text" id="datepicker" value="{{$purchase_master->purchase_date}}" name="purchase_date" class=" form-control input-sm" required="">
                </div>
               </div>
               <div class="form-group">
                    Bill no: 
                    <div class="form-group">
                        <input autocomplete="OFF" type="text" name="bill_no" value="{{ $purchase_master->bill_no }}" class=" form-control input-sm">
                    </div>
                </div>
                <div class="form-group">
                    Supplier: 
                    <div class="form-group">
                        <select name="supplier_id" id="supplier_id" class="form-control input-sm select2" style="width: 100%;"  required> 
                            <option value="">---Select---</option>
                            @foreach($suppliers as $data)
                                <option value="{{ $data->supplier_id }}" {{$purchase_master->supplier_id==$data->supplier_id?'selected':''}}>{{$data->sup_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

             
            <div class="form-group">
                
             </div>
             <div class="modal-footer">
                <input type="submit" value="Update" class="btn btn-warning">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </form></div>
      </div>
      
    </div>
</div>
<!-- END MODAL -->

<!-- START ADD ITEM MODAL -->
<div class="modal fade" id="myModal_2" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title text-success">ADD NEW ITEM</h4>
        </div>
        <div class="modal-body">
            <form action="{{ url('admin/purchase/add-to-existing-bill') }}" method="post" name="add_item_form">
              {{ csrf_field() }}
              <input autocomplete="OFF" type="hidden" name="purchase_master_id" value="{{$purchase_master->purchase_master_id}}"  />
            <div class="col-md-6">
              <div class="form-group">
                <label for="">Item</label>
                <select name="item_id" id="item_id" class="form-control input-sm select2" style="width: 100%;"  required> 
                    <option value="">---Select---</option>
                    @foreach($items as $item)
                        <option value="{{ $item->item_id }}">{{$item->item_name}}</option>
                    @endforeach
                </select>
             </div>
         </div>
        <div class="col-md-6">
              <div class="form-group">
                <label for="">Particulars</label>
                
                <input type="text" autocomplete="OFF" value="" name="particulars" id="particulars" class="form-control input-sm " >
             </div>
         </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="">Quantity</label>
                
                <input type="text" autocomplete="OFF" value="" name="stock_in" id="stock_in" class="form-control input-sm " required>
             </div>
         </div>
         <div class="col-md-6">
             <div class="form-group">
                <label for="">Purchase Rate</label>
                <input type="text" autocomplete="OFF" name="purchase_rate" id="purchase_rate" class="form-control input-sm" required>
             </div>   
         </div>  

            
        </div>
        <div class="modal-footer">
            <input type="submit" value="Save Changes" class="btn btn-success">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        </form> 
      </div>
      
    </div>
</div>
<!-- END ADD ITEM MODAL -->

@if(Session::has('success'))
  <script>
    Swal.fire({
      //position: 'top-end',
      type: 'success',
      title: 'Your work has been saved',
      showConfirmButton: false,
      timer: 1500
    })
  </script>
  @phpSession::forget('success');@endphp
@endif
<script>
$(document).ready(function(){
  $("#myInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#myTable tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>

<style type="text/css">
.select2-container
{
    z-index: 999999999999 !important;
    
}
</style>

@endsection