<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Blog Post - Start Bootstrap Template</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/blog-post.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Start Bootstrap</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="#">About</a>
                    </li>
                    <li>
                        <a href="#">Services</a>
                    </li>
                    <li>
                        <a href="#">Contact</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Post Content Column -->
            <div class="col-lg-8">

                <!-- Blog Post -->
            <?php 

                include "includes/db.php";
                include "includes/header.php";

            ?>
                <!-- Navigation -->

                <?php include "includes/navigation.php"; ?>

                <!-- Page Content -->
                <div class="container">

                    <div class="row">

                <!-- Blog Entries Column -->
                        <div class="col-md-8">

                        <?php 
                            
                        if(isset($_GET['p_id'])) {
                            
                            $the_post_id = $_GET['p_id'];
                            
                       $view_query = "UPDATE posts SET post_views_count = post_views_count + 1 WHERE post_id = '{$the_post_id}' ";
                        $send_query = mysqli_query($connection, $view_query);
                        
                            if(!$send_query) {
                                
                                die("QUERY FAILED");
                            }
                            
                            if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin' ) {
                            
                                $query = "SELECT * FROM posts WHERE post_id = '{$the_post_id}' ";
                                
                            } else {
                                
                                $query = "SELECT * FROM posts WHERE post_id = '{$the_post_id}' AND post_status = 'published' ";                             
                            }
                            
                        $select_all_posts_query = mysqli_query($connection, $query);     
                            
                            if(mysqli_num_rows($select_all_posts_query) < 1) {
                        
                        echo "<h1 class='text-center'>NO POST AVAILABLE</h1>";                        
                                
                    } else {
                                
                        while ($row = mysqli_fetch_assoc($select_all_posts_query)) {

                        $post_title = $row['post_title'];
                        $post_user = $row['post_user'];
                        $post_date = $row['post_date'];
                        $post_image = $row['post_image'];
                        $post_content = $row['post_content'];

                        ?>    

                        <h1 class="page-header">Posts</h1>

                        <!-- First Blog Post -->
                        <h2>
                        <a href="#"><?php echo $post_title; ?></a>
                        </h2>
                        <p>
                        by <a href="index.php"><?php echo $post_user; ?></a>
                        </p>
                        <p><span class="glyphicon glyphicon-time"></span> <?php echo $post_date; ?></p>
                        <hr>
                        <img class="img-responsive" src=images/<?php echo $post_image; ?> alt="">
                        <hr>
                        <p><?php echo $post_content; ?></p>
                       

                        <hr>

                        <?php } ?>
                        
                        <?php
                    
                        if(isset($_POST['create_comment'])) {
                                           
                        $the_post_id = $_GET['p_id'];
                    
                        $comment_author = $_POST['comment_author'];
                        $comment_email = $_POST['comment_email'];
                        $comment_content = $_POST['comment_content'];
                        
                        if(!empty($comment_author) && !empty($comment_email) && !empty($comment_content)) {
                    
                        $query = "INSERT INTO comments (comment_post_id, comment_author, comment_email, comment_content, comment_status, comment_date)";
                        $query .= "VALUES ($the_post_id, '{$comment_author}', '{$comment_email}', '{$comment_content}', 'unapproved', now())";

                        $create_comment_query = mysqli_query($connection,$query);
                        
                        if(!$create_comment_query) {
                            
                            die('QUERY FAILED' . mysqli_error($connection));
                        }
//                        
//                        $query = "UPDATE posts SET post_comment_count = post_comment_count + 1 ";
//                        $query .= "WHERE post_id = '{$the_post_id}' ";
//                        $update_comment_count = mysqli_query($connection,$query);
//                        
                    } else {
                            
                            echo "<script>alert('Fields cannot be empty')</script>";
                        }
                }
                    ?>

                    <!-- Comments Form -->
                    <div class="well">
                        <h4>Leave a Comment:</h4>
                        <form action="" method="post" role="form">
                           <div class="form-group">
                               <label for="Author">Author</label>
                                <input type="text" class="form-control" name="comment_author">
                            </div>

                            <div class="form-group">
                               <label for="email">Email</label>
                                <input type="email" class="form-control" name="comment_email">
                            </div>

                            <div class="form-group">
                               <labe for="comment">Your Comment</labe>
                                <textarea class="form-control" rows="3" name="comment_content"></textarea>
                            </div>

                            <button type="submit" name="create_comment" class="btn btn-primary">Submit</button>
                        </form>
                    </div>

                   <!-- Posted Comments -->

                   <?php

                   $query = "SELECT * FROM comments WHERE comment_post_id = '{$the_post_id}' ";
                   $query .= "AND comment_status = 'approved' ";
                    $query .= "ORDER BY comment_id DESC ";
                        $select_comment_query = mysqli_query($connection, $query);
                        if(!$select_comment_query) {

                            die('QUERY FAILED' . mysqli_error(connection));
                        }
                        while ($row = mysqli_fetch_array($select_comment_query)) {

                            $comment_date = $row['comment_date'];
                            $comment_content = $row['comment_content'];
                            $comment_author = $row['comment_author'];

                        ?>
                    
         <!-- Comment -->
                <div class="media">
                    <a class="pull-left" href="#">
                        <img class="media-object" src="http://placehold.it/64x64" alt="">
                    </a>
                    <div class="media-body">
                        <h4 class="media-heading"><?php echo $comment_author; ?>
                            <small><?php echo $comment_date; ?></small>
                        </h4>
                        <?php echo $comment_content; ?>
                    </div>
                </div>
            
            <?php } } } else {
                                                        
            header("Location: index.php");
                                                
             } ?>

                    </div>

                    <?php include "includes/sidebar.php"; ?>

                </div>
                    <!-- /.row -->

<!--            <hr>-->

                <?php include "includes/footer.php"; ?>
                 <!-- Blog Comments -->
   
            </div>

            <!-- Blog Sidebar Widgets Column -->
            <?php include "includes/sidebar.php"; ?>

        </div>
        <!-- /.row -->

    </div>
    <!-- /.container -->

    <!-- jQuery -->
<!--    <script src="js/jquery.js"></script>-->

    <!-- Bootstrap Core JavaScript -->
<!--    <script src="js/bootstrap.min.js"></script>-->

</body>

</html>
