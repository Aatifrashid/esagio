<?php

namespace Database\Factories;

use App\Models\TreatmentPlan;
use App\Models\TreatmentPlanAttachment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TreatmentPlanAttachment>
 */
class TreatmentPlanAttachmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fileTypes = [
            ['name' => 'xray_panoramic.jpg', 'type' => 'image/jpeg', 'size' => 1240000],
            ['name' => 'cbct_scan.dcm', 'type' => 'application/dicom', 'size' => 45000000],
            ['name' => 'consent_form.pdf', 'type' => 'application/pdf', 'size' => 320000],
            ['name' => 'before_photo.jpg', 'type' => 'image/jpeg', 'size' => 2100000],
            ['name' => 'finance_agreement.pdf', 'type' => 'application/pdf', 'size' => 480000],
            ['name' => 'medical_questionnaire.pdf', 'type' => 'application/pdf', 'size' => 250000],
        ];

        $file = $this->faker->randomElement($fileTypes);

        return [
            'treatment_plan_id' => TreatmentPlan::factory(),
            'file_name' => $file['name'],
            'file_path' => 'attachments/'.now()->year.'/'.$this->faker->uuid().'/'.$file['name'],
            'file_type' => $file['type'],
            'file_size_bytes' => $file['size'],
            'description' => $this->faker->optional(0.5)->sentence(),
            'is_visible_to_patient' => $this->faker->boolean(70),
            'uploaded_by' => null,
        ];
    }
}
