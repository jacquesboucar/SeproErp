<?php
$imagePath = theme_path("images/login");
?>

<style type="text/css">

    body {
        background-color: #eaeaea;
        height: 700px;
    }

    img {
        border: none;
    }
    #btnLogin {
        padding: 0;
    }
    input:not([type="image"]) {
        background-color: transparent;
        border: none;
    }

    input:focus, select:focus, textarea:focus {
        background-color: transparent;
        border: none;
    }

    .textInputContainer {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 20px;
        
    }

    #divLogin {
        height: 520px;
        width: 100%;
        border-style: hidden;
        margin: auto;
        padding-left: 10px;
    }

    #divUsername {
        padding-top: 0px;       
    }

    #divPassword {
        padding-top: 0px;
        
    }

    #txtUsername {
        width: 300px ;
	 height: 30px;
        border: 10px;
	 background-color: #FFFFFF;
        
    }

    #txtPassword {
        width: 300px;
	height: 30px;
        border: 10px;
        background-color: #FFFFFF;
    }

    #txtUsername, #txtPassword {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 20px;
        color: #000000;
        padding-top:0;
    }
    
    #divLoginHelpLink {
        width: 270px;
        background-color: transparent;
        height: 20px;
        margin-top: 12px;
        margin-right: 0px;
        margin-bottom: 0px;
        margin-left: 50%;
    }

    #divLoginButton {
        padding: 25px 45px 45px; ;
	width: 300px;
	vertical-align: middle;
    }

    #btnLogin {
        background-color: #A13487 ;
        cursor:pointer;
        width: 200px;
        height: 26px;
        border:  1px solid rgba(0, 0, 0, 0.1);
        color:#112233;
        font-weight: bold;
        font-size: 13px;
    }

    #divLink {
        padding-left: 230px;
        padding-top: 105px;
        float: left;
    }

    #divLogo {
         
	margin-left: 40%;
    }

    #spanMessage {
        background: transparent url(<?php echo "{$imagePath}/mark.png"; ?>) no-repeat;
        padding-left: 18px; 
        padding-top: 5px;
        color: #A13487;
        font-weight: bold;
    }
    
    #logInPanelHeading{
        
	margin: 0px auto;
        font-family:'Lato' sans-serif;
        font-size: 22px;
        color: #A13487;
        font-weight: bold;
	padding: 1em 1em 2em;
    }
    
    .form-hint {
    color: #A13487;
    padding: 4px 8px;
    left:-150px;
	 font-size: 15px;

}

    
.form-group  {
	margin-bottom: 30px;
	display: inline-block;
   	vertical-align: middle;
	 margin-left: -5px;
	margin-right: -25px;
	  
}
.form-signin {
 max-width: 300px;
padding: 15px 35px 45px;
    padding-top: 15px;
    padding-right-value: 35px;
    padding-bottom: 45px;
    padding-left-value: 35px;
    padding-left-ltr-source: physical;
    padding-left-rtl-source: physical;
    padding-right-ltr-source: physical;
    padding-right-rtl-source: physical;
margin: 2em auto;
background-color: #98BB9C;
border: 1px solid rgba(0, 0, 0, 0.1);
}

.form-group:before, .form-group:after {

   display: table;

}

 


    
</style>

<div id= "divlogin" >
    <div id="divLogo">
	 <img src="<?php echo "{$imagePath}/logo.png"; ?>" />
      </div>

    <form class="form-signin" method="post" action="<?php echo url_for('auth/validateCredentials'); ?>">
        <input type="hidden" name="actionID"/>
        <input type="hidden" name="hdnUserTimeZoneOffset" id="hdnUserTimeZoneOffset" value="0" />
        <?php 
            echo $form->renderHiddenFields(); // rendering csrf_token 
        ?>
        <div id="logInPanelHeading"><?php echo __('Veuillez vous connecter'); ?></div>

        <div class="form-group" class="textInputContainer">
            <?php echo $form['Username']->render(array(
		'placeholder' => __('Identifiant') ,
)); ?>
        
       </div>
        <div class="form-group" class="textInputContainer">
            <?php echo $form['Password']->render(array(
                'placeholder' => __('Mot de Passe') ,
)); ?>
         
        </div>

        <div id="divLoginButton">
            <input type="submit" name="Submit" class="button" id="btnLogin" value="<?php echo __('CONNEXION'); ?>" />
            <?php if (!empty($message)) : ?>
            <span id="spanMessage"><?php echo __($message); ?></span>
            <?php endif; ?>
        </div>
    </form>

</div>

<?php include_partial('global/footer_copyright_social_links'); ?>

<script type="text/javascript">
    
    function calculateUserTimeZoneOffset() {
        var myDate = new Date();
        var offset = (-1) * myDate.getTimezoneOffset() / 60;
        return offset;
    }
            
    function addHint(inputObject, hintImageURL) {
        if (inputObject.val() == '') {
            inputObject.css('background', "url('" + hintImageURL + "') no-repeat 10px 3px");
        }
    }
            
    function removeHint() {
       $('.form-hint').css('display', 'none');
    }
    
    function showMessage(message) {
        if ($('#spanMessage').size() == 0) {
            $('<span id="spanMessage"></span>').insertAfter('#btnLogin');
        }

        $('#spanMessage').html(message);
    }
    
    function validateLogin() {
        var isEmptyPasswordAllowed = false;
        
        if ($('#txtUsername').val() == '') {
            showMessage('<?php echo __('Username cannot be empty'); ?>');
            return false;
        }
        
        if (!isEmptyPasswordAllowed) {
            if ($('#txtPassword').val() == '') {
                showMessage('<?php echo __('Password cannot be empty'); ?>');
                return false;
            }
        }
        
        return true;
    }
    
    $(document).ready(function() {
        /*Set a delay to compatible with chrome browser*/
        setTimeout(checkSavedUsernames,100);
        
        $('#txtUsername').focus(function() {
            removeHint();
        });
        
        $('#txtPassword').focus(function() {
             removeHint();
        });
        
        $('.form-hint').click(function(){
            removeHint();
            $('#txtUsername').focus();
        });
        
        $('#hdnUserTimeZoneOffset').val(calculateUserTimeZoneOffset().toString());
        
        $('#frmLogin').submit(validateLogin);
        
    });

    function checkSavedUsernames(){
        if ($('#txtUsername').val() != '') {
            removeHint();
        }
    }

    if (window.top.location.href != location.href) {
        window.top.location.href = location.href;
    }
</script>
