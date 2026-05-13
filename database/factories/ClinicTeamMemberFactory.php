<?php

namespace Database\Factories;

use App\Models\Clinic;
use App\Models\ClinicTeamMember;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ClinicTeamMember>
 */
class ClinicTeamMemberFactory extends Factory
{
    private static array $dentists = [
        [
            'first_name' => 'James',
            'last_name' => 'Hargreaves',
            'title' => 'Dr.',
            'role' => 'Implantologist',
            'specialisation' => 'Oral Implantology and Full Arch Rehabilitation',
            'bio' => 'Dr. James Hargreaves is a specialist implantologist with over 15 years of experience placing implants in the UK and across Europe. He holds a Diploma in Implant Dentistry from the Royal College of Surgeons of England and has trained with Nobel Biocare and Straumann. He has completed over 3,000 implant cases and lectures internationally on full arch rehabilitation.',
            'qualifications' => 'BDS (Hons) University of Leeds, MFDS RCS(Ed), Diploma in Implant Dentistry RCS(Eng)',
            'years_experience' => 15,
            'languages_spoken' => 'English',
        ],
        [
            'first_name' => 'Sophia',
            'last_name' => 'Castellani',
            'title' => 'Dr.',
            'role' => 'Cosmetic Dentist',
            'specialisation' => 'Aesthetic Dentistry and Smile Design',
            'bio' => 'Dr. Sophia Castellani has dedicated her career to cosmetic and restorative dentistry. She trained in Rome and completed her postgraduate studies in smile design at the Eastman Dental Institute, London. She has a particular interest in porcelain veneers, composite bonding, and digital smile design.',
            'qualifications' => 'Laurea in Odontoiatria (University of Rome), MClinDent Aesthetic Dentistry (UCL)',
            'years_experience' => 12,
            'languages_spoken' => 'English, Italian',
        ],
        [
            'first_name' => 'Mehmet',
            'last_name' => 'Ozkan',
            'title' => 'Dr.',
            'role' => 'Oral and Maxillofacial Surgeon',
            'specialisation' => 'Implant Surgery, Bone Grafting, Wisdom Tooth Removal',
            'bio' => 'Dr. Mehmet Ozkan is a highly experienced oral surgeon who qualified from Istanbul University Faculty of Dentistry. He completed his surgical training in Germany and has been practising implant surgery and complex extractions for 18 years. He is a certified trainer for MegaGen and Osstem implant systems.',
            'qualifications' => 'DDS Istanbul University, Postgraduate Certificate in Oral Surgery (Berlin)',
            'years_experience' => 18,
            'languages_spoken' => 'English, Turkish, German',
        ],
        [
            'first_name' => 'Priya',
            'last_name' => 'Sharma',
            'title' => 'Dr.',
            'role' => 'Orthodontist',
            'specialisation' => 'Clear Aligners and Fixed Orthodontics',
            'bio' => 'Dr. Priya Sharma completed her specialist orthodontic training at King\'s College London after qualifying from the University of Birmingham. She is a certified provider of Invisalign and ClearCorrect aligners and has treated over 800 cases. She has a special interest in interdisciplinary treatment combining orthodontics with implants and veneers.',
            'qualifications' => 'BDS University of Birmingham, MOrth RCS(Eng), Dip Orth',
            'years_experience' => 10,
            'languages_spoken' => 'English, Hindi',
        ],
        [
            'first_name' => 'Aisha',
            'last_name' => 'Patel',
            'title' => 'Dr.',
            'role' => 'Periodontist',
            'specialisation' => 'Periodontal Disease, Gum Surgery, and Implant Maintenance',
            'bio' => 'Dr. Aisha Patel is a GDC-registered specialist in periodontics with a clinical focus on the management of severe gum disease and peri-implantitis. She completed her specialist training at the Eastman Dental Institute and holds a Masters degree in periodontology.',
            'qualifications' => 'BDS (Hons) UCL, MClinDent Periodontology (UCL), MPerio RCS(Ed)',
            'years_experience' => 8,
            'languages_spoken' => 'English, Gujarati',
        ],
        [
            'first_name' => 'Robert',
            'last_name' => 'Fenton',
            'title' => 'Dr.',
            'role' => 'Prosthodontist',
            'specialisation' => 'Complex Restorations, Dentures, and Implant Prosthetics',
            'bio' => 'Dr. Robert Fenton is a specialist prosthodontist with extensive experience in planning and delivering complex full-mouth rehabilitation cases. He works closely with oral surgeons and uses the latest digital workflow technology to design and fabricate implant-supported bridges and dentures.',
            'qualifications' => 'BDS University of Sheffield, MClinDent Prosthodontics (King\'s College London)',
            'years_experience' => 13,
            'languages_spoken' => 'English',
        ],
        [
            'first_name' => 'Elif',
            'last_name' => 'Yildirim',
            'title' => 'Dr.',
            'role' => 'General and Aesthetic Dentist',
            'specialisation' => 'Preventive Dentistry and Composite Bonding',
            'bio' => 'Dr. Elif Yildirim qualified from Hacettepe University in Ankara and completed further training in aesthetic dentistry in London. She is particularly skilled in composite bonding, teeth whitening, and creating natural-looking smile makeovers without invasive preparation.',
            'qualifications' => 'DDS Hacettepe University, Certificate in Aesthetic Dentistry (London)',
            'years_experience' => 9,
            'languages_spoken' => 'English, Turkish',
        ],
    ];

    private static int $sortCounter = 0;

    public function definition(): array
    {
        $member = $this->faker->randomElement(self::$dentists);
        self::$sortCounter++;

        return [
            'clinic_id' => Clinic::factory(),
            'first_name' => $member['first_name'],
            'last_name' => $member['last_name'],
            'title' => $member['title'],
            'role' => $member['role'],
            'specialisation' => $member['specialisation'],
            'bio' => $member['bio'],
            'photo_path' => null,
            'qualifications' => $member['qualifications'],
            'years_experience' => $member['years_experience'],
            'languages_spoken' => $member['languages_spoken'],
            'sort_order' => self::$sortCounter,
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(['is_active' => false]);
    }

    public function implantologist(): static
    {
        $member = self::$dentists[0];

        return $this->state([
            'first_name' => $member['first_name'],
            'last_name' => $member['last_name'],
            'role' => $member['role'],
            'specialisation' => $member['specialisation'],
        ]);
    }
}
