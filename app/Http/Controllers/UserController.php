<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Http\Requests;
use App\Role;
use Auth;
use App\User;
use Illuminate\Support\Facades\Input;
use Redirect;
use Flash;
use DB;
use App\Http\Controllers\Mailer;
use Hash;
use Session;

class UserController extends Controller
{
    public function __construct()
    {
       
    }

    protected function getGuard()
    {
        return property_exists($this, 'guard') ? $this->guard : null;
    }

     public function create()
    {
        $data['role'] = Role::get();

        return view('auth.register', $data);
    }

    public function store()
    {
       $input = Input::all();

        $validator = $this->validator($input);
        if ($validator->fails())
        {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        }
      
        if (!isset($input['role']))
        {
            return Redirect::back()
                ->withErrors('Must select at least one role.')
                ->withInput();
        }

        Flash::success('User saved successfully.');

        $user = $this->postCreate($input);
       
        if ($user)
        {
            if (isset($input['role']))
            {
                $data = ['user_id' => $user->id, 'role_id' => $input['role']];
                DB::table('role_user')->insert($data);
            }
        }

        return redirect('/login');
    }

    protected function postCreate(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    public function getResetpassword()
    {
        $bool = false;

        if (Input::has('id'))
        {
            $login_id = Input::get('id');
            if (Auth::id() == $login_id)
            {
                $bool = true;
            }
        }

        if (!$bool)
        {
            return redirect($this->redirectPath('/home'))
                ->with('alert-danger', trans("Please change only your account"));
        }

        $data = ['user_id' => $login_id];
        return view('auth.passwords.reset', $data);
    }

    protected function validatorReset(array $data)
    { 
        return Validator::make($data, [
            'new_password' => 'required|min:6',
            'password_confirmation' => 'required|min:6'
        ]);
    }

    public function postResetpassword()
    {
        $bool = false;

        if (Input::has('id'))
        {
            $user_id = Input::get('id');
            if (Auth::id() == $user_id)
            {
                $bool = true;
            }
        }

        if (!$bool)
        {
            return redirect($this->redirectPath('/home'))
                ->with('alert-danger', trans("Please change only your account"));
        }

        $url_path = URL('/users/resetpassword?id=' . $user_id);

        $input = Input::all();

        if (Input::get('new_password'))
        {
            $validatorReset = $this->validatorReset($input);

            if ($validatorReset->fails())
            {
                return Redirect::to($url_path)->withErrors($validatorReset)->withInput();
            }
            else
            {
                $user = User::find($user_id);
                $this->resetPassword($user, $input['new_password']);

                $user->where('id', $user->id)->update(['status' => 0]);

                $mailer = new Mailer('sovoeun201@gmail.com');
                $mailer->addRecipients(Input::get('email'));
                $mailer->setSubject('Password Confirmation');

                $data['token'] = $user->remember_token;
                $body = view('auth.passwords.confirm_email', $data);

                $mailer->setBody($body);
                $mailer->send();

                Session::put('password_messages', trans("Password has been changed successfully"));

                return redirect('/logout');
            }
        }
        else
        {
            Session::put('error_password', trans("Invalid Email or Password."));
            return Redirect::to($url_path)->withInput();
        }
    }

    protected function resetPassword($user, $password)
    {
        $user->password = bcrypt($password);

        $user->save();

        Auth::guard($this->getGuard())->login($user);
    }

    public function getConfirm()
    {
        if(Input::get('token'))
        {
            $user = User::where('remember_token', Input::get('token'))->get();

            if(count($user) < 1)
                return 'Page not found.';

            $user_update = User::find($user[0]['id']);
            $result = $user_update->where('id', $user_update->id)->update(['status' => 1]);
            if($result)
                Session::put('confirm_success', trans("Password confirm successfully."));
                return redirect('/login');
        }
        else
        {
            return 'Page not found!';
        }
    }

    protected function redirectPath($path)
    {
        if (property_exists($this, 'redirectPath'))
        {
            return $this->redirectPath;
        }
        $url_path = URL('/') . $path;
        return property_exists($this, 'redirectTo') ? $this->redirectTo : $url_path;
    }

    public function getForgotpassword()
    {
        return view('auth.passwords.email');
    }

    public function postForgotpassword()
    {
        if(Input::get('email'))
        {
            $user = User::where('email', Input::get('email'))->get();

            if(count($user) < 1)
                return 'Email not exist!.';

            $user = User::find($user[0]->id);

            $mailer = new Mailer('sovoeun201@gmail.com');
            $mailer->addRecipients(Input::get('email'));
            $mailer->setSubject('Password Confirmation');

            $data = [
                'email' => $user->email,
                'token' => $user->remember_token
            ];

            $body = view('auth.emails.password', $data);

            $mailer->setBody($body);
            $mailer->send();

            Session::put('password_messages', trans("Password has been changed successfully"));

            return redirect('/');

            $user_update = User::find($user[0]['id']);
                Session::put('confirm_success', trans("Password confirm successfully."));
                return redirect('/login');
        }
        else
        {
            return 'Page not found!';
        }        
    }

    public function getConfirmforgotpass()
    {
        if(Input::get('email'))
        {
            $user = User::where('email', Input::get('email'))->get();

            $data = [
                'user_id' => $user[0]['id']
            ];

            return view('auth.emails.confirmforgot', $data);
        }
    }

    public function postConfirmforgotpass()
    {
        $user_update = User::find(Input::get('id'));

        if(count($user_update) < 1)
            return 'Page not found.';
        $input = Input::all();
        if (Input::get('new_password'))
        {
            $validatorReset = $this->validatorReset($input);

            if ($validatorReset->fails())
            {
                return Redirect::to($url_path)->withErrors($validatorReset)->withInput();
            }
            else
            {
                $result = $user_update->where('id', $user_update->id)->update(['password' => bcrypt(Input::get('new_password'))]);

                if($result)
                    Session::put('confirm_success', trans("Password confirm successfully."));
                    return redirect('/login');
            }
        }else{
            return 'Error while submit data';
        }
    }
}
