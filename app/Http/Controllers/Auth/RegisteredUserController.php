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
use Illuminate\Validation\Rules\Password;

use App\Models\StudentProfiles;
use App\Models\Roles;
use App\Models\Program;



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
        
        
        // $password =Str::random(12);
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'suffix' => ['nullable', 'string',  'max:5'],
            'sex' => ['required', 'string', 'max:5'],
            'birthdate' => ['required', 'date'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone_number' => ['required', 'regex:/^(09|\+639)\d{9}$/'],
            'password' => ['required', 'confirmed', Password::defaults()],
            //address
            'house_number' => ['nullable', 'string', 'max:20'],
            'street' => ['nullable', 'string', 'max:100'],
            'barangay' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'province' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:100'],
            //educational background
            'high_school' => ['required', 'string', 'max:100'],
            'HS_grad_date'  => ['required', 'date'],
            'Strand'  => ['required', 'string', 'max:100'],
            'college'  => ['nullable', 'string', 'max:100'],
            'col_grad_date'  => ['nullable', 'date', ],
            'prev_field'  => ['nullable', 'string', 'max:100'],

            //preferrences /selected
            'program'  => ['required', 'string', 'max:10'],
            'preferred_time'  => ['required', 'string', 'max:10'],

        ]);
        $program = Program::where('code', $validated['program'])->orWhere('name', $validated['program'])->firstOrFail();

        $newStudent = Roles::firstWhere('role', 'student');
        try {
    $user = DB::transaction(function () use ($validated, $newStudent, $program) {

        $user = User::create([
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id'=> $newStudent->id,
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
            'house_number' => $validated['house_number'] ?? null,
            'street' => $validated['street'] ?? null,
            'barangay' => $validated['barangay'],
            'city' => $validated['city'],
            'province' => $validated['province'],
            'postal_code' => $validated['postal_code'],
        ]);
       

         $student = $profile->student()->create([
            'program' => $program->id,
            'preferred_time' => $validated['preferred_time'],
            'year_level' => '0',
        ]);
        

        $educbackground = $student->educationalbackground()->create([
            'school' => $validated['high_school'],
            'grad_date' => $validated['HS_grad_date'],
            'strand_or_course' => $validated['Strand'],
        ]);
        

        if (isset($validated['college'], $validated['col_grad_date'], $validated['prev_field'])) {
            $educbackground = $student->educationalbackground()->create([
                'school' => $validated['college'],
                'grad_date' => $validated['col_grad_date'],
                'strand_or_course' => $validated['prev_field'],
            ]);
        };
            


        return $user;
        dd('before education', $user);
    });

} catch (\Throwable $e) {
    // Handle the exception, log it, or return an error response
    dd('Error during registration: ' . $e->getMessage());
}

        
        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('verification.notice');
    }
}
