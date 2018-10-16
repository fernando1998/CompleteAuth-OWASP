<?php
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('auth/facebook', 'Auth\SocialAuthController@redirectToFacebook');
Route::get('auth/facebook/callback', 'Auth\SocialAuthController@handleFacebookCallback');

Route::post('user/{id}/doubleAuth/{code}',function($id, $code){
    $user = App\User::find($id);
    
    if($code == 1 ){
        \Log::info('Habilitar');
        //Activar double auth
        $user->doubleAuth = 1;
        $user->save();
        
        return redirect()->route('home');
    }else if($code == 2){
        \Log::info('Deshabilitar');
       //Activar double auth
        $user->doubleAuth = 0;
        $user->save(); 
        
        return redirect()->route('home');
    }else {
        \Log::info('Error');
    }
})->name('changeDoubleAuth');

Route::get('/validateDoubleAuth', function(){
    $user = \Auth::user();
    $doubleAuth = $user->doubleAuth;
    
    //Código Clave para ingresar
    $codes = [];
    for ($x = 1; $x < 5; $x++) {
        $random = rand(0, 9);
        array_push($codes, $random);
    }
    
    $code = implode("", $codes);
            
    if($doubleAuth){
        $emailDestiny = \Auth::user()->email;
        
        $data = array(
            'code' => $code
            );
        
        Mail::send('emails.dobleAuthCode', $data, function ($message) use ($emailDestiny) {
                $message->to($emailDestiny, 'Doble Auth Code')
                    ->subject('Código de Doble Autentificación');
            });
        
        $user->lastCode = $code;
        $user->save();
        
        return redirect('/user/doubleAuth');
        
    }else{
        return redirect('/home');
    }
});

Route::get('user/doubleAuth',function(){
    return view('doubleAuthView');
});

//Validación del código
Route::post('confirm/doubleAuth/{id}',function($id, Request $request){
    $user = \App\User::find($id);
    
    $realCode = $user->lastCode;
    
    if($realCode == $request->code){
        return redirect('/home');
    } else {
        return redirect('/user/doubleAuth');
    }
})->name('confirmCodeAuth');

//Reenviar código 
Route::get('resendCode/user/{id}', function($id){
    $user = \App\User::find($id);
    
    //Código Clave para ingresar
    $codes = [];
    for ($x = 1; $x < 5; $x++) {
        $random = rand(0, 9);
        array_push($codes, $random);
    }
    
    $code = implode("", $codes);
    
    $emailDestiny = $user->email;
        
        $data = array(
            'code' => $code
            );
        
        Mail::send('emails.dobleAuthCode', $data, function ($message) use ($emailDestiny) {
                $message->to($emailDestiny, 'Doble Auth Code')
                    ->subject('Código de Doble Autentificación');
            });
        
        $user->lastCode = $code;
        $user->save();
    
    return redirect('/user/doubleAuth');
    
    
})->name('RetryCode');