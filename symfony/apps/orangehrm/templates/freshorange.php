<?php 

// Allow header partial to be overridden in individual actions
// Can be overridden by: slot('header', get_partial('module/partial'));
include_slot('header', get_partial('global/header'));
$imagePath = theme_path("images/login");
?>

    </head>
    <body>

        <header class="headerbackend arrow-box">
            <div class="navbar navbar-inner-backend">

                    <div class="container-fluid">

                        <div class="pull-right">
                            <ul class="nav">
                                <li class="account"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo __("%username% %userlastname%", array("%username%" => $sf_user->getAttribute('auth.firstName'), "%userlastname%"=> $sf_user->getAttribute('auth.firstName'))); ?> <b class="caret"></b></a>
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

        </header>

        <div class="wrap">
            <nav class="navbar navbarcontent">
              <div class="container">
                  <div class="pull-left">
                   <img src="<?php echo "{$imagePath}/logo.png"; ?>" width="50%" height="50%">
                  </div>

                    <?php //include_component('core', 'mainMenu'); ?>
              </div><!-- /.container -->
            </nav><!-- /.navbar -->


            <div id="content" >

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

              <div class="pull-left footer-block copyright">
                  <?php include_partial('global/copyright');?>
              </div>
              <div class="pull-right footer-block versions">
                   SeproGRH V1.0
              </div>

       </footer>



<?php include_slot('footer', get_partial('global/footer'));?>