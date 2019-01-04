<?php

class User_Model extends CI_Model {
    protected $images_path = 'public/images/users/';
    protected $counts_per_page = 10;

    function __construct() {
        parent::__construct();
        
        $this->load->database();
        
        $this->load->library('session');
        $this->load->library('email');

        $this->response = array(
            'data' => null,
            'error_type' => -1
        );
        
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
        
        $this->message_review_header = '</tbody></table><table width="540" align="center" cellspacing="0" cellpadding="0" border="0" style="border-collapse:collapse; margin-top: 20px; margin-bottom: 20px;"><tbody><tr style="padding-top:30px"><td valign="top" align="center" width="60" style="padding-right:30px"><div style="font-family:\'Gotham SSm\', Helvetica,arial,sans-serif;font-size:16px;color:#222222"><strong>';
        $this->message_review_name_footer = '</strong></div><img src="' . base_url() . 'public/images/users/no_avatar.png" width="60" height="60"></td><td valign="top" width="100%" style="vertical-align:top;font-family:Helvetica,arial,sans-serif;font-size:16px;color:#222222;text-align:left;line-height:20px;border-collapse:collapse" align="left">';
        $this->message_review_footer = '</td></tr></tbody></table>';

        $this->message_content_header = '<div style="margin-top: 20px; margin-bottom: 20px;">';
        $this->message_content_footer = '</div>';

        $this->message_text_header = '<div style="line-height:24px">';
        $this->message_small_text_header = '<div style="font-size:12px">';
        $this->message_text_footer = '</div>';
        
        $this->message_review_start_checked = '<img src="' . base_url() . 'public/images/rating/star_checked.png" alt="Star " style="max-width: 20px">';
        $this->message_review_start = '<img src="' . base_url() . 'public/images/rating/star.png" alt=" " style="max-width: 20px">';

        $this->message_footer = '</table></tr></td></table></div><div class="block"><table style="width:100%!important;line-height:100%!important;border-collapse:collapse;margin:0;padding:0" width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#f2f2f2"><tr><td style="padding:20px 10px 20px 10px" align="center"><span style="font-family:Arial,Helvetica,Sans serif;font-size:10px;line-height:12px;color:#494949">© ' . date("Y") .  ' Dougout.</span></td></tr></table></div>';

        $this->message_main_footer = '</div>';
    }

    private function generate_code($len = 30, $type = 'heavy') {
        $char_seed = 'bcghlpxUVW34Jq8drafs7#BCwjGHL125NOZMY06%EPX9!@QneDRAFSozmTKItuvkiy';
        if ( $type == 'light' ) {
            $char_seed = '0123456789';
        } else if ( $type == 'middle' ) {
            $char_seed = 'bcgZMEPhlnXQoziy@#CVWJmepxqakRAF!GHLNODTUstuvwjS%fBdrKIY';
        } else if ( $type == 'common' ) {
            $char_seed = 'bcgZMEPhlnXQoziyCVWJmepxqakRAFGHLNODTUstuvwjSfBdrKIY';
        }
        
        $chars_len = strlen($char_seed);
        $ret = '';
        for($i = 0; $i < $len; $i++){
            $ret .= $char_seed[rand(0, $chars_len  - 1)];
        }
        
        return $ret;
    }

