@extends('layouts.app')

@section('content')



<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default panel-primary">
                <div class="panel-heading">Currency Converter</div>

                <div class="panel-body">
                    <div class="col-md-12"> 
                        
                        @if($telegram_id != "")
                       
                            
                            <div class="panel-success">
                                <!--
                                <div class="alert alert-success alert-dismissable fade in">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                                    <p style="color:green">Congratulations! Your Paybee account is linked to your Telegram account</p>
                                </div>-->
                                <div class="row">
                                    <div class="col-md-4">
                                        Telegram ID:
                                    </div>
                                    <div class="col-md-8">
                                        <p>{{$telegram_id}}</p>
                                    </div>
                                </div>
                                @if($code != "")
                                <div class="row">
                                    <div class="col-md-4">
                                        <p><b>Default Currency:</b></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p>{{$code}} ({{$country}})</p>
                                    </div>
                                </div>
                                @else
                                
                                    <div class="panel panel-default panel-warning ">
                                        <p>You have no default currency set. Please set it by going to <a href="{{ url('/bot-config') }}">My Settings</a></p>
                                        <p>(USD will be used as default currency)</p>
                                    </div>
                                
                                @endif
                                
                            </div>
                       
                        <div class="row">
                            <label class="col-md-4 control-label" for="currency">Choose other currency:</label>
                            <div class="col-md-6">
                              <select id="currency" name="currency" class="form-control select2">

                                @foreach($currencies as $curid=>$curdata)

                                @if($code == $curdata['code'])
                                <option selected="" value="{{$curdata['code']}}">{{$curdata['code']}} ({{$curdata['description']}})</option>
                                @else
                                <option value="{{$curdata['code']}}">{{$curdata['code']}} ({{$curdata['description']}})</option>
                                @endif

                                @endforeach
                              </select>
                            </div>
                        </div>
                       
                        <div class="row" style="margin-top: 10px">
                            <label class="col-md-4 control-label" for="amount">Amount:</label>
                            <div class="col-md-4">
                                <input class="form-control input-md" type="number" min="1" max="1000000" id="amount" name="amount" value="1"/>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-offset-2 col-md-8" id="getstarted"></div> 
                            <div class="col-md-offset-4 col-md-4" style="align-content: center; ">
                                <button style="display:none;" class="btn btn-info" id="getRequest" name="getRequest">Send Response</button>
                            </div>
                        </div>   
                        
                        @else
                        <div class="row"> 
                            <div class="panel-danger">
                                <p>If you do not have a Telegram account yet please create one by going to <a target="_blank" href="https://web.telegram.org/#/login">this link</a></p>
                                <p>If you have a Telegram account please follow the steps below to link your Telegram account with your Paybee account</p>   
                                <ul>
                                    <li><b>Step 1:</b> Open your Telegram app and search for @wpaybee_bot and click on Start. </li>
                                    <li><b>Step 2:</b> Enter the command /linkAccount/<label id="utoken"></label></li>
                                    <li id="linkAccountLi"><b>Step 3:</b> A "Link Account" button will appear here. Please click on it to finalize the linking process</li> 
                                </ul>
                            </div>
                        </div>
                        @endif
                      
                        
                    
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
        
        function linkAccount(utoken){
            $.ajax({
                type: "GET",
                url: './respond/linkAccount',
                data: "",
                success: function() {
                   // console.log("link success");
                    location.reload();
                }
            });
        }
        
        
        function getLatestRequest()
        {
            $.ajax({
                type: "GET",
                url: './updates',
                data: "",
                success: function(responsedata) {
                    
                    responsedata = responsedata.substring(1);
                    //console.log(responsedata);
                    
                    var grtext = $("#getRequest").text();
                    
                    if(responsedata == 'getUserID' && grtext != 'Send User ID')
                    {
                        $("#getstarted").html('');
                        $("#getRequest").removeClass("btn-warning");
                        $("#getRequest").addClass("btn-info");
                        $("#getRequest").text('Send User ID');
                        $("#getRequest").show();
                    }
                    
                    if(responsedata == 'getBTC' && grtext != 'Send BTC')
                    {
                        $("#getstarted").html('');
                        $("#getRequest").removeClass("btn-info");
                        $("#getRequest").addClass("btn-warning");
                        $("#getRequest").text('Send BTC');
                        $("#getRequest").show();
                    }
                    
                    if(responsedata.indexOf('linkAccount')!== -1)
                    {
                        
                        var telegramid = '<?php echo $telegram_id;?>';
                        
                        if(telegramid == "")
                        {
                            
                            var utoken = $("#utoken").text();
                        
                            var sent_token = responsedata.substring(12);

                            if(sent_token == utoken)
                            {
                                $("#linkAccountLi").html('<button onclick="linkAccount('+utoken+')" class="btn btn-success" id="linkAccount" name="linkAccount">Link Account</button>');
                            }
                            else
                            {
                                $("#linkAccountLi").html('<b>Step 3:</b> A "Link Account" button will appear here. Please click on it to finalize the linking process');
                            }
                            
                            
                        }
                        else
                        {
                            $("#getstarted").html('Type /getUserID OR /getBTC in your Telegram app to get started');
                            
                            $("#getRequest").hide();
                        }
                    }
                }
            });
            
            setTimeout(function() {
                getLatestRequest();
            },10000);
        }
        
        function randomNumberFromRange()
        {
            return Math.floor(Math.random()*(10000-1000+1)+1000);
        }


        
        $(document).ready(function(){
            
            var telegram_id = '<?php echo $telegram_id;?>';
            
            if(telegram_id == "")
            {
                var utoken = randomNumberFromRange();
                $("#utoken").text(utoken);
                
            }
            
            //if(telegramid != "")
            //{
                setTimeout(function() {
                getLatestRequest();
                
                },2000);
           // }
            
            $("#getRequest").on("click",function(){
                
                var command = "";
                var btnText = $("#getRequest").text();
                
                if(btnText == 'Send User ID')
                {  
                    $.ajax({
                        type: "GET",
                        url: './respond/getUserID',
                        data: "",
                        success: function() {
                            //console.log("get user id success");


                        }
                    });
                }
                
                if(btnText == 'Send BTC')
                {
                    var code = $("#currency option:selected").val();
                    var amount = $("#amount").val();
                
                    if(code == "")
                    {
                        code = "USD";
                    }
                    
                    if(amount == "")
                    {
                        amount = 1;
                    }

                    $.ajax({
                        type: "GET",
                        url: './respond/getBTC/'+code+'/'+amount,
                        data: "",
                        success: function() {
                            //console.log("get btc success");
                        }
                    });
                }
                
            });

        });
</script>
@endsection
