<?php $data = backend\controllers\AdminController::getUserTypes(); ?>        

<!-- BEGIN CONTAINER -->
<!--        <div class="page-container">-->
            <!-- BEGIN SIDEBAR -->
            <div class="page-sidebar-wrapper">
                <!-- BEGIN SIDEBAR -->
                <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
                <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
                <div class="page-sidebar navbar-collapse collapse">
                    <!-- BEGIN SIDEBAR MENU -->
                    <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
                    <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
                    <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
                    <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
                    <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
                    <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
                    <ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">
                        <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
                        <li class="sidebar-toggler-wrapper hide">
                            <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                            <div class="sidebar-toggler"> </div>
                            <!-- END SIDEBAR TOGGLER BUTTON -->
                        </li>
                        <!-- DOC: To remove the search box from the sidebar you just need to completely remove the below "sidebar-search-wrapper" LI element -->
<!--                        <li class="sidebar-search-wrapper">
                             BEGIN RESPONSIVE QUICK SEARCH FORM 
                             DOC: Apply "sidebar-search-bordered" class the below search form to have bordered search box 
                             DOC: Apply "sidebar-search-bordered sidebar-search-solid" class the below search form to have bordered & solid search box 
                            <form class="sidebar-search  sidebar-search-bordered" action="page_general_search_3.html" method="POST">
                                <a href="javascript:;" class="remove">
                                    <i class="icon-close"></i>
                                </a>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search...">
                                    <span class="input-group-btn">
                                        <a href="javascript:;" class="btn submit">
                                            <i class="icon-magnifier"></i>
                                        </a>
                                    </span>
                                </div>
                            </form>
                             END RESPONSIVE QUICK SEARCH FORM 
                        </li>-->
                        <li class="nav-item start ">
                            <a href="<?php echo Yii::$app->urlManager->createAbsoluteUrl("site/index");?>" class="nav-link nav-toggle">
                                <i class="icon-grid"></i>
                                <span class="title">Dashboard</span>
                            </a>
<!--                            <ul class="sub-menu">
                                <li class="nav-item start ">
                                    <a href="index.html" class="nav-link ">
                                        <i class="icon-bar-chart"></i>
                                        <span class="title">Dashboard 1</span>
                                    </a>
                                </li>
                            </ul>-->
                        </li>
                        <li class="nav-item">
                            <a href="javascript:void(0)" class="nav-link nav-toggle">
                                <i class="icon-user"></i>
                                <span class="title">User</span>
                                <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu">
                                <?php if(Yii::$app->user->identity->user_role_ref_id == 1 && Yii::$app->user->isSuperadmin){?>
                                <li class="nav-item  ">
                                    <a href="<?php echo Yii::$app->urlManager->createAbsoluteUrl("admin/create-user");?>" class="nav-link nav-toggle">
                                        <i class="icon-user"></i>
                                        <span class="title">Create User</span>
                                    </a>
                                </li>
                                <li class="nav-item  ">
                                    <a href="<?php echo Yii::$app->urlManager->createAbsoluteUrl("admin/create");?>" class="nav-link nav-toggle">
                                        <i class="icon-user"></i>
                                        <span class="title">Create Admin User</span>
                                    </a>
                                </li>
                                <li class="nav-item  ">
                                    <a href="<?php echo Yii::$app->urlManager->createAbsoluteUrl("admin/admin_list");?>" class="nav-link nav-toggle">
                                        <i class="icon-user"></i>
                                        <span class="title">Admin Users</span>
                                    </a>
                                </li>
                                <?php } ?>
                                <li class="nav-item  ">
                                    <a href="<?php echo Yii::$app->urlManager->createAbsoluteUrl("admin/user_list");?>" class="nav-link nav-toggle">
                                        <i class="icon-user"></i>
                                        <span class="title">All Users</span>
                                    </a>
                                </li>
                                <li class="nav-item  ">
                                    <a href="<?php echo Yii::$app->urlManager->createAbsoluteUrl("admin/subscribed-users");?>" class="nav-link nav-toggle">
                                        <i class="icon-user"></i>
                                        <span class="title">Subscribed Users</span>
                                    </a>
                                </li>
