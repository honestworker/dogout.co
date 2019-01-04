<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {
    function __construct() {
        parent::__construct();
        
        $this->load->helper('form');
        $this->load->helper('email');
        $this->load->library('session');
        $this->load->library('form_validation');
       
        $this->load->helper('url');
        
        $this->load->model('Auth_Model');
        $this->load->model('User_Model');
        
        $this->response = array(
            'status' => 'fail',
            'message' => '',
            'data' => null,
            'error_type' => 'no_fill'
        );
    }

    public function login() {
        $jsonRequest = json_decode(file_get_contents('php://input'), true);
        if ( !isset($jsonRequest['email']) || !isset($jsonRequest['password']) ) {
            echo json_encode($this->response);
            exit(-1);
        }
        if ( strlen($jsonRequest['password'])  < 6 || strlen($jsonRequest['password']) > 255 ) {
            $this->response['error_type'] = 'length_error';
            echo json_encode($this->response);
            exit(-1);
        }
        if ( !valid_email($jsonRequest['email']) ) {
            $this->response['error_type'] = 'invalid_email';
            echo json_encode($this->response);
            exit(-1);
        }
        
        $user_data = array(
            'role'			    => 2,
            'email'			    => strtolower(strip_tags(trim($jsonRequest['email']))),
            'password'		    => strip_tags($jsonRequest['password']),
        );
        $result = $this->Auth_Model->login($user_data);
        if ( $result['error_type'] == 0 ) {
            $this->response['status'] = 'success';
            $this->response['data'] = $result['token'];
            $this->response['error_type'] = '';
        } else if ( $result['error_type'] == -1 ) {
            $this->response['error_type'] = 'no_activated';
        } else if ( $result['error_type'] == -2 ) {
            $this->response['error_type'] = 'wrong_password';
        } else if ( $result['error_type'] == -3 ) {
            $this->response['error_type'] = 'no_user';
        }
        
        echo json_encode($this->response);
        exit(-1);
    }

    public function signup() {
        $jsonRequest = json_decode(file_get_contents('php://input'), true);
        if ( !isset($jsonRequest['email']) || !isset($jsonRequest['name']) || !isset($jsonRequest['password']) || !isset($jsonRequest['confirm_password']) ) {
            echo json_encode($this->response);
            exit(-1);
        }
        if ( strlen($jsonRequest['name'])  < 2 || strlen($jsonRequest['name']) > 255 || strlen($jsonRequest['password'])  < 6 || strlen($jsonRequest['password']) > 255 || strlen($jsonRequest['confirm_password'])  < 6 || strlen($jsonRequest['confirm_password']) > 255 ) {
            $this->response['error_type'] = 'length_error';
            echo json_encode($this->response);
            exit(-1);
        }
        if ( !valid_email($jsonRequest['email']) ) {
            $this->response['error_type'] = 'invalid_email';
            echo json_encode($this->response);
            exit(-1);
        }
        if ($jsonRequest['password'] != $jsonRequest['confirm_password']) {
            $this->response['error_type'] = 'confirm_password';
            echo json_encode($this->response);
            exit(-1);
        }
        
        $user_data = array(
            'email'			    => strtolower(strip_tags(trim($jsonRequest['email']))),
            'name'              => strip_tags($jsonRequest['name']),
            'password'		    => strip_tags($jsonRequest['password']),
            'role'			    => 2,
        );
        
        $result = $this->Auth_Model->createUser($user_data);
        if ( $result['error_type'] == 0 ) {
            $this->response['status'] = 'success';
            $this->response['data'] = $result['user'];
            $this->response['error_type'] = '';
        } else if ( $result['error_type'] == -1 ) {
            $this->response['error_type'] = 'duplicate_name';
        } else if ( $result['error_type'] == -2 ) {
            $this->response['error_type'] = 'registered';
        } else if ( $result['error_type'] == -3 ) {
            $this->response['error_type'] = 'database';
        }
        
        echo json_encode($this->response);
        exit(-1);
    }

    public function logout() {
        $jsonRequest = json_decode(file_get_contents('php://input'), true);
        if ( !isset($jsonRequest['token'])) {
            echo json_encode($this->response);
            exit(-1);
        }
        
        $result = $this->Auth_Model->logout(strip_tags(trim($jsonRequest['token'])));
        if ( $result == 0 ) {
            $this->response['status'] = 'success';
            $this->response['error_type'] = '';
        } else if ( $result == -1 ) {
            $this->response['error_type'] = 'token_error';
        }
        
        echo json_encode($this->response);
        exit(-1);
    }

    public function emailVerify() {
        $jsonRequest = json_decode(file_get_contents('php://input'), true);
        if ( !isset($jsonRequest['email']) || !isset($jsonRequest['code']) ) {
            echo json_encode($this->response);
            exit(-1);
        }
        if ( !valid_email($jsonRequest['email']) ) {
            $this->response['error_type'] = 'invalid_email';
            echo json_encode($this->response);
            exit(-1);
        }
        
        $result = $this->User_Model->emailVerify(2, strtolower(strip_tags(trim($jsonRequest['email']))), strip_tags(trim($jsonRequest['code'])));
        if ( $result == 0 ) {
            $this->response['status'] = 'success';
            $this->response['error_type'] = '';
        } else if ( $result == -1 ) {
            $this->response['error_type'] = 'error_code';
        } else if ( $result == -2 ) {
            $this->response['error_type'] = 'no_user';
        }
        
        echo json_encode($this->response);
        exit(-1);
    }
    
    public function forgotPassword() {
        $jsonRequest = json_decode(file_get_contents('php://input'), true);
        if ( !isset($jsonRequest['email']) ) {
            echo json_encode($this->response);
            exit(-1);
        }
        if ( !valid_email($jsonRequest['email']) ) {
            $this->response['error_type'] = 'invalid_email';
            echo json_encode($this->response);
            exit(-1);
        }
        
        $result = $this->User_Model->forgotPassword(2, strtolower(strip_tags(trim($jsonRequest['email']))));
        if ( $result == 0 ) {
            $this->response['status'] = 'success';
            $this->response['error_type'] = '';
        } else if ( $result == -1 ) {
            $this->response['error_type'] = 'no_user';
        }
        
        echo json_encode($this->response);
        exit(-1);
    }

    public function changePassword() {
        $jsonRequest = json_decode(file_get_contents('php://input'), true);
        if ( !isset($jsonRequest['email']) || !isset($jsonRequest['password']) || !isset($jsonRequest['code']) ) {
            echo json_encode($this->response);
            exit(-1);
        }
        if ( strlen($jsonRequest['password'])  < 6 || strlen($jsonRequest['password']) > 255 ) {
            $this->response['error_type'] = 'length_error';
            echo json_encode($this->response);
            exit(-1);
        }
        if ( !valid_email($jsonRequest['email']) ) {
            $this->response['error_type'] = 'invalid_email';
            echo json_encode($this->response);
            exit(-1);
        }
        
        $result = $this->User_Model->changePassword(2, strtolower(strip_tags(trim($jsonRequest['email']))), strip_tags($jsonRequest['password']), strip_tags(trim($jsonRequest['code'])));
        if ( $result == 0 ) {
            $this->response['status'] = 'success';
            $this->response['error_type'] = '';
        } else if ( $result == -1 ) {
            $this->response['error_type'] = 'error_code';
        } else if ( $result == -2 ) {
            $this->response['error_type'] = 'no_user';
        }
        
        echo json_encode($this->response);
        exit(-1);
    }

    public function reportAppReview() {
        $jsonRequest = json_decode(file_get_contents('php://input'), true);
        if ( !isset($jsonRequest['token']) || !isset($jsonRequest['place']) || !isset($jsonRequest['address']) || !isset($jsonRequest['rating']) || !isset($jsonRequest['comment']) ) {
            echo json_encode($this->response);
            exit(-1);
        }
        if ( strlen($jsonRequest['place']) > 255 || strlen($jsonRequest['address']) > 255 || strlen($jsonRequest['comment']) > 255 ) {
            $this->response['error_type'] = 'length_error';
            echo json_encode($this->response);
            exit(-1);
        }
        
        $result = $this->User_Model->reportAppReview(strip_tags(trim($jsonRequest['token'])), strip_tags(trim($jsonRequest['place'])), strip_tags(trim($jsonRequest['address'])), $jsonRequest['rating'], strip_tags(trim($jsonRequest['comment'])));
        if ( $result == 0 ) {
            $this->response['status'] = 'success';
            $this->response['error_type'] = '';
        } else if ( $result == -1 ) {
            $this->response['error_type'] = 'token_error';
        } else if ( $result == -2 ) {
            $this->response['error_type'] = 'registered';
        }
        
        echo json_encode($this->response);
        exit(-1);
    }

    public function reportNonDogFriendly() {
        $jsonRequest = json_decode(file_get_contents('php://input'), true);
        if ( !isset($jsonRequest['token']) || !isset($jsonRequest['place']) || !isset($jsonRequest['address']) || !isset($jsonRequest['comment']) ) {
            echo json_encode($this->response);
            exit(-1);
        }
        if ( strlen($jsonRequest['place']) > 255 || strlen($jsonRequest['address']) > 255 || strlen($jsonRequest['comment']) > 255 ) {
            $this->response['error_type'] = 'length_error';
            echo json_encode($this->response);
            exit(-1);
        }

        $result = $this->User_Model->reportNonDogFriendly(strip_tags(trim($jsonRequest['token'])), strip_tags(trim($jsonRequest['place'])), strip_tags(trim($jsonRequest['address'])), strip_tags(trim($jsonRequest['comment'])));
        if ( $result == 0 ) {
            $this->response['status'] = 'success';
            $this->response['error_type'] = '';
        } else if ( $result == -1 ) {
            $this->response['error_type'] = 'token_error';
        } else if ( $result == -2 ) {
            $this->response['error_type'] = 'registered';
        }
        
        echo json_encode($this->response);
        exit(-1);
    }

    public function getAppReview() {
        $jsonRequest = json_decode(file_get_contents('php://input'), true);
        if ( !isset($jsonRequest['token']) || !isset($jsonRequest['place']) || !isset($jsonRequest['address']) ) {
            echo json_encode($this->response);
            exit(-1);
        }
        if ( strlen($jsonRequest['place']) > 255 || strlen($jsonRequest['address']) > 255 ) {
            $this->response['error_type'] = 'length_error';
            echo json_encode($this->response);
            exit(-1);
        }
        $page = 1;
        if ( isset($jsonRequest['page']) ) {
            $page = $jsonRequest['page'];
        }

        $result = $this->User_Model->getAppReview(strip_tags(trim($jsonRequest['token'])), $page, strip_tags(trim($jsonRequest['place'])), strip_tags(trim($jsonRequest['address'])));
        if ( $result['error_type'] == 0 ) {
            $this->response['status'] = 'success';
            $this->response['error_type'] = '';
            $this->response['data'] = $result['data'];
        } else if ( $result['error_type'] == -1 ) {
            $this->response['error_type'] = 'token_error';
        }
        
        echo json_encode($this->response);
        exit(-1);
    }
    
    public function getNonDogFriendly() {
        $jsonRequest = json_decode(file_get_contents('php://input'), true);
        if ( !isset($jsonRequest['token']) || !isset($jsonRequest['place']) || !isset($jsonRequest['address']) ) {
            echo json_encode($this->response);
            exit(-1);
        }
        if ( strlen($jsonRequest['place']) > 255 || strlen($jsonRequest['address']) > 255 ) {
            $this->response['error_type'] = 'length_error';
            echo json_encode($this->response);
            exit(-1);
        }
        $page = 1;
        if ( isset($jsonRequest['page']) ) {
            $page = $jsonRequest['page'];
        }
        
        $result = $this->User_Model->getNonDogFriendly(strip_tags(trim($jsonRequest['token'])), $page, strip_tags(trim($jsonRequest['place'])), strip_tags(trim($jsonRequest['address'])));
        if ( $result['error_type'] == 0 ) {
            $this->response['status'] = 'success';
            $this->response['error_type'] = '';
            $this->response['data'] = $result['data'];
        } else if ( $result['error_type'] == -1 ) {
            $this->response['error_type'] = 'token_error';
        }
        
        echo json_encode($this->response);
        exit(-1);
    }
}