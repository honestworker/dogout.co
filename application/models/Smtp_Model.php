<?php

class Smtp_Model extends CI_Model {
    function __construct() {
        parent::__construct();

        $this->load->library('email');
        
        $this->message_main_header = '<div style="width:100%!important;background:#f2f2f2;margin:0;padding:0" bgcolor="#f2f2f2">' .
                                '<div class="block">' .
                                '<table style="width:100%!important;line-height:100%!important;border-collapse:collapse;margin:0;padding:0" width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#f2f2f2">' .
                                '<tbody><tr>' .
                                '<td class="header" style="padding: 40px 0px;" align="center">' .
                                '<a href="https://dogout.co/">' .
                                '<img src="' . base_url() . 'assets/custom/images/logo.png" alt="Dogout" style="max-width: 150px">' .
                                '</a></td></tr></table></div><div class="block">' .
                                '<table style="border-collapse:collapse" width="600" cellspacing="0" cellpadding="0" border="0" bgcolor="#ffffff" align="center"><tbody><tr><td><table width="540" align="center" cellspacing="0" cellpadding="0" border="0" style="border-collapse:collapse">' .
                                '<tbody><tr><td width="100%" height="30" style="border-collapse:collapse"></td></tr>';

        $this->message_element_header = '<tr><td style="vertical-align:top;font-family:Helvetica,arial,sans-serif;font-size:16px;color:#767676;text-align:left;line-height:20px;border-collapse:collapse" valign="top">';
        $this->message_element_footer = '</td></tr>';

        $this->message_element_seperator = '<tr><td width="100%" height="30" style="border-collapse:collapse;border-bottom-color:#e0e0e0;border-bottom-style:solid;border-bottom-width:1px"></td></tr>';
        
        $this->message_review_header = '</tbody></table><table width="540" align="center" cellspacing="0" cellpadding="0" border="0" style="border-collapse:collapse; margin-top: 20px; margin-bottom: 20px;"><tbody><tr style="padding-top:30px"><td valign="top" align="center" width="60" style="padding-right:20px"><img src="' . base_url() . 'public/images/users/no_avatar.png" width="60" height="60"><div style="font-family:\'Gotham SSm\', Helvetica,arial,sans-serif;font-size:16px;color:#222222"><strong>';
        $this->message_review_name_footer = '</strong></div>';
        $this->message_review_email_header = '<div style="font-family:\'Gotham SSm\', Helvetica,arial,sans-serif;font-size:16px;color:#222222"><strong>';
        $this->message_review_email_footer = '</strong></div></td><td valign="top" width="100%" style="vertical-align:top;font-family:Helvetica,arial,sans-serif;font-size:16px;color:#222222;text-align:left;line-height:20px;border-collapse:collapse" align="left">';
        $this->message_review_footer = '</td></tr></tbody></table>';

        $this->message_content_header = '<div style="margin: 20px;">';
        $this->message_content_footer = '</div>';

        $this->message_text_header = '<div style="line-height:24px">';
        $this->message_small_text_header = '<div style="font-size:12px">';
        $this->message_text_footer = '</div>';
        
        $this->message_review_start_checked = '<img src="' . base_url() . 'public/images/rating/star_checked.png" alt="Star " style="max-width: 20px">';
        $this->message_review_start = '<img src="' . base_url() . 'public/images/rating/star.png" alt=" " style="max-width: 20px">';

        $this->message_footer = '</table></tr></td></table></div><div class="block"><table style="width:100%!important;line-height:100%!important;border-collapse:collapse;margin:0;padding:0" width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#f2f2f2"><tr><td style="padding:20px 10px 20px 10px" align="center"><span style="font-family:Arial,Helvetica,Sans serif;font-size:10px;line-height:12px;color:#494949">© ' . date("Y") .  ' Dougout.</span></td></tr></table></div>';

        $this->message_main_footer = '</div>';
    }

