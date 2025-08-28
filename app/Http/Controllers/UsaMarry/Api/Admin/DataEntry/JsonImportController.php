<?php

namespace App\Http\Controllers\UsaMarry\Api\Admin\DataEntry;

use App\Models\User;
use App\Models\Photo;
use App\Models\Profile;
use Illuminate\Http\File;
use Illuminate\Support\Str;

use Illuminate\Http\Request;
use App\Models\PartnerPreference;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class JsonImportController extends Controller
{


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'json' => 'required',
            'email' => 'required',
            'phone' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }


        $jsonData = $request->json;
        $email = $request->email;
        $phone = $request->phone;
        $photo = $request->photo ?? null;



        // try {



            $result = $this->processJsonData($jsonData,$email,$phone,$photo);

            return response()->json($result);

        // } catch (\Exception $e) {
        //     return redirect()->back()
        //         ->with('error', 'Error processing JSON: ' . $e->getMessage())
        //         ->withInput();
        // }

    }

    private function processJsonData($jsonData,$email,$phone,$photo)
    {
        if (!isset($jsonData['data'])) {
            return [
                'success' => false,
                'message' => 'Invalid JSON structure: missing data key'
            ];
        }

         $data = $jsonData['data'];








         $userData = $this->extractUserData($data,$email,$phone);





        // Extract profile data
        $profileData = $this->extractProfileData($data);




        // Create user and profile
        $user = User::create($userData);
        $profileData['user_id'] = $user->id;
        Profile::create($profileData);

        // Create partner preference
        $partnerPreferenceData = $this->extractPartnerPreferences($data);
        $partnerPreferenceData['user_id'] = $user->id;
        PartnerPreference::create($partnerPreferenceData);

        $photos = $this->saveProfilePhoto($data, $user->id,$photo);

        updateProfileCompletionWithPercentage($user);

        return [
            'success' => true,
            'user' =>  new UserResource($user),
            'message' => 'Profile imported successfully'
        ];
    }

    private function extractUserData($data,$email,$phone)
    {
        $contact = $data['contact'] ?? [];
        $flags = $data['flags'] ?? [];
        $family = $flags['family'] ?? [];
        $base = $flags['base']['infoList'] ?? [];
        $dob = $this->extractDateOfBirthFromAge($data);



        $userdatas =  [
            'name' => $data['name'] ?? null,
            'password' => \Illuminate\Support\Facades\Hash::make("Password123"),
            'email' => $email ?? null,
            'phone' => $phone ?? null,
            'whatsapps' => $phone ?? null, // Not available in JSON
            'gender' => $data['gender'] ?? null,
            'dob' => $dob ?? null, // Not available (masked in JSON)
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
            'disability' => 0, // Not available in JSON
            'mother_tongue' => $this->extractMotherTongue($data),
            'profile_created_by' => $data['profileCreatedBy'] ?? $data['createdBy'] ?? null,
            'verified' => $contact['mobile_verified'] === 'Y',
            'account_status' => 'active',
            // 'photo_privacy' => $data['privacy']['photo'] ?? 'Show All',
            // 'photo_visibility' => $contact['contact_details_title_status'] ?? 'when_i_contact',
            // 'is_top_profile' => $flags['membershipLevel'] !== 'Free',
        ];

        return $userdatas;

    }

    private function extractProfileData($data)
    {
        $detailed = $data['detailed'] ?? [];
        $lifestyle = $detailed['lifestyle'] ?? [];
        $family = $data['flags']['family'] ?? [];
        $education = $detailed['education'] ?? [];
        $profession = $detailed['profession'] ?? [];



        $profileData = [
            'about' => $detailed['about'] ?? null,
            'highest_degree' => $this->extractHighestDegree($education),
            'institution' => null, // Not available in JSON
            'occupation' => $this->extractOccupation($data),


            'annual_income' => $this->convertIncomeToUSD($family['familyincome']) ?? null,

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
            // 'show_contact' => $data['contact']['contact_details_title_status'] ?? 'When I Contact',
            // 'visible_to' => $data['privacy']['profile_privacy'] ?? 'Show All',
        ];

        return $profileData;
    }



    private function extractPartnerPreferences($data)
    {
        $preferences = $data['detailed']['preferences']['items'] ?? [];

        $result = [
            'age_min' => null,
            'age_max' => null,
            'height_min' => null,
            'height_max' => null,
            'marital_status' => [],
            'religion' => [],
            'caste' => [],
            'education' => [],
            'occupation' => [],
            'country' => [],
            'family_type' => [],
            'state' => [],
            'city' => [],
            'mother_tongue' => [],
        ];

        foreach ($preferences as $item) {
            $key = $item['preferenceKey'] ?? null;
            $desc = $item['desc'] ?? null;

            switch ($key) {
                case 'age':
                    if (preg_match('/(\d+)\s*to\s*(\d+)/', $desc, $matches)) {
                        $result['age_min'] = (int) $matches[1];
                        $result['age_max'] = (int) $matches[2];
                    }
                    break;

                case 'height':
                    if (preg_match_all("/(\d+)'\s*(\d+)?\"?\((\d+)cm\)/", $desc, $matches)) {
                        $result['height_min'] = isset($matches[3][0]) ? (int) $matches[3][0] : null;
                        $result['height_max'] = isset($matches[3][1]) ? (int) $matches[3][1] : null;
                    }
                    break;

                case 'maritalstatus':
                    $result['marital_status'] = array_map('trim', explode(',', $desc));
                    break;

                case 'caste':
                    $result['caste'] = array_map('trim', explode(',', $desc));
                    break;

                case 'mothertongue':
                    $result['mother_tongue'] = array_map('trim', explode(',', $desc));
                    break;

                case 'country':
                    $result['country'] = array_map('trim', explode(',', $desc));
                    break;

                default:
                    // handle other keys if needed
                    break;
            }
        }


        return $result;
    }



   private function saveProfilePhoto($data, $userId, $photos)
{
    if (!is_array($photos) || empty($photos)) {
        Log::warning('No photos provided for user ID: ' . $userId);
        return;
    }

    foreach ($photos as $index => $photoUrl) {
        if (!$photoUrl) {
            continue;
        }

        try {
            // Download the image
            $response = Http::get($photoUrl);

            if (!$response->successful()) {
                Log::error("Failed to download photo from URL: $photoUrl");
                continue;
            }

            // Create a temporary file
            $tempPath = storage_path('app/temp_photo_' . Str::random(10) . '.webp');
            file_put_contents($tempPath, $response->body());

            // Create UploadedFile instance
            $file = new \Illuminate\Http\UploadedFile(
                $tempPath,
                basename($tempPath),
                'image/webp',
                null,
                true
            );

            // Upload to S3
            $s3Path = uploadFileToS3($file, 'profile-photos');

            // Delete temp file
            @unlink($tempPath);

            // Save to DB
            Photo::create([
                'user_id' => $userId,
                'path' => $s3Path,
                'is_primary' => $index === 0, // First photo is primary
                'is_approved' => true,
            ]);
        } catch (\Exception $e) {
            Log::error("Error saving profile photo from URL $photoUrl: " . $e->getMessage());
        }
    }
}


