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
        <div class="col-xs-4 col-sm-4 col-lg-4">
            <a class="" href="<?php echo url_for('recruitment/viewRecruitmentModule'); ?>">
                <div class="form-hexagone">
                    RECRUTEMENT
                </div>
            </a>
        </div>
        <div class="col-xs-4 col-sm-4 col-lg-4">
            <a class="" href="<?php echo url_for('pim/viewEmployeeList'); ?>">
                <div class="form-hexagone">
                    PERSONNEL SABLUX
                </div>
            </a>
        </div>
        <div class="col-xs-4 col-sm-4 col-lg-4">
            <a class="" href="<?php echo url_for('leave/viewLeaveList'); ?>">
                <div class="form-hexagone">
                    CONGES
                </div>
            </a>
        </div>
        <div class="col-xs-4 col-sm-4 col-lg-4">
            <a class="" href="<?php echo url_for('performance/viewEmployeePerformanceTrackerList'); ?>">
                <div class="form-hexagone">
                    EVALUATION
                </div>
            </a>
        </div>
        <div class="col-xs-4 col-sm-4 col-lg-4">
            <a class="" href="#">
                <div class="form-hexagone">
                    FORMATION
                </div>
            </a>
        </div>
        <div class="col-xs-4 col-sm-4 col-lg-4">
            <a class="" href="<?php echo url_for('admin/viewModules'); ?>">
            <div class="form-hexagone">
                    CONFIGURATIONS
            </div>
             </a>
        </div>
        <div class="col-xs-4 col-sm-4 col-lg-4">
            <a class="" href="<?php echo url_for('dashboard/dashboard'); ?>">
            <div class="form-hexagone">
                    DASHBOARD
            </div>
             </a>
        </div>
    </div>
</div>
