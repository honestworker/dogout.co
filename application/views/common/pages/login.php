<div class="landing-box">
	<!-- /.landing-logo -->
    <div class="landing-logo">
        <img src="<?php echo base_url();?>assets/custom/images/logo.png">
    </div>
	<!-- /.landing-body -->
    <div class="landing-body">
        <form action="<?php echo base_url()?>login" method="post">
            <div class="row">
                <input type="email" class="form-control landing-input" name="email" placeholder="Email" required />
            </div>
            <div class="row">
                <input type="password" class="form-control landing-input" name="password" placeholder="Password" required />
            </div>
            <div class="row landing-input landing-input-left mt-10 mb-5">
                <input type="checkbox" class="landing-check"><span>  Remember me</span>
            </div>
            <div class="row">
                <button type="submit" class="btn btn-wblock btn-black">Log in</button>
            </div>
            <div class="row landing-input landing-input-left mt-10 mb-5">
                <p style="text-align: center;"><a href="<?php echo base_url();?>forgot">Forgot password?</a></p>
            </div>
        </form>
    </div>
    <div class="landing-footer">
        <p>Don't have an account? <a href="<?php echo base_url()?>signup">Create one</a></p>
    </div>
</div>