    <!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>user logs</title>
        <!-- <link href='//fonts.googleapis.com/css?family=Lato:300' rel='stylesheet' type='text/css'> -->
        <link href="/css/bootstrap.min.css" rel="stylesheet">
        <!-- <link href="assets/css/custom.css" rel="stylesheet"> -->
        <link rel="stylesheet" href="/jalalicalendar/skins/aqua/theme.css">
                <style>
            #ajaxloading
            {
                position: fixed;
                left: 0px;
                top: 0px;
                width: 100%;
                height: 100%;
                background:transparent url('image/ajax-loader.gif') center no-repeat;
                font-size: 0px;
                z-index: 9999;
                display: block;
            }
        </style>
        <script src="/js/jquery-1.12.4.js"></script>
        <script src="/jalalicalendar/jalali.js"></script>
        <script src="/jalalicalendar/calendar.js"></script>
        <script src="/jalalicalendar/calendar-setup.js"></script>
        <script src="/jalalicalendar/lang/calendar-fa.js"></script>
    </head>
    <body>
        <nav class="navbar navbar-inverse fixed-top"> <!-- fixme: change to navbar-fixed-top -->
        <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>
            <ul class="nav navbar-nav">
                <li><a id='home' href="">home</a></li>
                <li class='dropdown'>
                <a class='dropdown-toggle' href='#' data-toggle='dropdown' role='button' aria-expanded='false' aria-haspopup='true'>
                    NAS
                </a>
                    <ul class="dropdown-menu">
                        <li><a id='manageNases' href="">manage nases</a></li> 
                        <li><a id='addNas' href="">add nas</a></li>
                    </ul>
                </li>
                <li class='dropdown'>
                <a class='dropdown-toggle' href='#' data-toggle='dropdown' role='button' aria-expanded='false' aria-haspopup='true'>
                    RULE
                </a>
                    <ul class="dropdown-menu">
                        <li><a id='manageRules' href="">manage rules</a></li>
                        <li><a id='addRule' href="">add rule</a></li>
                    </ul>
                </li>

            </ul>
            <div id="navbar" class="navbar-collapse collapse">
                <!--+++++ login form++++++++++++++++-->
                <form method="post" action="/token" id="loginform" class="navbar-form navbar-right">
                <div class="form-group">
                <input type="text" placeholder="username" id="username" class="form-control" value="admin">
                </div>
                <div class="form-group">
                <input type="password" placeholder="password" id="password" class="form-control" value="admin">
                </div>
                <input type="submit" id="btnLogin" class="btn btn-success" value="login">
                </form>
                <button class="btn btn-success" id="btnlogout" style="display:none">log out</button>
            </div>
        </div>
        </nav>


        <div class="container" id="message">
        </div>
        <div class="container">
        <!--+++++++++++++ add post form +++++++++++++++++++-->
        <form method="POST" action="/posts" id="createform" style="display:none">
        <input type="text" placeholder="title" id="title_text"  class="form-control" required>
        <textarea placeholder="content" id="content_text" rows="10" cols="10" class="form-control">
        </textarea>
        <input type="submit" id="create" class="btn btn-success">
        <hr>
        </form>
       <div id="headform"> 
            <button id="alllogins" class="btn"> all logins</button>
            <button id="showuserlogs" class="btn">user login details</button>
            <input type="text" placeholder="username" id ="usertext" value="user1" >

            <input id="start_date_input" type="text">
            <input id="start_date_btn" type="button" value="start_date">
            <script>
                Calendar.setup({
                    inputField: 'start_date_input',
                    button: 'start_date_btn',
                    ifFormat: '%Y/%m/%d',
                    dateType: 'jalali'
                });
            </script>

            <input id="end_date_input" type="text">
            <input id="end_date_btn" type="button" value="end_date">
            <script>
                Calendar.setup({
                    inputField: 'end_date_input',
                    button: 'end_date_btn',
                    ifFormat: '%Y/%m/%d',
                    dateType: 'jalali'
                });
            </script>
        </div>

        <div id="ajaxloading">
        </div>
        <div id="users">
        </div>

        <div id="addNasForm" style="display:none">
            <div class="form-group">
                <label for="nasip">nas ip: </label>
                <input type="text" class="form-control" id="nasip" required>
            </div>
            <div class="form-group">
                <label for="username">username:</label>
                <input type="text" class="form-control" id="nasusername">
            </div>
            <div class="form-group">
                <label for="password">password</label>
                <input type="password" class="form-control" id="naspassword">
            </div>
            <div class="form-group">
                <label for="description">description</label>
                <textarea id="description" cols="15" rows="5"></textarea> 
            </div>
            <button type="submit" class="btn btn-default" id="btnAddNas">submit</button>
        </div>

       <div id="addRulesForm" style="display:none">
            <div class="form-group">
                <label for="exclusionName"> filter type: </label>
                <select id="exclusionName" class="form-control">
                    <option value="1">extension</option>
                    <option value="2">domain</option>
                </select>
            </div>
            <div class="form-group">
                <label for="exclusionValue">value: </label>
                <input type="text" id="exclusionValue" class="form-control" required  minlength="2">
            </div>
            <button type="submit" class="btn btn-default" id="btnAddRule">submit</button>
        </div>
        </div>

 

        <script src="/js/bootstrap.min.js"></script>
        <script>
         $(document).ready(function(){
                $("#ajaxloading").hide();
            $(document).ajaxStart(function(){
                $("#ajaxloading").show();
            });
            $(document).ajaxStop(function(){
                $("#ajaxloading").hide();
            });
         });
        </script>
        <script>
             var store = store || {};
             store.setJWT = function(data){
                  this.JWT = data;
            }

            base_url = 'http://172.16.8.13:8000/';
            // base_url = 'http://localhost:8000/';
            all_logs_url = base_url + 'logs/';
            user_log_url = base_url + 'logs/';
            weblogs_url = base_url + 'weblogs/';
            user_log_details_url = base_url + 'logdetails/';

            all_logs_page = 1;
            user_log_page = 1;
            weblog_page = 1;
            userlog_details_page = 1;

            function reset_all()
            {
                $("#headform").hide();
                $("#addNasForm").hide();
                $("#addRulesForm").hide();
                $("#users").empty();
                $("#exclusionValue").val('');
                $("#nasusername").val('');
                $("#nasip").val('');
                $("#naspassword").val('');
                $("#start_date_input").val('');
                $("#end_date_input").val('');
                $("#usertext").val('');
            }

       $('#alllogins').click(function(e){
                "ues strict";
                $('#users').empty();
                e.preventDefault();
                all_logs_page = 1;
                all_logs(function(data){
                    $("#users").append(render_log(data));
                    all_logs_page += 1;
                });
            });
            //---------------------btnNext-----------------------
            $('#users').on('click', '#btnNext', function(e){
                e.preventDefault();
                all_logs(function(data){
                    $("#users").empty();
                    $("#users").append(render_log(data));
                    all_logs_page += 1;
                });
            });
            //-------------------btnPrev-------------------------
            $("#users").on('click','#btnPrev', function(e){
                e.preventDefault();
                all_logs(function(data){
                    $("#users").empty();
                    $("#users").append(render_log(data));
                    all_logs_page -= 1;
                });
            });
            //-------------------------------------------------
            function all_logs(handleData){
                url = all_logs_url + all_logs_page;
                data ={'jwt' : store.JWT};
                $.ajax({
                    url: url,
                    type: 'GET',
                    contentType:'application/json',
                    dataType:'json',
                    data: data,
                    success:function(data){
                        handleData(data);
                    }
                });
            }
