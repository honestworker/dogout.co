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
        if ( !$this->User_Model->isActivated($user_data['id']) ) {
            $this->session->unset_userdata('id');
            $this->session->unset_userdata('name');
            $this->session->unset_userdata('avatar');
            
            redirect('../login');
        }
        
        $data['users'] = $this->User_Model->getUserCounts();
        $data['reports'] = $this->User_Model->getReports();
        
        $this->load->view('admin/layouts/header');
        $this->load->view('admin/layouts/siderbar');
        $this->load->view('admin/pages/dashboard', $data);
        $this->load->view('admin/layouts/footer');
    }
    
	public function middleware( $action ) {
        $user_data = $this->session->get_userdata();
        if ( !$user_data ) {
            redirect('../login');
        } else {
            if ( !isset($user_data['id']) ) {
                redirect('../login');
            }
        }
        if ( !$this->User_Model->isActivated($user_data['id']) ) {
            $this->session->unset_userdata('id');
            $this->session->unset_userdata('name');
            $this->session->unset_userdata('avatar');
            
            redirect('../login');
        }

        $this->$action();
    }

	public function middleware1( $action, $param1 ) {
        $user_data = $this->session->get_userdata();
        if ( !$user_data ) {
            redirect('../login');
        } else {
            if ( !isset($user_data['id']) ) {
                redirect('../login');
            }
        }
        if ( !$this->User_Model->isActivated($user_data['id']) ) {
            $this->session->unset_userdata('id');
            $this->session->unset_userdata('name');
            $this->session->unset_userdata('avatar');
            
            redirect('../login');
        }

        $this->$action( $param1 );
    }

	public function middleware2( $action, $param1, $param2 ) {
        $user_data = $this->session->get_userdata();
        if ( !$user_data ) {
            redirect('../login');
        } else {
            if ( !isset($user_data['id']) ) {
                redirect('../login');
            }
        }
        if ( !$this->User_Model->isActivated($user_data['id']) ) {
            $this->session->unset_userdata('id');
            $this->session->unset_userdata('name');
            $this->session->unset_userdata('avatar');
            
            redirect('../login');
        }

        $this->$action( $param1, $param2 );
    }

	private function getAllAdmins() {        
        $data['admins'] = $this->User_Model->getUsers(1);
        
        $this->load->view('admin/layouts/header');
        $this->load->view('admin/layouts/siderbar');
        $this->load->view('admin/pages/admins', $data);
        $this->load->view('admin/layouts/footer');
    }
    
	public function getAllUsers() {        
        $data['users'] = $this->User_Model->getUsers(2);
        
        $this->load->view('admin/layouts/header');
        $this->load->view('admin/layouts/siderbar');
        $this->load->view('admin/pages/users', $data);
        $this->load->view('admin/layouts/footer');
    }
    
	public function activeUser( $user_id ) {
        $result = $this->User_Model->activeUser( $user_id );
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

	private function disableUser( $user_id ) {        
        $result = $this->User_Model->disableUser( $user_id );
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

	private function deleteUser( $user_id ) {        
        $result = $this->User_Model->deleteUser( $user_id );
        if ($result == 0) {
            $this->response['error_type'] = '';
            $this->response['status'] = 'success';
            $this->flash_data['alerts']['success'][] = 'The user has been deleted successfully.';
        } else if ($result == -1) {
            $this->response['error_type'] = 'no_user';
            $this->response['message'] = 'Can not find this user.';
        }
        
        $this->session->set_flashdata('flash_data', $this->flash_data);
        
        echo json_encode($this->response);
        exit(-1);
    }

	private function getAllAppReviews() {
        $data['reports'] = $this->User_Model->getAllAppReviews();
        
        $this->load->view('admin/layouts/header');
        $this->load->view('admin/layouts/siderbar');
        $this->load->view('admin/pages/appreviews', $data);
        $this->load->view('admin/layouts/footer');
    }
    
	private function getAppReview( $place, $address ) {        
        $data['data'] = $this->User_Model->getAppReviews(urldecode($place), urldecode($address));
        
        $this->load->view('admin/layouts/header');
        $this->load->view('admin/layouts/siderbar');
        $this->load->view('admin/pages/appreview', $data);
        $this->load->view('admin/layouts/footer');
    }

    private function deleteAppReviews( $place, $address ) {
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

    private function deleteAppReview( $id ) {
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

	private function getAllNonDogFriendlys() {
        $data['reports'] = $this->User_Model->getAllNonDogFriendlys();
        
        $this->load->view('admin/layouts/header');
        $this->load->view('admin/layouts/siderbar');
        $this->load->view('admin/pages/nondogfriendlys', $data);
        $this->load->view('admin/layouts/footer');
    }

	private function getNonDogFriendly( $place, $address ) {
        $data['data'] = $this->User_Model->getNonDogFriendlys(urldecode($place), urldecode($address));
        
        $this->load->view('admin/layouts/header');
        $this->load->view('admin/layouts/siderbar');
        $this->load->view('admin/pages/nondogfriendly', $data);
        $this->load->view('admin/layouts/footer');
    }

    private function deleteNonDogFriendlys( $place, $address ) {
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

    private function deleteNonDogFriendly( $id ) {
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
    
	private function getAllNewLocations() {        
        $data['reports'] = $this->User_Model->getAllNewLocations();
        
        $this->load->view('admin/layouts/header');
        $this->load->view('admin/layouts/siderbar');
        $this->load->view('admin/pages/newlocations', $data);
        $this->load->view('admin/layouts/footer');
    }
    
	private function getNewLocation( $place, $address ) {        
        $data['data'] = $this->User_Model->getNewLocation(urldecode($place), urldecode($address));
        
        $this->load->view('admin/layouts/header');
        $this->load->view('admin/layouts/siderbar');
        $this->load->view('admin/pages/newlocation', $data);
        $this->load->view('admin/layouts/footer');
    }
    
    private function deleteNewLocations( $place, $address ) {        
        $result = $this->User_Model->deleteReports('New Location', urldecode($place), urldecode($address));
        if ($result == 0) {
            $this->response['error_type'] = '';
            $this->response['status'] = 'success';
            $this->flash_data['alerts']['success'][] = 'The dog-friendly locations have been deleted successfully.';
        } else if ($result == -1) {
            $this->response['error_type'] = 'no_newlocations';
            $this->response['message'] = 'Can not find these dog-friendly locations.';
        }
        
        $this->session->set_flashdata('flash_data', $this->flash_data);
        
        echo json_encode($this->response);
        exit(-1);
    }
    
    private function deleteNewLocation( $id ) {        
        $result = $this->User_Model->deleteReport('New Location', $id);
        if ($result == 0) {
            $this->response['error_type'] = '';
            $this->response['status'] = 'success';
            $this->flash_data['alerts']['success'][] = 'The dog-friendly location has been deleted successfully.';
        } else if ($result == -1) {
            $this->response['error_type'] = 'no_appreview';
            $this->response['message'] = 'Can not find this dog-friendly location.';
        }
        
        $this->session->set_flashdata('flash_data', $this->flash_data);
        
        echo json_encode($this->response);
        exit(-1);
    }
}