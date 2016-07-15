<?php
$imagePath = theme_path("images/login");
?>


<div class="container-fluid">
    <div class="row">
        <div class="logo-back">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
                <img src="<?php echo "{$imagePath}/logo.png"; ?>">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-8 col-md-4 col-lg-4"></div>
        <div class="col-xs-12 col-sm-8 col-md-4 col-lg-4">
            <div class="box" id="boxlogin">
                <form action="<?php echo url_for('auth/validateCredentials'); ?>" method="post" class="login__form">
                    <h1 class="form-title text-center" style="color: #770a82">Mon Compte</h1>
                    <?php
                    echo $form->renderHiddenFields(); // rendering csrf_token
                    ?>
                    <div class="form-group">
                        <div class="input-icon">
                            <?php if (!empty($message)) : ?>
                                <span id="spanMessage"><?php echo __($message); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-icon">
                            <i class="icon-user"></i>
                            <input type="text" id="txtUsername" name="txtUsername" value="" required="required" class="form-control" placeholder="Username"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="input-icon">
                            <i class="icon-lock"></i>
                            <input type="password" id="txtPassword" name="txtPassword" required="required" class="form-control" placeholder="Password"/>
                        </div>
                    </div>

                    <hr>
                    <div class="pull-left">
                        <input type="checkbox" id="remember_me" name="_remember_me" value="on" />
                        <label for="remember_me">se souvenir</label>
                    </div>
                    <div class="pull-right">
                        <input type="submit" id="_submit" name="_submit" value="Login" class="btn btn-primary" style="color: #770a82 " />
                    </div><br><br>


                </form>
            </div><!--end box-->
        </div>
        <div class="col-xs-12 col-sm-8 col-md-4 col-lg-4"></div>
    </div>
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
