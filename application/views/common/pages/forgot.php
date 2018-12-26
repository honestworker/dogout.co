<div class="landing-box">
	<!-- /.landing-logo -->
    <div class="landing-logo">
        <img src="<?php echo base_url();?>assets/custom/images/logo.png">
    </div>
	<!-- /.landing-body -->
    <div class="landing-body">
        <form action="<?php echo base_url()?>forgot" method="post">
            <div class="row mb-20">
                <input type="email" class="form-control landing-input" name="email" placeholder="Email" required />
            </div>
            <div class="row">
                <button type="submit" class="btn btn-wblock btn-black">Send password reset email</button>
            </div>
        </form>
    </div>
    <div class="landing-footer">
        <p>Already I have an account? <a href="<?php echo base_url();?>login">Login here</a></p>
    </div>
</div>