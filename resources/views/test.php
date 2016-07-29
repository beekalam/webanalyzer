<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Slim 3</title>
        <!-- <link href='//fonts.googleapis.com/css?family=Lato:300' rel='stylesheet' type='text/css'> -->
        <link href="/css/bootstrap.min.css" rel="stylesheet">
        <!-- <link href="assets/css/custom.css" rel="stylesheet"> -->

        <script src="/js/jquery-1.12.4.js"></script>
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
                <input type="text" placeholder="username" id="username" class="form-control">
                </div>
                <div class="form-group">
                <input type="password" placeholder="password" id="password" class="form-control">
                </div>
                <input type="submit" id="login" class="btn btn-success">
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
        
        <input type="button" id="showusers" value="show users"/>
        <div id="users">
        </div>
        </div>

        <script src="/js/bootstrap.min.js"></script>
        <script>
            //todo : make urls for weblogs logdetails and logs here
            base_url = 'http://localhost:8000/';
            user_log_url = base_url + 'logs/';
            weblogs_url = base_url + 'weblogs/';
            page = 1;
            $('#showusers').click(function(e){
                "ues strict";
                $('#users').empty();
                e.preventDefault();
                // data = get_user_log();
                get_user_log(function(data){
                    $("#users").append(render_user(data));
                    page += 1;
                });
                // $("#users").append(render_user(data));
                // page += 1;
                // url = base_url + "logs/" + page;
                // $.ajax({
                //     url: url,
                //     type: 'GET',
                //     contentType:'application/json',
                //     dataType:'json',
                //     success:function(data){
                //         $("#users").append(render_user(data));
                //     }
                // });
            });
            //-------------------------------------------------
            function get_user_log(handleData){
                url = user_log_url + page;
                $.ajax({
                    url: url,
                    type: 'GET',
                    contentType:'application/json',
                    dataType:'json',
                    success:function(data){
                        handleData(data);
                    }
                });
            }

            function get_web_log(connection_log_id,handleData){
                url = weblogs_url + connection_log_id + "/" + page;
                $.ajax({
                    url: url,
                    type: 'GET',
                    contentType: 'application/json',
                    dataType:'json',
                    success:function(data){
                        handleData(data);
                    }
                });
            }
            //--------------------------------------------------
            function render_user(data)
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
                        if (i == "connection_log_id"){
                            tbody += "<td><button class='showweblog btn btn-success' value='" + item[i] + "'>log</button></td>";
                        }else{
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
            //---------------------btnNext---------------------------------------
            $('#users').on('click', '#btnNext', function(e){
                e.preventDefault();
                get_user_log(function(data){
                    $("#users").empty();
                    $("#users").append(render_user(data));
                    page += 1;
                });
            });
            //-------------------btnPrev------------------------------------------
            $("#users").on('click','#btnPrev', function(e){
                e.preventDefault();
                get_user_log(function(data){
                    $("#users").empty();
                    $("#users").append(render_user(data));
                    page -= 1;
                });
            });
            //--------------------showweblog---------------------------------------
            $("#users").on('click', '.showweblog',function(e){
                e.preventDefault();
                $("#users").empty();
                connection_log_id = e.target.value;
                page = 1;
                get_web_log(connection_log_id, function(data){
                    $("#users").append(render_weblog(data,connection_log_id));
                    page += 1;
                });
            });
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
            //---------------------btnWeblogNext---------------------------------------
            $('#users').on('click', '#btnWeblogNext', function(e){
                e.preventDefault();
                connection_log_id = $("#btnWeblogNext").val(); 
                get_web_log(connection_log_id, function(data){
                    $("#users").empty();
                    $("#users").append(render_weblog(data, connection_log_id));
                    page += 1;
                });
            });
            //-------------------btnWeblogPrev------------------------------------------
            $("#users").on('click','#btnWeblogPrev', function(e){
                e.preventDefault();
                connection_log_id = $("#btnWeblogPrev").val();
                get_web_log(connection_log_id, function(data){
                    $("#users").empty();
                    $("#users").append(render_weblog(data,connection_log_id));
                    page -= 1;
                });
            });

        </script>
       
    </body>
</html>
