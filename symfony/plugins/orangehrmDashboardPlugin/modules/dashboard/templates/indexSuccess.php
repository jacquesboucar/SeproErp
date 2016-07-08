<?php echo stylesheet_tag(plugin_web_path('orangehrmDashboardPlugin', 'css/orangehrmDashboardPlugin.css')); ?>
<style type="text/css">
    .loadmask {
        top:0;
        left:0;
        -moz-opacity: 0.5;
        opacity: .50;
        filter: alpha(opacity=50);
        background-color: #CCC;
        width: 100%;
        height: 100%;
        zoom: 1;
        background: #fbfbfb url("<?php echo plugin_web_path('orangehrmDashboardPlugin', 'images/loading.gif') ?>") no-repeat center;
    }
    .thumbnail{
        background-color: #000000;
    }
</style>
<div class="box">
    <div class="row">
        <div class="col-lg-2"></div>
        <div class="col-lg-8">
            <div class="row">
                <div class="col-lg-4">
                    <a class="thumbnail thumbnail-success" href="<?php echo url_for('recruitment/viewRecruitmentModule'); ?>">
                        <img src="<?php echo plugin_web_path('orangehrmDashboardPlugin', 'images/' .'RECRUTEMENT.jpg') ?>" alt="">
                    </a>
                </div>
                <div class="col-lg-4">
                    <a class="thumbnail thumbnail-success" href="<?php echo url_for('pim/viewEmployeeList'); ?>">
                        <img src="<?php echo plugin_web_path('orangehrmDashboardPlugin', 'images/PERSONNEL.jpg') ?>" alt="">
                    </a>
                </div>
                <div class="col-lg-4">
                    <a class="thumbnail thumbnail-success" href="<?php echo url_for('leave/viewLeaveList'); ?>">
                        <img src="<?php echo plugin_web_path('orangehrmDashboardPlugin', 'images/GESTIONRH.jpg') ?>" alt="">
                    </a>
                </div>
            </div>
        </div>
        <div class="col-lg-2"></div>
    </div>
    <div class="row">
        <div class="col-lg-2"></div>
        <div class="col-lg-8">
            <div class="row">
                <div class="col-lg-4">
                    <a class="thumbnail thumbnail-success" href="<?php echo url_for('performance/viewEmployeePerformanceTrackerList'); ?>">
                        <img src="<?php echo plugin_web_path('orangehrmDashboardPlugin', 'images/EVOLUTION.jpg') ?>" alt="">
                    </a>
                </div>
                <div class="col-lg-4">
                    <a class="thumbnail thumbnail-success" href="#">
                        <img src="<?php echo plugin_web_path('orangehrmDashboardPlugin', 'images/FORMATION.jpg') ?>" alt="">
                    </a>
                </div>
                <div class="col-lg-4">
                    <a class="thumbnail thumbnail-success" href="<?php echo url_for('admin/viewModules'); ?>">
                        <img src="<?php echo plugin_web_path('orangehrmDashboardPlugin', 'images/ADMINISTRATION.jpg') ?>" alt="">
                    </a>
                </div>
            </div>
        </div>
        <div class="col-lg-2"></div>
    </div>
</div>