private function extractDateOfBirthFromAge($data)
{
    $infoList = $data['base']['infoList'] ?? [];

    foreach ($infoList as $item) {
        if (($item['key'] ?? '') === 'age-height') {
            $value = $item['value'] ?? '';

            // Extract age from string like "25 yrs, 5' 1\", Muslim, Bengali"
            if (preg_match('/(\d+)\s*yrs/', $value, $matches)) {
                $age = (int)$matches[1];

                // Calculate approximate DOB (assuming birthday already occurred this year)
                $dob = now()->subYears($age)->format('Y-m-d');
                return $dob;
            }
        }
    }

    return null; // If not found or format doesn't match
}




    private function convertIncomeToUSD($incomeString)
    {
        // Approximate exchange rate
        $exchangeRate = 0.012; // 1 INR = 0.012 USD (update as needed)

        // Match the numbers in the string
        if (preg_match('/INR\s*([\d.]+)-([\d.]+)\s*lakhs/i', $incomeString, $matches)) {
            $minInLakh = (float) $matches[1];
            $maxInLakh = (float) $matches[2];

            // Convert lakhs to INR (1 lakh = 100,000)
            $minINR = $minInLakh * 100000;
            $maxINR = $maxInLakh * 100000;

            // Convert to USD
            $minUSD = $minINR * $exchangeRate;
            $maxUSD = $maxINR * $exchangeRate;

            // Round for clean format
            $minUSD = round($minUSD);
            $maxUSD = round($maxUSD);

            return "$minUSD - $maxUSD";
        }

        return null;
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

        // Match pattern like 5' 1" or 5'1"
        if (preg_match("/(\d+)'\s*(\d+)?\"?/", $baseInfo, $matches)) {
            $feet = (int) $matches[1];
            $inches = isset($matches[2]) ? (int) $matches[2] : 0;

            // Convert to centimeters
            $cm = ($feet * 30.48) + ($inches * 2.54);

            // Return as rounded integer or float with one decimal
            return round($cm, 2);
        }

        return null;
    }



    private function extractGrewUpIn($data)
    {
        $value = $data['base']['infoList'][3]['value'] ??
                $data['summary']['infoMapNonIndian'][4]['value'] ?? null;

        if ($value) {
            // "Grew up in Bangladesh" → "Bangladesh"
            return str_replace('Grew up in ', '', $value);
        }

        return null;
    }




    private function extractHobbies($data)
    {
        $hobbies = [];

        if (!empty($data['detailed']['personalityTags'])) {
            foreach ($data['detailed']['personalityTags'] as $tag) {
                $value = $tag['tag_display'] ?? $tag['tag'] ?? null;
                if (!empty($value)) {
                    $hobbies[] = $value;
                }
            }
        }

        return $hobbies;
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
        $brothers = isset($family['brothers']) ? (int) $family['brothers'] : 0;
        $sisters = isset($family['sisters']) ? (int) $family['sisters'] : 0;

        $totalSiblings = $brothers + $sisters;

        Log::info("Extracted siblings: Brothers = $brothers, Sisters = $sisters, Total = $totalSiblings");

        return $totalSiblings;
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
