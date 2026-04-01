<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UmkmProfileRequest;
use App\Http\Resources\UmkmProfileResource;
use App\Models\UmkmProfile;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class UmkmProfileController extends Controller
{
    use ApiResponse;

    public function show(Request $request)
    {
        $profile = $request->user()->umkmProfile;

        if (!$profile) {
            return $this->error('UMKM Profile belum dibuat.', null, 404);

        }



        return $this->success(new UmkmProfileResource($profile), 'UMKM Profile detail');
    }



    public function store(UmkmProfileRequest $request)
    {

        $user = $request->user();

        if ($user->umkmProfile) {
            return $this->error('UMKM Profile sudah ada.', null, 400);
        }

        $profile = $user->umkmProfile()->create($request->validated());

        return $this->success(new UmkmProfileResource($profile), 'UMKM Profile berhasil dibuat', 201);
    }

    public function update(UmkmProfileRequest $request)
    {
        $profile = $request->user()->umkmProfile;

        if (!$profile) 
            {
            return $this->error('UMKM Profile tidak ditemukan.', null, 404);
        }

        $profile->update($request->validated());

        return $this->success(new UmkmProfileResource($profile), 'UMKM Profile berhasil diperbarui');
    }
}
