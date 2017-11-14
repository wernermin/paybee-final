@extends('layouts.app')

<style>
.top-right {
    position: absolute;
    right: 200px;
    
}

.links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }
</style>
@section('content')
<div class="container">
    <div class="top-right links">
        @if (Auth::check())
                        <a href="{{ url('/home') }}">Home</a>
                    @endif  
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default panel-primary">
                <div class="panel-heading">My Settings</div>
                <div class="panel-body">
                    <p>You can set your default settings here</p>
                    <div class="row">
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="currency">Default Currency</label>
                            <div class="col-md-6">
                              <select id="currencyx" name="currencyx" class="form-control select2">
                                @foreach($currencies as $curdatax)
                                
                                @if($code == $curdatax['code'])
                                <option selected="" value="{{$curdatax['id']}}">{{$curdatax['code']}} ({{$curdatax['description']}})</option>
                                @else
                                <option value="{{$curdatax['id']}}">{{$curdatax['code']}} ({{$curdatax['description']}})</option>
                                @endif

                                @endforeach
                              </select>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div id="resultDiv" name="resultDiv" class="alert col-md-offset-2 col-md-8" style="text-align: center;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function(){
    
    $("#currencyx").on("change",function(){
        
        var currency = $("#currencyx option:selected").val();
        
        if(currency != "")
        {
            $.ajax({
                type: "GET",
                url: './update_currency/'+currency,
                data: "",
                success: function(response) {
                    //console.log(response);
                    
                    if(response == 'Success')
                    {
                        $("#resultDiv").addClass("alert-success");
                        $("#resultDiv").html('Your default currency has been updated');
                        
                        setTimeout(function() {
                            $("#resultDiv").html('');
                            $("#resultDiv").removeClass("alert-success");
                           
                        },2000);
                    }
                    else
                    {
                        $("#resultDiv").addClass("alert-danger");
                        $("#resultDiv").html('There was a problem with setting your default currency. Please try again later.');
                        
                         setTimeout(function() {
                            $("#resultDiv").html('');
                            $("#resultDiv").removeClass("alert-danger");
                           
                        },2000);
                    }
                    
                }
            });
        }
        
        
        
    });
    
});
</script>
@endsection