<!--                                <li class="nav-item  ">
                                    <a href="<?php //echo Yii::$app->urlManager->createAbsoluteUrl("media");?>" class="nav-link nav-toggle">
                                        <i class="icon-user"></i>
                                        <span class="title">Media Agencies</span>
                                    </a>
                                </li>-->
                                <?php foreach($data as $val){?>
                                <li class="nav-item  ">
                                    <a href="<?php echo Yii::$app->urlManager->createAbsoluteUrl("admin/user_list?id=".$val['user_type_id']);?>" class="nav-link nav-toggle">
                                        <i class="icon-user"></i>
                                        <span class="title"><?php echo $val['user_type'];?></span>
                                    </a>
                                </li>
                                <?php } ?>
                            </ul>                           
                        </li>
                        <li class="nav-item">
                            <a href="javascript:void(0)" class="nav-link nav-toggle">
                                <i class="icon-docs"></i>
                                <span class="title">Project</span>
                                <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu">
                                <li class="nav-item  ">
                                    <a href="<?php echo Yii::$app->urlManager->createAbsoluteUrl("projects/index");?>" class="nav-link nav-toggle">
                                        <i class="icon-list"></i>
                                        <span class="title">All Projects</span>
                                    </a>
                                </li>
                                <li class="nav-item  ">
                                    <a href="<?php echo Yii::$app->urlManager->createAbsoluteUrl("projects/create");?>" class="nav-link nav-toggle">
                                        <i class="icon-note"></i>
                                        <span class="title">Create a Project</span>
                                    </a>
                                </li>
                                <li class="nav-item  ">
                                    <a href="<?php echo Yii::$app->urlManager->createAbsoluteUrl("projects/approve");?>" class="nav-link nav-toggle">
                                        <i class="icon-lock-open"></i>
                                        <span class="title">Private Project Approval</span>
                                    </a>
                                </li>
                                <li class="nav-item  ">
                                    <a href="<?php echo Yii::$app->urlManager->createAbsoluteUrl("projects/commentslist");?>" class="nav-link nav-toggle">
                                        <i class="icon-bubbles"></i>
                                        <span class="title">Project Comments</span>
                                    </a>
                                </li>
                                <li class="nav-item  ">
                                    <a href="<?php echo Yii::$app->urlManager->createAbsoluteUrl("/project-participation");?>" class="nav-link nav-toggle">
                                        <i class="icon-flag"></i>
                                        <span class="title">Project Participations</span>
                                    </a>
                                </li>
                            </ul>                           
                        </li>
                        <li class="nav-item">
                            <a href="javascript:void(0)" class="nav-link nav-toggle">
                                <i class="icon-envelope-letter"></i>
                                <span class="title">Communique</span>
                                <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu">
                                <li class="nav-item  ">
                                    <a href="<?php echo Yii::$app->urlManager->createAbsoluteUrl("communique/new-message");?>" class="nav-link nav-toggle sentnav">
                                        <i class="icon-paper-plane"></i>
                                        <span class="title">Compose new mail</span>
                                    </a>
                                </li>
                                <li class="nav-item  ">
                                    <a href="<?php echo Yii::$app->urlManager->createAbsoluteUrl("communique/inbox-mails");?>" class="nav-link nav-toggle inboxnav">
                                        <i class="icon-envelope-open"></i>
                                        <span class="title">Inbox</span>
                                    </a>
                                </li>
                                <li class="nav-item  ">
                                    <a href="<?php echo Yii::$app->urlManager->createAbsoluteUrl("communique/sent-mails");?>" class="nav-link nav-toggle sentnav">
                                        <i class="icon-paper-plane"></i>
                                        <span class="title">Sent</span>
                                    </a>
                                </li>
                            </ul>                           
                        </li>
                    </ul>
                    <!-- END SIDEBAR MENU -->
                    <!-- END SIDEBAR MENU -->
                </div>
                <!-- END SIDEBAR -->
            </div>
            <!-- END SIDEBAR -->

        