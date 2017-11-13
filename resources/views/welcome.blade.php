<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>GitHub Followers</title>

        <!-- Styles -->
        <link rel="stylesheet" href="/css/bootstrap.min.css" />
        <link rel="stylesheet" href="/css/custom.css" />
    </head>
    <body>

        <div class="container">
            <div class="header clearfix">
                <h3 class="text-muted">GitHub Followers</h3>
            </div>

            <div class="jumbotron">
                <h1 class="display-4">Find Github Users</h1>
                <p class="lead">This application can search Github users by their username. If the username matches, it shows followers count, followers and a button to load more followers.</p>
                <div class="form-inline">
                    <div class="form-group">
                        <label for="username">Enter username:</label>
                        <input type="text" class="form-control" id="username">
                    </div>
                    <button type="button" id="search-btn" class="btn btn-default btn-primary">Search</button>
                    
                    <div class="loader"></div> 
                
                </div> 
            </div>

            <div class="row">
                
                <div class="col-lg-12" id="user-info">
                    
                </div>
            </div>

            <div class="row" id="followers"></div>
            <div class="row text-center">
                <input style="display: none;" id="load-btn" type="button" style="margin: 0 auto 30px;" value="Load more" class="btn btn-default" />
            </div>


            <footer class="footer">
                <p>&copy; Company 2017</p>
            </footer>

        </div>

        <script src="/js/jquery-3.2.1.min.js"></script>
        <script>
            
            let username = "";
            let current_page = 1;
            
    $(document).ready(function () {
        $("#search-btn").click(function (e) {
            e.preventDefault();

            username = $("#username").val();
                    $.get("/github/user/", {
                    username: username
                    }, function (response) {
                        
                        if (response.user_info){
                        let html = "<h4><a target='_blank' href='" +
                                response.user_info.html_url + "'>" + response.user_info.login +
                                "</a></h4><p>Followers# " + response.user_info.followers + "</p>";
                        $("#user-info").empty().html(html);

                        let followers_html = getFollowersHtml(response.followers);

                        $("#followers").html(followers_html);
                        if(response.followers.length>0)
                            $("#load-btn").show();
                    } else{
                        $("#followers").empty();
                        $("#load-btn").hide();
                        $("#user-info").html("<div class=\"alert alert-danger\">"+
                                "<strong>Error!</strong> "+response.error+"</div>");
                    }
                });
    });

    
    $("#load-btn").click(function (){
        current_page +=1;

        $.get("/github/followers/", {
            page: current_page,
            username:username
        }, function (response) {
            if(response.length >0){
                let followers_html = getFollowersHtml(response);
                $("#followers").append(followers_html);
            }else{
                $("#user-info").html("<div class=\"alert alert-info\">"+
                                "<strong>Notice!</strong> No more followers to show.</div>");
                $("#load-btn").hide();
            }
        });
    });

    function getFollowersHtml(followers){
        let followers_html = '';
        $.each(followers, function (i) {
            followers_html += "<div class='col-md-2'><a target='_blank' href='" +
                            followers[i].html_url + "'><img src='" + followers[i].avatar_url + "' /></a></div>";
        });
        return followers_html;
    }
});
        </script>
    </body>
</html>
