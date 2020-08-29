@include('admin.sales.GetCurrencyClass')
<style>
html *
{
   font-size: 12 !important;
   color: #000 !important;
   font-family: sans-serif !important;
}
div.ex1 {
    width:250px;
    margin: auto;


}

.button {
    background-color: #4CAF50; /* Green */
    border: none;
    color: white;
    padding: 3px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    -webkit-transition-duration: 0.4s; /* Safari */
    transition-duration: 0.4s;
    cursor: pointer;
}

.button1 {
    background-color: white; 
    color: black; 
    border: 2px solid #4CAF50;
}

.button1:hover {
    background-color: #4CAF50;
    color: white;
} 

.button2 {
    background-color: white; 
    color: black; 
    border: 2px solid #008CBA;
} 

.button2:hover {
    background-color: #008CBA;
    color: white;
}

.button3 {
    background-color: white; 
    color: black; 
    border: 2px solid #f44336;
} 

.button3:hover {
    background-color: #f44336;
    color: white;
}

.button4 {
    background-color: white;
    color: black;
    border: 2px solid #e7e7e7;
}

.button4:hover {background-color: #e7e7e7;}

.button5 {
    background-color: white;
    color: black;
    border: 2px solid #555555;
}

.button5:hover {
    background-color: #555555;
    color: white;
}
  
  div.fontsize {
  font-size: 15px !important;
  } 
  div.fontsize1 {
  font-size: 20px !important;
   font-weight: bold;
  } 

  .company {
   font-size: 20px !important;
   font-weight: bold;
   text-decoration: underline;
  } 
  .powered-by{
    font-size: 10px !important;;
  }
</style>

  
<!doctype html>
<div class="ex1">
<html>

<body>
<!-- Changes here! -->
<style type="text/css" media="print">
.dontprint
{ display: none; }
</style>
  <style type="text/css" media="print">
      @page 
      {
          size: auto;   /* auto is the initial value */
          margin: 0mm;  /* this affects the margin in the printer settings */
      }
  </style>
<div class="dontprint">
<!--<a href="clear_reciept.php" onclick="if (window.print) window.print();" autofocus><strong><center>[PRINT]</center></strong></a>-->

</div>
<!-- Changes here! -->
  <head>
    <meta charset="utf-8">
    <title>Receipt</title>
    
    <!--<link rel="stylesheet" href="css/receipt_style.css">
    <link rel="license" href="http://www.opensource.org/licenses/mit-license/">
    <script src="js/receipt_script.js"></script>-->
  </head>

  <body onload="window.print();">
  
      
        
        <strong>
        <center>
        <div class="company">
        {{ $globalSettings->company_name}}      
        </div>
        
        </strong>
        
        {!! $globalSettings->address!!} <br>
        @if($globalSettings->phone!='')Phone: {{ $globalSettings->phone }} <br>@endif
        @if($globalSettings->mobile!='')Mobile: {{ $globalSettings->mobile }} <br>@endif
        
        @if($globalSettings->vat_registration_no)
        VAT Reg# {{ $globalSettings->vat_registration_no}}<br>
        @endif
        </center>
        <center>------------------------------------------------------------- <br/></center>
        <center>
        
        <strong>SALES RETURN-EXCHANGE</strong><br>
        
        {{ date("F j, Y  h:i:sa") }}       </center>

        <center>-------------------------------------------------------------</center>

        <table class="table table-bordered">
          <tbody>
        <tr><!--border-style: solid; to check the width below--> 
          <td><div style='width: 240px;   text-align: left;'>Cust: {{ $_Master->customer_name }}</div></td> 

        </tr>   
        </tbody>
        </table>
        
    

        <center>------------------------------------------------------------- <br /></center>

        <table class="table table-bordered">
        <tbody>
          
        @foreach($_Details as $key => $data)                 
        <tr><!--border-style: solid; to check the width below--> 
          <td><div style='width: 180px; text-align: left;'>
          <b>
          {{ $data->item_code }} | {{ $data->item_name }} | {{ $data->size }}</b>            
          </div></td> 
          <td><div style='width: 60px; text-align: right;'>{{ number_format($data->rate * $data->qty) }}</div></td>
        </tr>
        <tr><!--border-style: solid; to check the width below--> 
          <td><div style='width: 180px; text-align: left;'>{{ $data->qty }} x @if($data->discounted_price!=null)<del>{{ number_format($data->mrp,2) }}</del>@endif {{ number_format($data->rate,2) }} </div></td>  
          <td><div style='width: 60px; text-align: right;border-top: 1px solid #000000'>{{ $data->type }}</div></td>
        </tr>
        @endforeach
                        
        </tbody>
        </table>
                    
          
          
        <center>------------------------------------------------------------- <br /></center>
        <table class="table table-bordered">
          <tbody>

        <tr><!--border-style: solid; to check the width below--> 
          <td><div style='width: 140px; text-align: left;'>Paid:</div></td> 
          <td><div style='width: 100px; text-align: right;'> 

            @if($_Master->cash_in>0)
            {{number_format($_Master->cash_in,2)}}
            @elseif($_Master->cash_out>0)
            {{number_format($_Master->cash_out,2)}}
            @endif
          </div></td>

        </tr>
        <tr><!--border-style: solid; to check the width below--> 
          <td><div style='width: 140px; text-align: left;'>Payment Method:</div></td> 
          <td>
            <div style='width: 100px; text-align: right;'>
              @if(strpos(strtolower("$_Master->payment_method"), 'cash') !== false)
               Cash
               @elseif(strpos(strtolower("$_Master->payment_method"), 'card') !== false)
               Card
               @else
               {{ $_Master->payment_method }}
               @endif
            </div>
          </td>

        </tr>

   <!--      <tr> 
          <td colspan='2'><div style='text-align: left;'><b>In Words:</b> 
            @if($_Master->cash_in>0)
            {{ getCurrency($_Master->cash_in) }}
            @elseif($_Master->cash_out>0)
            {{ getCurrency($_Master->cash_out) }}
            @endif
              </div></td>       
        </tr>   -->     
        </tbody>
        </table>

        <center>------------------------------------------------------------- <br /></center>
    
        <center>

        <p class="powered-by">Software by: V-link Network</p>
        
        </center>

        
<script>

    function closeWindow() {
      setTimeout(function() {
      window.close();
      }, 5000); // 5 sec
    }

    
</script>
  
</html>
</div>
<br /><br />
