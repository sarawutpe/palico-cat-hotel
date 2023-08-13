<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Employee;
use App\Models\Admin;
use Illuminate\Support\Facades\Storage;
use App\Http\Helpers\Helper;
use App\Http\Helpers\Key;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Rules\UniqueUser;

class MemberController extends Controller
{

    public function show($id)
    {
        $member = Member::findOrFail($id);
        return view('members.show', compact('member'));
    }

    public function edit($id)
    {
        $member = Member::findOrFail($id);
        return view('members.edit', compact('member'));
    }

    public function update(Request $request, $id)
    {
        $member = Member::findOrFail($id);
        $member->member_name = $request->input('member_name');
        $member->member_user = $request->input('member_user');
        if ($request->filled('member_pass')) {
            $member->member_pass = bcrypt($request->input('member_pass'));
        }
        $member->member_address = $request->input('member_address');
        $member->member_phone = $request->input('member_phone');
        $member->member_facebook = $request->input('member_facebook');
        $member->member_lineid = $request->input('member_lineid');
        // Upload member_img logic here
        $member->save();

        return redirect()->route('members.index')->with('success', 'Member updated successfully');
    }

    public function destroy($id)
    {
        $member = Member::findOrFail($id);
        $member->delete();
        return redirect()->route('members.index')->with('success', 'Member deleted successfully');
    }
}
