<?php 

// Allow header partial to be overridden in individual actions
// Can be overridden by: slot('header', get_partial('module/partial'));
include_slot('header', get_partial('global/header'));
?>

    </head>
    <body>

        <header class="headerlogin">
            <div id="branding">
               <!-- <a href="http://www.orangehrm.com/" target="_blank"><img src="<?php //echo theme_path('images/logo.png')?>" width="283" height="56" alt="OrangeHRM"/></a> -->
                <!--<a href="http://www.orangehrm.com/user-survey-registration.php" class="subscribe" target="_blank"><?php echo __('Join OrangeHRM Community'); ?></a>-->
                <a href="#" id="welcome" class="panelTrigger"><?php echo __("Welcome %username%", array("%username%" => $sf_user->getAttribute('auth.firstName'))); ?></a>
                <div id="welcome-menu" class="panelContainer">
                    <ul>
                        <li><?php include_component('communication', 'beaconAbout'); ?></li>
                        <li><a href="<?php echo url_for('admin/changeUserPassword'); ?>"><?php echo __('Change Password'); ?></a></li>
                        <li><a href="<?php echo url_for('auth/logout'); ?>"><?php echo __('Logout'); ?></a></li>
                    </ul>
                </div>
                  <?php include_component('communication', 'beaconNotification'); ?>
<!--                <a href="#" id="help" class="panelTrigger"><?php echo __("Help & Training"); ?></a>
                <div id="help-menu" class="panelContainer">
                    <ul>

                        <li><a href="http://www.orangehrm.com/support-plans.php?utm_source=application_support&amp;utm_medium=app_url&amp;utm_campaign=orangeapp" target="_blank"><?php echo __('Support'); ?></a></li>
                        <li><a href="http://www.orangehrm.com/training.php?utm_source=application_traning&amp;utm_medium=app_url&amp;utm_campaign=orangeapp" target="_blank"><?php echo __('Training'); ?></a></li>
                        <li><a href="http://www.orangehrm.com/addon-plans.shtml?utm_source=application_addons&amp;utm_medium=app_url&amp;utm_campaign=orangeapp" target="_blank"><?php echo __('Add-Ons'); ?></a></li>
                        <li><a href="http://www.orangehrm.com/customizations.php?utm_source=application_cus&amp;utm_medium=app_url&amp;utm_campaign=orangeapp" target="_blank"><?php echo __('Customizations'); ?></a></li>
                        <li><a href="http://forum.orangehrm.com/" target="_blank"><?php echo __('Forum'); ?></a></li>
                        <li><a href="http://blog.orangehrm.com/" target="_blank"><?php echo __('Blog'); ?></a></li>
                        <li><a href="http://sourceforge.net/apps/mantisbt/orangehrm/view_all_bug_page.php" target="_blank"><?php echo __('Bug Tracker'); ?></a></li>
                    </ul>
                </div>-->
            </div> <!-- branding -->
        </header>

        <div id="wrappers">
            <nav class="navbar navbar-default">
              <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                  </button>
                  <a class="navbar-brand" href="#"></a>
                </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

            <?php include_component('core', 'mainMenu'); ?>

            </div><!-- /.navbar-collapse -->
          </div><!-- /.container-fluid -->
        </nav>


            <div id="content">

                  <?php echo $sf_content ?>

            </div> <!-- content -->

        </div> <!-- wrapper -->

       <footer class="footerlayout">
          <div id="footer">
            <?php include_partial('global/copyright');?>
          </div> <!-- footer -->
       </footer>



<?php include_slot('footer', get_partial('global/footer'));?>