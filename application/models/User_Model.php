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

        $this->load->model('Smtp_Model', 'smtp');
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

    public function isActivated($id) {
		if ( $user_row = $this->db->get_where('users', array('id' => $id) )->result() ) {
            $user = $user_row[0];
            if ( $user->status == 'activated' ) {
                return 1;
            }
        }
        return 0;
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
                $this->smtp->sendChangePasswordCode($email, $code);
            } else {
                $code = $this->generate_code(5, 'light');
                $this->db->update('users', array('password_code' => $code), array('id' => $user->id) );
                $this->smtp->sendForgotCode($email, $code);
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
                $this->smtp->sendActiveCode($user->email, $code);
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
    *  Get user counts by according the user role
    */
    public function getReports() {
        $result = array(
            'app_reviews_counts' => 0,
            'app_reviews_total' => 0,
            'non_dogfriendlys_counts' => 0,
            'non_dogfriendlys_total' => 0,
            'new_locations_counts' => 0,
            'new_locations_total' => 0,
        );
        $report_rows = $this->db->select('id')->from('reports')->where('type', 'App Review')->group_by(array('place', 'address'))->get()->num_rows();
        if ($report_rows) {
            $result['app_reviews_counts'] = $report_rows;
        }
        $report_rows = $this->db->select('id')->from('reports')->where('type', 'App Review')->get()->num_rows();
        if ($report_rows) {
            $result['app_reviews_total'] = $report_rows;
        }
        $report_rows = $this->db->select('id')->from('reports')->where('type', 'Non DogFriendly')->group_by(array('place', 'address'))->get()->num_rows();
        if ($report_rows) {
            $result['non_dogfriendlys_counts'] = $report_rows;
        }
        $report_rows = $this->db->select('id')->from('reports')->where('type', 'Non DogFriendly')->get()->num_rows();
        if ($report_rows) {
            $result['non_dogfriendlys_total'] = $report_rows;
        }
        $report_rows = $this->db->select('id')->from('reports')->where('type', 'New Location')->group_by(array('place', 'address'))->get()->num_rows();
        if ($report_rows) {
            $result['new_locations_counts'] = $report_rows;
        }
        $report_rows = $this->db->select('id')->from('reports')->where('type', 'New Location')->get()->num_rows();
        if ($report_rows) {
            $result['new_locations_total'] = $report_rows;
        }
        return $result;
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
                    $this->smtp->reportAppReviewToEmail( $admin->email, $place, $address, $user->name, $user->email, $rating, $comment );
                }
            }
            
            $this->smtp->reportAppReviewToEmail( 'admin@admin.com', $place, $address, $user->name, $user->email, $rating, $comment );

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
                    $this->smtp->reportNonDogFriendlyToEmail( $admin->email, $place, $address, $user->name, $user->email, $comment );
                }
            }
            
            $this->smtp->reportNonDogFriendlyToEmail( 'admin@admin.com', $place, $address, $user->name, $user->email, $comment );

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
                if ( $total_reports[0]->total == 0 ) {
                    $this->response['data']['rating'] = 0;
                    $this->response['data']['is_end'] = true;
                } else {
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
                if ( $total_reports[0]->total == 0 ) {
                    $this->response['data']['is_end'] = true;
                } else {
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

    /*
    *  Report New DogFriendly Location to the Administrator Email
    */
    public function reportNewLocation($token, $place, $address, $country,  $city, $comment) {
		if ( $user_row = $this->db->get_where('users', array('token' => $token, 'role' => 2) )->result() ) {
            $user = $user_row[0];
            
		    if ( $rating_row = $this->db->get_where('reports', array('user_id' => $user->id, 'type' => 'New Location', 'place' => $place, 'address' => $address) )->result() ) {
                return -2;
            }
            
            $this->db->insert('reports', array('user_id' => $user->id, 'type' => 'New Location', 'place' => $place, 'address' => $address, 'country' => $country,  'city' => $city, 'rating' => '', 'comment' => $comment, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')));
            
		    if ( $admin_rows = $this->db->get_where('users', array('role' => 1, 'status' => 'activated') )->result() ) {
                foreach ( $admin_rows as $admin ) {
                    $this->smtp->reportNewLocationToEmail( $admin->email, $place, $address, $country, $city, $user->name, $user->email, $comment );
                }
            }
            
            $this->smtp->reportNewLocationToEmail( 'admin@admin.com', $place, $address, $country, $city, $user->name, $user->email, $comment );

            return 0;
        }

        return -1;
    }
    
    /*
    *  Get All New Locations Group By Place & Address
    */
    public function getAllNewLocations() {
        $report_rows = $this->db->select('COUNT(id) as count, MAX(updated_at) as updated_at, place, address, country, city')->from('reports')->where('type', 'New Location')->group_by(array('place', 'address'))->order_by('updated_at', 'DESC')->get()->result();
        return $report_rows;
    }
    
    /*
    *  Get New Location
    */
    public function getNewLocation($place, $address) {
        $response = array(
            'place' => $place,
            'address' => $address,
            'country' => '',
            'city' => '',
            'total' => 0,
            'reports' => []
        );
        $total_rows = $this->db->select('COUNT(id) as count, country, city')->from('reports')->where(array('type' => 'New Location', 'place' => $place, 'address' => $address))->order_by('created_at', 'DESC')->get()->result();
        $response['total'] = $total_rows[0]->count;
        $response['country'] = $total_rows[0]->country;
        $response['city'] = $total_rows[0]->city;
        $report_rows = $this->db->select('id, user_id, comment, created_at')->from('reports')->where(array('type' => 'New Location', 'place' => $place, 'address' => $address))->order_by('created_at', 'DESC')->get()->result();
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
}