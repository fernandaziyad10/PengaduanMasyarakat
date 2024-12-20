<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $staffs = Staff::all();
        return view('staff.index', compact('staffs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('staff.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'email'=>'required',
            'password' => 'required'
        ]);

        $staff= new Staff();
        $staff->email= $request->email;
        $staff->password = $request->password;
        $staff->save();

        return redirect()->route('staff-index')->with('success', 'Berhasil menambahkan akun!!');

    }

    /**
     * Display the specified resource.
     */
    public function show(Staff $staff)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
        // $pengaduan = TambahAduan::findOrFail($id);
        $staff = Staff::findOrFail($id);
        return view('staff.edit', compact('staff'));
    
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $request->validate ([
            'email'=> 'required',
            'password'=> 'required',
        ]);

        $staff = Staff::findOrFail($id);
        $staff->email = $request->email;

       
        if ($request->filled('password')) {
            $staff->password = $request->password;
        }

        $staff->save();

        return redirect()->route('staff-index')->with('success', 'Staff berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $staff = Staff::findOrFail($id);

        $staff->delete();
        return redirect()->route('staff-index')->with('success', 'Berhasil mengahapus akun!!');
    }
}