    public function sendActiveCode( $email, $code ) {
        $this->email->from( 'hello@dogout.co', 'Dogout' );
        $this->email->to( $email );
        $this->email->subject( 'Active Your Dogout Administrator Account.' );
        $message_html = $this->message_main_header . $this->message_element_header;
        $message_html .= "Active your Dogout administrator account.";
        $message_html .= $this->message_element_seperator;
        
        $message_html .= $this->message_content_header;
        $message_html .= $this->message_text_header;
        $message_html .= "Hi, Welcome to Dogout";
        $message_html .= $this->message_text_footer;
        $message_html .= $this->message_text_header;
        $message_html .= "Please active your dogout administrator account.";
        $message_html .= $this->message_text_footer;
        $message_html .= $this->message_text_header;
        $message_html .= "Link here: ". base_url() . 'active/'. $code;
        $message_html .= $this->message_text_footer;
        $message_html .= $this->message_text_header;
        $message_html .= 'Thanks.';
        $message_html .= $this->message_content_footer;
        $message_html .= $this->message_text_footer;

        $message_html .= $this->message_element_footer . $this->message_footer . $this->message_main_footer;
        $this->email->message( $message_html);
        $this->email->set_mailtype('html');
        $this->email->send();
    }

    public function sendChangePasswordCode( $email, $code ) {
        $this->email->from( 'hello@dogout.co', 'Dogout' );
        $this->email->to( $email );
        $this->email->subject( 'Change Your Dogout Account Password' );
        $message_html = $this->message_main_header . $this->message_element_header;
        $message_html .= "Change your Dogout account password";
        $message_html .= $this->message_element_seperator;
        
        $message_html .= $this->message_content_header;
        $message_html .= $this->message_text_header;
        $message_html .= "Hi, Welcome to Dogout";
        $message_html .= $this->message_text_footer;
        $message_html .= $this->message_text_header;
        $message_html .= "Please change your Dogout account password.";
        $message_html .= $this->message_text_footer;
        $message_html .= $this->message_text_header;
        $message_html .= "Link here: ". base_url() . 'change/'. $code;
        $message_html .= $this->message_text_footer;
        $message_html .= $this->message_text_header;
        $message_html .= 'Thanks.';
        $message_html .= $this->message_content_footer;
        $message_html .= $this->message_text_footer;

        $message_html .= $this->message_element_footer . $this->message_footer . $this->message_main_footer;
        $this->email->message( $message_html);
        $this->email->set_mailtype('html');
        $this->email->send();
    }

    public function sendEmailVerifyCode( $email, $code ) {
        $this->email->from( 'hello@dogout.co', 'Dogout' );
        $this->email->to( $email );
        $this->email->subject( 'Verify Your Dogout Account' );
        $message_html = $this->message_main_header . $this->message_element_header;
        $message_html .= "Verify your Dogout account";
        $message_html .= $this->message_element_seperator;
        
        $message_html .= $this->message_content_header;
        $message_html .= $this->message_text_header;
        $message_html .= "Hi, Welcome to Dogout";
        $message_html .= $this->message_text_footer;
        $message_html .= $this->message_text_header;
        $message_html .= "To get started, please verify your email address with the code below.";
        $message_html .= $this->message_text_footer;
        $message_html .= $this->message_text_header;
        $message_html .= "Verification Code: " . $code;
        $message_html .= $this->message_text_footer;
        $message_html .= $this->message_text_header;
        $message_html .= 'Thanks.';
        $message_html .= $this->message_content_footer;
        $message_html .= $this->message_text_footer;

        $message_html .= $this->message_element_footer . $this->message_footer . $this->message_main_footer;
        $this->email->message( $message_html);
        $this->email->set_mailtype('html');
        $this->email->send();
    }

    public function sendForgotCode( $email, $code ) {
        $this->email->from( 'hello@dogout.co', 'Dogout' );
        $this->email->to( $email );
        $this->email->subject( 'Change Your Dogout Account Password' );
        $message_html = $this->message_main_header . $this->message_element_header;
        $message_html .= "Change your Dogout account password";
        $message_html .= $this->message_element_seperator;
        
        $message_html .= $this->message_content_header;
        $message_html .= $this->message_text_header;
        $message_html .= "Hi, Welcome to Dogout";
        $message_html .= $this->message_text_footer;
        $message_html .= $this->message_text_header;
        $message_html .= "To change your password, please verify your email address with the code below.";
        $message_html .= $this->message_text_footer;
        $message_html .= $this->message_text_header;
        $message_html .= "Verification Code: " . $code;
        $message_html .= $this->message_text_footer;
        $message_html .= $this->message_text_header;
        $message_html .= 'Thanks.';
        $message_html .= $this->message_content_footer;
        $message_html .= $this->message_text_footer;

        $message_html .= $this->message_element_footer . $this->message_footer . $this->message_main_footer;
        $this->email->message( $message_html);
        $this->email->set_mailtype('html');
        $this->email->send();
    }
    
