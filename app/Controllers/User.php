<?php

namespace App\Controllers;
use Facebook\Facebook;

class User extends BaseController
{
    public function login(): string
    {

        $data = [];
        // return view('login page');
        // return "<h1>login page</h1>"; 
        $facebook = new Facebook([
            'app_id' => '3526523347608131',
            'app_secret' => 'e2832dcc9c6fe49b7f34dfc21c8d00b7',
            'default_graph_version' => 'v2.3'
        ]);

        $fb_helper = $facebook->getRedirectLoginHelper();

        if($this->request->getVar('state')){
            $fb_helper->getPersistentDataHandler()->set('state', $this->request->getVar('state'));
        }


        if($this->request->getVar('code')){
            if(session()->get('access_token')){
                $access_token = session()->get('access_token');
            }
            else{
                $access_token = $fb_helper->getAccessToken();
                session()->set('access_token', $access_token);
                $facebook->setDefaultAccessToken(session()->get('access_token'));

            }
            $graph_response = $facebook->get('/me?fields=name,email', $access_token);
            $fb_user_info = $graph_response->getGraphUser();

            if(!empty($fb_user_info['id'])){
                $fbdata = [
                    'profile_pic' => 'http://graph.facebook.com/'.$fb_user_info['id'].'/picture',
                    'user_name' => $fb_user_info['name'],
                    'email' => $fb_user_info['email'],
                    'userid' => $fb_user_info['id'],
                ];

                session()->set('userdata', $fbdata);
            }
        }
        else{
            $fb_permissions = ['email'];
            $data['fb_login_url'] = $fb_helper->getLoginUrl('http://localhost:8080/user/login', $fb_permissions);
        }
        return view("fb_view", $data);
    }

    public function logout(){
        if(session()->has('userdata')){
            session()->destroy();
            return redirect()->to(base_url().'/user/login');
        }
    }

    public function signUp(){
        // return "<h1>signUp page</h1>";   
        return view('signUpPage.php');     
    }
    
}
