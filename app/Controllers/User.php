<?php

namespace App\Controllers;

use Facebook\Facebook;
use \App\Models\UserModel;
use CodeIgniter\CLI\Console;

class User extends BaseController
{

    // private $facebook="";
    // private $fb_helper="";
    // function __construct(){
    //     $this->facebook = new Facebook([
    //         'app_id' => '3526523347608131',
    //         'app_secret' => 'e2832dcc9c6fe49b7f34dfc21c8d00b7',
    //         'default_graph_version' => 'v2.3'
    //     ]);
    //     $this->fb_helper = $facebook->getRedirectLoginHelper();
    // }  

    public function login()
    {
        // ini_set("display_errors", true);
        // error_reporting(E_ALL);
        $data = [];
        // return view('login page');
        // return "<h1>login page</h1>"; 
        $facebook = new Facebook([
            'app_id' => '3526523347608131',
            'app_secret' => 'e2832dcc9c6fe49b7f34dfc21c8d00b7',
            'default_graph_version' => 'v2.3'
        ]);

        $fb_helper = $facebook->getRedirectLoginHelper();

        if ($this->request->getVar('state')) {
            $fb_helper->getPersistentDataHandler()->set('state', $this->request->getVar('state'));
        }


        if ($this->request->getVar('code')) {
            if (session()->get('access_token')) {
                $access_token = session()->get('access_token');
            } else {
                $access_token = $fb_helper->getAccessToken();
                session()->set('access_token', $access_token);
                $facebook->setDefaultAccessToken(session()->get('access_token'));
            }
            $graph_response = $facebook->get('/me?fields=name,email', $access_token);
            $fb_user_info = $graph_response->getGraphUser();

            if (!empty($fb_user_info['id'])) {
                $fbdata = [
                    'profile_pic' => 'http://graph.facebook.com/' . $fb_user_info['id'] . '/picture',
                    'user_name' => $fb_user_info['name'],
                    'email' => $fb_user_info['email'],
                    'userid' => $fb_user_info['id'],
                ];

                session()->set('userdata', $fbdata);
            }
        } else {
            $fb_permissions = ['email'];
            $data['fb_login_url'] = $fb_helper->getLoginUrl('http://localhost:8080/user/login', $fb_permissions);
        }

        // return view("fb_view", $data);
        return view("loginPage", $data);


        // *********************************************************************************************************************************
        // $fb_permissions = ['email'];
        // $data['fb_login_url'] = $this->fb_helper->getLoginUrl('http://localhost:8080/user/login', $fb_permissions);
        // return view("fb_view", $data);
    }

    // public function authWithFB(){
    //     if ($this->request->getVar('state')) {
    //         $this->fb_helper->getPersistentDataHandler()->set('state', $this->request->getVar('state'));
    //     }

    //     if ($this->request->getVar('code')) {
    //             if (session()->get('access_token')) {
    //                 $access_token = session()->get('access_token');
    //             } else {
    //                 $access_token = $this->fb_helper->getAccessToken();
    //                 session()->set('access_token', $access_token);
    //                 $this->facebook->setDefaultAccessToken(session()->get('access_token'));
    //             }
    //             $graph_response = $this->facebook->get('/me?fields=name,email', $access_token);
    //             $fb_user_info = $graph_response->getGraphUser();
    //     }
    // }

    public function logout()
    {
        if (session()->has('userdata')) {
            session()->destroy();
            return redirect()->to('user/login');
        }
    }

    public function signUp()
    {

        $data = [];

        $facebook = new Facebook([
            'app_id' => '3526523347608131',
            'app_secret' => 'e2832dcc9c6fe49b7f34dfc21c8d00b7',
            'default_graph_version' => 'v2.3'
        ]);

        $fb_helper = $facebook->getRedirectLoginHelper();
        if ($this->request->getVar('state')) {
            $fb_helper->getPersistentDataHandler()->set('state', $this->request->getVar('state'));
        }


        if ($this->request->getVar('code')) {
            if (session()->get('access_token')) {
                $access_token = session()->get('access_token');
            } else {
                $access_token = $fb_helper->getAccessToken();
                session()->set('access_token', $access_token);
                $facebook->setDefaultAccessToken(session()->get('access_token'));
            }
            $graph_response = $facebook->get('/me?fields=name,email', $access_token);
            $fb_user_info = $graph_response->getGraphUser();

            if (!empty($fb_user_info['id'])) {
                $fbdata = [
                    'profile_pic' => 'http://graph.facebook.com/' . $fb_user_info['id'] . '/picture',
                    'user_name' => $fb_user_info['name'],
                    'email' => $fb_user_info['email'],
                    'userid' => $fb_user_info['id'],
                ];

                session()->set('userdata', $fbdata);
            }
        } else {
            $fb_permissions = ['email'];
            $data['fb_login_url'] = $fb_helper->getLoginUrl('http://localhost:8080/user/login', $fb_permissions);
        }
        return view('signUpPage.php', $data);


        // *********************************************************************************************************************************
        // $fb_permissions = ['email'];
        // $data['fb_btn'] = $this->fb_helper->getLoginUrl('http://localhost:8080/user/login', $fb_permissions);
        // return view("signUpPage.php");
    }

