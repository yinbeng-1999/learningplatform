<? session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/picture/sem.ico" type="image/x-icon">

    <!--環境-->
    <link rel="stylesheet" href="/javascript/bootstrap.min.css">
    <link rel="stylesheet" href="/javascript/bootstrap-icons.css">
    <script src="/javascript/jquery.min.js" charset="utf-8"></script>
    <script src="/javascript/popper.min.js"></script>
    <script src="/javascript/bootstrap.min.js"></script>
    <script src="/javascript/boxicons.js"></script>
    <link rel="stylesheet" href="/css/navbar.css">
    <link rel="stylesheet" type="text/css" href="/javascript/dataTables.bootstrap4.min.css" />
    <script src="/javascript/jquery.dataTables.min.js"></script>
    <script src="/javascript/dataTables.bootstrap4.min.js"></script>
    <script src="/javascript/chart.min.js"></script>

</head>

<body>
    <header class="navbar navbar-expand-md navbar-dark bg-dark " role="navigation">
        <a class="navbar-brand" href="/sem_intro/data/<?php echo $_SESSION['id']; ?>">
            <span class="h3 mx-1">Learning Platform</span>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto" name="navbarTab">
            </ul>
            <span id="connectCount"> </span>
            <li class="nav-item dropdown no-arrow">
                <a class="nav-link" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="form-inline">
                        <img id="profile_picture" class="img-profile rounded-circle" src="123" width="45" height="45" loading="lazy">
                        <span class="ml-2 name">
                            <div id='login_name'></div>
                        </span>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                    <a class="dropdown-item text-center" href="/">
                        登出
                    </a>
                </div>
            </li>
        </div>
    </header>
</body>

<script>
    let picture = "";

    const originalSetItem = localStorage.setItem;

    localStorage.setItem = function(key, value) {
        const event = new Event("itemInserted");

        event.key = key;
        event.value = value;

        document.dispatchEvent(event);
        originalSetItem.apply(this, arguments);
    }

    document.addEventListener("itemInserted", function() {
        setTimeout(function() {
            document.getElementById("connectCount").firstChild.nodeValue = "線上人數 : " + localStorage.getItem("connectCount");
        }, 500)
    }, true);

    $(document).ready(function() {
        getUserName();
        getUserRolePermission();
        let current = location.pathname.substring(0, 5);
        $("[name='mainnav'].nav-link").each(function() {
            let address = $(this).attr("href").substring(0, 5);
            if (current == address) {
                $(this).addClass("active")
            }
        })
        $("#profile_picture").attr("src", picture);
    });
    // 取得使用者權限
    function getUserRolePermission() {
        $.ajax({
            url: "/userRole/getRolePermission",
            type: "GET",
            async: false,
            data: {
                user_id: <?php echo $_SESSION['id']; ?>
            },
            success: function(response) {
                $("[name='navbarTab']").empty();
                $(response).each(function() {
                    $("[name='navbarTab']").append(`
                            <li class="nav-item">
                                <a class="nav-link" name="mainnav" href="${this.router}${this.permission_id}">${this.permission_name}<span class="sr-only"></span></a>
                            </li>
                        `);
                });
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest.status);
            }
        });
    };
    // 取得使用者名稱
    function getUserName() {
        $.ajax({
            url: "/billboard/getUserData",
            type: "GET",
            async: false,
            success: function(response) {
                $(response).each(function() {
                    $("#login_name").html(`${this.user_name}`);
                    if (this.picture != null) {
                        picture = `/picture/${this.picture}`;
                    } else {
                        picture = `/picture/admin.jpeg`;
                    }
                });
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest.status);
            }
        });
    };
    // 取得年份
    function getYear() {
        $.ajax({
            url: '/mainpage/getYear',
            type: 'get',
            dataType: 'json',
            async: false,
            success: function(response) {
                $("select[name='year']").empty();
                option = "";
                $(response).each(function() {
                    if (this.name != null) {
                        option += `<option value="${this.year_id}">${this.name}</option>`;
                    }
                });
                $("select[name='year']").append(option);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest.status);
            }
        });
    };
    // 取得學期
    function getSemester() {
        $.ajax({
            url: '/mainpage/getSemester',
            type: 'get',
            dataType: 'json',
            async: false,
            success: function(response) {
                $("select[name='semester']").empty();
                option = "";
                $(response).each(function() {
                    if (this.name != null) {
                        option += `<option value="${this.semester_id}">${this.name}</option>`;
                    }
                });
                $("select[name='semester']").append(option);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest.status);
            }
        });
    };
    // 取得使用者資料
    function getUserData() {
        $.ajax({
            url: "/billboard/getUserData",
            type: "GET",
            async: false,
            success: function(response) {
                $(response).each(function() {
                    if (this.picture != null) {
                        $(".left-intro").html(`<img src="/picture/${this.picture}">`);
                    } else {
                        $(".left-intro").html(`<img src="/picture/admin.jpeg">`);
                    }
                    $(".right-intro").html(`
                        <p>姓名：${this.user_name || '--'} 性別：${this.gender || '--'}</p>
                        <p>系級：${this.major || '--'} ${this.grade || '--'}年級</p>
                        <p>學號：${this.user_number || '--'}</p>
                        <p>生日：${this.birthday || '--'}</p>
                        <p>行動電話：${this.telephone || '--'}</p>
                        <p class="normal">通訊地址：${this.address || '--'}</p>
                        <p>Email：${this.email || '--'}</p>
                    `);
                    person_data = this;
                });
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest.status);
            }
        });
    };
</script>

</html>