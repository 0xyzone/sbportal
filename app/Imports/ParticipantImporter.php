<?php

namespace App\Imports;

use App\Models\Participant;
use Maatwebsite\Excel\Concerns\ToModel;

class ParticipantImporter implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Participant([
            // Use auth()->id() to link the participant to the current user
            'user_id'          => auth()->id(), 
            
            // Map Excel headers to your DB columns
            'full_name'        => $row['full_name'],
            'date_of_birth'    => $this->transformDate($row['date_of_birth']),
            'grade_level'      => $row['grade_level'],
            'gender'           => $row['gender'],
            'physical_address' => $row['physical_address'] ?? null,
            
            // image_path is left null for now as it's a file upload, 
            // unless you have paths in your Excel.
            'image_path'       => null,
        ]);
    }

    public function rules(): array
    {
        return [
            'full_name'     => 'required|string|max:255',
            'date_of_birth' => 'nullable', // You can add 'date' here if the format is consistent
            'grade_level'   => 'nullable|string',
            'gender'        => 'nullable|string|in:Male,Female,Other',
        ];
    }

    /**
     * Helper to handle Excel date formatting if needed
     */
    private function transformDate($value)
    {
        if (empty($value)) return null;
        
        try {
            return \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
        } catch (\ErrorException $e) {
            return \Carbon\Carbon::parse($value);
        }
    }
}