    public function create()
    {

        if ($this->request->is('post')) {



            // helper(['form']);
            // $validation = \Config\Services::validation();

            // $rules = $this->validate([

            //     'email_id' => 'required|min_length[10]|is_unique[ci4.email]',
            //     'mobile_No' => 'required',
            //     'fullName' => 'required|min_length[10]',
            //     'userName' => 'required|min_length[10]',
            //     'password' => 'required|max_length[255]|min_length[8]'
            // ]);

            // if (!$rules) {
            //     return view('signUpPage.php', ['validation' => $this->validator]);
            // } else {
            $user = new UserModel();

            $phone = '';
            $email = '';

            if (is_numeric($this->request->getPost('mobileNumberEmailAddress')) == 1) {
                $phone = $this->request->getPost('mobileNumberEmailAddress');
            } else {
                $email = $this->request->getPost('mobileNumberEmailAddress');
            }

            // $password = md5($this->request->getPost('password'));

            $data = [
                'email_id' => $email,
                'mobile_No' => $phone,
                'fullName' => $this->request->getPost('fullName'),
                'userName' => $this->request->getPost('userName'),
                // 'password' =>  $this->request->getPost('password')
                'password' => md5((string) $this->request->getPost('password'))

            ];

            print_r($data);
            $user->save($data);
            echo "<br>";
            
            // return "successfully inserted";
            // }
        }
        session()->setFlashData("error_controller", "account is created, Now you can login");
        return redirect()->to(base_url('/user/login'));

        // die("123");
        // return "create page";

    }


    public function searchAndLogin()
    {
        if ($this->request->is('post')) {



            // helper(['form']);
            // $validation = \Config\Services::validation();

            // $rules = $this->validate([
            //     'email_id' => 'required|min_length[10]|is_unique[ci4.email]',
            //     'mobile_No' => 'required',
            //     'fullName' => 'required|min_length[10]',
            //     'userName' => 'required|min_length[10]',
            //     'password' => 'required|max_length[255]|min_length[8]'
            // ]);

            // if (!$rules) {
            //     return view('loginPage.php', ['validation' => $this->validator]);
            // } 


            $user = new UserModel();

            // $phone = '';
            // $email = '';
            $data = '';
            $result = '';
            $password = md5($this->request->getPost('password'));
            // if(!empty($password)){
            //     $password = md5($password);
            // }
            

            if (is_numeric($this->request->getPost('mobileNumberEmailAddress')) == 1) {
                // $this->$phone = $this->request->getPost('mobileNumberEmailAddress');
                // $this->$data = [
                //     'mobile_No' => $this->request->getPost('mobileNumberEmailAddress'),
                //     'password' => $this->request->getPost('password')
                // ];

                $mobile_No = $this->request->getPost('mobileNumberEmailAddress');

                $result = $user->where('mobile_No', $mobile_No)->first();

                // print_r($result);



            } else {
                // $email = $this->request->getPost('mobileNumberEmailAddress');
                // $this->$data = [
                //     'email_id' => $this->request->getPost('mobileNumberEmailAddress'),
                //     'password' => $this->request->getPost('password')
                // ];

                $email = $this->request->getPost('mobileNumberEmailAddress');
                // $password = $this->request->getPost('password');

                $result = $user->where('email_id', $email)->first();

                // print_r($this->$result);
            }

            // $data = [
            //     'email_id' => $email,
            //     'mobile_No' => $phone,
            //     'password' => $this->request->getPost('password')
            // ];

            // print_r($this->$data);
            // echo "hello";
            // die;
            // echo $user->find($data);
            // echo "<br>";
            // die;
            // return "successfully inserted";

            if ($result != null) {
                // if (password_verify($this->$password, $this->$result['password'])) {
                //     return 'Password is valid!';
                // } else {
                //     return 'Invalid password.';
                // }
                // echo $password;
                // echo "<br>," . $result['password'];
                if ($password == $result['password']) {
                    // return 'Password is valid!';

                    // SETTING SESSIONS
                    $session = session();
                    $session->set('userdata', $result);
                    $session->set('loggedin', 'loggedin');
                    return redirect()->to('user/dashboard');

                } else {
                    session()->setFlashData("error_controller", "Sorry, your password was incorrect. Please double-check your password.");
                    return redirect()->to('user/login');
                }
            } else {
                session()->setFlashData("error_controller", "Sorry, your password was incorrect. Please double-check your password.");
                return redirect()->to('user/login');
            }
            // }
        }
    }

    


