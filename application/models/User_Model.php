<?php

class User_Model extends CI_Model {
    private $images_path = 'public/images/users/';

    function __construct() {
        parent::__construct();
        
        $this->load->database();
        
        $this->load->library('session');
        $this->load->library('email');
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
        $this->email->subject( 'Please active your account.' );
        $this->email->message( "Hi,<br/><br/>"  . " Please active your account.<br/>Linke here: " . base_url() . 'active/'. $code . "<br/><br/>Thank you.");
        $this->email->set_mailtype('html');
        $this->email->send();
    }

    private function sendChangePasswordCode( $email, $code ) {
        $this->email->from( 'hello@dogout.co', 'Dogout' );
        $this->email->to( $email );
        $this->email->subject( 'Please change your password.' );
        $this->email->message( "Hi,<br/><br/>"  . " Please change your password.<br/>Linke here: " . base_url() . 'change/'. $code . "<br/><br/>Thank you.");
        $this->email->set_mailtype('html');
        $this->email->send();
    }

    private function sendEmailVerifyCode( $email, $code ) {
        $this->email->from( 'hello@dogout.co', 'Dogout' );
        $this->email->to( $email );
        $this->email->subject( 'Please verify your email.' );
        $this->email->message( "Hi,<br/><br/>"  . " Verification Code: " . $code . "<br/><br/>Thank you.");
        $this->email->set_mailtype('html');
        $this->email->send();
    }

    private function sendForgotCode( $email, $code ) {
        $this->email->from( 'hello@dogout.co', 'Dogout' );
        $this->email->to( $email );
        $this->email->subject( 'Please change your password.' );
        $this->email->message( "Hi,.<br/><br/>"  . " Verification Code: " . $code . "<br/><br/>Thank you.");
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
}