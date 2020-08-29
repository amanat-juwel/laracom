<!DOCTYPE html>
<html lang="en">
<head>
  <title>Barcode</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<style type="text/css" media="print">
    @page 
    {
        size: A4;   /* auto is the initial value */
        margin: 0mm;  /* this affects the margin in the printer settings */
        padding:0px;
    }
    
</style>
<style type="text/css">
	div.inline {display: inline-block;}
     font-size: 12.5px;}
</style>
<body onload="window.print()">

<!-- Main content -->
<section class="content">
    <center>
      <h4><u>{{$globalSettings->company_name}}</u></h4>
    </center>
    <br>
    <div class="row">
        @foreach($barcode_array as $key=>$data)
        <div class="col-xs-3">
               <p style="margin:0px; padding:0px; font-weight: bold; font-size:12px;">{{$data['item_code']}}</p>
               
                <!-- <img src="data:image/png;base64,{{DNS1D::getBarcodePNG($data['item_code'], 'C39+')}}" alt="barcode"
                style="width: 50%; height: 25px; margin:0px; padding:0px;" /> -->
                <p style="margin-top:3px; padding:0px; font-size:12px;">{{$data['item_name']}}</p>
                <!-- <p style="margin:0px; padding:0px; font-size:12px;">MRP: {{$data['price']}} </p> -->
        </div>
        @php
            $key++;
            if($key%4==0)
                echo "</div><div class='row'>";
        @endphp
        @endforeach
    </div>

</section>
<!-- End Main Content -->

<style>

@page 
{
    size: auto;   /* auto is the initial value */
    margin: 10mm;  /* this affects the margin in the printer settings */

}

.barcode:first-child
{
  margin-top:56px;
}

.barcode
{
  display: inline-block;
  margin-bottom:23px;
  margin-left:48px;
  margin-top:0px;
}


</style>

</body>
</html>