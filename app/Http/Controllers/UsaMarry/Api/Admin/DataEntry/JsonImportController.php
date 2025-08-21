<?php

namespace App\Http\Controllers\UsaMarry\Api\Admin\DataEntry;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class JsonImportController extends Controller
{


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'json_data' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }


        $jsonData = $request->json_data;




        try {
         return   $result = $this->processJsonData($jsonData);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error processing JSON: ' . $e->getMessage())
                ->withInput();
        }
    }

    private function processJsonData($jsonData)
    {
        if (!isset($jsonData['data'])) {
            return [
                'success' => false,
                'message' => 'Invalid JSON structure: missing data key'
            ];
        }

         $data = $jsonData['data'];
         $profileId = $data['uid'] ?? strtoupper(Str::random(10));

 

        // Check if user already exists
        if (User::where('profile_id', $profileId)->exists()) {
            return [
                'success' => false,
                'message' => 'Profile with this ID already exists'
            ];
        }

        // Extract user data
        return $userData = $this->extractUserData($data);
        $userData['profile_id'] = $profileId;

        // Extract profile data
        $profileData = $this->extractProfileData($data);
        $profileData['user_id'] = $profileId;

        // Create user and profile
        $user = User::create($userData);
        Profile::create($profileData);

        return [
            'success' => true,
            'profile_id' => $profileId,
            'message' => 'Profile imported successfully'
        ];
    }

    private function extractUserData($data)
    {
        $contact = $data['contact'] ?? [];
        $flags = $data['flags'] ?? [];
        $family = $flags['family'] ?? [];

        return [
            'name' => $data['name'] ?? null,
            'email' => $contact['email'] ?? null,
            'phone' => $contact['contact_number'] ?? null,
            'whatsapps' => null, // Not available in JSON
            'gender' => $data['gender'] ?? null,
            'dob' => null, // Not available (masked in JSON)
            'religion' => $this->extractReligion($data),
            'caste' => $this->extractCaste($data),
            'sub_caste' => null, // Not explicitly available
            'marital_status' => $data['marital_status'] ?? null,
            'height' => $this->extractHeight($data),
            'blood_group' => null, // Not available in JSON
            'disability_issue' => null, // Not available in JSON
            'family_location' => $family['location'] ?? null,
            'grew_up_in' => $this->extractGrewUpIn($data),
            'hobbies' => $this->extractHobbies($data),
            'disability' => null, // Not available in JSON
            'mother_tongue' => $this->extractMotherTongue($data),
            'profile_created_by' => $data['profileCreatedBy'] ?? $data['createdBy'] ?? null,
            'verified' => $contact['mobile_verified'] === 'Y',
            'profile_completion' => $this->calculateProfileCompletion($data),
            'account_status' => 'active',
            'photo_privacy' => $data['privacy']['photo'] ?? 'Show All',
            'photo_visibility' => $contact['contact_details_title_status'] ?? 'when_i_contact',
            'is_top_profile' => $flags['membershipLevel'] !== 'Free',
        ];
    }

    private function extractProfileData($data)
    {
        $detailed = $data['detailed'] ?? [];
        $lifestyle = $detailed['lifestyle'] ?? [];
        $family = $data['flags']['family'] ?? [];
        $education = $detailed['education'] ?? [];
        $profession = $detailed['profession'] ?? [];

        return [
            'about' => $detailed['about'] ?? null,
            'highest_degree' => $this->extractHighestDegree($education),
            'institution' => null, // Not available in JSON
            'occupation' => $this->extractOccupation($data),
            'annual_income' => $family['familyincome'] ?? null,
            'employed_in' => $profession['working_with'] ?? null,
            'father_status' => $family['father_profession'] ?? null,
            'mother_status' => $family['mother_profession'] ?? null,
            'siblings' => $this->extractSiblings($family),
            'family_type' => $family['type'] ?? null,
            'family_values' => $family['cultural_values'] ?? null,
            'financial_status' => $family['affluence'] ?? null,
            'diet' => $lifestyle['diet'] ?? null,
            'drink' => $lifestyle['drink'] ?? null,
            'smoke' => $lifestyle['smoke'] ?? null,
            'country' => 'Bangladesh', // From location data
            'state' => 'Dhaka', // From location data
            'city' => 'Dhaka', // From location data
            'resident_status' => $this->extractResidentStatus($data),
            'has_horoscope' => !empty($data['astro']['details']),
            'rashi' => $data['astro']['details']['moon_sign'] ?? null,
            'nakshatra' => $data['astro']['details']['birth_star_nakshatra'] ?? null,
            'manglik' => $data['astro']['details']['manglik'] ?? null,
            'show_contact' => $data['contact']['contact_details_title_status'] ?? 'When I Contact',
            'visible_to' => $data['privacy']['profile_privacy'] ?? 'Show All',
        ];
    }

    private function extractReligion($data)
    {
        if (isset($data['detailed']['infoMap'])) {
            foreach ($data['detailed']['infoMap'] as $info) {
                if (($info['icon'] ?? '') === 'profile_religion') {
                    return explode(',', $info['value'])[0] ?? null;
                }
            }
        }

        return $data['summary']['infoMap'][1]['value'] ?? null;
    }

    private function extractCaste($data)
    {
        if (isset($data['detailed']['infoMap'])) {
            foreach ($data['detailed']['infoMap'] as $info) {
                if (($info['icon'] ?? '') === 'profile_community') {
                    return trim($info['value']);
                }
            }
        }

        return $data['summary']['infoMap'][3]['value'] ?? null;
    }

    private function extractHeight($data)
    {
        $baseInfo = $data['base']['infoMap'][0]['value'] ?? '';
        if (preg_match('/(\d+\'\s*\d+")/', $baseInfo, $matches)) {
            return $matches[1];
        }
        return null;
    }

    private function extractGrewUpIn($data)
    {
        return $data['base']['infoList'][3]['value'] ??
               $data['summary']['infoMapNonIndian'][4]['value'] ?? null;
    }

    private function extractHobbies($data)
    {
        $hobbies = [];
        if (isset($data['detailed']['personalityTags'])) {
            foreach ($data['detailed']['personalityTags'] as $tag) {
                $hobbies[] = $tag['tag_display'] ?? $tag['tag'] ?? '';
            }
        }
        return implode(', ', array_filter($hobbies));
    }

    private function extractMotherTongue($data)
    {
        return $data['base']['infoMap'][2]['value'] ??
               $data['summary']['infoMap'][2]['value'] ?? null;
    }

    private function extractHighestDegree($education)
    {
        if (isset($education['items'])) {
            foreach ($education['items'] as $item) {
                if (($item['icon'] ?? '') === 'edu_qualification') {
                    return $item['desc'] ?? null;
                }
            }
        }
        return null;
    }

    private function extractOccupation($data)
    {
        return $data['base']['infoMap'][3]['value'] ??
               $data['summary']['infoMap'][6]['value'] ?? null;
    }

    private function extractSiblings($family)
    {
        $siblings = [];
        if (!empty($family['brothers'])) {
            $siblings[] = $family['brothers'] . ' Brother(s)';
        }
        if (!empty($family['sisters'])) {
            $siblings[] = $family['sisters'] . ' Sister(s)';
        }
        return implode(', ', $siblings);
    }

    private function extractResidentStatus($data)
    {
        if (isset($data['detailed']['background'])) {
            foreach ($data['detailed']['background'] as $bg) {
                if (str_contains($bg['desc'] ?? '', '(Citizen)')) {
                    return 'Citizen';
                }
            }
        }
        return null;
    }

    private function calculateProfileCompletion($data)
    {
        $completion = 0;
        $fields = ['name', 'email', 'gender', 'marital_status', 'religion', 'height'];

        foreach ($fields as $field) {
            if (!empty($this->extractUserData($data)[$field])) {
                $completion += 15;
            }
        }

        return min(100, $completion);
    }
}
