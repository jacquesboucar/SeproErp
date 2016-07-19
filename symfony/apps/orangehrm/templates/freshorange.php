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
                    <a href="<?php echo url_for('dashboard/index'); ?>" class="homeicone">
                      <img src="<?php echo "{$imagePath}/logo.png"; ?>" width="30%" height="5%">
                    </a>
                  </div>
                  <div class="pull-right">

                        <div class="column">
                            <div id="dl-menu" class="dl-menuwrapper">
                                <button class="dl-trigger">Open Menu</button>
                                <?php include_component('core', 'mainMenu'); ?>
                            </div>
                            <!-- /dl-menuwrapper -->
                        </div>
                  </div>
                    <?php //include_component('core', 'mainMenu'); ?>
              </div><!-- /.container -->
            </nav><!-- /.navbar -->


            <div id="content" >
                <div class='container'>
                  <div class="row">
                    <?php if (count(explode('/',$_SERVER['REQUEST_URI'])) <= 5): ?>
                    <div class="col-xs-9 col-xs-offset-1 col-sm-9 col-sm-offset-1 col-md-9 col-md-offset-1 col-lg-9 col-lg-offset-1">
                    <?php else: ?>
                    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                        Add the menu bar here
                    </div>
                    <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                    <?php endif ?>
                      <?php echo $sf_content ?>
                    </div>
                    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                      <img src="<?php echo "{$imagePath}/logoligne.png"; ?>">
                    </div>
                  </div>
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
