<?php 

// Allow header partial to be overridden in individual actions
// Can be overridden by: slot('header', get_partial('module/partial'));
include_slot('header', get_partial('global/header'));
?>

    </head>
    <body>

        <header class="headerlogin">
            <div class="navbar nav">
                <div class="navbar-inner">
                    <div class="container">

                        <div class="pull-right">
                            <ul class="nav pull-right">
                                <li class="account"><a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:orange"><i class="icon-user-md"></i><?php echo __("Welcome %username%", array("%username%" => $sf_user->getAttribute('auth.firstName'))); ?> <b class="caret"></b></a>
                                    <ul class="dropdown-menu submenu">
                                        <li><?php include_component('communication', 'beaconAbout'); ?></li>
                                        <li><a href="<?php echo url_for('admin/changeUserPassword'); ?>"><i class="icon-edit-sign"></i><?php echo __('Change Password'); ?></a></li>
                                        <li class="divider"></li>
                                        <li><a href="<?php echo url_for('auth/logout'); ?>"><i class="icon-off"></i> <?php echo __('Logout'); ?></a></li>
                                    </ul>
                                </li>
                            </ul>
                      </div>
                    </div>
                </div>
            </div>

        </header>

        <div id="wrappers">
            <nav class="navbar">
              <div class="container">

                    <?php include_component('core', 'mainMenu'); ?>
              </div><!-- /.container -->
            </nav><!-- /.navbar -->


            <div id="content">

                  <div class="row">
                     <div class="col-lg-1">

                        <!-- cd-accordion-menu -->
                     </div>
                     <div class="col-lg-11">
                        <?php echo $sf_content ?>
                    </div>
                  </div>

            </div> <!-- content -->

        </div> <!-- wrapper -->

       <footer class="footerlayout">
          <div id="footer">
            <?php include_partial('global/copyright');?>
          </div> <!-- footer -->
       </footer>



<?php include_slot('footer', get_partial('global/footer'));?>