//==================================================================================
            //---------------------------------------------------
            $("#showuserlogs").click(function(e){
                e.preventDefault();
                username = $("#usertext").val();
                if(username == ''){
                    alert('provide username');
                    return;
                }
                user_log_page = 1;
                get_user_logs(function(data){
                    $("#users").empty();
                    $("#users").append(render_user_log(data));
                });
            });

            //---------------- btnUserdetailsNext----------------------------------------
            $("#users").on('click', '#btnUserdetailNext', function(e){
                e.preventDefault();
                get_user_logs(function(data){
                    $("#users").empty();
                    $("#users").append(render_user_log(data));
                    user_log_page += 1;
                });
            });
            //------------------btnUserdetaiNext------------------------------------------
            $("#users").on('click', '#btnUserdetailPrev', function(e){
                e.preventDefault();
                get_user_logs(function(data){
                    $("#users").empty();
                    $("#users").append(render_user_log(data));
                    user_log_page -= 1;
                });
            });

            //--------------------------------------------------
            function get_user_logs(handleData){
                username = $("#usertext").val();
                url = base_url + "logs/" +  username + "/" + user_log_page;
                //todo check for empty values
                start_date = $("#start_date_input").val();
                end_date = $("#end_date_input").val();
                if(start_date.length > 0 && end_date.length > 0)
                {
                    start_date = convert_to_gregorian(start_date);
                    end_date = convert_to_gregorian(end_date);
                    console.log("start_date.length: " + start_date.length);
                    console.log("end_date.length: " + end_date.length);
                        url = base_url + "logs/" + username + "/" + start_date + "/" + end_date + "/" + user_log_page;
                }

                data ={'jwt' : store.JWT};
                $.ajax({
                    url: url,
                    type: 'GET',
                    contentType:'application/json',
                    dataType: 'json',
                    data: data,
                    success:function(data){
                        handleData(data);
                    }
                });
            }
