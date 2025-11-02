<?php

namespace App\Http\Controllers\Freelancer;

use App\Http\Controllers\Controller;
use App\Helpers\MessageHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class ProfileController extends Controller
{
    /**
     * Get freelancer profile data
     */
    public function getProfile(Request $request): JsonResponse
    {
        try {
            $userId = $request->user()->user_id;

            // Get user basic details
            $user = DB::table('users as u')
                      ->leftJoin('freelancer_details as fd', 'u.user_id', '=', 'fd.user_id')
                      ->where('u.user_id', $userId)
                      ->select(
                          'u.user_id',
                          'u.email',
                          'fd.first_name',
                          'fd.last_name',
                          'fd.country',
                          'fd.designation',
                          'fd.hourly_rate',
                          'fd.profile_image',
                          'fd.summary',
                          'fd.experience_years'
                      )
                      ->first();

            if (!$user) {
                return response()->json(
                    MessageHelper::error('User not found'),
                    404
                );
            }

            // Get work experience
            $workExperience = DB::table('work_experience')
                                ->where('user_id', $userId)
                                ->orderBy('start_date', 'desc')
                                ->get()
                                ->map(function ($exp) {
                                    return [
                                        'id' => $exp->experience_id,
                                        'company_name' => $exp->company_name,
                                        'joining_date' => $exp->start_date,
                                        'location' => $exp->location,
                                        'responsibility' => $exp->description
                                    ];
                                });

            // Get expertise
            $expertise = DB::table('expertise')
                           ->where('user_id', $userId)
                           ->pluck('expertise_name')
                           ->toArray();

            // Get skills
            $skills = DB::table('skills')
                        ->where('user_id', $userId)
                        ->pluck('skill_name')
                        ->toArray();

            // Get education
            $education = DB::table('education')
                           ->where('user_id', $userId)
                           ->orderBy('start_date', 'desc')
                           ->get()
                           ->map(function ($edu) {
                               return [
                                   'id' => $edu->education_id,
                                   'college' => $edu->institution_name,
                                   'subject' => $edu->degree,
                                   'starting_time' => $edu->start_date,
                                   'finishing_time' => $edu->end_date
                               ];
                           });

            // Get portfolio
            $portfolio = DB::table('portfolio')
                           ->where('user_id', $userId)
                           ->get()
                           ->map(function ($item) {
                               return [
                                   'id' => $item->portfolio_id,
                                   'image' => $item->image_url,
                                   'title' => $item->title,
                                   'tags' => json_decode($item->tags, true) ?? []
                               ];
                           });

            // Get certifications
            $certifications = DB::table('certifications')
                                ->where('user_id', $userId)
                                ->orderBy('issue_date', 'desc')
                                ->get()
                                ->map(function ($cert) {
                                    return [
                                        'id' => $cert->certification_id,
                                        'institute_name' => $cert->issuing_organization,
                                        'course_name' => $cert->certification_name,
                                        'starting_time' => $cert->issue_date,
                                        'finishing_time' => $cert->expiration_date
                                    ];
                                });

            $profileData = [
                'basic_info' => [
                    'profile_picture' => $user->profile_image,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'country' => $user->country,
                    'designation' => $user->designation,
                    'experience_years' => $user->experience_years,
                    'hourly_rate' => $user->hourly_rate,
                    'summary' => $user->summary,
                ],
                'work_experience' => $workExperience,
                'expertise' => $expertise,
                'skills' => $skills,
                'education' => $education,
                'portfolio' => $portfolio,
                'certifications' => $certifications
            ];

            return response()->json(
                MessageHelper::success('Profile data retrieved successfully', $profileData),
                200
            );

        } catch (Exception $e) {
            return response()->json(
                MessageHelper::error('An error occurred: ' . $e->getMessage()),
                500
            );
        }
    }

    /**
     * Update freelancer profile
     */
    public function updateProfile(Request $request): JsonResponse
    {
        try {
            $userId = $request->user()->user_id;

            $validator = Validator::make($request->all(), [
                'first_name' => 'nullable|string|max:255',
                'last_name' => 'nullable|string|max:255',
                'country' => 'nullable|string|max:255',
                'designation' => 'nullable|string|max:255',
                'experience_years' => 'nullable|integer|min:0',
                'hourly_rate' => 'nullable|numeric|min:0',
                'summary' => 'nullable|string',
                'profile_picture' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json(
                    MessageHelper::error($validator->errors()->first()),
                    422
                );
            }

            DB::beginTransaction();

            // Update freelancer_details
            $updateData = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'country' => $request->country,
                'designation' => $request->designation,
                'experience_years' => $request->experience_years,
                'hourly_rate' => $request->hourly_rate,
                'summary' => $request->summary,
                'updated_at' => now()
            ];

            // Add profile_image if profile_picture is provided
            if ($request->has('profile_picture')) {
                $updateData['profile_image'] = $request->profile_picture;
            }

            DB::table('freelancer_details')->updateOrInsert(
                ['user_id' => $userId],
                $updateData
            );

            DB::commit();

            return response()->json(
                MessageHelper::success('Profile updated successfully'),
                200
            );

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(
                MessageHelper::error('An error occurred: ' . $e->getMessage()),
                500
            );
        }
    }

    /**
     * Add work experience
     */
    public function addWorkExperience(Request $request): JsonResponse
    {
        try {
            $userId = $request->user()->user_id;

            $validator = Validator::make($request->all(), [
                'company_name' => 'required|string|max:255',
                'joining_date' => 'required|date',
                'location' => 'nullable|string|max:255',
                'responsibility' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json(
                    MessageHelper::error($validator->errors()->first()),
                    422
                );
            }

            $experienceId = DB::table('work_experience')->insertGetId([
                'user_id' => $userId,
                'company_name' => $request->company_name,
                'start_date' => $request->joining_date,
                'location' => $request->location,
                'description' => $request->responsibility,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json(
                MessageHelper::success(['id' => $experienceId], 'Work experience added successfully'),
                201
            );

        } catch (Exception $e) {
            return response()->json(
                MessageHelper::error('An error occurred: ' . $e->getMessage()),
                500
            );
        }
    }

    /**
     * Delete work experience
     */
    public function deleteWorkExperience(Request $request, int $id): JsonResponse
    {
        try {
            $userId = $request->user()->user_id;

            $deleted = DB::table('work_experience')
                         ->where('experience_id', $id)
                         ->where('user_id', $userId)
                         ->delete();

            if (!$deleted) {
                return response()->json(
                    MessageHelper::error('Work experience not found'),
                    404
                );
            }

            return response()->json(
                MessageHelper::success(null, 'Work experience deleted successfully'),
                200
            );

        } catch (Exception $e) {
            return response()->json(
                MessageHelper::error('An error occurred: ' . $e->getMessage()),
                500
            );
        }
    }

    /**
     * Add education
     */
    public function addEducation(Request $request): JsonResponse
    {
        try {
            $userId = $request->user()->user_id;

            $validator = Validator::make($request->all(), [
                'college' => 'required|string|max:255',
                'subject' => 'required|string|max:255',
                'starting_time' => 'required|date',
                'finishing_time' => 'nullable|date|after:starting_time',
            ]);

            if ($validator->fails()) {
                return response()->json(
                    MessageHelper::error($validator->errors()->first()),
                    422
                );
            }

            $educationId = DB::table('education')->insertGetId([
                'user_id' => $userId,
                'institution_name' => $request->college,
                'degree' => $request->subject,
                'start_date' => $request->starting_time,
                'end_date' => $request->finishing_time,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json(
                MessageHelper::success(['id' => $educationId], 'Education added successfully'),
                201
            );

        } catch (Exception $e) {
            return response()->json(
                MessageHelper::error('An error occurred: ' . $e->getMessage()),
                500
            );
        }
    }

    /**
     * Delete education
     */
    public function deleteEducation(Request $request, int $id): JsonResponse
    {
        try {
            $userId = $request->user()->user_id;

            $deleted = DB::table('education')
                         ->where('education_id', $id)
                         ->where('user_id', $userId)
                         ->delete();

            if (!$deleted) {
                return response()->json(
                    MessageHelper::error('Education not found'),
                    404
                );
            }

            return response()->json(
                MessageHelper::success(null, 'Education deleted successfully'),
                200
            );

        } catch (Exception $e) {
            return response()->json(
                MessageHelper::error('An error occurred: ' . $e->getMessage()),
                500
            );
        }
    }

    /**
     * Add portfolio item
     */
    public function addPortfolio(Request $request): JsonResponse
    {
        try {
            $userId = $request->user()->user_id;

            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'image' => 'required|string',
                'tags' => 'nullable|array',
            ]);

            if ($validator->fails()) {
                return response()->json(
                    MessageHelper::error($validator->errors()->first()),
                    422
                );
            }

            $portfolioId = DB::table('portfolio')->insertGetId([
                'user_id' => $userId,
                'title' => $request->title,
                'image_url' => $request->image,
                'tags' => json_encode($request->tags ?? []),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json(
                MessageHelper::success(['id' => $portfolioId], 'Portfolio item added successfully'),
                201
            );

        } catch (Exception $e) {
            return response()->json(
                MessageHelper::error('An error occurred: ' . $e->getMessage()),
                500
            );
        }
    }

    /**
     * Delete portfolio item
     */
    public function deletePortfolio(Request $request, int $id): JsonResponse
    {
        try {
            $userId = $request->user()->user_id;

            $deleted = DB::table('portfolio')
                         ->where('portfolio_id', $id)
                         ->where('user_id', $userId)
                         ->delete();

            if (!$deleted) {
                return response()->json(
                    MessageHelper::error('Portfolio item not found'),
                    404
                );
            }

            return response()->json(
                MessageHelper::success(null, 'Portfolio item deleted successfully'),
                200
            );

        } catch (Exception $e) {
            return response()->json(
                MessageHelper::error('An error occurred: ' . $e->getMessage()),
                500
            );
        }
    }

    /**
     * Add certification
     */
    public function addCertification(Request $request): JsonResponse
    {
        try {
            $userId = $request->user()->user_id;

            $validator = Validator::make($request->all(), [
                'institute_name' => 'required|string|max:255',
                'course_name' => 'required|string|max:255',
                'starting_time' => 'required|date',
                'finishing_time' => 'nullable|date',
            ]);

            if ($validator->fails()) {
                return response()->json(
                    MessageHelper::error($validator->errors()->first()),
                    422
                );
            }

            $certificationId = DB::table('certifications')->insertGetId([
                'user_id' => $userId,
                'issuing_organization' => $request->institute_name,
                'certification_name' => $request->course_name,
                'issue_date' => $request->starting_time,
                'expiration_date' => $request->finishing_time,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json(
                MessageHelper::success(['id' => $certificationId], 'Certification added successfully'),
                201
            );

        } catch (Exception $e) {
            return response()->json(
                MessageHelper::error('An error occurred: ' . $e->getMessage()),
                500
            );
        }
    }

    /**
     * Delete certification
     */
    public function deleteCertification(Request $request, int $id): JsonResponse
    {
        try {
            $userId = $request->user()->user_id;

            $deleted = DB::table('certifications')
                         ->where('certification_id', $id)
                         ->where('user_id', $userId)
                         ->delete();

            if (!$deleted) {
                return response()->json(
                    MessageHelper::error('Certification not found'),
                    404
                );
            }

            return response()->json(
                MessageHelper::success(null, 'Certification deleted successfully'),
                200
            );

        } catch (Exception $e) {
            return response()->json(
                MessageHelper::error('An error occurred: ' . $e->getMessage()),
                500
            );
        }
    }

    /**
     * Update expertise/skills
     */
    public function updateExpertiseSkills(Request $request): JsonResponse
    {
        try {
            $userId = $request->user()->user_id;

            $validator = Validator::make($request->all(), [
                'expertise' => 'nullable|array',
                'skills' => 'nullable|array',
            ]);

            if ($validator->fails()) {
                return response()->json(
                    MessageHelper::error($validator->errors()->first()),
                    422
                );
            }

            DB::beginTransaction();

            // Update expertise
            if ($request->has('expertise')) {
                DB::table('expertise')->where('user_id', $userId)->delete();

                foreach ($request->expertise as $item) {
                    DB::table('expertise')->insert([
                        'user_id' => $userId,
                        'expertise_name' => $item,
                        'created_at' => now()
                    ]);
                }
            }

            // Update skills
            if ($request->has('skills')) {
                DB::table('skills')->where('user_id', $userId)->delete();

                foreach ($request->skills as $item) {
                    DB::table('skills')->insert([
                        'user_id' => $userId,
                        'skill_name' => $item,
                        'created_at' => now()
                    ]);
                }
            }

            DB::commit();

            return response()->json(
                MessageHelper::success('Expertise and skills updated successfully'),
                200
            );

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(
                MessageHelper::error('An error occurred: ' . $e->getMessage()),
                500
            );
        }
    }
}
