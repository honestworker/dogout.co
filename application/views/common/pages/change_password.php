<div class="landing-box">
	<!-- /.landing-logo -->
    <div class="landing-logo">
        <img src="<?php echo base_url();?>assets/custom/images/logo.png">
    </div>
	<!-- /.landing-body -->
    <div class="landing-body">
        <form action="<?php echo base_url()?>change_password" method="post">
            <div class="row">
                <input type="password" class="form-control landing-input" name="password" placeholder="Password" required />
            </div>
            <div class="row">
                <input type="password" class="form-control landing-input" name="confirm_password" placeholder="Repeat Password" required />
            </div>
            <div class="row">
                <button type="submit" class="btn btn-wblock btn-black">Change Password</button>
            </div>
        </form>
    </div>
    <div class="landing-footer">
		<p>The dog-friendly map</p>
    </div>
</div>