//==================================================================================
            // ------------ Manage Nases --------------------------------
            $("#manageNases").click(function(e){
                e.preventDefault();
                $("#addNasForm").hide();
                $("#headform").hide();
                $("#addRulesForm").hide();
                get_nas_list(function(data){
                    $("#users").empty();
                    $("#users").append(render_nases(data));
                })
            });

            $("#users").on('click', '#deleteNas', function(e){
                e.preventDefault();
                nasid = parseInt(this["value"]);
                url = base_url + "nases/delete/" + nasid;
                data ={'jwt' : store.JWT};
                $.ajax({
                    url:url,
                    type:'GET',
                    contentType:'application/json',
                    dataType:'json',
                    data: data,
                    success:function(data){
                        console.log(data);
                        reset_all();
                         $("#manageNases").trigger('click');
                    },
                    error:function(){
                        alert("could not delete nas");
                    }
                });
            });

            function get_nas_list(handleData){
                url = base_url + "nases";
                data ={'jwt' : store.JWT};
                $.ajax({
                    url: url,
                    type: 'GET',
                    contentType:'application/json',
                    dataType:'json',
                    data: data,
                    success:function(data){
                        handleData(data);
                    }
                });
            }
            function render_nases(data)
            {
                ret = "<div class='table-responsive'>";
                ret += "<table class='table table-striped'>";
                thead = "<thead><tr>";
                tbody = "<tbody>";
                for(var i in data["data"][0])
                {
                    thead += "<th>" + i + "</th>";
                }
                // thead += "<th> edit </th>";
                thead += "<th> delete </th>";
                thead += "</tr></thead>";

                $.each(data["data"], function(index,item){
                    tbody += "<tr>";
                    for (var i in item) {
                        tbody += "<td>" + item[i] + "</td>";
                    }
                    console.log(item);
                    // tbody += "<td><button id='editNas' class='btn btn-success' value='" + item['nas_id'] + "'>edit</button></td>";
                    tbody += "<td><button id='deleteNas' class='btn btn-success' value='" + item['nas_id'] + "'>delete</button></td>";
                    tbody += "</tr>";
                });

                tbody += "</tbody>";
                ret += thead;
                ret += tbody;
                ret += "</table>";
                ret += "</div>";
                return ret;
            }