    private function sendActiveCode( $email, $code ) {
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

    private function sendChangePasswordCode( $email, $code ) {        
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

    private function sendEmailVerifyCode( $email, $code ) {
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

    private function sendForgotCode( $email, $code ) {
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
    
    private function reportAppReviewToEmail( $email, $place, $address, $name, $rating, $comment ) {
        $this->email->from( 'hello@dogout.co', 'Dogout' );
        $this->email->to( $email );
        $this->email->subject( 'App review' );
        $message_html = $this->message_main_header . $this->message_element_header;
        $message_html .= "App review";
        $message_html .= $this->message_element_seperator;
        $message_html .= $this->message_review_header;
        $message_html .= $name;
        $message_html .= $this->message_review_name_footer;
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
    
    private function reportNonDogFriendlyToEmail( $email, $place, $address, $name, $comment ) {
        $this->email->from( 'hello@dogout.co', 'Dogout' );
        $this->email->to( $email );
        $this->email->subject( 'Report non-dogfriendly' );
        $message_html = $this->message_main_header . $this->message_element_header;
        $message_html .= "Report non-dogfriendly";
        $message_html .= $this->message_element_seperator;
        $message_html .= $this->message_review_header;
        $message_html .= $name;
        $message_html .= $this->message_review_name_footer;
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

    public function emailVerify( $role, $email, $code ) {
		if ( $user_row = $this->db->get_where('users', array('email' => $email, 'role' => $role) )->result() ) {
            $user = $user_row[0];
            if ( $user->email_code != $code ) {
                return -1;
            }

            $this->db->update('users', array('status' => 'activated'), array('id' => $user->id) );
            return 0;
        }

        return -2;
    }

    public function forgotPassword( $role, $email ) {
		if ( $user_row = $this->db->get_where('users', array('email' => $email, 'role' => $role) )->result() ) {
            $user = $user_row[0];
            $code = $this->generate_code(5, 'light');
            $this->db->update('users', array('password_code' => $code), array('id' => $user->id) );
            
            if ($user->role == 1) {
                $code = $this->generate_code(50, 'common');
                $this->db->update('users', array('password_code' => $code), array('id' => $user->id) );
                $this->sendChangePasswordCode($email, $code);
            } else {
                $code = $this->generate_code(5, 'light');
                $this->db->update('users', array('password_code' => $code), array('id' => $user->id) );
                $this->sendForgotCode($email, $code);
            }
            return 0;
        }

        return -1;
    }
    
    public function changePassword( $role, $email, $password, $code ) {
		if ( $user_row = $this->db->get_where('users', array('email' => $email, 'role' => $role) )->result() ) {
            $user = $user_row[0];
            if ( $user->password_code != $code ) {
                return -1;
            }

            $this->db->update('users', array('password_code' => ''), array('id' => $user->id) );
            $salt = $this->generate_code(10, 'middle');
            $this->db->update('users', array('salt' => $salt, 'password' => md5($password . $salt)), array('id' => $user->id) );
            return 0;
        }

        return -2;
    }
    
    public function changeAdminPassword( $code, $password ) {
		if ( $user_row = $this->db->get_where('users', array('password_code' => $code, 'role' => 1) )->result() ) {
            $user = $user_row[0];

            $this->db->update('users', array('password_code' => ''), array('id' => $user->id) );
            $salt = $this->generate_code(10, 'middle');
            $this->db->update('users', array('salt' => $salt, 'password' => md5($password . $salt)), array('id' => $user->id) );
            return 0;
        }

        return -1;
    }
    
    public function activePassword( $code ) {
		if ( $user_row = $this->db->get_where('users', array('password_code' => $code, 'role' => 1) )->result() ) {
            $user = $user_row[0];

            $this->db->update('users', array('password_code' => ''), array('id' => $user->id) );
            $salt = $this->generate_code(10, 'middle');
            $this->db->update('users', array('salt' => $salt, 'password' => md5($password . $salt)), array('id' => $user->id) );
            return 0;
        }
    }

    /*
    *  Get user counts by according the user role
    */
    public function getUserCounts() {
        $result = array(
            'admins' => 0,
            'users' => 0,
        );
        $user_rows = $this->db->get_where('users', array('role' => 1) )->result();
        if ($user_rows) {
            $result['admins'] = count($user_rows);
        }
        $user_rows = $this->db->get_where('users', array('role' => 2) )->result();
        if ($user_rows) {
            $result['users'] = count($user_rows);
        }
        return $result;
    }

    /*
    *  Get all users by according the user role
    */
    public function getUsers($role) {
        $this->db->select('id, email, name, created_at, status'); // Select field
        $this->db->from('users');
        if ($role) {
            $this->db->where('role', $role);
        }
        $this->db->order_by('created_at', 'DESC');
        $users = $this->db->get()->result();

        return $users;
    }
    
    /*
    *  Active User
    */
    public function activeUser($user_id) {
		if ( $user_row = $this->db->get_where('users', array('id' => $user_id) )->result() ) {
            $user = $user_row[0];
            if ( $user->status == 'activated' ) {
                return -1;
            }
            if ( $user->role == 1 ) {
                $code = $this->generate_code(50, 'common');
                $this->db->update('users', array('activation_code' => $code), array('id' => $user->id) );
                $this->sendActiveCode($user->email, $code);
                return 0;
            } else if ( $user->role == 2) {
                $this->db->update('users', array('status' => 'activated'), array('id' => $user->id) );
                return 0;
            }
        }

        return -2;
    }

    /*
    *  Disable User
    */
    public function disableUser($user_id) {
		if ( $user_row = $this->db->get_where('users', array('id' => $user_id) )->result() ) {
            $user = $user_row[0];
            if ($user->status == 'disabled') {
                return -1;
            }
            $this->db->update('users', array('status' => 'disabled'), array('id' => $user_id));
            return 0;
        }
        return -2;
    }

    /*
    *  Delete User
    */
    public function deleteUser($user_id) {
		if ( $user_row = $this->db->get_where('users', array('id' => $user_id) )->result() ) {
            $user = $user_row[0];

            $this->db->delete('users', array('id' => $user->id));
            return 0;
        }

        return -1;
    }

    /*
    *  Report App Review to the Administrator Email
    */
    public function reportAppReview($token, $place, $address, $rating, $comment) {
		if ( $user_row = $this->db->get_where('users', array('token' => $token, 'role' => 2) )->result() ) {
            $user = $user_row[0];
            
		    if ( $rating_row = $this->db->get_where('reports', array('user_id' => $user->id, 'type' => 'App Review', 'place' => $place, 'address' => $address) )->result() ) {
                return -2;
            }
            
            $this->db->insert('reports', array('user_id' => $user->id, 'type' => 'App Review', 'place' => $place, 'address' => $address, 'rating' => $rating, 'comment' => $comment, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')));
            
		    if ( $admin_rows = $this->db->get_where('users', array('role' => 1, 'status' => 'activated') )->result() ) {
                foreach ( $admin_rows as $admin ) {
                    $this->reportAppReviewToEmail( $admin->email, $place, $address, $user->name, $rating, $comment );
                }
            }
            
            $this->reportAppReviewToEmail( 'dogout.co@gmail.com', $place, $address, $user->name, $rating, $comment );

            return 0;
        }

        return -1;
    }
    
    /*
    *  Report Non-DogFriendly to the Administrator Email
    */
    public function reportNonDogFriendly($token, $place, $address, $comment) {
		if ( $user_row = $this->db->get_where('users', array('token' => $token, 'role' => 2) )->result() ) {
            $user = $user_row[0];
            
		    if ( $rating_row = $this->db->get_where('reports', array('user_id' => $user->id, 'type' => 'Non DogFriendly', 'place' => $place, 'address' => $address) )->result() ) {
                return -2;
            }
            
            $this->db->insert('reports', array('user_id' => $user->id, 'type' => 'Non DogFriendly', 'place' => $place, 'address' => $address, 'rating' => '', 'comment' => $comment, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')));
            
		    if ( $admin_rows = $this->db->get_where('users', array('role' => 1, 'status' => 'activated') )->result() ) {
                foreach ( $admin_rows as $admin ) {
                    $this->reportNonDogFriendlyToEmail( $admin->email, $place, $address, $user->name, $comment );
                }
            }
            
            $this->reportNonDogFriendlyToEmail( 'dogout.co@gmail.com', $place, $address, $user->name, $comment );

            return 0;
        }

        return -1;
    }

    /*
    *  Get App Review
    */
    public function getAppReview($token, $page, $place, $address) {
		if ( $user_row = $this->db->get_where('users', array('token' => $token, 'role' => 2) )->result() ) {
            $user = $user_row[0];

            $this->response['data']['place'] = $place;
            $this->response['data']['address'] = $address;
            $this->response['data']['page'] = $page;
            $this->response['data']['total'] = 0;
            $this->response['data']['rating'] = 0;
            $this->response['data']['is_end'] = true;
            $this->response['data']['reports'] = null;

            $sql_query = "SELECT COUNT(rating) AS total, SUM(rating) AS sum_rating";
            $sql_query .= " FROM reports ";
            $sql_query .= " WHERE place = '" . $place . "' AND address = '" . $address . "' AND type = 'App Review';";
            $total_reports = $this->db->query($sql_query)->result();
            if ( $total_reports ) {
                $this->response['data']['total'] = $total_reports[0]->total + 0;
                $this->response['data']['rating'] = $total_reports[0]->sum_rating / $total_reports[0]->total;
                if ( $total_reports[0]->total >  $page * $this->counts_per_page ) {
                    $this->response['data']['is_end'] = false;
                }
                
                $this->db->select('id, user_id, rating, comment, created_at')->from('reports')->where(array('type' => 'App Review', 'place' => $place, 'address' => $address))->limit($this->counts_per_page, ($page - 1) * $this->counts_per_page);
                $report_rows = $this->db->order_by('created_at', 'DESC')->get()->result();
                if ( $report_rows ) {
                    foreach ( $report_rows as $report_row ) {
                        $report = array(
                            'rating' => $report_row->rating + 0,
                            'comment' => $report_row->comment,
                            'created_at' => $report_row->created_at,
                            'user_name' => '',
                            'user_email' => '',
                        );
                        if ( $report_user_row = $this->db->get_where('users', array('id' => $report_row->user_id, 'role' => 2) )->result() ) {
                            $report_user = $report_user_row[0];
                            $report['user_name'] = $report_user->name;
                            $report['user_email'] = $report_user->email;
                        }
                        $this->response['data']['reports'][] = $report;
                    }
                }
            }
            
            $this->response['error_type'] = 0;
        }

        return $this->response;
    }
    
    /*
    *  Get Non-DogFriendly
    */
    public function getNonDogFriendly($token, $page, $place, $address) {
		if ( $user_row = $this->db->get_where('users', array('token' => $token, 'role' => 2) )->result() ) {
            $user = $user_row[0];

            $this->response['data']['place'] = $place;
            $this->response['data']['address'] = $address;
            $this->response['data']['page'] = $page;
            $this->response['data']['total'] = 0;
            $this->response['data']['is_end'] = true;
            $this->response['data']['reports'] = null;

            $sql_query = "SELECT COUNT(id) AS total";
            $sql_query .= " FROM reports ";
            $sql_query .= " WHERE place = '" . $place . "' AND address = '" . $address . "' AND type = 'Non DogFriendly';";
            $total_reports = $this->db->query($sql_query)->result();
            if ( $total_reports ) {
                $this->response['data']['total'] = $total_reports[0]->total + 0;
                if ( $total_reports[0]->total >  $page * $this->counts_per_page ) {
                    $this->response['data']['is_end'] = false;
                }
                
                $this->db->select('id, user_id, comment, created_at')->from('reports')->where(array('type' => 'Non DogFriendly', 'place' => $place, 'address' => $address))->limit($this->counts_per_page, ($page - 1) * $this->counts_per_page);
                $report_rows = $this->db->order_by('created_at', 'DESC')->get()->result();
                if ( $report_rows ) {
                    foreach ( $report_rows as $report_row ) {
                        $report = array(
                            'comment' => $report_row->comment,
                            'created_at' => $report_row->created_at,
                            'user_name' => '',
                            'user_email' => '',
                        );
                        if ( $report_user_row = $this->db->get_where('users', array('id' => $report_row->user_id, 'role' => 2) )->result() ) {
                            $report_user = $report_user_row[0];
                            $report['user_name'] = $report_user->name;
                            $report['user_email'] = $report_user->email;
                        }
                        $this->response['data']['reports'][] = $report;
                    }
                }
            }
            
            $this->response['error_type'] = 0;
        }

        return $this->response;
    }

    /*
    *  Get All App Reviews Group By Place & Address
    */
    public function getAllAppReviews() {
        $report_rows = $this->db->select('COUNT(id) as count, AVG(rating) as rating, MAX(updated_at) as updated_at, place, address')->from('reports')->where('type', 'App Review')->group_by(array('place', 'address'))->order_by('updated_at', 'DESC')->get()->result();
        return $report_rows;
    }
    
    /*
    *  Get All Non-DogFriendlys Group By Place & Address
    */
    public function getAllNonDogFriendlys() {
        $report_rows = $this->db->select('COUNT(id) as count, MAX(updated_at) as updated_at, place, address')->from('reports')->where('type', 'Non DogFriendly')->group_by(array('place', 'address'))->order_by('updated_at', 'DESC')->get()->result();
        return $report_rows;
    }

    /*
    *  Get App Reviews
    */
    public function getAppReviews($place, $address) {
        $response = array(
            'place' => $place,
            'address' => $address,
            'total' => 0,
            'rating' => 0,
            'reports' => []
        );
        $total_rows = $this->db->select('COUNT(id) as count, AVG(rating) as rating')->from('reports')->where(array('type' => 'App Review', 'place' => $place, 'address' => $address))->order_by('created_at', 'DESC')->get()->result();
        $response['total'] = $total_rows[0]->count;
        $response['rating'] = $total_rows[0]->rating;
        $report_rows = $this->db->select('id, user_id, rating, comment, created_at')->from('reports')->where(array('type' => 'App Review', 'place' => $place, 'address' => $address))->order_by('created_at', 'DESC')->get()->result();
        if ( $report_rows ) {
            foreach ( $report_rows as $report_row ) {
                $report = array(
                    'id' => $report_row->id,
                    'rating' => $report_row->rating,
                    'comment' => $report_row->comment,
                    'created_at' => $report_row->created_at,
                    'user_name' => '',
                    'user_email' => '',
                );
                if ( $report_user_row = $this->db->get_where('users', array('id' => $report_row->user_id, 'role' => 2) )->result() ) {
                    $report_user = $report_user_row[0];
                    $report['user_name'] = $report_user->name;
                    $report['user_email'] = $report_user->email;
                }
                $response['reports'][] = $report;
            }
        }
        return $response;
    }

    /*
    *  Get Non-DogFriendlys
    */
    public function getNonDogFriendlys($place, $address) {
        $response = array(
            'place' => $place,
            'address' => $address,
            'total' => 0,
            'reports' => []
        );
        $total_rows = $this->db->select('COUNT(id) as count')->from('reports')->where(array('type' => 'Non DogFriendly', 'place' => $place, 'address' => $address))->order_by('created_at', 'DESC')->get()->result();
        $response['total'] = $total_rows[0]->count;
        $report_rows = $this->db->select('id, user_id, comment, created_at')->from('reports')->where(array('type' => 'Non DogFriendly', 'place' => $place, 'address' => $address))->order_by('created_at', 'DESC')->get()->result();
        if ( $report_rows ) {
            foreach ( $report_rows as $report_row ) {
                $report = array(
                    'id' => $report_row->id,
                    'comment' => $report_row->comment,
                    'created_at' => $report_row->created_at,
                    'user_name' => '',
                    'user_email' => '',
                );
                if ( $report_user_row = $this->db->get_where('users', array('id' => $report_row->user_id, 'role' => 2) )->result() ) {
                    $report_user = $report_user_row[0];
                    $report['user_name'] = $report_user->name;
                    $report['user_email'] = $report_user->email;
                }
                $response['reports'][] = $report;
            }
        }
        return $response;
    }
    
    /*
    *  Delete Reports
    */
    public function deleteReports($type, $place, $address) {
        $report_rows = $this->db->select('id')->from('reports')->where(array('type' => $type, 'place' => $place, 'address' => $address))->get()->result();
        if ( $report_rows ) {
            foreach ( $report_rows as $report ) {
                $this->db->delete('reports', array('id' => $report->id));
            }
            return 0;
        }
        return -1;
    }

    /*
    *  Delete Report
    */
    public function deleteReport($type, $id) {
        $report_row = $this->db->select('id')->from('reports')->where(array('type' => $type, 'id' => $id))->get()->result();
        if ( $report_row ) {
            $this->db->delete('reports', array('id' => $id));
            return 0;
        }
        return -1;
    }
}