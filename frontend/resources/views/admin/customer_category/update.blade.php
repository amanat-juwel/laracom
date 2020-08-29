<div class="modal modal-default fade" id="update_category">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Update Category</h4>
            </div>
            <form class="form" action="category_update" method="POST" id="update_form">
                <div class="modal-body">
                    {{ method_field('PUT') }}
                    {{ csrf_field() }}
                    <input type="hidden" name="id" id="cata_id_1" >
                    <div class="form-group">
                        <label for="">Catagory Name</label>
                        <input type="text" name="cat_name" id="cat_name_1" placeholder="Catagory Name" class="form-control"  />
                        @if($errors->has('cat_name'))
                            <span class="text-danger">{{ $errors->first('cat_name')}}</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="">Category Details</label>
                        <textarea rows="5" name="credit_limit" id="credit_limit_1" class="form-control"  placeholder="Details about category" ></textarea>
                        @if($errors->has('credit_limit'))
                            <span class="text-danger">{{ $errors->first('credit_limit')}}</span>
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