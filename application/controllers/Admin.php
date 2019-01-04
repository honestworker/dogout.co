<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    function __construct() {
        parent::__construct();
        
        $this->load->helper('form');
        $this->load->library('session');
        $this->load->library('form_validation');
       
        $this->load->helper('url');
        
        $this->load->model('User_Model');

        $this->response = array(
            'status' => 'fail',
            'message' => '',
            'data' => null,
            'error_type' => 'no_fill'
        );
        $this->flash_data = array(
            'errors' => null,
            'alerts' => array(
                'info' => null,
                'success' => null,
                'error' => null
            )
        );
    }
    
	public function index() {
        $user_data = $this->session->get_userdata();
        if ( !$user_data ) {
            redirect('../login');
        } else {
            if ( !isset($user_data['id']) ) {
                redirect('../login');
            }
        }
        
        $data['users_counts'] = $this->User_Model->getUserCounts();
        
        $this->load->view('admin/layouts/header');
        $this->load->view('admin/layouts/siderbar');
        $this->load->view('admin/pages/dashboard', $data);
        $this->load->view('admin/layouts/footer');
    }
    
	public function getAllAdmins() {
        $user_data = $this->session->get_userdata();
        if ( !$user_data ) {
            redirect('../login');
        } else {
            if ( !isset($user_data['id']) ) {
                redirect('../login');
            }
        }
        
        $data['admins'] = $this->User_Model->getUsers(1);
        
        $this->load->view('admin/layouts/header');
        $this->load->view('admin/layouts/siderbar');
        $this->load->view('admin/pages/admins', $data);
        $this->load->view('admin/layouts/footer');
    }
    
	public function getAllUsers() {
        $user_data = $this->session->get_userdata();
        if ( !$user_data ) {
            redirect('../login');
        } else {
            if ( !isset($user_data['id']) ) {
                redirect('../login');
            }
        }
        
        $data['users'] = $this->User_Model->getUsers(2);
        
        $this->load->view('admin/layouts/header');
        $this->load->view('admin/layouts/siderbar');
        $this->load->view('admin/pages/users', $data);
        $this->load->view('admin/layouts/footer');
    }
    
	public function activeUser($user_id) {
        $user_data = $this->session->get_userdata();
        if ( !$user_data ) {
            redirect('../login');
        } else {
            if ( !isset($user_data['id']) ) {
                redirect('../login');
            }
        }
        
        $result = $this->User_Model->activeUser($user_id);
        if ($result == 0) {
            $this->response['error_type'] = '';
            $this->response['status'] = 'success';
            $this->flash_data['alerts']['success'][] = 'The user has been actived successfully.';
        } else if ($result == -1) {
            $this->response['error_type'] = 'no_action';
            $this->response['message'] = 'The user has beed activated already.';
        } else if ($result == -2) {
            $this->response['error_type'] = 'no_profile';
            $this->response['message'] = 'Can not find this user profile.';
        } else if ($result == -3) {
            $this->response['error_type'] = 'no_user';
            $this->response['message'] = 'Can not find this user.';
        }
        
        $this->session->set_flashdata('flash_data', $this->flash_data);
        
        echo json_encode($this->response);
        exit(-1);
    }

	public function disableUser($user_id) {
        $user_data = $this->session->get_userdata();
        if ( !$user_data ) {
            redirect('../login');
        } else {
            if ( !isset($user_data['id']) ) {
                redirect('../login');
            }
        }
        
        $result = $this->User_Model->disableUser($user_id);
        if ($result == 0) {
            $this->response['error_type'] = '';
            $this->response['status'] = 'success';
            $this->flash_data['alerts']['success'][] = 'The user has been disabled successfully.';
        } else if ($result == -1) {
            $this->response['error_type'] = 'no_action';
            $this->response['message'] = 'The user has beed disabled already.';
        } else if ($result == -2) {
            $this->response['error_type'] = 'no_user';
            $this->response['message'] = 'Can not find this user.';
        }
        
        $this->session->set_flashdata('flash_data', $this->flash_data);
        
        echo json_encode($this->response);
        exit(-1);
    }

	public function deleteUser($user_id) {
        $user_data = $this->session->get_userdata();
        if ( !$user_data ) {
            redirect('../login');
        } else {
            if ( !isset($user_data['id']) ) {
                redirect('../login');
            }
        }
        
        $result = $this->User_Model->deleteUser($user_id);
        if ($result == 0) {
            $this->response['error_type'] = '';
            $this->response['status'] = 'success';
            $this->flash_data['alerts']['success'][] = 'The user has been disabled successfully.';
        } else if ($result == -1) {
            $this->response['error_type'] = 'no_user';
            $this->response['message'] = 'Can not find this user.';
        }
        
        $this->session->set_flashdata('flash_data', $this->flash_data);
        
        echo json_encode($this->response);
        exit(-1);
    }

	public function getAllAppReviews() {
        $user_data = $this->session->get_userdata();
        if ( !$user_data ) {
            redirect('../login');
        } else {
            if ( !isset($user_data['id']) ) {
                redirect('../login');
            }
        }
        
        $data['reports'] = $this->User_Model->getAllAppReviews();
        
        $this->load->view('admin/layouts/header');
        $this->load->view('admin/layouts/siderbar');
        $this->load->view('admin/pages/appreviews', $data);
        $this->load->view('admin/layouts/footer');
    }

	public function getAllNonDogFriendlys() {
        $user_data = $this->session->get_userdata();
        if ( !$user_data ) {
            redirect('../login');
        } else {
            if ( !isset($user_data['id']) ) {
                redirect('../login');
            }
        }
        
        $data['reports'] = $this->User_Model->getAllNonDogFriendlys();
        
        $this->load->view('admin/layouts/header');
        $this->load->view('admin/layouts/siderbar');
        $this->load->view('admin/pages/nondogfriendlys', $data);
        $this->load->view('admin/layouts/footer');
    }
    
	public function getAppReview($place, $address) {
        $user_data = $this->session->get_userdata();
        if ( !$user_data ) {
            redirect('../login');
        } else {
            if ( !isset($user_data['id']) ) {
                redirect('../login');
            }
        }
        
        $data['data'] = $this->User_Model->getAppReviews(urldecode($place), urldecode($address));
        
        $this->load->view('admin/layouts/header');
        $this->load->view('admin/layouts/siderbar');
        $this->load->view('admin/pages/appreview', $data);
        $this->load->view('admin/layouts/footer');
    }

	public function getNonDogFriendly($place, $address) {
        $user_data = $this->session->get_userdata();
        if ( !$user_data ) {
            redirect('../login');
        } else {
            if ( !isset($user_data['id']) ) {
                redirect('../login');
            }
        }
        
        $data['data'] = $this->User_Model->getNonDogFriendlys(urldecode($place), urldecode($address));
        
        $this->load->view('admin/layouts/header');
        $this->load->view('admin/layouts/siderbar');
        $this->load->view('admin/pages/nondogfriendly', $data);
        $this->load->view('admin/layouts/footer');
    }

    public function deleteAppReviews($place, $address) {
        $user_data = $this->session->get_userdata();
        if ( !$user_data ) {
            redirect('../login');
        } else {
            if ( !isset($user_data['id']) ) {
                redirect('../login');
            }
        }
        
        $result = $this->User_Model->deleteReports('App Review', urldecode($place), urldecode($address));
        if ($result == 0) {
            $this->response['error_type'] = '';
            $this->response['status'] = 'success';
            $this->flash_data['alerts']['success'][] = 'The app reviews have been deleted successfully.';
        } else if ($result == -1) {
            $this->response['error_type'] = 'no_appreviews';
            $this->response['message'] = 'Can not find these app reviews.';
        }
        
        $this->session->set_flashdata('flash_data', $this->flash_data);
        
        echo json_encode($this->response);
        exit(-1);
    }

    public function deleteNonDogFriendlys($place, $address) {
        $user_data = $this->session->get_userdata();
        if ( !$user_data ) {
            redirect('../login');
        } else {
            if ( !isset($user_data['id']) ) {
                redirect('../login');
            }
        }
        
        $result = $this->User_Model->deleteReports('Non DogFriendly', urldecode($place), urldecode($address));
        if ($result == 0) {
            $this->response['error_type'] = '';
            $this->response['status'] = 'success';
            $this->flash_data['alerts']['success'][] = 'The non dogfriendlys have been deleted successfully.';
        } else if ($result == -1) {
            $this->response['error_type'] = 'no_nondogfriendlys';
            $this->response['message'] = 'Can not find these non dogfriendlys.';
        }
        
        $this->session->set_flashdata('flash_data', $this->flash_data);
        
        echo json_encode($this->response);
        exit(-1);
    }

    public function deleteAppReview($id) {
        $user_data = $this->session->get_userdata();
        if ( !$user_data ) {
            redirect('../login');
        } else {
            if ( !isset($user_data['id']) ) {
                redirect('../login');
            }
        }
        
        $result = $this->User_Model->deleteReport('App Review', $id);
        if ($result == 0) {
            $this->response['error_type'] = '';
            $this->response['status'] = 'success';
            $this->flash_data['alerts']['success'][] = 'The app review has been deleted successfully.';
        } else if ($result == -1) {
            $this->response['error_type'] = 'no_appreview';
            $this->response['message'] = 'Can not find this app review.';
        }
        
        $this->session->set_flashdata('flash_data', $this->flash_data);
        
        echo json_encode($this->response);
        exit(-1);
    }

    public function deleteNonDogFriendly($id) {
        $user_data = $this->session->get_userdata();
        if ( !$user_data ) {
            redirect('../login');
        } else {
            if ( !isset($user_data['id']) ) {
                redirect('../login');
            }
        }
        
        $result = $this->User_Model->deleteReport('Non DogFriendly', $id);
        if ($result == 0) {
            $this->response['error_type'] = '';
            $this->response['status'] = 'success';
            $this->flash_data['alerts']['success'][] = 'The non dogfriendly has been deleted successfully.';
        } else if ($result == -1) {
            $this->response['error_type'] = 'no_nondogfriendly';
            $this->response['message'] = 'Can not find this non dogfriendly.';
        }
        
        $this->session->set_flashdata('flash_data', $this->flash_data);
        
        echo json_encode($this->response);
        exit(-1);
    }
}