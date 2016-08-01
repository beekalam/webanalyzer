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
        <script src="/js/jquery-1.12.4.js"></script>
        <script src="/jalalicalendar/jalali.js"></script>
        <script src="/jalalicalendar/calendar.js"></script>
        <script src="/jalalicalendar/calendar-setup.js"></script>
        <script src="/jalalicalendar/lang/calendar-fa.js"></script>
    </head>
    <body>
        <nav class="navbar navbar-inverse fixed-top">
        <div class="container">
        <div class="navbar-header">
        </div>
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
        
        <button id="showusers" class="btn"> all logins</button>
        <button id="showuserdetails" class="btn">user login details</button>
        <input type="text" placeholder="username" id ="usertext" value="phasan" >

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

        <div id="users">
        </div>
        </div>

        <script src="/js/bootstrap.min.js"></script>

        <script>
             var store = store || {};
             store.setJWT = function(data){
                  this.JWT = data;
            }

            base_url = 'http://localhost:8000/';
            user_log_url = base_url + 'logs/';
            weblogs_url = base_url + 'weblogs/';
            user_log_details_url = base_url + 'logdetails/';

            user_log_page = 1;
            weblog_page = 1;
            userlog_details_page = 1;

            $('#showusers').click(function(e){
                "ues strict";
                $('#users').empty();
                e.preventDefault();
                user_log_page = 1;
                get_user_log(function(data){
                    $("#users").append(render_user(data));
                    user_log_page += 1;
                });
            });
            //---------------------btnNext-----------------------
            $('#users').on('click', '#btnNext', function(e){
                e.preventDefault();
                get_user_log(function(data){
                    $("#users").empty();
                    $("#users").append(render_user(data));
                    user_log_page += 1;
                });
            });
            //-------------------btnPrev-------------------------
            $("#users").on('click','#btnPrev', function(e){
                e.preventDefault();
                get_user_log(function(data){
                    $("#users").empty();
                    $("#users").append(render_user(data));
                    user_log_page -= 1;
                });
            });
            //-------------------------------------------------
            function get_user_log(handleData){
                url = user_log_url + user_log_page;
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
//=====================================================================================
            //--------------------showweblog---------------------------------------
            $("#users").on('click', '.showweblog',function(e){
                e.preventDefault();
                $("#users").empty();
                connection_log_id = e.target.value;
                weblog_page = 1;
                get_web_log(connection_log_id, function(data){
                    $("#users").append(render_weblog(data,connection_log_id));
                    weblog_page += 1;
                });
            });
            //---------------------btnWeblogNext---------------------------------------
            $('#users').on('click', '#btnWeblogNext', function(e){
                e.preventDefault();
                connection_log_id = $("#btnWeblogNext").val(); 
                get_web_log(connection_log_id, function(data){
                    $("#users").empty();
                    $("#users").append(render_weblog(data, connection_log_id));
                    weblog_page += 1;
                });
            });
            //-------------------btnWeblogPrev------------------------------------------
            $("#users").on('click','#btnWeblogPrev', function(e){
                e.preventDefault();
                connection_log_id = $("#btnWeblogPrev").val();
                get_web_log(connection_log_id, function(data){
                    $("#users").empty();
                    $("#users").append(render_weblog(data,connection_log_id));
                    weblog_page -= 1;
                });
            });
            //-------------------------------------------------
            function get_web_log(connection_log_id,handleData){
                url = weblogs_url + connection_log_id + "/" + weblog_page;
                data ={'jwt' : store.JWT};
                $.ajax({
                    url: url,
                    type: 'GET',
                    contentType: 'application/json',
                    dataType:'json',
                    data: data,
                    success:function(data){
                        handleData(data);
                    }
                });
            }
//==================================================================================
            //---------------------------------------------------
            $("#showuserdetails").click(function(e){
                e.preventDefault();
                username = $("#usertext").val();
                start_date = $("#start_date_input").val();
                sd_arr = start_date.split("/");
                end_date = $("#end_date_input").val();
                ed_arr = end_date.split("/");
                console.log("start_date: " + start_date);
                console.log("end date: " + end_date);

                console.log("jaliedate: " + JalaliDate.jalaliToGregorian(sd_arr[0],sd_arr[1],sd_arr[2]));
                console.log("jaliedate: " + JalaliDate.jalaliToGregorian(ed_arr[0],ed_arr[1],ed_arr[2]));
                start_date =  JalaliDate.jalaliToGregorian(sd_arr[0],sd_arr[1],sd_arr[2]);
                end_date = JalaliDate.jalaliToGregorian(ed_arr[0],ed_arr[1],ed_arr[2]);
                
                if(username == ''){
                    alert('provide username');
                    return;
                }
                userlog_details_page = 1;
                get_users_details(username,function(data){
                    console.log(data);
                    $("#users").empty();
                    $("#users").append(render_users_details( data,username));
                });
            });

            //---------------- btnUserdetailsNext----------------------------------------
            $("#users").on('click', '#btnUserdetailNext', function(e){
                e.preventDefault();
                username = $("#btnUserdetailNext").val();
                console.log("username: " + username);
                get_users_details(username,function(data){
                    $("#users").empty();
                    $("#users").append(render_users_details(data, username));
                    
                    userlog_details_page += 1;
                });
            });
            //------------------btnUserdetaiNext------------------------------------------
            $("#users").on('click', '#btnUserdetailPrev', function(e){
                e.preventDefault();
                username = $("#btnUserdetailPrev").val();
                console.log("username: " + username);
                get_users_details(username,function(data){
                    $("#users").empty();
                    $("#users").append(render_users_details(data, username));
                    userlog_details_page -= 1;
                });
            });
            //-------------------------------------------------
            function get_users_details(username,handleData){
                url = user_log_details_url  + username + "/" + userlog_details_page;
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
            //--------------------------------------------------
            function render_users_details(data,username)
            {
                ret = "<div class='table-responsive'>";
                ret += "<table class='table table-striped'>";
                thead = "<thead><tr>";
                tbody = "<tbody>";
                for(var i in data["data"][0]){
                    if(i=="connection_log_id" || i=="username" || i=="bytes_out" || i=="bytes_in" || i=="ippool_assigned_ip" || i =="login_time" || i=="logout_time"){
                    thead += "<th>" + i + "</th>";
                    }
                }
                
                thead += "</tr></thead>";

                $.each(data["data"],function(index,item){
                    tbody +="<tr>";
                    for (var i in item){
                        if (i == "connection_log_id"){
                            tbody += "<td><button class='showweblog btn btn-success' value='" + item[i] + "'>weblogs</button></td>";
                        }else if(i=="connection_log_id" || i=="username" || i=="bytes_out" || i=="bytes_in" || i=="ippool_assigned_ip" || i =="login_time" || i=="logout_time"){
                            tbody += "<td>" +  item[i] + "</td>";
                        }
                    }
                    tbody += "</tr>";
                });

                tbody += "</tbody>";
                ret += thead;
                ret += tbody;
                ret += "</table>";
                ret += "<button id='btnUserdetailPrev' class='btn btn-success' value='" + username +"'>prev</button>";
                ret += "<button id='btnUserdetailNext' class='btn btn-success' value='" + username + "'>next</button>";
                ret += "</div>";
                return ret;
            }
          
            //--------------------------------------------------
            function render_user(data)
            {
                ret = "<div class='table-responsive'>";
                ret += "<table class='table table-striped'>";
                thead = "<thead><tr>";
                tbody = "<tbody>";
                for(var i in data["data"][0]){
                    if(i=="connection_log_id" || i=="username" || i=="bytes_out" || i =="bytes_in" || i == "login_time" || i=="logout_time"|| i=="ippool_assigned_ip"){
                    thead += "<th>" + i + "</th>";
                    }
                }
                
                thead += "</tr></thead>";

                $.each(data["data"],function(index,item){
                    tbody +="<tr>";
                    for (var i in item){
                        if (i == "connection_log_id"){
                            tbody += "<td><button class='showweblog btn btn-success' value='" + item[i] + "'>log</button></td>";
                        }else if(i=="connection_log_id" || i=="username" || i=="bytes_out" || i =="bytes_in" || i == "login_time" || i=="logout_time"|| i=="ippool_assigned_ip"){
                            tbody += "<td>" +  item[i] + "</td>";
                        }
                    }
                    tbody += "</tr>";
                });

                tbody += "</tbody>";
                ret += thead;
                ret += tbody;
                ret += "</table>";
                ret += "<button id='btnPrev' class='btn btn-success'>prev</button>";
                ret += "<button id='btnNext' class='btn btn-success'>next</button>";
                ret += "</div>";
                return ret;
               //       item += '<button class="btnDelete" style="display:none" value="' + post.id + '">delete</button>----';
               //       item += '<button class="btnEdit" style="display:none" value="' + post.id + '">edit</button>';
            }

         
            //----------------------------------------------------------------------
            function render_weblog(data,connection_log_id)
            {
                ret = "<div class='table-responsive'>";
                ret += "<table class='table table-striped'>";
                thead = "<thead><tr>";
                tbody = "<tbody>";
                for(var i in data["data"][0]){
                    thead += "<th>" + i + "</th>";
                }
                
                thead += "</tr></thead>";

                $.each(data["data"],function(index,item){
                    tbody +="<tr>";
                    for (var i in item){
                        // if (i == "connection_log_id"){
                        //     tbody += "<td><button class='showweblog btn btn-success' value='" + item[i] + "'>log</button></td>";
                        // }else{
                            tbody += "<td>" +  item[i] + "</td>";
                        // }
                    }
                    tbody += "</tr>";
                });

                tbody += "</tbody>";
                ret += thead;
                ret += tbody;
                ret += "</table>";
                ret += "<button id='btnWeblogPrev' class='btn btn-success' value='" + connection_log_id  + "'>prev</button>";
                ret += "<button id='btnWeblogNext' class='btn btn-success' value='" + connection_log_id + "'>next</button>";
                ret += "</div>";
                return ret;
            }

        </script>

    </body>
</html>