    public function password_Reset()
    {
        return view('resetPage');
    }


    public function email()
    {

        $auth = bin2hex(random_bytes(30));

        
        if ($this->request->is('post')) {
            $user = new UserModel();

            // $email = '';
            $email = $this->request->getPost('mobileNumberEmailAddress');
            // print_r($email);
            $result = $user->where('email_id', $email)->first();
            // $user->save($data);
            // echo $result['email_id'] . " <br>";
            // print_r($result);

            
            if ($result == null) {
                
                session()->setFlashData("error_controller", "No users found");
                return redirect()->to('user/password/reset');
            } 
            else {
                $data = [
                    'resetToken' => $auth
                ];
                $user->update($result['id'], $data);

                $username = $result['userName'];
                $email = $result['email_id'];
                // echo "found";

                $to = $email;
                $subject = $username . ", we've made it easy to get back to Instagram";
                $message = "Hi ".$username.",<br>
                We're sorry to hear that you're having trouble with logging in to Instagram. We've received a message that you've forgotten your password. If this was you, you can get straight back into your account or reset your password now. <br>
                <a  href='http://localhost:8080/user/password/reset/confirm/".$auth."'><button style='text-align: center; background-color: blue; color:white;'>reset your password</button></a><br>

                If you didn't request a login link or password reset, you can ignore this message and learn more about why you might have received it.

                Only people who know your Instagram password or click the login link in this email can log in to your account.";

                

                $email = \Config\Services::email();

                // $email->setFrom('admin@gmail.com', 'Kushal');
                $email->setTo($to);
                $email->setFrom('kushgujrati01@gmail.com', 'Instagram');
                // $email->setCC('another@another-example.com');
                // $email->setBCC('them@their-example.com');

                $email->setSubject($subject);
                $email->setMessage($message);

                if ($email->send()) {
                    $dataa['email'] = $to;
                    // print_r($dataa);
                    // die;

                    return view('emailSendMsg.php', $dataa);

                } else {
                    $data = $email->printDebugger(['headers']);
                    print_r($data);
                }
            }
        }
        // return redirect()->to(base_url('/user/login'));





        // return "email route";
    }

    public function confirm_Password_Reset($auth){
        $user = new UserModel();
        
        if ($this->request->is('post')) {
            $password = $this->request->getPost('spassword');
            $cpassword = $this->request->getPost('cpassword');

            // php validation
            if($password != $cpassword){
                session()->setFlashData("error_controller", "Sorry, your password and confirm password are not same. Please double-check your password.");
                return redirect()->to('user/password/reset/confirm/'.$auth);
            }
            if(strlen((string) $password) < 6 || strlen((string) $cpassword) < 6){
                session()->setFlashData("error_controller", "Your password must be at least 6 characters");
                return redirect()->to('user/password/reset/confirm/'.$auth);
            }
            // Uppercase and lowercase letter check
            if (!preg_match("/[A-Z]/", ((string) $password)) || !preg_match("/[a-z]/", ((string) $password))) {
                session()->setFlashData("error_controller", "Password must contain both uppercase and lowercase letters");
                return redirect()->to('user/password/reset/confirm/'.$auth);
            }
            // Numeric character check
            if (!preg_match("/[0-9]/", ((string) $password))) {
                session()->setFlashData("error_controller", "Password must contain at least one numeric character");
                return redirect()->to('user/password/reset/confirm/'.$auth);
            }
            // Special character check (you can modify this pattern as needed)
            if (!preg_match("/[^A-Za-z0-9]/", ((string) $password))) {
                session()->setFlashData("error_controller", "Password must contain at least one special character");
                return redirect()->to('user/password/reset/confirm/'.$auth);
            }

            // change pass and store into database
            $result = $user->where('resetToken', $auth)->first();
            // print_r($result);
            $data = [
                'resetToken' => '',
                'password' => md5($password)
            ];
            $user->update($result['id'], $data);
            session()->setFlashData("error_controller", "password changed successfully");
            return redirect()->to('user/login');
        }

        else{ 
            $result = $user->where('resetToken', $auth)->first();
            // $user = new UserModel();
            // $result = $user->where('resetToken', $auth)->first();
            // print_r($result);
            if($result == null){
                return view('linkExpirePage.php');
            }
            $data['auth'] = $auth;
            return view('old&NewPass.php', $data);
        }

    }

    // *********************************************************************************
    public function dashboard()
    {
        return view('dashboard.php');

    }
}