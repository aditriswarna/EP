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
                        <!--<li class="nav-item start ">
                            <a href="<?php //echo Yii::$app->request->BaseUrl; ?>/site/dashboard" class="nav-link nav-toggle">
                                <i class="icon-bar-chart"></i>
                                <span class="title">Dashboard</span>
                            </a>
                            <ul class="sub-menu">
                                <li class="nav-item start ">
                                    <a href="index.html" class="nav-link ">
                                        <i class="icon-bar-chart"></i>
                                        <span class="title">Dashboard 1</span>
                                    </a>
                                </li>
                                <li class="nav-item start ">
                                    <a href="dashboard_2.html" class="nav-link ">
                                        <i class="icon-bulb"></i>
                                        <span class="title">Dashboard 2</span>
                                        <span class="badge badge-success">1</span>
                                    </a>
                                </li>
                                <li class="nav-item start ">
                                    <a href="dashboard_3.html" class="nav-link ">
                                        <i class="icon-graph"></i>
                                        <span class="title">Dashboard 3</span>
                                        <span class="badge badge-danger">5</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="<?php //echo Yii::$app->urlManager->createAbsoluteUrl("site/user-profile");?>" class="nav-link nav-toggle">
                                <i class="icon-user"></i>
                                <span class="title">My Profile</span>
                            </a>
                        </li>-->
                        <li class="nav-item">
                            <a href="<?php echo Yii::$app->request->BaseUrl; ?>/../../search-projects" class="nav-link nav-toggle">
                                <i class="icon-bar-chart"></i>
                                <span class="title">Explore Projects</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="javascript:void(0)" class="nav-link nav-toggle">
                                <i class="icon-docs"></i>
                                <span class="title">Projects</span>
                                <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu">
                                <li class="nav-item  ">
                                    <a href="<?php echo Yii::$app->urlManager->createAbsoluteUrl("../../projects");?>" class="nav-link nav-toggle">
                                        <i class="icon-list"></i>
                                        <span class="title">All Projects</span>
                                    </a>
                                </li>
                                <li class="nav-item  ">
                                    <a href="<?php echo Yii::$app->urlManager->createAbsoluteUrl("../../create-project");?>" class="nav-link nav-toggle">
                                        <i class="icon-note"></i>
                                        <span class="title">Create Project</span>
                                    </a>
                                </li>
                                <li class="nav-item  ">
                                    <a href="<?php echo Yii::$app->urlManager->createAbsoluteUrl("../../private-project-requests");?>" class="nav-link nav-toggle">
                                        <i class="icon-lock-open"></i>
                                        <span class="title">Private Project Approval</span>
                                    </a>
                                </li>
                            </ul>                           
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo Yii::$app->urlManager->createAbsoluteUrl("../../inbox");?>" class="nav-link nav-toggle">
                                <i class="icon-envelope-letter"></i>
                                <span class="title">Communique</span>
                                <!--<span class="arrow"></span>-->
                            </a>
                            <!--<ul class="sub-menu">
                                <li class="nav-item  ">
                                    <a href="<?php //echo Yii::$app->urlManager->createAbsoluteUrl("../../compose-mail");?>" class="nav-link nav-toggle">
                                        <i class="icon-envelope-open"></i>
                                        <span class="title">Compose new mail</span>
                                    </a>
                                </li>
                                <li class="nav-item  ">
                                    <a href="<?php //echo Yii::$app->urlManager->createAbsoluteUrl("../../inbox");?>" class="nav-link nav-toggle">
                                        <i class="icon-envelope-open"></i>
                                        <span class="title">Inbox</span>
                                    </a>
                                </li>
                                <li class="nav-item  ">
                                    <a href="<?php //echo Yii::$app->urlManager->createAbsoluteUrl("../../sent");?>" class="nav-link nav-toggle">
                                        <i class="icon-paper-plane"></i>
                                        <span class="title">Sent</span>
                                    </a>
                                </li>
                            </ul>-->
                        </li>
                    </ul>
                    <!-- END SIDEBAR MENU -->
                    <!-- END SIDEBAR MENU -->
                </div>
                <!-- END SIDEBAR -->
            </div>
            <!-- END SIDEBAR -->
