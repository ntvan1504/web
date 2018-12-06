<?php
$user = new Apps_Libs_UserIdentity();
$router = new Apps_Libs_Router();
$u = new Apps_Models_Users();

$id = intval($router->getGET("id")); //$id là id lấy được bằng phương thức GET từ url và phải có kiểu dữ liệu số nguyên


if ($id) {
    $uDetail = $u->buildQueryParams([
        "where"=>"id=$id",
        ])->selectOne();
    if(!$uDetail){
        $router->pageNotFound();
    }

}else{
    $uDetail = [
        "id"=>"",
        "username"=> "",
        "password"=> ""
    ];
}    

if ($router->getPOST("submit") 
        && $router->getPOST("name")
        && $router->getPOST("password")){ 
    //nếu đã bấm submit và có giá trị name
//    $params = [
//        ":name" => $router->getPOST("name"),
//        ":id" => $router->getGET("id")
//    ];
        $name = $router->getPOST("name");
        $pass = $router->getPOST("password");
    
    $result = FALSE;
    
    if ($id){
//        $params[":id"]=$id;
//        var_dump($params);die;
        $result = $u->buildQueryParams([
            "value"=>"username='$name'",
            "where"=>"id=$id",
//            "params"=>$params
        ])->update(); //nếu có id thì tiến hành update
    } else {
        $result = $u->buildQueryParams([
            "field"=>"(username,password,created_time) VALUES (?,?,now())", //created_time là thời gian hiện tại. //trong ngoặc có bao nhiêu phần tử thì phải có bấy nhiêu dấu hỏi
            "value"=>[$name,$pass] //name là name lấy từ phương thức POST, created_by là userId lấy được bằng hàm getId
        ])->insert(); //nếu không có id thì tiến hành insert
    }
    
    if ($result){
        $router->redirect("users/index");
    }else{
        $router->pageError("Cannot update database");
    }
}
?>


<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Admin Users - Đồ ăn thức uống</title>

        <!-- Bootstrap Core CSS -->
        <link href="../startbootstrap-sb-admin-2-gh-pages/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

        <!-- MetisMenu CSS -->
        <link href="../startbootstrap-sb-admin-2-gh-pages/vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

        <!-- Custom CSS -->
        <link href="../startbootstrap-sb-admin-2-gh-pages/dist/css/sb-admin-2.css" rel="stylesheet">

        <!-- Custom Fonts -->
        <link href="../startbootstrap-sb-admin-2-gh-pages/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

    </head>

    <body>

        <div id="wrapper">

            <!-- Navigation -->
            <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">Welcome to Admin Page <?php echo $user->getSESSION("username") ?></a>
                </div>
                <!-- /.navbar-header -->

                <ul class="nav navbar-top-links navbar-left">
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="<?= $router->createUrl("logout") ?>"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                            </li>
                        </ul>
                        <!-- /.dropdown-user -->
                    </li>
                    <!-- /.dropdown -->
                </ul>
                <!-- /.navbar-top-links -->

                <div class="navbar-default sidebar" role="navigation">
                    <div class="sidebar-nav navbar-collapse">
                        <ul class="nav" id="side-menu">
                            <li>
                                <a href="<?= $router->createUrl('posts/index') ?>"><i class="fa fa-files-o fa-fw"></i> Manage Posts</a>
                            </li>
                            <li>
                                <a href="<?= $router->createUrl('categories/index') ?>"><i class="fa fa-sitemap fa-fw"></i> Manage Categories</a>
                            </li>
                            <li>
                                <a href="<?= $router->createUrl('users/index') ?>"><i class="fa fa-user fa-fw"></i> Manage Users</a>
                            </li>
                        </ul>
                    </div>
                    <!-- /.sidebar-collapse -->
                </div>
                <!-- /.navbar-static-side -->
            </nav>

            <div id="page-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header"><?= !$id ? "Tạo mới " : "Xem nội dung " ?> tài khoản Admin <?=$uDetail ["username"] ?></h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Nội dung
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-6" class="show-data">
                                        <form role="form" action="<?php echo $router->createUrl('users/detail',["id" => $uDetail["id"]]) ?>" method="POST">
                                            <div class="form-group">
                                                <label>Account ID</label>
                                                <input class="form-control" placeholder="Tên chuyên mục" type="text" name="name" value="<?= $uDetail["username"] ?>">
                                            </div>
                                            <div class="form-group">
                                                <label>Password</label>
                                                <input class="form-control" placeholder="Tên chuyên mục" type="password" name="password" value="<?= $uDetail["password"] ?>">
                                            </div>
                                            <input class="btn btn-info" type="submit" name="submit" value="Post">
                                            <input class="btn btn-info" onclick="window.location.href = '<?= $router->createUrl("users/index") ?>'" type="button" value="Cancel">
                                        </form>
                                    </div>
                                    <!-- /.col-lg-6 (nested) -->

                                </div>
                                <!-- /.row (nested) -->
                            </div>
                            <!-- /.panel-body -->
                        </div>
                        <!-- /.panel -->
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /#page-wrapper -->

        </div>
        <!-- /#wrapper -->

        <!-- jQuery -->
        <script src="../startbootstrap-sb-admin-2-gh-pages/vendor/jquery/jquery.min.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="../startbootstrap-sb-admin-2-gh-pages/vendor/bootstrap/js/bootstrap.min.js"></script>

        <!-- Metis Menu Plugin JavaScript -->
        <script src="../startbootstrap-sb-admin-2-gh-pages/vendor/metisMenu/metisMenu.min.js"></script>

        <!-- Custom Theme JavaScript -->
        <script src="../startbootstrap-sb-admin-2-gh-pages/dist/js/sb-admin-2.js"></script>

    </body>

</html>