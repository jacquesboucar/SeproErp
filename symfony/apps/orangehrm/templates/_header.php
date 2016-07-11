<!DOCTYPE html>
<?php 
$cultureElements = explode('_', $sf_user->getCulture()); 
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $cultureElements[0]; ?>" lang="<?php echo $cultureElements[0]; ?>">
    
    <head>

        <title>SeproHR</title>
        
        <?php include_http_metas() ?>
        <?php include_metas() ?>
        
        <link rel="shortcut icon" href="<?php echo theme_path('images/favicon.ico')?>" />
        
        <!-- Library CSS files -->
        <link href="<?php echo theme_path('css/reset.css')?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo theme_path('css/tipTip.css')?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo theme_path('css/jquery/jquery-ui-1.8.21.custom.css')?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo theme_path('css/jquery/jquery.autocomplete.css')?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo public_path('../../themes/orange/css/bootstrap.css')?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo public_path('../../themes/orange/css/bootstrap.min.css')?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo public_path('../../themes/orange/css/style-login.css')?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo public_path('../../themes/orange/css/bootstrap-theme.css')?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo public_path('../../themes/orange/css/font-awesome.css')?>" rel="stylesheet">
        <link href="http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
        <!-- Custom CSS files -->
        <link href="<?php echo theme_path('css/main.css')?>" rel="stylesheet" type="text/css"/>
        
        <?php       
        // Library JavaScript files


        echo javascript_include_tag('jquery/jquery-1.9.1.js');

        echo javascript_include_tag('jquery/validate/jquery.validate.js');
        
        echo javascript_include_tag('jquery/jquery.ui.core.js');
        echo javascript_include_tag('jquery/jquery.autocomplete.js');
        echo javascript_include_tag('orangehrm.autocomplete.js');
        echo javascript_include_tag('jquery/jquery.ui.datepicker.js');
        echo javascript_include_tag('jquery/jquery.form.js');
        echo javascript_include_tag('jquery/jquery.tipTip.minified.js');
        echo javascript_include_tag('jquery/bootstrap-modal.js');
        echo javascript_include_tag('jquery/jquery.clickoutside.js');
        echo javascript_include_tag('jquery/bootstrap.js');
        echo javascript_include_tag('jquery/bootstrap.min.js');


        // Custom JavaScript files
        echo javascript_include_tag('orangehrm.validate.js');
        echo javascript_include_tag('archive.js');
        

        /* Note: use_javascript() doesn't work well when we need to maintain the order of JS inclutions.
         * Ex: It may include a jQuery plugin before jQuery core file. There are two position options as
         * 'first' and 'last'. But they don't seem to resolve the issue.
         */
        ?>   
        
        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
            <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->



