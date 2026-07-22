<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return response()->json(Member::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name'         => ['required', 'string', 'max:255'],
            'member_id'    => ['required', 'string', 'max:255', 'unique:members,member_id'],
            'email'        => ['nullable', 'email', 'max:255', 'unique:members,email'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'address'      => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        // Menggunakan validated() lebih aman daripada request->all()
        $member = Member::create($validator->validated());
        
        return response()->json($member, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $member = Member::find($id);

        if (!$member) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Member not found'
            ], 404);
        }

        return response()->json($member);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $member = Member::find($id);

        if (!$member) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Member not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name'         => ['required', 'string', 'max:255'],
            // Menggunakan class Rule agar format unique ignore id lebih rapi dan aman
            'member_id'    => ['required', 'string', 'max:255', Rule::unique('members')->ignore($id)],
            'email'        => ['nullable', 'email', 'max:255', Rule::unique('members')->ignore($id)],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'address'      => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        $member->update($validator->validated());
        
        return response()->json($member);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $member = Member::find($id);

        if (!$member) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Member not found'
            ], 404);
        }

        $member->delete();
        
        return response()->json([
            'status'  => 'success',
            'message' => 'Member deleted'
        ]);
    }

    /**
     * Register a new member.
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', 'unique:members,email'],
            'phone_number' => ['required', 'string', 'max:20'],
            'address'      => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        // Menggunakan latest() sebagai pengganti orderBy('id', 'desc')
        $lastMember = Member::latest('id')->first();
        $nextId = $lastMember ? (int) substr($lastMember->member_id, 4) + 1 : 1;
        $memberId = 'MEM-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        // Menggabungkan data yang sudah divalidasi dengan memberId buatan sistem
        $memberData = array_merge($validator->validated(), ['member_id' => $memberId]);
        Member::create($memberData);

        return response()->json([
            'status'  => 'success',
            'message' => 'Member registered successfully'
        ], 201);
    }
}