<?php

namespace App\Http\Controllers;

use App\Models\ClinicStaff;
use Illuminate\Http\Request;

class ClinicStaffController extends Controller
{
    public function index(Request $request)
    {
        $staff = ClinicStaff::latest()->get();
        if ($request->ajax()) {
            return response()->json($staff);
        }
        return view('accounts.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'       => 'required|exists:users,id',
            'Lname'         => 'required|string',
            'Fname'         => 'required|string',
            'Gender'        => 'required|in:Male,Female',
            'DateofBirth'   => 'nullable|date',
            'Age'           => 'nullable|integer',
            'ContactNumber' => 'nullable|string',
            'Address'       => 'nullable|string',
        ]);

        $staff = ClinicStaff::create($validated);

        return response()->json(['message' => 'Clinic Staff added successfully!', 'data' => $staff]);
    }

    public function update(Request $request, $id)
    {
        $staff = ClinicStaff::findOrFail($id);
        $staff->update($request->all());
        return response()->json(['message' => 'Clinic Staff updated!', 'data' => $staff]);
    }

    public function destroy($id)
    {
        ClinicStaff::findOrFail($id)->delete();
        return response()->json(['message' => 'Clinic Staff deleted!']);
    }
}
