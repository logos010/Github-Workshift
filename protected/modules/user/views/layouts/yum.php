<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>        
        <link rel="stylesheet" media="screen,projection" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/css/reset.css" /> <!-- RESET -->
        <link rel="stylesheet" media="screen,projection" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/css/admin_main.css" /> <!-- MAIN STYLE SHEET -->
        <link rel="stylesheet" media="screen,projection" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/css/2col.css" title="2col" /> <!-- DEFAULT: 2 COLUMNS -->
        <link rel="alternate stylesheet" media="screen,projection" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/css/1col.css" title="1col" /> <!-- ALTERNATE: 1 COLUMN -->
        <!--[if lte IE 6]><link rel="stylesheet" media="screen,projection" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/css/main-ie6.css" /><![endif]--> <!-- MSIE6 -->
        <link rel="stylesheet" media="screen,projection" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/css/style.css" /> <!-- GRAPHIC THEME -->
        <link rel="stylesheet" media="screen,projection" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/css/mystyle.css" /> <!-- WRITE YOUR CSS CODE HERE -->
        <?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>    
    </head>
    <body>
        <script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/js/jquery.switcher.js"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/js/toggle.js"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/js/ui.core.js"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/js/ui.tabs.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                $(".tabs > ul").tabs();
//                var time = new Date();
//                var hour = time.getHours();
//                var minute = time.getMinutes();
//                alert(hour+':'+minute);
            });
        </script>
        <div id="main">
            <!-- Tray -->
            <div id="tray" class="box">

                <p class="f-left box">

                    <!-- Switcher -->
                    <span class="f-left" id="switcher">
                        <a href="#" rel="1col" class="styleswitch ico-col1" title="Display one column"><img src="<?php echo Yii::app()->baseUrl ?>/images/switcher-1col.gif" alt="1 Column" /></a>
                        <a href="#" rel="2col" class="styleswitch ico-col2" title="Display two columns"><img src="<?php echo Yii::app()->baseUrl ?>/images/switcher-2col.gif" alt="2 Columns" /></a>
                    </span>
                    Web Application: <strong>Workshift</strong>
                </p>

                <p class="f-right">
                    User: <strong><a href="<?php echo Yii::app()->createUrl('profile/profile/update/', array('id' => Yii::app()->user->id)) ?>"><?php echo Yii::app()->user->data()->username;?></a></strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                    Role: <strong><a href="#"><?php echo ServiceUtil::getRole(false); ?></a></strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                    <strong><a href="<?php echo Yii::app()->createUrl('user/user/changePassword'); ?>" id="changepass">Change Pass</a></strong>
                    <strong><a href="<?php echo Yii::app()->createUrl('user/auth/logout'); ?>" id="logout">Log out</a></strong>
                </p>

            </div> <!--  /tray -->

            <hr class="noscreen" />

            <!-- Menu -->
            <div id="menu" class="box">
            <?php Yii::app()->user->can('user_delete'); ?>
                <ul class="box f-right">
                    <li><a href="#"><span><strong>Calendar View &raquo;</strong></span></a></li>
                </ul>
                <?php                
                    $id = (isset($_GET['id'])) ? $_GET['id'] : 0;
                    $month = (isset($_GET['month'])) ? $_GET['month'] : CURRENT_MONTH;
                    $year = (isset($_GET['year'])) ? $_GET['year'] : CURRENT_YEAR;                                       
                ?>
                <?php //$this->renderMenu(); ?>
                <ul class="box">
                    <li><a href="<?php echo Yii::app()->createUrl('/user/user/admin'); ?>"><span>Home</span></a></li>                    
                    <li><a href="<?php echo Yii::app()->createUrl('/user/user/checkinList'); ?>"><span>Check login</span></a></li>
                    <li><a href="<?php echo Yii::app()->createUrl('/user/user/salaryList'); ?>"><span>Salary Calculate</span></a></li>                    
                    <?php 
                    if (ServiceUtil::getRole(true) <= 2 ): ?>
                    <li onmouseover="this.className = 'dropdown-on'" onmouseout="this.className = 'dropdown-off'">
                        <div><a href="javascript:void(0)"><span>User Management</span></a>
                        <!-- Dropdown menu -->
                            <div class="drop">
                                <ul class="box">
                                        <li><a href="<?php echo Yii::app()->createUrl('/user/user/index') ?>">User List</a></li>                                        
                                        <li><a href="<?php echo Yii::app()->createUrl('/profile/profile/admin/') ?>">User Profile</a></li>                                        
                                </ul>
                            </div> <!-- /drop -->
			</div>
                    </li>
                    <?php endif; ?>
                    <?php if (ServiceUtil::getRole(true) <= 2): ?>
                    <li onmouseover="this.className = 'dropdown-on'" onmouseout="this.className = 'dropdown-off'">
                        <div><a href="javascript:void(0)"><span>Role Management</span></a>
                        <!-- Dropdown menu -->
                            <div class="drop">
                                <ul class="box">
                                        <li><a href="<?php echo Yii::app()->createUrl('role/role/') ?>">Role List</a></li>
                                        <li><a href="<?php echo Yii::app()->createUrl('role/role/create') ?>">Create User Role</a></li>
                                        <li><a href="<?php echo Yii::app()->createUrl('role/permission/admin') ?>">Permission</a></li>
                                        <li><a href="<?php echo Yii::app()->createUrl('role/permission/create') ?>">Grant Permission</a></li>                                        
                                        <li><a href="<?php echo Yii::app()->createUrl('role/action/admin') ?>">Actions</a></li>
                                        <li><a href="<?php echo Yii::app()->createUrl('role/action/create') ?>">Create New Action</a></li>
                                </ul>
                            </div> <!-- /drop -->
			</div>
                    </li>
                    <?php endif; ?>
                    <li><a href="<?php echo (ServiceUtil::getRole(true) <= 2) ? Yii::app()->createUrl('/user/holiday') : Yii::app()->createUrl('/user/holiday/index'); ?>"><span>Holidays</span></a></li>
                </ul>

            </div> <!-- /header -->

            <hr class="noscreen" />

            <!-- Columns -->
            <!-- Columns -->
            <div id="cols" class="box">

                <!-- Aside (Left Column) -->
                <div id="aside" class="box">

                    <div class="padding box">
                        <!-- Logo (Max. width = 200px) -->
                        <p id="logo"><a href="#"><img src="" alt="Our logo" title="Visit Site" /></a></p>

                        <?php 
                            if (ServiceUtil::getRole(true) <= 2): 
                                $this->widget('application.components.CreateLinkwidget', array(
                                    'module' => Yii::app()->controller->module->id,
                                    'controller' => Yii::app()->controller->id
                                )); 
                            endif;
                        ?>
                        
                    </div> <!-- /padding -->
                </div> <!-- /aside -->

                <hr class="noscreen" />

                <!-- Content (Right Column) -->
                <div id="content" class="box">

                    <h1><?php echo $this->title; ?></h1>
                    
                    <div class="clear"></div>
                    
                    <div id="usercontent">
                        <?php echo $content;  ?>
                    </div>

                </div> <!-- /content -->

            </div> <!-- /cols -->
            <hr class="noscreen" />
            <!-- Footer -->
            <div id="footer" class="box">

                <p class="f-left">Copyright &copy; 2013 MAU DAT Ltd, All Rights Reserved &reg;</p>

                <p class="f-right">Templates by <a href="http://www.adminizio.com/">Adminizio</a></p>

            </div> <!-- /footer -->
        </div><!-- /main -->
    </body>
</html>

