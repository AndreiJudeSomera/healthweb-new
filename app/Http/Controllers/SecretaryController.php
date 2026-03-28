<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ClinicStaff;
use App\Models\Secretary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

class SecretaryController extends Controller
{
    public function index(Request $request)
    {
        $secretaries = Secretary::with(['user', 'clinicStaff'])->latest()->get();

        if ($request->ajax()) {
            return response()->json($secretaries);
        }
        return view('accounts.index', compact('secretaries'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username'       => 'required|string|max:255',
            'email'          => 'required|email|unique:users,email',
            'password'       => 'required|min:6|confirmed',
            'Lname'          => 'required|string|max:255',
            'Fname'          => 'required|string|max:255',
            'Mname'          => 'nullable|string|max:255',
            'Gender'         => 'required|in:Male,Female',
            'DateofBirth'    => 'nullable|date',
            'Age'            => 'nullable|integer',
            'ContactNumber'  => 'nullable|string|max:20',
            'Address'        => 'nullable|string|max:255',
            'SecAssignedID'  => 'required|string|max:50',
        ]);

        return DB::transaction(function () use ($validated) {
            // 1) Create user
            $user = User::create([
                'username' => $validated['username'],
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role'     => 1, // Secretary
            ]);

            // 2) Create clinic_staff with encryption
            $staff = ClinicStaff::create([
                'user_id'       => $user->id,
                'Lname'         => $validated['Lname'],
                'Fname'         => $validated['Fname'],
                'Mname'         => $validated['Mname'],
                'Gender'        => $validated['Gender'],
                'DateofBirth'   => $validated['DateofBirth'],
                'Age'           => $validated['Age'],
                'ContactNumber' => $validated['ContactNumber'],
                'Address'       => $validated['Address'],
            ]);

            // 3) Create secretary record
            $secretary = Secretary::create([
                'user_id'        => $user->id,
                'sec_assigned_id'=> $validated['SecAssignedID'],
            ]);

            return response()->json([
                'message'   => 'Secretary created!',
                'secretary' => $secretary,
                'clinic_staff' => $staff
            ]);
        });
    }

    public function update(Request $request, $id)
    {
        $secretary = Secretary::findOrFail($id);
        $staff     = $secretary->clinicStaff;

        $secretary->update($request->only('sec_assigned_id'));

        if ($staff) {
            $staff->update($request->only([
                'Lname', 'Fname', 'Mname', 'Gender', 'DateofBirth',
                'Age', 'ContactNumber', 'Address'
            ]));
        }

        return response()->json(['message' => 'Secretary updated!']);
    }

    public function destroy($id)
    {
        Secretary::findOrFail($id)->delete();
        return response()->json(['message' => 'Secretary deleted!']);
    }
}
