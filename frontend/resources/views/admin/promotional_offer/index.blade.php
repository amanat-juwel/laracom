@extends('admin.layouts.template')

@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>
        PROMOTIONAL OFFER
        </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Promotional Offer</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<div class="row">
    <div class="col-md-6">
        <section class="content">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-primary">
                              <div class="panel-heading"><i class="fa fa-comments-o"></i> Send SMS</div>
                              <div class="panel-body">
                                <form class="form" action="" method="">
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        <label for="">Contact Persons*</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                            <select id="mobile_no" name="mobile_no" class="form-control select2" multiple="multiple" data-placeholder="Select" required="">
                                                @foreach($customers as $customer)
                                                <option value="{{$customer->mobile_no}}">{{$customer->customer_name}} : {{$customer->mobile_no}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="">Text (Max 160 Character)*</label>
                                        <textarea rows="4" name="text" class="form-control" id="text" maxlength="160" placeholder="" required=""></textarea>
                                        
                                    </div>
                                    <div class="form-group">
                                        <input type="" class="btn btn-success" id="submit" value="Send"/>
                                    </div>
                                </form>
                            </div>
                            </div>
                            
                            
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Main Content -->
    </div>
</div>

<script>
$(document).ready(function () {    
    
    // var fruits = 'apple,orange,pear,banana,raspberry,peach';
    // var ar = fruits.split(','); // split string on comma space
    // console.log( ar );

    $("#submit").click(function() {  
        var mobile_no = ""+$('#mobile_no').val()+""; 
        var text = $('#text').val(); 
        var re = /\s*,\s*/;
        var numberList = mobile_no.split(re);
        console.log( numberList );

        var data = JSON.stringify({  "from": "InfoSMS",  "to": numberList,  "text": text });
        var xhr = new XMLHttpRequest();
        // xhr.withCredentials = true;
        xhr.addEventListener("readystatechange", function () {  
            if (this.readyState === this.DONE) {    
                console.log(this.responseText); 
                var obj = JSON.parse(this.responseText); 
                //alert(obj.messages[0].status.description);
                if(obj.messages[0].status.description=="Message sent to next instance"){
                    alert("Success: Message Sent");
                    $('#mobile_no').val('')
                    $('#text').val(''); 
                }
                else{
                    alert("Failed: Something went wrong");

                }
            } 
        });
        xhr.open("POST", "http://107.20.199.106/restapi/sms/1/text/single"); 
        xhr.setRequestHeader("authorization", "Basic c29oYWlsQHYtbGlua25ldHdvcmsuY29tOnBhc3N3b3JkMjAxOA=="); 
        xhr.setRequestHeader("content-type", "application/json"); 
        xhr.setRequestHeader("accept", "application/json");
        xhr.send(data);

    });
});
</script>

@endsection
