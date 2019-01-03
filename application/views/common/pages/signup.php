<div class="landing-box">
	<!-- /.landing-logo -->
    <div class="landing-logo">
        <img src="<?php echo base_url();?>assets/custom/images/logo.png">
    </div>
	<!-- /.landing-body -->
    <div class="landing-body">
        <form action="<?php echo base_url();?>signup" method="post">
            <div class="row">
                <input type="email" class="form-control landing-input" name="email" placeholder="Email" required />
            </div>
            <div class="row">
                <input type="password" class="form-control landing-input" name="password" placeholder="Password" required />
            </div>
            <div class="row">
                <input type="password" class="form-control landing-input" name="confirm_password" placeholder="Repeat Password" required />
            </div>
            <div class="row landing-input landing-input-left mt-10 mb-5">
                <input type="checkbox" class="landing-check" required /><span>  I accept the <a href="<?php echo base_url();?>terms">Terms & Conditions</a></span>
            </div>
            <div class="row">
                <button type="submit" class="btn btn-wblock btn-black">Sign Up</button>
            </div>
        </form>
    </div>
    <div class="landing-footer">
        <p>Already I have an account? <a href="<?php echo base_url();?>login">Login here</a></p>
    </div>
</div>