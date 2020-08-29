<div class="modal modal-default fade" id="update_category">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Update Category</h4>
            </div>
            <form class="form" action="{{url('admin/category/update')}}" method="POST" id="update_form">
                <div class="modal-body">
                    {{ method_field('PUT') }}
                    {{ csrf_field() }}
                    <input type="hidden" name="cata_id" id="cata_id_1" >
                    <div class="form-group">
                        <label for="">Catagory Name</label>
                        <input type="text" name="cata_name" id="cata_name_1" placeholder="Catagory Name" class="form-control"  />
                        @if($errors->has('cata_name'))
                            <span class="text-danger">{{ $errors->first('cata_name')}}</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="">Category Details</label>
                        <textarea rows="5" name="cata_details" id="cata_details_1" class="form-control"  placeholder="Details about category" ></textarea>
                        @if($errors->has('cata_details'))
                            <span class="text-danger">{{ $errors->first('cata_details')}}</span>
                        @endif
                    </div>
                    
                </div>
                <div class="modal-footer">
                   
                   
                    <div class="form-group" >
                        <input type="submit" style="color: black" class="btn btn-warning" id="save" value="Update" />
                         <button type="button" style="color: black" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>