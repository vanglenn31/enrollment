<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use App\Models\StudentProfiles;
use App\Models\Roles;



class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $password =Str::random(12);
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'suffix' => ['nullable', 'string',  'max:5'],
            'sex' => ['required', 'string', 'max:5'],
            'birthdate' => ['required', 'date'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone_number' => ['required', 'regex:/^(09|\+639)\d{9}$/'],
            //address
            'house_number' => ['required', 'string', 'max:20'],
            'street' => ['required', 'string', 'max:100'],
            'barangay' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'province' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:100'],
            //educational background
            'high_school' => ['required', 'string', 'max:100'],
            'HS_grad_date'  => ['required', 'date'],
            'Strand'  => ['required', 'string', 'max:100'],
            'college'  => ['required', 'string', 'max:100'],
            'col_grad_date'  => ['required', 'date', ],
            'prev_field'  => ['required', 'string', 'max:100'],

            //preferrences /selected


        ]);

        $unenrolled = Roles::firstWhere('role', 'unenrolled');
        try {
    $user = DB::transaction(function () use ($validated, $password, $unenrolled) {

        $user = User::create([
            'email' => $validated['email'],
            'password' => Hash::make($password),
            'role_id'=> $unenrolled->id,
            
        ]);

        $profile = $user->profile()->create([
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'],
            'last_name' => $validated['last_name'],
            'suffix' => $validated['suffix'],
            'sex' => $validated['sex'],
            'birthdate' => $validated['birthdate'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
        ]);

         $address = $profile->address()->create([
            'house_number' => $validated['house_number'],
            'street' => $validated['street'],
            'barangay' => $validated['barangay'],
            'city' => $validated['city'],
            'province' => $validated['province'],
            'postal_code' => $validated['postal_code'],
        ]);


        return $user;
    });

} catch (\Throwable $e) {
    dd($e->getMessage());
}

        
        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