//==================================================================================
            //------------ add nas  ---------------------------------
            $('#addNas').click(function(e){
                e.preventDefault();
                reset_all()
                $("#addNasForm").show();
            })

            $('#btnAddNas').click( function(e){
                e.preventDefault(); 
                nasip = $('#nasip').val();
                username = $('#nasusername').val();
                password = $('#naspassword').val();
                description = $('#description').val();
                data = JSON.stringify({"nasip" : nasip,
                                        "username": username,
                                        "password": password,
                                        "description" : description,
                                        "jwt": store.JWT });
                console.log(data);
                url= base_url + "nases/add";
                $.ajax({
                    url : url,
                    type : 'POST',
                    contentType : 'application/json',
                    dataType: 'json',
                    data : data,
                    success: function(data){
                        // alert("successfull inserted nas to db");
                        $("#addNasForm").hide();
                    },
                    error : function(err){
                        //alert('could not insert to db');
                        console.log(err["error"]);
                    }
                });
            });
//==================================================================================
            //----------------- home url click -----------------
            $("#home").click(function(e){
                e.preventDefault();
                reset_all();
                $("#headform").show();
            })
//=========================== rules  ==================================================
            //---------------- manage rules click ----------------

             $("#manageRules").click(function(e){
                e.preventDefault();
                reset_all();
                get_rule_list(function(data){
                    if(data['status'] === 'success')
                    {
                        $("#users").append(render_rules(data));
                    }
                })
             });

             function get_rule_list(handleData)
             {
                url = base_url + "rules";
                data ={'jwt' : store.JWT};
                $.ajax({
                    url: url,
                    type: 'GET',
                    contentType:'application/json',
                    dataType:'json',
                    data: data,
                    success:function(data){
                        handleData(data);
                    }
                });
             }

             function render_rules(data)
             {
                ret = "<div class='table-responsive'>";
                ret += "<table class='table table-striped'>";
                thead = "<thead><tr>";
                tbody = "<tbody>";
                for(var i in data["data"][0])
                {
                    thead += "<th>" + i + "</th>";
                }
                thead += "<th> delete </th>";
                thead += "</tr></thead>";

                $.each(data["data"], function(index,item){
                    tbody += "<tr>";
                    for (var i in item) {
                        tbody += "<td>" + item[i] + "</td>";
                    }
                    console.log(item);
                    tbody += "<td><button id='deleteRule' class='btn btn-success' value='" + item['exclusion_rules_id'] + "'>delete</button></td>";
                    tbody += "</tr>";
                });

                tbody += "</tbody>";
                ret += thead;
                ret += tbody;
                ret += "</table>";
                ret += "</div>";
                return ret;
             }
             //---------------deleteRue ------------------------
             $("#users").on('click', '#deleteRule', function(e){
                e.preventDefault();
                ruleid = parseInt(this["value"]);
                url = base_url + "rules/delete/" + ruleid;
                data ={'jwt' : store.JWT};
                console.log(data);
                $.ajax({
                    url:url,
                    type:'GET',
                    contentType:'application/json',
                    dataType: 'json',
                    data: data,
                    success:function(data){
                        console.log(data);
                        reset_all();
                         $("#manageRules").trigger('click');
                    },
                    error:function(xhr, status, error){
                        console.log(xhr);
                        console.log(status);
                        console.log(error);
                        alert("could not delete rule");
                    }
                });
             })
            //---------------- btnAddRule click-------------------
            $("#addRule").click(function(e){
                e.preventDefault();
                reset_all();
                $("#addRulesForm").show();
            });

            var errorHandler = function (msg)
            {
                alert(msg);
            };

            $("#btnAddRule").click(function(e){
                e.preventDefault();
                exclusion_value = $("#exclusionValue").val();
                selIndex = parseInt($('#exclusionName').val());
                exclusion_name = '';
                switch(selIndex)
                {
                    case 1  :
                        exclusion_name = 'by_ext';
                        break;
                    case 2:
                        exclusion_name = 'by_domain';
                        break;
                }
                data = JSON.stringify({ 'exclusion_name' : exclusion_name,
                                        'exclusion_value' : exclusion_value,
                                         'jwt' : store.JWT});
                url = base_url + "rules/";

                $.ajax({
                    url: url,
                    type: 'POST',
                    contentType: 'application/json',
                    dataType: 'json',
                    data: data,
                    success: function(data) {
                        if ( data["status"] === "error")
                        {
                            errorHandler(data['msg']);
                            return;
                        }
                         reset_all();
                         $("#manageRules").trigger('click');
                    },
                    error: function(xhr, status, error){
                        alert("error in addrule");
                    }
                });
            });