    public function reportAppReviewToEmail( $toEmail, $place, $address, $name, $email, $rating, $comment ) {
        $this->email->from( 'hello@dogout.co', 'Dogout' );
        $this->email->to( $toEmail );
        $this->email->subject( 'App review' );
        $message_html = $this->message_main_header . $this->message_element_header;
        $message_html .= "App review";
        $message_html .= $this->message_element_seperator;
        $message_html .= $this->message_review_header;
        $message_html .= $name;
        $message_html .= $this->message_review_name_footer;
        $message_html .= $this->message_review_email_header;
        $message_html .= $email;
        $message_html .= $this->message_review_email_footer;
        $message_html .= $this->message_text_header;
        $message_html .= $place;
        $message_html .= $this->message_text_footer;
        $message_html .= $this->message_small_text_header;
        $message_html .= $address;
        $message_html .= $this->message_text_footer;
        $message_html .= $this->message_text_header;
        for ( $rating_index = 0; $rating_index < 5; $rating_index++ ) {
            if ( $rating_index < $rating ) {
                $message_html .= $this->message_review_start_checked;
            } else {
                $message_html .= $this->message_review_start;
            }
        }
        $message_html .= $this->message_text_footer;
        $message_html .= $this->message_text_header;
        $message_html .= $comment;
        $message_html .= $this->message_text_footer;
        $message_html .= $this->message_review_footer;
        $message_html .= $this->message_element_footer . $this->message_footer . $this->message_main_footer;
        $this->email->message( $message_html);
        $this->email->set_mailtype('html');
        $this->email->send();
    }
    
    public function reportNonDogFriendlyToEmail( $toEmail, $place, $address, $name, $email, $comment ) {
        $this->email->from( 'hello@dogout.co', 'Dogout' );
        $this->email->to( $toEmail );
        $this->email->subject( 'Report non-dogfriendly' );
        $message_html = $this->message_main_header . $this->message_element_header;
        $message_html .= "Report non-dogfriendly";
        $message_html .= $this->message_element_seperator;
        $message_html .= $this->message_review_header;
        $message_html .= $name;
        $message_html .= $this->message_review_name_footer;
        $message_html .= $this->message_review_email_header;
        $message_html .= $email;
        $message_html .= $this->message_review_email_footer;
        $message_html .= $this->message_text_header;
        $message_html .= $place;
        $message_html .= $this->message_text_footer;
        $message_html .= $this->message_small_text_header;
        $message_html .= $address;
        $message_html .= $this->message_text_footer;
        $message_html .= $this->message_text_footer;
        $message_html .= $this->message_text_header;
        $message_html .= $comment;
        $message_html .= $this->message_text_footer;
        $message_html .= $this->message_review_footer;
        $message_html .= $this->message_element_footer . $this->message_footer . $this->message_main_footer;
        $this->email->message( $message_html);
        $this->email->set_mailtype('html');
        $this->email->send();
    }

    public function reportNewLocationToEmail( $toEmail, $place, $address, $country, $city, $name, $email, $comment ) {
        $this->email->from( 'hello@dogout.co', 'Dogout' );
        $this->email->to( $toEmail );
        $this->email->subject( 'New Dog-Friendly Location' );
        $message_html = $this->message_main_header . $this->message_element_header;
        $message_html .= "New Dog-Friendly Location";
        $message_html .= $this->message_element_seperator;
        $message_html .= $this->message_review_header;
        $message_html .= $name;
        $message_html .= $this->message_review_name_footer;
        $message_html .= $this->message_review_email_header;
        $message_html .= $email;
        $message_html .= $this->message_review_email_footer;
        $message_html .= $this->message_text_header;
        $message_html .= $place;
        $message_html .= $this->message_text_footer;
        $message_html .= $this->message_small_text_header;
        $message_html .= $address;
        $message_html .= $this->message_text_footer;
        $message_html .= $this->message_small_text_header;
        $message_html .= $country;
        $message_html .= $this->message_text_footer;
        $message_html .= $this->message_small_text_header;
        $message_html .= $city;
        $message_html .= $this->message_text_footer;
        $message_html .= $this->message_text_footer;
        $message_html .= $this->message_text_header;
        $message_html .= $comment;
        $message_html .= $this->message_text_footer;
        $message_html .= $this->message_review_footer;
        $message_html .= $this->message_element_footer . $this->message_footer . $this->message_main_footer;
        $this->email->message( $message_html);
        $this->email->set_mailtype('html');
        $this->email->send();
    }
}