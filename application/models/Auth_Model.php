<?php

class Auth_Model extends CI_Model {
    
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
            $char_seed = 'bcgZMEPhlnXQoziy@#CVWJmepxqakRAF!GHLNODTUstuvwjSfBdrKIY';
        } else if ( $type == 'common' ) {
            $char_seed = 'bcgZMEPhlnXQoziyCVWJmepxqakRAFGHLNODTUstuvwjSfBdrKIY';
        }
        
        $chars_len = strlen($char_seed);
        $ret = '';
        for($i = 0; $i < $len; $i++) {
            $ret .= $char_seed[rand(0, $chars_len  - 1)];
        }
        
        return $ret;
    }

    private function generate_token() {
        $token = "";
        $repeat = 1;
        while ($repeat) {
            $token = $this->generate_code(100, 'middle');
            if ( !$this->db->get_where('users', array('token' => $token))->result() ) {
                $repeat = 0;
            }
        } 
        
        return $token;
    }
	
    private function sendEmailVerifyCode( $email, $code ) {
        $this->email->from( 'hello@dogout.co', 'Dogout' );
        $this->email->to( $email );
        $this->email->subject( 'Please verify your email.' );
        $this->email->message( "Hi,<br/><br/>"  . " Verification Code: " . $code . "<br/><br/>Thank you.");
        $this->email->set_mailtype('html');
        $this->email->send();
    }

    public function createUser( $data ) {
        $response = array(
            'user' => null,
            'error_type' => -3
        );
		if ( $user_row = $this->db->get_where('users', array('name' => $data['name'], 'email !=' => $data['email'], 'role' => $data['role']))->result() ) {
            $response['error_type'] = -1; // Already registered
            return $response;
        }
		if ( $user_row = $this->db->get_where('users', array('email' => $data['email'], 'role' => $data['role']))->result() ) {
            $user = $user_row[0];
            if ($user->status == 'activated') {
                $response['error_type'] = -2; // Already registered
                return $response;
            }

            $salt = $this->generate_code(10, 'middle');
            $this->db->update('users', array('name' => $data['name'], 'salt' => $salt, 'password' => md5($data['password'] . $salt), 'updated_at' => date('Y-m-d H:i:s')), array('id' => $user->id) );

            if ($data['role'] == 2) {
                $code = $this->generate_code(5, 'light');
                $this->db->update('users', array('email_code' => $code), array('id' => $user->id) );
                $this->sendEmailVerifyCode($user->email, $code);
            }

            $response['error_type'] = 0;
            $response['user'] = array(
                'id'			    => $user->id,
                'email'			    => $user->email,
                'name'			    => $data['name'],
                'created_at'	    => $user->created_at,
            );
        } else {
            $salt = $this->generate_code(10, 'middle');
            $user_data = array(
                'email'			    => $data['email'],
                'name'			    => $data['name'],
                'role'			    => $data['role'],
                'salt'			    => $salt,
                'password'		    => md5($data['password'] . $salt),
                'status'		    => 'registered',
                'created_at'	    => date('Y-m-d H:i:s'),
                'updated_at'	    => date('Y-m-d H:i:s'),
            );
            
            if ( $this->db->insert('users', $user_data) ) {
                $user_id = $this->db->insert_id();
                
                if ( $user_row = $this->db->get_where('users', array('id' => $user_id))->result() ) {
                    $user = $user_row[0];

                    if ($data['role'] == 2) {
                        $code = $this->generate_code(5, 'light');
                        $this->db->update('users', array('email_code' => $code), array('id' => $user->id) );
                        $this->sendEmailVerifyCode($user->email, $code);
                    }

                    $response['error_type'] = 0;
                    $response['user'] = array(
                        'id'			    => $user->id,
                        'name'			    => $user->name,
                        'email'			    => $user->email,
                        'created_at'	    => $user->created_at,
                    );
                }
            }
        }

        return $response;
    }

    public function activation( $activation_code ) {
		if ( $this->db->get_where('users', array('activation_code' => $activation_code) )->result() ) {
            $this->db->update('users', array('status' => 'activated'), array('activation_code' => $activation_code) );
            return 1;
        }
        return 0;
    }

    public function login( $data ) {
        $response = array(
            'token' => '',
            'error_type' => -3 // No user
        );
        
		if ( $user_row = $this->db->get_where('users', array('email' => $data['email'], 'role' => $data['role']) )->result() ) {
            $user = $user_row[0];
            if ( $user->password == md5($data['password'] . $user->salt) ) {
                if ( $user->status  != 'activated' ) {
                    $response['error_type'] = -1; // No activated
                    return $response;
                }
            } else {
                $response['error_type'] = -2; // Wrong Password
                return $response;
            }

            $response['error_type'] = 0; // OK
            if ( $data['role'] == 1) {
                $this->session->set_userdata('id', $user->id);
                $this->session->set_userdata('email', $user->email);
            } else {
                $token = $this->generate_token();
                $this->db->update('users', array('token' => $token), array('id' => $user->id) );
                $response['token'] = $token;
                return $response;
            }
        }        

        return $response;
    }

    public function logout( $token ) {
		if ( $user_row = $this->db->get_where('users', array('token' => $token) )->result() ) {
            $user = $user_row[0];
            $this->db->update('users', array('token' => ''), array('id' => $user->id) );
            return 0;
        }
        return -1;
    }
}