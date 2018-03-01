 <script type="text/javascript">
     $(document).on("click" , ".read-notif" , function(){
        var href = $(this).data("href");
        var url = '<?php echo site_url("app/dashboard/read_notif"); ?>';
        var id = $(this).data("id");
        
        $.ajax({
            url : url ,
            data : {id : id},
            method : "POST",
            success : function(){
                window.location.href = href;
            }
        });
     });
 </script>
 <nav class="navbar navbar-default navbar-fixed-top navbar-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-expand-toggle">
                <i class="fa fa-bars icon"></i>
            </button>
            <ol class="breadcrumb navbar-breadcrumb">
                <li class="active"><?php echo $page_name; ?></li>
            </ol>

            <button type="button" class="navbar-right-expand-toggle pull-right visible-xs">
                <i class="fa fa-th icon"></i>
            </button>
        </div>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="<?php echo site_url(); ?>">Go To Customer Panel</a></li>
            <button type="button" class="navbar-right-expand-toggle pull-right visible-xs">
                <i class="fa fa-times icon"></i>
            </button>
            <li class="dropdown danger">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-comment"></i> <?php echo count($notification_list); ?></a>
                <ul class="dropdown-menu danger  animated fadeInDown" style="width: 450px;">
                    <li class="title">
                        Notification
                    </li>
                    <li>
                        <ul class="list-group notifications">
                            <?php if($notification_list) : ?>
                                <?php foreach($notification_list as $row) : ?>
                                    <a href="javascript:void(0);" data-href="<?php echo $row->url; ?>" data-id="<?php echo $row->id; ?>" class="read-notif">
                                        <li class="list-group-item">
                                            <i class="fa <?php echo $row->icon; ?> icon"></i> <?php echo $row->sender->name.' '.$row->reference; ?>
                                        </li>
                                    </a>
                                <?php endforeach; ?>
                                <a href="javascript:void(0);">
                                    <li class="list-group-item message">
                                        view all
                                    </li>
                                </a>
                            <?php else : ?>
                                <a href="javascript:void(0);">
                                    <li class="list-group-item message">
                                         No new notification
                                    </li>
                                </a>
                            <?php endif; ?>
                        </ul>
                    </li>
                </ul>
            </li>
            <li class="dropdown profile">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?php echo $session_data->name; ?> <span class="caret"></span></a>
                <ul class="dropdown-menu animated fadeInDown">
                    <li class="profile-img">
                        <img src="<?php echo site_url("thumbs/images/user/".$session_data->image_path.'/300/300/'.$session_data->image_name); ?>" class="profile-img">
                    </li>
                    <li>
                        <div class="profile-info">
                            <h4 class="username"><?php echo $session_data->name; ?></h4>
                            <p><?php echo $session_data->email; ?></p>
                            <div class="btn-group margin-bottom-2x" role="group">
                                <button type="button" class="btn btn-default"><i class="fa fa-user"></i> Profile</button>
                                <a href="<?php echo site_url('Login/logout'); ?>"  class="btn btn-default" ><i class="fa fa-sign-out"></i> Logout </a>
                            </div>
                        </div>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>