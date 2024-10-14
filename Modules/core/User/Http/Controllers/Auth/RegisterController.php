<?php

namespace Modules\User\Http\Controllers\Auth;

use App\Exceptions\Handler;
use Modules\User\Facades\TwoFactorAuth;
use Modules\User\Models\User;
use Modules\Foundation\Http\Controllers\AuthBaseController;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends AuthBaseController
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->modules_middleware = ['guest'];
        parent::__construct();
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:60',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'terms' => 'required',
            // 'phone_country_code' => 'required',
            'country_id' => 'required',
            'phone_number' => 'required|unique:users',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \Modules\User\Models\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'country_id' => $data['country_id'],
            'phone_number' => $data['phone_number'],
            'user_type' => 'student',
        ]);

        try {

            TwoFactorAuth::register($user);

            return $user;
        } catch (\Exception $exception) {
            $user->forceDelete();

            app(Handler::class)->report($exception);

            return response()->json(['error' => ['Unable To Register User']], 422);
        }
    }

    /**
     * Handle a registration request for the application.
     * @param Request $request
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\JsonResponse|mixed
     */
    public function register(Request $request)
    {
        if(str_contains($request->get('name'),'http://') || str_contains($request->get('name'),'https://')){
            return response()->json(['message'=>'الاسم غير صحيح ... الفزلكة ممنوعة'],422);
        }
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        \Actions::do_action('user_registered', $user);

        if (TwoFactorAuth::isEnabled($user)) {
            $request->session()->put('authy:auth:id', $user->id);

            return redirectTo(url('auth/token'));
        }

        if (\Settings::get('confirm_user_registration_email', false)) {
            try{
                $this->sendConfirmationToUser($user);
            }catch(\Exception $e){}

            flash(trans('User::messages.confirmation.confirm_email'), 'success');

            return redirectTo('login');
        } else {
            $this->guard()->login($user);

            return $this->registered($request, $user)
                ?: redirectTo($this->redirectPath());
        }
    }

    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectPath()
    {
    // $redirect_to = \Filters::do_filter('auth_redirect_to', 'profile');
        $redirect_to = url('/');
        if(\Settings::get('after_register_url')){
           $redirect_to = \Settings::get('after_register_url', url('/')); 
        }
        
        return $redirect_to;
    }

    /**
     * Send the confirmation code to a user.
     *
     * @param $user
     */
    protected function sendConfirmationToUser($user)
    {
        // Create the confirmation code
        $user->confirmation_code = str_random(25);

        $user->save();

        event('notifications.user.confirmation', ['user' => $user]);
    }


    /**
     * Confirm a user with a given confirmation code.
     *
     * @param $confirmation_code
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirm($confirmation_code)
    {
        $model = $this->guard()->getProvider()->createModel();
        $user = $model->where('confirmation_code', $confirmation_code)->firstOrFail();

        $user->confirmation_code = null;
        $user->confirmed_at = now();

        $user->save();

        $this->guard()->login($user);

        flash(trans('User::messages.confirmation.confirmation_successful'), 'success');

        return $this->confirmed($user)
            ?: redirect($this->redirectPath());
    }

    /**
     * The users email address has been confirmed.
     *
     * @param  mixed $user
     * @return mixed
     */
    protected function confirmed($user)
    {
        //
    }


    /**
     * Resend a confirmation code to a user.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resendConfirmation(Request $request)
    {
        $model = $this->guard()->getProvider()->createModel();

        $user = $model->findOrFail($request->session()->pull('confirmation_user_id'));

        $this->sendConfirmationToUser($user);

        flash(trans('User::messages.confirmation.confirmation_resent'), 'success');

        return redirect(route('login'));
    }
}
