<style type="text/css">
    .dashboard-stat.red {
        background-color: #e7505a !important;
    }
    .dashboard-stat.green {
        background-color: #32c5d2 !important;
    }
    /*.slimScrollDiv {
            width: 100% !important;
    }
    .feeds li .col1>.cont>.cont-col2>.desc {
            margin-left: 0px !important;
    }*/

    .dashboard-page .portlet.light span {
        color: #fff;
    }
    .txtComments {
        width: 100%;
        min-height: 58px;
        border: 0px;
        background: none;
    }
    .table-events.table-head-fix{}
    .table-events.table-head-fix .table-head-fix-body{ overflow-y:auto; height:350px;}
	
</style>

<?php if(Yii::$app->controller->id == 'site' && Yii::$app->controller->action->id == 'index') { ?>
    <script src="<?php echo Yii::getAlias('@web'); ?>/themes/metronic/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<?php } ?>
                                <?php
//$this->registerJsFile(yii::getAlias('@web/themes/metronic/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'),['position' => \yii\web\View::POS_HEAD]);
                                ?>

<?php
/* @var $this yii\web\View */

use yii\helpers\Url;

$this->title = 'Dashboard & Statistics ';
	$phpdateformat = Yii::getAlias('@phpdateformat');
?>

<div class="row">
    <ul class="notice-mailbox"><li>
            <div class='dropdown '>
                <button class='dropdown-toggle EmailNotifications notice-btn' type="button" data-toggle="dropdown">
                    <div class="alert-msgcnt">
                        <i class="icon-envelope-open mail-btnicon"></i>
                        <span class="badge badge-danger emailsCount"> <?php echo $email_notif_count; ?> </span>
                    </div>
                </button>
<?php if ($email_notif_count > 0) {
    ?>
                    <div class="dropdown-menu nitif-dropdown">
                        <div class="external-views">
                            <div class="msg-info"><h3>You have <span class="bold" id="email_msg_count"><?php echo $email_notif_count; ?></span> New Messages</h3></div><div class="views-info"><a href="<?php echo Url::to(['communique/inbox-mails']); ?>">view all</a></div>
                        </div>
                        <div class="scroller" style="height:200px;" data-always-visible="1" data-rail-visible="0">
                            <ul class="nitification"></ul>
                        </div></div>
                <?php
                } else {
                    ?>
                    <div class="dropdown-menu nitif-dropdown mb-notice">
                        <div class="external-views">
                            <div class="msg-info"><h3>You have <span class="bold">0  New</span> Messages</h3></div><div class="views-info"><a href="<?php echo Url::to(['communique/inbox-mails']); ?>">view all</a></div>
                        </div>
                        <div class="scroller" style="height: 100px;" data-always-visible="1" data-rail-visible="0">
                            <ul class="nitification_no_msg"><li> No New  messages</li></ul></div></div>
<?php } ?>
            </div></li>

        <!-- for comment and notification -->
        <li>
            <div class='dropdown'>
                <button class='dropdown-toggle notification_lc notice-btn' type="button" data-toggle="dropdown">
                    <div class="alert-msgcnt">
                        <i class="icon-bell mail-btnicon"></i>
                        <span class="badge badge-danger clr-org notificationsCount"> <?php echo ($project_likes_notif + $project_comment_notif); ?> </span></div>
                </button>
                <?php if ($project_likes_notif > 0 || $project_comment_notif > 0) {
                    ?>
                    <div class="dropdown-menu nitif-dropdown">
                        <div class="external-views"></h3>
                        <div class="msg-info"><h3>You have <span class="bold" id="notification_msg_count"><?php echo ($project_likes_notif + $project_comment_notif); ?> </span> New</h3> Messages</div><div class="views-info"><!--<a href="<?php echo Url::to(['/communique/inbox-mails']); ?>">view a</h3>ll</a>--></div>
                        </div>
                        <div class="scroller" style="height: 300px;" data-always-visible="1" data-rail-visible="0">
                            <ul class="nitification_lc_app"></ul></div></div>
                <?php
                } else {
                    ?>
                    <div class="dropdown-menu nitif-dropdown">
                        <div class="external-views">
                        <div class="msg-info"><h3>You have <span class="bold">0  New</span> Messages</h3></div><div class="views-info"><!--<a href="<?php echo Url::to(['site/</h3>index']); ?>"></h3>view all</a>--></div>
                        </div>
                        <div class="scroller" style="height: 300px;" data-always-visible="1" data-rail-visible="0">
                            <ul class="nitification_lc_app"> <li>No messages</li></ul></div></div>
                <?php } ?>

            </div></li></ul>
</div>

<div class="dashboard-page">
    <h3 class="page-title"> Dashboard & Statistics
      
    </h3>
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat blue">
                <div class="visual">
                    <i class="fa fa-comments"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span data-counter="counterup" data-value="1349"><?php echo $totalProjectInitiated; ?></span>
                    </div>
                    <div class="desc"> Projects Initiated </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat red">
                <div class="visual">
                    <i class="fa fa-bar-chart-o"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span data-counter="counterup" data-value="12,5"><?php echo $totalProjectParticipated; ?></span></div>
                    <div class="desc"> Projects Participated </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat green">
                <div class="visual">
                    <i class="fa fa-shopping-cart"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span data-counter="counterup" data-value="549"><?php echo $totalUsers; ?></span>
                    </div>
                    <div class="desc"> Users </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat purple">
                <div class="visual">
                    <i class="fa fa-globe"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span data-counter="counterup" data-value="89"><?php echo $totalParticipants; ?></span> </div>
                    <div class="desc"> Participants </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-sm-6">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-equalizer font-yellow"></i>
                        <span class="caption-subject font-yellow bold uppercase">Project Statistics</span>
                    </div>
                </div>
                <div class="portlet-body1">
                    <div class="scroller" style="height: 200px;" data-always-visible="1" data-rail-visible="0">
                        <ul class="feeds">
                            <li>
                                <div class="col1">
                                    <div class="cont">
                                        <div class="cont-col1">
                                            <div class="label label-sm label-info"><i class="fa fa-folder-open"></i></div>
                                        </div>
                                        <div class="cont-col2">
                                            <div class="desc"> Total EquiPPP Projects</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col2">
                                    <div class="date"> <span class="label label-sm label-info"><?php echo $totalProjects; ?></span> </div>
                                </div>
                            </li>
                            <!-- <li>
                                <div class="col1">
                                    <div class="cont">
                                        <div class="cont-col1">
                                            <div class="label label-sm label-success"><i class="fa fa-car"></i></div>
                                        </div>
                                        <div class="cont-col2">
                                            <div class="desc"> Total MPs/MLAs Projects </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col2">
                                    <div class="date"> <span class="label label-sm label-success"><?php echo $mpMlaProjects; ?></span> </div>
                                </div>
                            </li> -->
                            <li>
                                <div class="col1">
                                    <div class="cont">
                                        <div class="cont-col1">
                                            <div class="label label-sm label-warning"><i class="fa fa-building"></i></div>
                                        </div>
                                        <div class="cont-col2">
                                            <div class="desc"> Total Companies </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col2">
                                    <div class="date"><span class="label label-sm label-warning"> <?php echo $csrProjects; ?> </span></div>
                                </div>
                            </li>
                            <!-- <li>
                                <div class="col1">
                                    <div class="cont">
                                        <div class="cont-col1">
                                            <div class="label label-sm label-danger"><i class="fa fa-university"></i></div>
                                        </div>
                                        <div class="cont-col2">
                                            <div class="desc"> Total Banks </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col2">
                                    <div class="date"> <span class="label label-sm label-danger"><?php echo $bankProjects; ?></span> </div>
                                </div>
                            </li> -->
                        </ul>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-md-6 col-sm-6">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-share font-blue"></i>
                        <span class="caption-subject font-blue bold uppercase">Recent Activities</span>
                    </div>
                </div>
                <div class="portlet-body1">
                    <div class="scroller" style="height: 200px;" data-always-visible="1" data-rail-visible="0">
                        <ul class="feeds">
                            <?php foreach ($project_recent_activities as $project_recent_activitie) { ?>
                                <li>
                                    <div class="col1" style="width: 80%">
                                        <div class="cont">
                                            <div class="cont-col1">
                                                <div class="label label-sm label-default">
                                                    <?php
                                                    if (empty($project_recent_activitie['comments']) && is_numeric($project_recent_activitie['status']))
                                                        $class = 'fa-heart';
                                                    elseif (!empty($project_recent_activitie['comments']))
                                                        $class = 'fa-comment';
                                                    else
                                                        $class = 'fa-envelope';
                                                    ?>
                                                    <i class="fa <?php echo $class; ?>" style="color: #FFF; margin: 0px;"></i>
                                                </div>
                                            </div>
                                            <div class="cont-col2">
                                                <div class="desc">
                                                    <?php
                                                    if (!empty($project_recent_activitie['comments']))
                                                        echo 'You Commented on <b>' . $project_recent_activitie['project_title'] . '</b> project';
                                                    elseif (empty($project_recent_activitie['comments']) && !is_numeric($project_recent_activitie['status']))
                                                        echo 'You received an email about <b>' . $project_recent_activitie['project_title'] . '</b> project';
                                                    else
                                                        echo 'You liked <b>' . $project_recent_activitie['project_title'] . '</b> project';
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col2" style="width: 20%; float: right;">
                                        <div class="date"> <?php echo date($phpdateformat, strtotime($project_recent_activitie['created_date'])); ?> </div>
                                    </div>
                                </li>
                            <?php } ?>

                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-6 col-sm-6">
            <!-- BEGIN PORTLET-->
            <div class="portlet light calendar bordered evn-cal">
                <div class="portlet-title ">
                    <div class="caption">
                        <i class="icon-calendar font-green-sharp"></i>
                        <span class="caption-subject font-green-sharp bold uppercase">Projects Calendar
                            <span style="font-size: 10px; color: #000000;">Click on date to view scheduled projects</span>
                        </span>
                    </div>
                </div>
                <div class="portlet-body1">
                    <div class="scroller" style="height: 350px;" data-always-visible="1" data-rail-visible="0">
                        <div id="calendar">
                            <div style="width: 100%">

                                <link rel="stylesheet" type="text/css" href="//zabuto.com/assets/css/style.css">
                                <link rel="stylesheet" type="text/css" href="//zabuto.com/assets/css/examples.css">

                                

                                <!-- Zabuto Calendar -->
                                <script src="<?php echo Yii::$app->urlManagerFrontend->baseUrl ?>/../js/zabuto_calendar.min.js"></script>
                                <link rel="stylesheet" type="text/css" href="<?php echo Yii::$app->urlManagerFrontend->baseUrl ?>/../css/zabuto_calendar.min.css">

                                <div id="datepicker"></div>

                                <style>
                                    .table-condensed {
                                        width: 480px;
                                        height: 330px;
                                    }
                                </style>
                                <script>
//                                    $( "#datepicker" ).datepicker({
//                                        gotoCurrent: true
//                                    });
                                        $(function () {
                                            var today = new Date();
                                                $('#datepicker').datepicker({
                                                format: 'dd/mm/yyyy',
                                                autoclose: true,
                                                forceParse: false,
                                                Default: true,
                                                pickDate: true,
                                                todayHighlight: true,
                                                //initialDate: new Date('15/10/2016'),
                                                initialDate: today.getDate() + "/" + today.getMonth() + "/" + today.getFullYear(),
                                            }).on('changeDate', function(e) {
                                                //alert(e.format(0,"yyyy-mm-dd"));
                                                $.ajax({
                                                    url: "<?php echo Yii::$app->request->BaseUrl; ?>/projects/listofprojects",
                                                    type: 'get',
                                                    data: {'selDate': e.format(0, "yyyy-mm-dd")},
                                                    success: function (data) {
                                                    //alert(data);
                                                    $('#viewProjects').attr('style', 'opacity: 1 !important; padding-top: 140px; background: rgba(0, 0, 0, 0.64);');
                                                        $('#viewProjects').show();
                                                        $('.events-titles').html('List of scheduled projects on '+e.format(0, "dd-mm-yyyy"));
                                                        $('.divProjectsList').html(data);
                                                    }
                                                });
                                            }).on('mouseover', function(e) {
                                                //alert(e.format(0, "yyyy-mm-dd"));
                                                $(this).attr('title', 'Click to view all the scheduled projects');
                                            });
                                        });                                </script>

                                <div class="modal fade dismissModal" id="viewProjects" role="dialog">
                                    <div class="modal-dialog">

                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close update-close" style="opacity:1" data-dismiss="dismissModal" aria-label="Close" onclick="$('.dismissModal').hide();">
                                                    <!--<span aria-hidden="true">&times;</span>-->
                                                </button>
                                                <span class="title-1 events-titles " style="text-transform: capitalize !important;">Projects List</span>
                                            </div>
                                            <div class="modal-body divProjectsList event-popup" style="padding: 0px 10px; height: 400px;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END PORTLET-->
        </div>

        <div class="col-md-6 col-sm-6">
            <div class="portlet light bordered">
                <div class="portlet-title tabbable-line">
                    <div class="caption">
                        <i class="icon-bubbles font-red"></i>
                        <span class="caption-subject font-red bold uppercase">Comments</span>
                    </div>
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#pendingComments" data-toggle="tab" onclick="javascript: displayComments('2')"> Pending </a>
                        </li>
                        <li>
                            <a href="#approvedComments" data-toggle="tab" onclick="javascript: displayComments('7')"> Approved </a>
                        </li>
                        <li>
                            <a href="#rejectedComments" data-toggle="tab" onclick="javascript: displayComments('8')"> Rejected </a>
                        </li>
                    </ul>
                    <input type="hidden" value="<?php echo Yii::$app->request->BaseUrl; ?>/site/displaycomments" id="commentsUrl">
                </div>
                <div class="portlet-body1">
                    <div class="scroller" style="height: 350px;" data-always-visible="1" data-rail-visible="0">
                        <div class="tab-content dash-bodcomment">
                            <div class="tab-pane active" id="pendingComments">
                                <!-- BEGIN: Comments -->
                                <div class="mt-comments pendingComments">
                                    <?php
                                    //echo '<pre>'; print_r($comments); echo '</pre>';
                                    if(count($comments) > 0) {
                                    foreach ($comments as $comment) {
                                        ?>
                                        <div class="mt-comment commet-info" id="divComment_<?php echo $comment['project_comment_id'] ?>">
                                            <div class="mt-comment-img">
                                                <?php
                                                if (!empty($comment['user_image']))
                                                    $userImageUrl = 'https://s3.ap-south-1.amazonaws.com/'. Yii::getAlias('@bucket') .  '/uploads/profile_images/' . $comment['user_ref_id'] . '/' . $comment['user_image'];
                                                else
                                                    $userImageUrl = Yii::$app->urlManagerFrontend->baseUrl . '/../images/avatar.png';
                                                ?>
                                                <img src="<?php echo $userImageUrl; ?>" width="50"> </div>
                                            <div class="mt-comment-body">
                                                <div class="mt-comment-info">
                                                    <span class="mt-comment-author"><?php echo $comment['project_title']; ?></span>
                                                    <span class="mt-comment-date"><?php echo $comment['created_date']; ?></span>
                                                </div>
                                                <!--<div class="mt-comment-text"><?php //echo $comment['comments'];  ?></div>-->
                                                <div class="mt-comment-text">
                                                    <div id='comment_display_<?php echo $comment['project_comment_id'] ?>'><?php echo stripslashes($comment['comments']); ?></div>
                                                    <textarea class='txtComments' id='comment_<?php echo $comment['project_comment_id'] ?>' style="display: none"><?php echo stripslashes($comment['comments']); ?></textarea>
                                                </div>
                                                <div class="mt-comment-details">
                                                    <span class="mt-comment-status mt-comment-status-pending"><?php echo $comment['status_name']; ?></span>
                                                    <ul class="mt-comment-actions">
                                                        <li>
                                                            <a id="btnEdit_<?php echo $comment['project_comment_id'] ?>" class="btnCommentAction commentEdit_<?php echo $comment['project_comment_id'] ?>" onclick="javascript: modifyComment('<?php echo $comment['project_comment_id'] ?>')">Edit</a>
                                                            <a style="display:none" id="btnSave_<?php echo $comment['project_comment_id'] ?>" class="btnCommentAction editComment commentEdit_<?php echo $comment['project_comment_id'] ?>" onClick="editComment()">Save</a>
                                                            <input type="hidden" name="projectId_<?php echo $comment['project_comment_id'] ?>" id="projectId_<?php echo $comment['project_comment_id'] ?>" value="<?php echo $comment['project_comment_id'] ?>" />
                                                        </li>
                                                        <li>
                                                            <a href="#" id="btnView_<?php echo $comment['project_comment_id'] ?>" data-target="#viewComment" onclick="javascript: viewComment1('<?php echo $userImageUrl; ?>', '<?php echo $comment['project_title']; ?>', '<?php echo $comment['created_date']; ?>', '<?php echo $comment['comments']; ?>', '<?php echo $comment['status_name']; ?>', '<?php echo $comment['fname'] . ' ' . $comment['lname']; ?>')" data-toggle="modal">View</a>
                                                        </li>
                                                        <li>
                                                            <a id="btnDelete_<?php echo $comment['project_comment_id'] ?>" class="btnCommentAction commentEdit_<?php echo $comment['project_comment_id'] ?>" onclick="javascript: deleteComment('<?php echo $comment['project_comment_id'] ?>')">Delete</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    <?php }
                                    } else {
                                        echo "<div align='center'>There are no comments to display</div>";
                                    }?>
                                   
                                </div>
                                <div class="tab-pane" id="approvedComments">
                                    <!-- BEGIN: Comments -->
                                    <div class="mt-comments approvedComments"></div>
                                    <!-- END: Comments -->
                                </div>
                                <div class="tab-pane" id="rejectedComments">
                                    <!-- BEGIN: Comments -->
                                    <div class="mt-comments rejectedComments"></div>
                                    <!-- END: Comments -->
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="commentId" name="commentId" value="" />
                    </div>
                </div>
                
                <div class="modal fade" id="viewComment" role="dialog" style="top: 100px;">
                    <div class="modal-dialog" style="width:400px;">

                        <!-- Modal content-->
                        <div class="modal-content" style="width:400px;">
                            <!-- <div class="modal-header">
                                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                     <span aria-hidden="true">&times;</span>
                                 </button>
                                 <span class="title-1" id="project_title"></span>
                             </div> -->
                            <div class="modal-body divCommentsDesc divCommentsDesc-comment" style="display: inline-block;">
                                <button type="button" class="close update-close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <div class="title-1" id="project_title"></div>
                                <div class="userImg"  style="margin-left:0px;" id="divUserImage">
                                    <img id="userImage" src="" /><br>
                                    <div id="username" style="width: 80px;"></div>
                                </div>
                                <div class="userComment" style="padding-left: 40px; "  id="project_comments"></div>
                            </div>
                        </div>
                    </div>
                    <!-- END: Comments -->
                </div>
            </div>

            <!--        <div class="col-md-6 col-sm-6">
                        <div class="portlet light bordered">
                            <div class="portlet-title ">
                                <div class="caption">
                                    <i class="icon-cursor font-purple"></i>
                                    <span class="caption-subject font-purple bold uppercase">Recommended Projects</span>
                                </div>
                            </div>
                            <div class="portlet-body1">
                                <div class="scroller" style="height: 200px;" data-always-visible="1" data-rail-visible="0">
            
                                    <div class="table-scrollable" style=" margin:  0!important;">
                                        <table class="table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th> # </th>
                                                    <th> Project Name </th>
                                                    <th> Category </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td> 1 </td>
                                                    <td> <a href="#">Health Project</a> </td>
                                                    <td>
                                                        <span class="label label-sm label-success"> Health </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td> 2 </td>
                                                    <td><a href="#"> Electricity Plant Project</a> </td>
                                                    <td>
                                                        <span class="label label-sm label-info"> Power </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td> 3 </td>
                                                    <td> <a href="#">Technical Project</a> </td>
                                                    <td>
                                                        <span class="label label-sm label-warning"> Technology </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td> 4 </td>
                                                    <td> <a href="#">School Buliding Project</a> </td>
                                                    <td>
                                                        <span class="label label-sm label-danger"> Education </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td> 1 </td>
                                                    <td> <a href="#">Health Project </a></td>
                                                    <td>
                                                        <span class="label label-sm label-success"> Health </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td> 2 </td>
                                                    <td> <a href="#">Electricity Plant Project</a> </td>
                                                    <td>
                                                        <span class="label label-sm label-info"> Power </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td> 3 </td>
                                                    <td> Technical Project </td>
                                                    <td>
                                                        <span class="label label-sm label-warning"> Technology </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td> 4 </td>
                                                    <td> School Buliding Project </td>
                                                    <td>
                                                        <span class="label label-sm label-danger"> Education </span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>-->
        </div>
        <div class="grap-admin">
            <?php 
            $highcharts_url = Yii::$app->urlManagerFrontend->baseUrl.'/../js/charts/highcharts.js';
            $charts_data_url = Yii::$app->urlManagerFrontend->baseUrl.'/../js/charts/data.js';
            $drilldown = Yii::$app->urlManagerFrontend->baseUrl.'/../js/charts/drilldown.js';

            $this->registerJsFile($highcharts_url,['position' => \yii\web\View::POS_HEAD]);
            $this->registerJsFile($charts_data_url,['position' => \yii\web\View::POS_HEAD]); 
            $this->registerCssFile($drilldown,['position' => \yii\web\View::POS_HEAD]); 
            ?>            
            </br></br>


            <div class="col-md-6 col-sm-6">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-equalizer font-yellow"></i>
                            <span class="caption-subject font-yellow bold uppercase">Finance</span>
                        </div>
                        
                    </div>
                    
                    <div class="col-lg-12">
                    <div class="row">
                    <div class="dropdowns-list1">
                            <div id="customLegend13">
                                <div class="drp3">
                                    <div class="btn-group finance-rang">
                                        <a href="" class="btn dark btn-outline btn-circle btn-sm dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" aria-expanded="true"> <span class="mnthadd2"><span class="mnth12"> To</span></span><span class="fa fa-angle-down"> </span></a>
                                        <ul class="dropdown-menu pull-right finance-dropdwon mth12">
                                            <?php for ($i = 0; $i < 6; $i++) { ?>
                                                <li class="listmth12" iindex12="<?php echo $i; ?>"><a href="javascript:;" class="monthoption1" id="tmonth" dropdwn1="3" iindex12="<?php echo $i; ?>" month12="<?php echo date('m', strtotime("-$i month")); ?>" selectedmonth12="<?php echo date('M', strtotime("-$i month")); ?>" selectedyear12="<?php echo date('Y', strtotime("-$i month")); ?>"><span class="label label-sm ">  </span><?php echo date('M', strtotime("-$i month")).' - '.date('Y', strtotime("-$i month")); ?></a></li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div id="customLegend12">
                                <div class="drp2">
                                    <div class="btn-group finance-rang">
                                        <a href="" class="btn dark btn-outline btn-circle btn-sm dropdown-toggle monthdrpdwn" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" aria-expanded="true"><span class="mnthadd11"><span class="mnth11"> From</span></span><span class="fa fa-angle-down"> </span></a>
                                        <ul class="dropdown-menu pull-right finance-dropdwon mth11">
                                            <?php for ($i = 0; $i < 6; $i++) { ?>
                                                <li class="listmth11" iindex11="<?php echo $i; ?>"><a href="javascript:;" class="monthoption1" id="fmonth" dropdwn1="2" iindex11="<?php echo $i; ?>" month11="<?php echo date('m', strtotime("-$i month")); ?>" selectedmonth11="<?php echo date('M', strtotime("-$i month")); ?>" selectedyear11="<?php echo date('Y', strtotime("-$i month")); ?>"><span class="label label-sm ">  </span><?php echo date('M', strtotime("-$i month")).' - '.date('Y', strtotime("-$i month")); ?></a></li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>



                            <div id="customLegend11">
                                <div class="actions">
                                    <div class="btn-group finance-rang">
                                        <a href="" class="btn dark btn-outline btn-circle btn-sm dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" aria-expanded="true"> 
                                            <span class="prjselects1"><span class="prjselectvalue1" selectedprjname1="All Projects"> All Projects</span></span><span class="fa fa-angle-down"> </span>
                                        </a>
                                        <div class="dropdown-menu pull-right">
                                        <div class=" scroller " style="height:200px; padding:0;">
                                        <ul class="finance-dropdwon finance-prjs prjct-allvies" style="width:250px;">
                                            <li><a href="javascript:;" class="monthoption1 prjname1" dropdwn1="1" prjindex1="all" selectedprj1="all" selectedprjname1="All Projects"><span class="label label-sm label-default">  </span>All Projects</a></li>
                                            <?php $projects = json_decode($allprojects);
                                            $i = 0;
                                            foreach ($projects as $prj) { ?>
                                                <li><input type="checkbox" name="check1" class="check1 monthoption1" dropdwn1="1" prjindex1="<?php echo $i; ?>" selectedprj1="<?php echo $prj->project_id ?>" selectedprjname1="<?php echo $prj->project_title ?>" value="<?php echo $prj->project_id ?>"><a href="javascript:;" class="monthoption1 prjname1" dropdwn1="1" prjindex1="<?php echo $i; ?>" selectedprj1="<?php echo $prj->project_id ?>" selectedprjname1="<?php echo $prj->project_title ?>"><span class="label label-sm label-default prj-label1<?php echo str_replace(' ', '', $prj->project_title); ?>">  </span><?php echo $prj->project_title; ?></a></li>
    <?php $i++;
} ?>
                                        </ul>
                                        </div></div>
                                    </div>
                                </div>
                            </div>






                        </div>
                    </div>
                    </div>
                    
                    <div class="portlet-body1">
                        <div id="container11" style="min-width: 310px; height: 400px; margin: 0 auto; display:none"></div>
                        <div id="container1" style="min-width: 310px; height: 400px; margin: 0 auto"></div></br></br>
                    </div>
                    <ul>
                        <li>The graph displays past 6 months projects only.</li>
                        <li>Financial graph is exclusive of "Kind" participation type.</li>            
                    </ul>
                </div>
            </div>

            <div class="col-md-6 col-sm-6">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-equalizer font-yellow"></i>
                            <span class="caption-subject font-yellow bold uppercase">Project Statistics</span>
                        </div>
                        
                    </div>
                    
                     <div class="col-lg-12">
                    <div class="row">
                    <div class="dropdowns-list2">
                            <div id="customLegend23">
                                <div class="drp3">
                                    <div class="btn-group finance-rang">
                                        <a href="" class="btn dark btn-outline btn-circle btn-sm dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" aria-expanded="true"> <span class="mnthadd22"><span class="mnth22"> To</span></span><span class="fa fa-angle-down"> </span></a>
                                        <ul class="dropdown-menu pull-right finance-dropdwon mth22">
											<?php for ($i = 0; $i < 6; $i++) { ?>
                                                <li class="listmth22" iindex22="<?php echo $i; ?>"><a href="javascript:;" class="monthoption2" id="tmonth" iindex22="<?php echo $i; ?>" dropdwn2="3" month22="<?php echo date('m', strtotime("-$i month")); ?>" selectedmonth22="<?php echo date('M', strtotime("-$i month")); ?>" selectedyear22="<?php echo date('Y', strtotime("-$i month")); ?>"><span class="label label-sm ">  </span><?php echo date('M', strtotime("-$i month")).' - '.date('Y', strtotime("-$i month")); ?></a></li>
											<?php } ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div id="customLegend22">
                                <div class="drp2">
                                    <div class="btn-group finance-rang">
                                        <a href="" class="btn dark btn-outline btn-circle btn-sm dropdown-toggle monthdrpdwn" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" aria-expanded="true"><span class="mnthadd21"><span class="mnth21"> From</span></span><span class="fa fa-angle-down"> </span></a>
                                        <ul class="dropdown-menu pull-right finance-dropdwon mth21">
											<?php for ($i = 0; $i < 6; $i++) { ?>
                                                <li class="listmth21" iindex21="<?php echo $i; ?>"><a href="javascript:;" class="monthoption2" id="fmonth" dropdwn2="2" iindex21="<?php echo $i; ?>" month21="<?php echo date('m', strtotime("-$i month")); ?>" selectedmonth21="<?php echo date('M', strtotime("-$i month")); ?>" selectedyear21="<?php echo date('Y', strtotime("-$i month")); ?>"><span class="label label-sm ">  </span><?php echo date('M', strtotime("-$i month")).' - '.date('Y', strtotime("-$i month")); ?></a></li>
											<?php } ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>



                            <div id="customLegend21">
                                <div class="actions">
                                    <div class="btn-group finance-rang">
                                        <a href="" class="btn dark btn-outline btn-circle btn-sm dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" aria-expanded="true"> 
                                            <span class="prjselects2"><span class="prjselectvalue2"> All Projects</span></span><span class="fa fa-angle-down"> </span>
                                        </a>
                                        <div class="dropdown-menu pull-right">
                                        <div class=" scroller " style="height:200px; padding:0;">
                                        <ul class=" finance-dropdwon statistics-prjs prjct-allvies" style="width:250px;">
                                            <li><a href="javascript:;" class="monthoption2 prjname2" dropdwn2="1" prjindex2="all" selectedprj2="all" selectedprjname2="All Projects"><span class="label label-sm label-default">  </span>All Projects</a></li>
												<?php $projects = json_decode($allprojectsbymembers);
												$i = 0;
												foreach ($projects as $prj) { ?>
													<li><input type="checkbox" name="check2" class="check2 monthoption2" dropdwn2="1" prjindex2="<?php echo $i; ?>" selectedprj2="<?php echo $prj->project_id ?>" selectedprjname2="<?php echo $prj->project_title ?>" value="<?php echo $prj->project_id ?>"><a href="javascript:;" class="monthoption2 prjname2" dropdwn2="1" prjindex2="<?php echo $i; ?>" selectedprj2="<?php echo $prj->project_id ?>" selectedprjname2="<?php echo $prj->project_title ?>"><span class="label label-sm label-default prj-label2<?php echo str_replace(' ', '', $prj->project_title); ?>">  </span><?php echo $prj->project_title; ?></a></li>
													<?php $i++;
												} ?>
                                        </ul></div></div>
                                    </div>
                                </div>
                            </div>






                        </div>
                    </div>
                    </div>
                    
                    <div class="portlet-body1">
                        <div id="container21" style="min-width: 310px; height: 400px; margin: 0 auto; display:none"></div>
                        <div id="container2" style="min-width: 310px; height: 400px; margin: 0 auto"></div></br></br>
                    </div>
                    <ul>
                        <li>The graph displays past 6 months projects only.</li>           
                        <li>Project Statistics graph is inclusive of "Kind" participation type.</li>
                    </ul>
                </div>
            </div>


            <script>

                                                                                $(function () {
                                                                                var chart = new Highcharts.Chart({
                                                                                legend: {
                                                                                enabled: false
                                                                                },
                                                                                        chart: {
                                                                                        renderTo: 'container11'
                                                                                        },
                                                                                        xAxis: {
                                                                                        title: {
                                                                                        text: 'Months'
                                                                                        }
                                                                                        },
                                                                                        yAxis: {
                                                                                        title: {
                                                                                        text: 'Rupees'
                                                                                        }
                                                                                        },
                                                                                        title: {
                                                                                        text: ''
                                                                                        },
                                                                                        credits: {
                                                                                        enabled: false
                                                                                        },
                                                                                        series: [{

                                                                                        data: [

                                                                                        ]
                                                                                        }],
                                                                                        plotOptions: {
                                                                                        series: {
                                                                                        connectNulls: true
                                                                                        }
                                                                                        }

                                                                                });
                                                                                        var chart = new Highcharts.Chart({
                                                                                        legend: {
                                                                                        enabled: false
                                                                                        },
                                                                                                chart: {
                                                                                                renderTo: 'container21'
                                                                                                },
                                                                                                xAxis: {
                                                                                                title: {
                                                                                                text: 'Months'
                                                                                                }
                                                                                                },
                                                                                                yAxis: {
                                                                                                title: {
                                                                                                text: 'No of Participants'
                                                                                                }
                                                                                                },
                                                                                                title: {
                                                                                                text: ''
                                                                                                },
                                                                                                credits: {
                                                                                                enabled: false
                                                                                                },
                                                                                                series: [{

                                                                                                data: [

                                                                                                ]
                                                                                                }],
                                                                                                plotOptions: {
                                                                                                series: {
                                                                                                connectNulls: true
                                                                                                }
                                                                                                }

                                                                                        });
                                                                                        var data = new Array();
                                                                                        var checkdata = JSON.parse('<?php echo $monthlyParticipation; ?>');
                                                                                        if (checkdata == undefined || checkdata == null || checkdata.length == 0){

                                                                                                $('#container1').hide();
                                                                                                        $('#container11').show();
                                                                                                } else{
                                                                                                $('#container11').hide();
                                                                                                        $('#container1').show();
                                                                                                }
                                                                                        data = JSON.parse('<?php echo $monthlyParticipation; ?>'),
                                                                                        months1 = data.reduce(function(p, c) {
																								return ~p.indexOf(c.months) ? p : p.concat(c.months)
																							  }, []),
																							  series = data.reduce(function(p, c) {
																								var f = undefined;
																								console.log(p);
																								p.map(function(x) {
																								  if (x.name == c.project_title) {
																									f = x;
																								  }
																								});

																								!!f ? f.data[months1.indexOf(c.months)] = c.amount * 1 :
																								  p.push({
																									name: c.project_title,
																									id: c.project_title,
																									data: (
																									  Array.apply(null, new Array(months1.length)).map(Number.prototype.valueOf, 0)
																									  .map(
																										function(e, i) {
																										  return i === months1.indexOf(c.months) ? c.amount * 1 : e
																										}
																									  ))
																								  });
																								return p;
																							  }, []);
                                                                                        var chart1 = new Highcharts.Chart({
                                                                                        chart: {
                                                                                        renderTo: 'container1'
                                                                                        },
                                                                                                xAxis: {
                                                                                                categories: months1,
                                                                                                        title: {
                                                                                                        text: 'Months'
                                                                                                        }
                                                                                                },
                                                                                                yAxis: {
                                                                                                min: 0,
                                                                                                        title: {
                                                                                                        text: 'Rupees'
                                                                                                        }
                                                                                            },
                                                                                                title: {
                                                                                                text: ''
                                                                                                },
                                                                                                credits: {
                                                                                                enabled: false
                                                                                                },
                                                                                                plotOptions: {
                                                                                                series: {
                                                                                                events: {
                                                                                                legendItemClick: function(event) {
                                                                                                var selected = this.index;
                                                                                                        var allSeries = this.chart.series;
                                                                                                        $.each(allSeries, function(index, series) {
                                                                                                        selected == index ? series.show() : series.hide();
                                                                                                        });
                                                                                                        return false;
                                                                                                }
                                                                                                }
                                                                                                }
                                                                                                },
                                                                                                series: series,
                                                                                                }, function(chart1){
										
																							
                                                                                        $.each(chart1.series, function(i, serie){
                                                                                        var pname = serie.name;		
																								var pjname = pname.replace(/([~!@#$%^&*()_+=`{}\[\]\|\\:;'<>,.\/? ])+/g, '').replace(/^(-)+|(-)+$/g,'');
																								var chartcolor = $('#container1 .highcharts-series-' + i + ' path').attr('stroke');
																								if(chartcolor == 'rgba(192,192,192,0.0001)'){
																								chartcolor = '#7cb5ec';
																								}
																								chart1.series[i].graph.attr({ stroke: chartcolor });
                                                                                                var $customLegend = $('.prj-label1' + pjname).css('background-color', chartcolor);
                                                                                        }
																						);
                                                                                        });
                                                                                        var murl1 = '<?php echo Yii::$app->urlManager->createAbsoluteUrl('site/get-months-data'); ?>';
                                                                                        $('.monthoption1').click(function (e) {

																						var selected = ''; var prjid = '';
                                                                                        selected = $(this).attr('prjindex1');
                                                                                        prjid = $(this).attr('selectedprj1');
                                                                                        prjname = $(this).attr('selectedprjname1');
																						if(prjname){
																						var projectname = prjname.replace(/([~!@#$%^&*()_+=`{}\[\]\|\\:;'<>,.\/? ])+/g, '').replace(/^(-)+|(-)+$/g,'');
																						var bgcolor = $('.prj-label1'+projectname).css("background-color");
																						var prjindex = $(this).attr('prjindex1');
																						}

                                                                                       if($(this).hasClass('prjname1') || $(this).hasClass('check1')){
                                                                                        var selectedmonth11 = $('.smonth11').html();
                                                                                        var selectedmonth12 = $('.smonth12').html();
																						var selectedyear11 = $('.syear11').html();
                                                                                        var selectedyear12 = $('.syear12').html();
																						var month11 = $(this).attr('month11');
                                                                                        var month12 = $(this).attr('month12');
																						}else{
																						var selectedmonth11 = $(this).attr('selectedmonth11');
                                                                                        var selectedmonth12 = $(this).attr('selectedmonth12');
																						var selectedyear11 = $(this).attr('selectedyear11');
                                                                                        var selectedyear12 = $(this).attr('selectedyear12');
																						}
																						var month11 = $(this).attr('month11');
                                                                                        var month12 = $(this).attr('month12');
																						var frommonth = '';
                                                                                        var tomonth = '';
                                                                                        var drdwnno = $(this).attr('dropdwn1');
																						if(selectedmonth11 == null && selectedmonth12 == null){
																						selectedmonth11 = '<?php echo date('M', strtotime('-5 month')); ?>';
																						selectedyear11 = '<?php echo date('Y', strtotime('-5 month')); ?>';
																						selectedmonth12 = '<?php echo date('M'); ?>';
																						selectedyear12 = '<?php echo date('Y'); ?>';
																						}
                                                                                        /*if (prjid){
																						$('.prjselect1').remove();
                                                                                        $('.prjselectname1').remove();
                                                                                        $('.prjselectvalue1').remove();
                                                                                        if(prjname.length>25){
																						var prjnamenew = prjname.substr(0,18)+'...';
																						}else{
																							var prjnamenew = prjname;
																						}
                                                                                        $('.prjselects1').append('<span class="prjselectvalue1">' + prjnamenew + '</span><span class="prjselect1" style="display:none">' + prjid + '</span><span class="prjselectname1" style="display:none">' + prjname + '</span>');
                                                                                        prjid = $('.prjselect1').html();
                                                                                        prjselectname1 = $('.prjselectname1').html();
                                                                                        prjselectname1 = prjselectname1.replace(/\s+/g, '');
                                                                                        var bgcolor = $('.prj-label1' + prjselectname1).css('background-color');
                                                                                }*/
                                                                                if (drdwnno == 2){
																				var iindex = $(this).attr('iindex11');
																				$(".listmth12").hide();
																				for (i = 0; i < iindex; i++) {
																				$(".mth12 li[iindex12=" + i + "]").show();
																				}
																				
                                                                                $('.mnth11').remove();
                                                                                        $('.smonth11').remove();
																						$('.syear11').remove();
                                                                                        $('.mnthadd11').append('<span class="mnth11">' + selectedmonth11 +' - ' + selectedyear11 + '</span><span class="smonth11" style="display:none">' + selectedmonth11 + '</span><span class="syear11" style="display:none">' + selectedyear11 + '</span>');
                                                                                } else if (drdwnno == 3){
																				
																				var iindex = $(this).attr('iindex12');
																				$(".listmth11").show();
																				iindex = parseInt(iindex)+1;
																				for (i = 0; i < iindex; i++) {
																				$(".mth11 li[iindex11=" + i + "]").hide();
																				}
                                                                                $('.mnth12').remove();
                                                                                        $('.smonth12').remove();
																						$('.syear12').remove();
                                                                                        $('.mnthadd2').append('<span class="mnth12">' + selectedmonth12 +' - ' + selectedyear12 + '</span><span class="smonth12" style="display:none">' + selectedmonth12 + '</span><span class="syear12" style="display:none">' + selectedyear12 + '</span>');
                                                                                } else if(drdwnno == 1){
																				if(prjid == 'all'){
																				$('input.check1[type=checkbox]').parent().removeClass("checked");
																				$('.prjname1').removeClass('prjchecked1');
																				$(".listmth11").show();
																				$(".listmth12").show();
																				
																				$('.mnth11').remove();
																				$('.smonth11').remove();
																				$('.syear11').remove();
																				selectedmonth11 = selectedmonth12 = selectedyear11 = selectedyear12 = '';
																				$('.mnthadd11').append('<span class="mnth11"> From</span><span class="smonth11" style="display:none">' + selectedmonth11 + '</span><span class="syear11" style="display:none">' + selectedyear11 + '</span>');
																				
																				$('.mnth12').remove();
																				$('.smonth12').remove();
																				$('.syear12').remove();
																				$('.mnthadd2').append('<span class="mnth12"> To</span><span class="smonth12" style="display:none">' + selectedmonth12 + '</span><span class="syear12" style="display:none">' + selectedyear12 + '</span>');
																				
																				}else{
																				if($('input.check1[type=checkbox][value='+prjid+']').parent().hasClass("checked"))
																				{
																					$('input.check1[type=checkbox][value='+prjid+']').parent().removeClass("checked");
																					$('[selectedprj1='+prjid+']').removeClass('prjchecked1');
																				}else{
																					$('input.check1[type=checkbox][value='+prjid+']').parent().addClass("checked");
																					$('[selectedprj1='+prjid+']').addClass('prjchecked1');
																				}
																				e.stopPropagation(); 
																				}
																			/*if(selectedmonth11 != null){
																			$('.mnth11').remove();
																				$('.smonth11').remove();
                                                                                    $('.syear11').remove();
                                                                                    $('.mnthadd11').append('<span class="mnth11"> From</span><span class="smonth11" style="display:none">' + selectedmonth11 + '</span><span class="syear11" style="display:none">' + selectedyear11 + '</span>');
																			}
                                                                           if(selectedmonth12 != null){
																		   $('.mnth12').remove();
                                                                                    $('.smonth12').remove();
                                                                                    $('.syear12').remove();
                                                                                        $('.mnthadd2').append('<span class="mnth12"> To</span><span class="smonth12" style="display:none">' + selectedmonth12 + '</span><span class="syear12" style="display:none">' + selectedyear12 + '</span>');
																			}*/
                                                                            }
																			var checkValues = [];
																			$(".finance-prjs .checked .check1").each(function() {
																				checkValues.push($(this).val());
																				});
																			if(checkValues.length == 0){
																				prjid = 'all';
																			}
																			
																			function getcolors1()
																			{
																			var bgcolors1 = [];
																			var $steps = $('a.prjchecked1');
																				$steps.each(function () {
																				var color = $(this).children('span.label-default').css('backgroundColor');
																				var id = $(this).attr('selectedprj1');
																				bgcolors1.push(color+'+'+id);
																				});
																				return bgcolors1;
																				}
                                                                                //prjid = $('.prjselect1').html();
                                                                                        frommonth = $('.smonth11').html();
                                                                                        tomonth = $('.smonth12').html();
																						fromyear = $('.syear11').html();
                                                                                        toyear = $('.syear12').html();
                                                                                        $.ajax({
                                                                                        url: murl1,
                                                                                                type: "get",
                                                                                                data: {frommonth:frommonth, tomonth:tomonth, fromyear:fromyear, toyear:toyear, prjid:prjid, checkValues:checkValues},
                                                                                                dataType: 'html',
                                                                                                success: function (data) {
																								var data = JSON.parse(data);
																								var pjids = [];
																								$.each(data, function(i, item) {
																									pjids.push(item.project_ref_id);
																								});
																								
																								pjids = $.unique( pjids );
																								
																								var colors = getcolors1();
																								var bgcolors1 = [];
																								for(var i=0; i<colors.length; i++){
																								var id = colors[i].split("+")[1];
																								var color = colors[i].split("+")[0];
																								if($.inArray(id, pjids)!='-1')
																								{
																								bgcolors1.push(color);
																								}
																								}
																								
																								if(drdwnno == 2 || drdwnno == 3){
																								var bgcolors1 = [];
																								for(var i=0; i<pjids.length; i++){
																								var color = $('a.prjname1[selectedprj1='+pjids[i]+'] > span.label-default').css('background-color');
																								bgcolors1.push(color);
																								}
																								}
                                                                                                        var checkdata = data;
                                                                                                         months1 = data.reduce(function(p, c) {
																											return ~p.indexOf(c.months) ? p : p.concat(c.months)
																										  }, []),
																										  series = data.reduce(function(p, c) {
																											var f = undefined;
																											console.log(p);
																											p.map(function(x) {
																											  if (x.name == c.project_title) {
																												f = x;
																											  }
																											});

																											!!f ? f.data[months1.indexOf(c.months)] = c.amount * 1 :
																											  p.push({
																												name: c.project_title,
																												id: c.project_title,
																												data: (
																												  Array.apply(null, new Array(months1.length)).map(Number.prototype.valueOf, 0)
																												  .map(
																													function(e, i) {
																													  return i === months1.indexOf(c.months) ? c.amount * 1 : e
																													}
																												  ))
																											  });
																											return p;
																										  }, []);
                                                                                                        if (checkdata == undefined || checkdata == null || checkdata.length == 0){

                                                                                                $('#container1').hide();
                                                                                                        $('#container11').show();
                                                                                                } else{
                                                                                                $('#container11').hide();
                                                                                                        $('#container1').show();
                                                                                                }


                                                                                                var chart1 = new Highcharts.Chart({
                                                                                                chart: {
                                                                                                renderTo: 'container1'
                                                                                                },
                                                                                                        xAxis: {
                                                                                                        categories: months1,
                                                                                                                title: {
                                                                                                                text: 'Months'
                                                                                                                }
                                                                                                        },
                                                                                                        yAxis: {
                                                                                                        min: 0,
                                                                                                                title: {
                                                                                                                text: 'Rupees'
                                                                                                                }
                                                                                                    },
                                                                                                        title: {
                                                                                                        text: ''
                                                                                                        },
                                                                                                        credits: {
                                                                                                        enabled: false
                                                                                                        },
                                                                                                        plotOptions: {
                                                                                                        series: {
                                                                                                        events: {
                                                                                                        legendItemClick: function(event) {
                                                                                                        var selected = this.index;
                                                                                                                var allSeries = this.chart.series;
                                                                                                                $.each(allSeries, function(index, series) {
                                                                                                                selected == index ? series.show() : series.hide();
                                                                                                                });
                                                                                                                return false;
                                                                                                        }
                                                                                                        }
                                                                                                        }
                                                                                                        },
                                                                                                        series: series,
																										}, function(chart1){
																										$.each(chart1.series, function(i, serie){
																										chart1.series[i].options.color = bgcolors1[i];
																										chart1.series[i].update(chart1.series[i].options);
																										});
																										chart1.redraw();
                                                                                                        });
																										
																										

                                                                                                }
                                                                                        });
                                                                                });
                                                                                        var data = new Array();
                                                                                        data = JSON.parse('<?php echo $monthlyParticipants; ?>');
                                                                                        var checkdata = data;
                                                                                        var allMonths = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                                                                                                'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                                                                                        var allProjectNames = $.map(data, function(el){return el.project_title}).filter(function(el, index, arr){return arr.indexOf(el) == index});
                                                                                        //Comment the line below if you want all 12 months and not just the ones with actual data
                                                                                        allMonths = $.map(data, function(el){return el.months}).filter(function(el, index, arr){return arr.indexOf(el) == index});
                                                                                        var series = [];
                                                                                        $.each(allProjectNames, function(index2, projectName){ //cycling all projects
                                                                                        var dataSeries = [];
                                                                                                $.each(allMonths, function(index1, month){
                                                                                                var dati = data.filter(function(el){ //filtering the data for the current project and month
                                                                                                return el.project_title === projectName && el.months === month;
                                                                                                })

                                                                                                        dati.length > 0 ? dataSeries.push(parseFloat(dati[0].members)) : dataSeries.push(null); //if the project doesn't have data for the current montht i set the datapoint to null
                                                                                                });
                                                                                                series.push({name : projectName, data : dataSeries});
                                                                                        });
                                                                                        var months2 = allMonths;
                                                                                        if (checkdata == undefined || checkdata == null || checkdata.length == 0){

                                                                                                $('#container2').hide();
                                                                                                        $('#container21').show();
                                                                                                } else{
                                                                                                $('#container21').hide();
                                                                                                        $('#container2').show();
                                                                                                }
                                                                                        var chart2 = new Highcharts.Chart({
                                                                                        chart: {
                                                                                        type: 'column',
                                                                                                renderTo: 'container2'
                                                                                        },
                                                                                                xAxis: {
                                                                                                categories: months2,
                                                                                                        title: {
                                                                                                        text: 'Months'
                                                                                                        }
                                                                                                },
                                                                                                yAxis: {
                                                                                                allowDecimals: false,
                                                                                                        min: 0,
                                                                                                        title: {
                                                                                                        text: 'No of Participants'
                                                                                                        }
                                                                                                },
                                                                                                title: {
                                                                                                text: ''
                                                                                                },
                                                                                                credits: {
                                                                                                enabled: false
                                                                                                },
                                                                                                plotOptions: {
                                                                                                series: {
                                                                                                events: {
                                                                                                legendItemClick: function(event) {
                                                                                                var selected = this.index;
                                                                                                        var allSeries = this.chart.series;
                                                                                                        $.each(allSeries, function(index, series) {
                                                                                                        selected == index ? series.show() : series.hide();
                                                                                                        });
                                                                                                        return false;
                                                                                                }
                                                                                                }
                                                                                                }
                                                                                                },
                                                                                                series: series,
                                                                                                }, function(chart2){

                                                                                        $.each(chart2.series, function(i, serie){
                                                                                        var pname = serie.name;
																								var pjname = pname.replace(/([~!@#$%^&*()_+=`{}\[\]\|\\:;'<>,.\/? ])+/g, '').replace(/^(-)+|(-)+$/g,'');
																								var chartcolor = $('#container2 .highcharts-series-' + i + ' rect').attr('fill');
																								if(chartcolor == 'rgba(192,192,192,0.0001)'){
																								chartcolor = '#7cb5ec';
																								}
                                                                                                var $customLegend = $('.prj-label2' + pjname).css('background-color', chartcolor);
                                                                                        });
                                                                                        });
                                                                                        var murl2 = '<?php echo Yii::$app->urlManager->createAbsoluteUrl('site/get-months-data-for-participants'); ?>';
                                                                                        $('.monthoption2').click(function (e) {

																						var selected = ''; var prjid = '';
                                                                                        selected = $(this).attr('prjindex2');
                                                                                        prjid = $(this).attr('selectedprj2');
                                                                                        prjname = $(this).attr('selectedprjname2');
                                                                                        var selectedmonth21 = $(this).attr('selectedmonth21');
                                                                                        var selectedmonth22 = $(this).attr('selectedmonth22');
																						var selectedyear21 = $(this).attr('selectedyear21');
                                                                                        var selectedyear22 = $(this).attr('selectedyear22');
																						var month21 = $(this).attr('month21');
                                                                                        var month22 = $(this).attr('month22');
                                                                                        var frommonth = '';
                                                                                        var tomonth = '';
                                                                                        var drdwnno = $(this).attr('dropdwn2');
																						if(selectedmonth21 == null && selectedmonth22 == null){
																						selectedmonth21 = '<?php echo date('M', strtotime('-5 month')); ?>';
																						selectedyear21 = '<?php echo date('Y', strtotime('-5 month')); ?>';
																						selectedmonth22 = '<?php echo date('M'); ?>';
																						selectedyear22 = '<?php echo date('Y'); ?>';
																						}
                                                                                        /*if (prjid){
																						$('.prjselect2').remove();
                                                                                        $('.prjselectname2').remove();
                                                                                        $('.prjselectvalue2').remove();
                                                                                        if(prjname.length>25){
                                                                                    var prjnamenew = prjname.substr(0,18)+'...';
                                                                                }else{
                                                                                    var prjnamenew = prjname;
                                                                                }
                                                                                        $('.prjselects2').append('<span class="prjselectvalue2">' + prjnamenew + '</span><span class="prjselect2" style="display:none">' + prjid + '</span><span class="prjselectname2" style="display:none">' + prjname + '</span>');
                                                                                        prjid = $('.prjselect2').html();
                                                                                        prjselectname2 = $('.prjselectname2').html();
                                                                                        prjselectname2 = prjselectname2.replace(/\s+/g, '');
                                                                                        var bgcolor = $('.prj-label2' + prjselectname2).css('background-color');
                                                                                }*/
                                                                                if (drdwnno == 2){
																				var iindex = $(this).attr('iindex21');
																				$(".listmth22").hide();
																				for (i = 0; i < iindex; i++) {
																				$(".mth22 li[iindex22=" + i + "]").show();
																				}
                                                                                $('.mnth21').remove();
                                                                                        $('.smonth21').remove();
																						$('.syear21').remove();
                                                                                        $('.mnthadd21').append('<span class="mnth21">' + selectedmonth21 +' - ' + selectedyear21 + '</span><span class="smonth21" style="display:none">' + selectedmonth21 + '</span><span class="syear21" style="display:none">' + selectedyear21 + '</span>');
                                                                                } else if (drdwnno == 3){
																				var iindex = $(this).attr('iindex22');
																				$(".listmth21").show();
																				iindex = parseInt(iindex)+1;
																				for (i = 0; i < iindex; i++) {
																				$(".mth21 li[iindex21=" + i + "]").hide();
																				}
                                                                                $('.mnth22').remove();
                                                                                        $('.smonth22').remove();
																						$('.syear22').remove();
                                                                                        $('.mnthadd22').append('<span class="mnth22">' + selectedmonth22 +' - ' + selectedyear22 + '</span><span class="smonth22" style="display:none">' + selectedmonth22 + '</span><span class="syear22" style="display:none">' + selectedyear22 + '</span>');
                                                                                }else if(drdwnno == 1){
																				if(prjid == 'all'){
																				$('input.check2[type=checkbox]').parent().removeClass("checked");
																				$('.prjname2').removeClass('prjchecked2');
																				$(".listmth21").show();
																				$(".listmth22").show();
																				
																				$('.mnth21').remove();
																				$('.smonth21').remove();
																				$('.syear21').remove();
																				selectedmonth21 = selectedmonth22 = selectedyear21 = selectedyear22 = '';
																				$('.mnthadd21').append('<span class="mnth21"> From</span><span class="smonth21" style="display:none">' + selectedmonth21 + '</span><span class="syear21" style="display:none">' + selectedyear21 + '</span>');
																							
																				$('.mnth22').remove();
																				$('.smonth22').remove();
																				$('.syear22').remove();
																				$('.mnthadd22').append('<span class="mnth22"> To</span><span class="smonth22" style="display:none">' + selectedmonth22 + '</span><span class="syear22" style="display:none">' + selectedyear22 + '</span>');			
																				}else{
																				if($('input.check2[type=checkbox][value='+prjid+']').parent().hasClass("checked"))
																				{
																					$('input.check2[type=checkbox][value='+prjid+']').parent().removeClass("checked");
																					$('[selectedprj2='+prjid+']').removeClass('prjchecked2');
																				}else{
																					$('input.check2[type=checkbox][value='+prjid+']').parent().addClass("checked");
																					$('[selectedprj2='+prjid+']').addClass('prjchecked2');
																				}
																				e.stopPropagation(); 
																				}
																					/*if(selectedmonth21 != null){
																					$('.mnth21').remove();
																						$('.smonth21').remove();
																							$('.syear21').remove();
																							$('.mnthadd21').append('<span class="mnth21"> From</span><span class="smonth21" style="display:none">' + selectedmonth21 + '</span><span class="syear21" style="display:none">' + selectedyear21 + '</span>');
																					}
																				   if(selectedmonth22 != null){
																				   $('.mnth22').remove();
																							$('.smonth22').remove();
																							$('.syear22').remove();
																								$('.mnthadd22').append('<span class="mnth22"> To</span><span class="smonth22" style="display:none">' + selectedmonth22 + '</span><span class="syear22" style="display:none">' + selectedyear22 + '</span>');
																					}*/
																					} 
																					var checkValues = [];
																						$(".statistics-prjs .checked .check2").each(function() {
																							checkValues.push($(this).val());
																							});
																						if(checkValues.length == 0){
																							prjid = 'all';
																						}
																						
																						function getcolors2()
																						{
																						var bgcolors2 = [];
																						var $steps = $('a.prjchecked2');
																							$steps.each(function () {
																							var color = $(this).children('span.label-default').css('backgroundColor');
																							var id = $(this).attr('selectedprj2');
																							bgcolors2.push(color+'+'+id);
																							});
																							return bgcolors2;
																							}
                                                                                //prjid = $('.prjselect2').html();
                                                                                        frommonth = $('.smonth21').html();
                                                                                        tomonth = $('.smonth22').html();
																						fromyear = $('.syear21').html();
                                                                                        toyear = $('.syear22').html();
                                                                                        $.ajax({
                                                                                        url: murl2,
                                                                                                type: "get",
                                                                                                dataType: 'html',
                                                                                                data: {frommonth:frommonth, tomonth:tomonth, fromyear:fromyear, toyear:toyear, prjid:prjid, checkValues:checkValues},
                                                                                                success: function (data) {
                                                                                                var data = JSON.parse(data);
																								var pjids = [];
																								$.each(data, function(i, item) {
																									pjids.push(item.project_ref_id);
																								});
																								
																								pjids = $.unique( pjids );
																								var colors = getcolors2();
																								var bgcolors2 = [];
																								for(var i=0; i<colors.length; i++){
																								var id = colors[i].split("+")[1];
																								var color = colors[i].split("+")[0];
																								if($.inArray(id, pjids)!='-1')
																								{
																								bgcolors2.push(color);
																								}
																								}
																								
																								if(drdwnno == 2 || drdwnno == 3){
																								var bgcolors2 = [];
																								for(var i=0; i<pjids.length; i++){
																								var color = $('a.prjname2[selectedprj2='+pjids[i]+'] > span.label-default').css('background-color');
																								bgcolors2.push(color);
																								}
																								}
                                                                                                        var checkdata = data;
                                                                                                        var allMonths = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                                                                                                                'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                                                                                                        var allProjectNames = $.map(data, function(el){return el.project_title}).filter(function(el, index, arr){return arr.indexOf(el) == index});
                                                                                                        //Comment the line below if you want all 12 months and not just the ones with actual data
                                                                                                        allMonths = $.map(data, function(el){return el.months}).filter(function(el, index, arr){return arr.indexOf(el) == index});
                                                                                                        var series = [];
                                                                                                        $.each(allProjectNames, function(index2, projectName){ //cycling all projects
                                                                                                        var dataSeries = [];
                                                                                                                $.each(allMonths, function(index1, month){
                                                                                                                var dati = data.filter(function(el){ //filtering the data for the current project and month
                                                                                                                return el.project_title === projectName && el.months === month;
                                                                                                                })
                                                                                                                        dati.length > 0 ? dataSeries.push(parseFloat(dati[0].members)) : dataSeries.push(null); //if the project doesn't have data for the current montht i set the datapoint to null
                                                                                                                });
                                                                                                                series.push({name : projectName, data : dataSeries});
                                                                                                        });
                                                                                                        var months2 = [];
                                                                                                        for (i = 0; i < data.length; ++i) {
                                                                                                {
                                                                                                months2.push(data[i]['months']);
                                                                                                }
                                                                                                }
                                                                                                var months2 = months2.filter(function(item, i, ar){ return ar.indexOf(item) === i; });
                                                                                                        if (checkdata == undefined || checkdata == null || checkdata.length == 0){

                                                                                                $('#container2').hide();
                                                                                                        $('#container21').show();
                                                                                                } else{
                                                                                                $('#container21').hide();
                                                                                                        $('#container2').show();
                                                                                                }
                                                                                                var chart2 = new Highcharts.Chart({
                                                                                                chart: {
                                                                                                type: 'column',
                                                                                                        renderTo: 'container2'
                                                                                                },
                                                                                                        xAxis: {
                                                                                                        categories: months2,
                                                                                                                title: {
                                                                                                                text: 'Months'
                                                                                                                }
                                                                                                        },
                                                                                                        yAxis: {
                                                                                                        allowDecimals: false,
                                                                                                                min: 0,
                                                                                                                title: {
                                                                                                                text: 'No of Participants'
                                                                                                                }
                                                                                                        },
                                                                                                        title: {
                                                                                                        text: ''
                                                                                                        },
                                                                                                        credits: {
                                                                                                        enabled: false
                                                                                                        },
                                                                                                        plotOptions: {
                                                                                                        series: {
                                                                                                        events: {
                                                                                                        legendItemClick: function(event) {
                                                                                                        var selected = this.index;
                                                                                                                var allSeries = this.chart.series;
                                                                                                                $.each(allSeries, function(index, series) {
                                                                                                                selected == index ? series.show() : series.hide();
                                                                                                                });
                                                                                                                return false;
                                                                                                        }
                                                                                                        }
                                                                                                        }
                                                                                                        },
                                                                                                        series: series,
																										}, function(chart2){

																										$.each(chart2.series, function(i, serie){
																										chart2.series[i].options.color = bgcolors2[i];
																										chart2.series[i].update(chart2.series[i].options);
																										chart2.series[i].data[i].update({
																										states: {
																											hover: {
																												 brightness: 20,
																												color: bgcolors2[i]
																											}    
																										}
																									});
																										});
																										chart2.redraw();
																									});
                                                                                                }
                                                                                        });
                                                                                });
                                                                                        });</script>






        </div>
        <div class="row">
            <div class="col-md-6 col-sm-6">

            </div>
        </div>

        <script language="javascript">
            var emailNotificationUrl = '<?php echo \Yii::$app->getUrlManager()->createAbsoluteUrl('site/notification-dashboard'); ?>';
            var dashboardNotificationUrl = '<?php echo \Yii::$app->getUrlManager()->createAbsoluteUrl('site/lc-notification'); ?>';

            function viewComment1(userImageUrl, project_title, created_date, comments1, status_name, username) {
                $('#project_title').html(project_title);
				$('#userImage').attr("src", userImageUrl)
				//$('#userImage').html(userImageUrl);
				$('#project_comments').html(comments1.replace(/\n/g, "<br />"));
				$('#viewComment').css('display', 'block');
            }

            function displayComments(status) {
            var commentsUrl = $('#commentsUrl').val();
        //        var projectCommentId = $('#commentId').val();
        //        var comments = $('#comment_'+projectCommentId).val();
        //        var projectId = $('#projectId_'+projectCommentId).val();

                $.ajax({
                    url: commentsUrl,
                    type: 'get',
                    data: {'status': status},
                    success: function (data) {
                        //console.log(data);
                        if (status == '2') {
                            $('.pendingComments').hide();
                                    $('.rejectedComments').hide();
                                    $('.approvedComments').hide();
                                    $('.pendingComments').show();
                                    $('.pendingComments').html(data);
                        } else if (status == '7') {
                            $('.approvedComments').hide();
                                    $('.rejectedComments').hide();
                                    $('.pendingComments').hide();
                                    $('.approvedComments').show();
                                    $('.approvedComments').html(data);
                        } else if (status == '8') {
                            $('.rejectedComments').hide();
                                    $('.approvedComments').hide();
                                    $('.pendingComments').hide();
                                    $('.rejectedComments').show();
                                    $('.rejectedComments').html(data);
                        }
                    },
                    error: function (xhr, status, error) {
                        alert('There was an error with your request.' + xhr.responseText);
                    }
                });
                return false;
            }

            function editComment() {
                var commentsUrl = $('#commentsUrl').val();
                var projectCommentId = $('#commentId').val();
                var comments = $('#comment_' + projectCommentId).val();
                var projectId = $('#projectId_' + projectCommentId).val();
                $('#comment_display_' + projectCommentId).text(comments);
                $.ajax({
                url: commentsUrl,
                    type: 'get',
                    data: {'comments': comments, 'projectCommentId': projectCommentId, 'projectId': projectId},
                    success: function (data) {
                        $('#comment_' + projectCommentId).css('display', "none");
                        $('#comment_display_' + projectCommentId).css('display', "block");
                        $('#comment_' + projectCommentId).css('border', '0px none black');
                        $('#comment_' + projectCommentId).css('background-color', 'transparent');
                        $('#btnEdit_' + projectCommentId).show();
                        $('#btnSave_' + projectCommentId).hide();
                        $('#btnView_' + projectCommentId).show();
                        $('#btnDelete_' + projectCommentId).show();
                        $('#commentId').val('');
                    },
                    error: function (xhr, status, error) {
                        alert('There was an error with your request.' + xhr.responseText);
                    }
                });
                return false;
            }
            
            function modifyComment(projectCommentId) {
                $('#comment_' + projectCommentId).css('display', "block");
                $('#comment_display_' + projectCommentId).css('display', "none");
                $('#comment_' + projectCommentId).css('border', '1px solid black');
                $('#comment_' + projectCommentId).css('background-color', '#FFFFFF');
                $('#btnEdit_' + projectCommentId).hide();
                $('#btnSave_' + projectCommentId).show();
                $('#btnView_' + projectCommentId).hide();
                $('#btnDelete_' + projectCommentId).hide();
                $('#commentId').val(projectCommentId);
            }

            function deleteComment(projectCommentId) {
            var commentsUrl = $('#commentsUrl').val();
                    $.ajax({
                    url: commentsUrl,
                            type: 'get',
                            data: {'projectCommentId': projectCommentId},
                            success: function () {
                            //alert('Success--'+projectCommentId);
                            //if (data) {
                            $('#divComment_' + projectCommentId).remove();
                                    return false;
                                    //}
                            },
                            error: function (xhr, status, error) {
                            alert('There was an error with your request.' + xhr.responseText);
                            }
                    });
            }


            $(function()
            {
                $('.EmailNotifications').on('click', function()
                {
                    if ($('.loaded').length == "0")
                    {                        
                        $('.nitification').load(emailNotificationUrl);
                        $('.emailsCount').html('0');
                    }else{
                        $('#email_msg_count').html('0');
                    }

                });

                $('.notification_lc').on('click', function()
                {
                    if ($('.loaded_for_lc').length == "0")
                    {
                        $('.nitification_lc_app').load(dashboardNotificationUrl);
                        $('.notificationsCount').html('0');
                    }else{
                        $('#notification_msg_count').html('0');
                    }
                });
            });



        </script>