//==================================================================================
            //--------------------btnLogin----------------------
            $("#btnLogin").click(function(e){
                e.preventDefault();
                data = JSON.stringify({"username" : $("#username").val(),
                                       "password": $("#password").val()});
                url = "/token";
                $.ajax({
                    url: url,
                    type: 'POST',
                    contentType:'application/json',
                    dataType:'json',
                    data: data,
                    success: function(data){
                        console.log("btnlogin-->success: ");
                        console.log(data);
                        store.setJWT(data["jwt"]);
                        $("#loginform").hide();
                        $("#btnlogout").show();
                    },
                    error: function(){
                        alert('login error')
                    }
                });
            });
            //-----------------------------------------------------------------
            $("#btnlogout").click(function(e){
               window.location.href = base_url; 
            });
            //-----------------------------------------------------------------
            function render_log(data)
            {
                ret = "<div class='table-responsive'>";
                ret += "<table class='table table-striped'>";
                thead = "<thead><tr>";
                tbody = "<tbody>";
                console.log(data["data"]);
                for(var i in data["data"][0]){
                    thead += "<th>" + i + "</th>";
                }
                
                thead += "</tr></thead>";

                $.each(data["data"],function(index,item){
                    tbody +="<tr>";
                    for (var i in item){
                            tbody += "<td>" +  item[i] + "</td>";
                    }
                    tbody += "</tr>";
                });

                tbody += "</tbody>";
                ret += thead;
                ret += tbody;
                ret += "</table>";
                if(data["hasPrev"] === "true")
                {
                    ret += "<button id='btnPrev' class='btn btn-success'>prev</button>";
                }
                if(data["hasNext"] === "true")
                {
                    ret += "<button id='btnNext' class='btn btn-success'>next</button>";
                }
                ret += "</div>";
                return ret;
            } 
            //----------------------------------------------------------------------
            function render_user_log(data)
            {
                ret = "<div class='table-responsive'>";
                ret += "<table class='table table-striped'>";
                thead = "<thead><tr>";
                tbody = "<tbody>";
                console.log(data["data"]);
                for(var i in data["data"][0]){
                    thead += "<th>" + i + "</th>";
                }
                
                thead += "</tr></thead>";

                $.each(data["data"],function(index,item){
                    tbody +="<tr>";
                    for (var i in item){
                            tbody += "<td>" +  item[i] + "</td>";
                    }
                    tbody += "</tr>";
                });

                tbody += "</tbody>";
                ret += thead;
                ret += tbody;
                ret += "</table>";
                ret += "<button id='btnUserdetailPrev' class='btn btn-success'>prev</button>";
                ret += "<button id='btnUserdetailNext' class='btn btn-success'>next</button>";
                ret += "</div>";
                return ret;
            } 
            //-----------------------------------------------------------------------
            function convert_to_gregorian(str)
            {
                str_arr = str.split("/");
                year = str_arr[0];
                month = str_arr[1];
                day = str_arr[2];

                converted_date = JalaliDate.jalaliToGregorian(year, month, day);
                c_year = converted_date[0];
                c_month = converted_date[1];
                c_day = converted_date[2];

                return c_year + "-" + c_month + "-" + c_day;
            }
            //------------------------------------------------------------------------
        </script>

    </body>
</html>
