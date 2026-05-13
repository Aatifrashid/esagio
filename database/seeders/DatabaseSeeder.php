<?php

namespace Database\Seeders;

use App\Models\AnimationClip;
use App\Models\BeforeAfterCase;
use App\Models\Clinic;
use App\Models\ClinicBranding;
use App\Models\ClinicTeamMember;
use App\Models\CrmActivity;
use App\Models\CrmPipeline;
use App\Models\CrmPipelineStage;
use App\Models\CrmTask;
use App\Models\LegalBlock;
use App\Models\Material;
use App\Models\Patient;
use App\Models\PriceList;
use App\Models\PriceListItem;
use App\Models\TreatmentCategory;
use App\Models\TreatmentTemplate;
use App\Models\User;
use Database\Factories\CrmPipelineStageFactory;
use Database\Factories\LegalBlockFactory;
use Database\Factories\TreatmentCategoryFactory;
use Database\Factories\TreatmentTemplateFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // -------------------------------------------------------------------
        // 1. Tooth chart conditions (system-wide lookup data)
        // -------------------------------------------------------------------
        $this->call(ToothChartConditionSeeder::class);

        // -------------------------------------------------------------------
        // 2. Shared (clinic-agnostic) reference data
        // -------------------------------------------------------------------
        $this->seedSharedTreatmentCategories();
        $this->seedSharedTreatmentTemplates();
        $this->seedSharedMaterials();
        $this->seedSharedLegalBlocks();
        $this->seedSharedAnimationClips();

        // -------------------------------------------------------------------
        // 3. Demo clinic with full CRM and treatment data
        // -------------------------------------------------------------------
        $clinic = $this->seedDemoClinic();

        $this->seedDemoAdmin($clinic);
        $this->seedClinicBranding($clinic);
        $this->seedTeamMembers($clinic);
        $this->seedCrmPipelines($clinic);
        $this->seedPriceList($clinic);
        $this->seedPatientsWithActivity($clinic);
        $this->seedBeforeAfterCases($clinic);
        $this->seedClinicLegalBlocks($clinic);
    }

    // -----------------------------------------------------------------------
    // Shared reference data
    // -----------------------------------------------------------------------

    private function seedSharedTreatmentCategories(): array
    {
        $categories = [];

        foreach (TreatmentCategoryFactory::CATEGORIES as $data) {
            $categories[$data['slug']] = TreatmentCategory::firstOrCreate(
                ['slug' => $data['slug'], 'clinic_id' => null],
                [
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'icon' => $data['icon'],
                    'sort_order' => $data['sort_order'],
                    'parent_id' => null,
                ]
            );
        }

        return $categories;
    }

    private function seedSharedTreatmentTemplates(): void
    {
        $categories = TreatmentCategory::whereNull('clinic_id')->get()->keyBy('slug');

        foreach (TreatmentTemplateFactory::$treatments as $treatment) {
            // Determine category by treatment code prefix
            $categorySlug = match (true) {
                str_starts_with($treatment['code'], 'IMP') => 'implants',
                str_starts_with($treatment['code'], 'CRN') => 'crowns-and-bridges',
                str_starts_with($treatment['code'], 'VEN') => 'veneers',
                str_starts_with($treatment['code'], 'WHT') => 'teeth-whitening',
                str_starts_with($treatment['code'], 'ORT') => 'orthodontics',
                str_starts_with($treatment['code'], 'END') => 'endodontics',
                str_starts_with($treatment['code'], 'SRG') => 'oral-surgery',
                str_starts_with($treatment['code'], 'PRD') => 'prosthodontics',
                default => 'general-dentistry',
            };

            $category = $categories->get($categorySlug);

            TreatmentTemplate::firstOrCreate(
                ['code' => $treatment['code'], 'clinic_id' => null],
                [
                    'category_id' => $category?->id,
                    'name' => $treatment['name'],
                    'description_short' => $treatment['description_short'],
                    'description_long' => $treatment['description_long'],
                    'standard_duration_days' => $treatment['standard_duration_days'],
                    'requires_imaging' => $treatment['requires_imaging'],
                    'legal_disclaimer' => 'Results may vary between patients. All treatment is subject to a full clinical assessment.',
                    'is_active' => true,
                    'usage_count' => 0,
                ]
            );
        }
    }

    private function seedSharedMaterials(): void
    {
        $materials = [
            ['name' => 'Nobel Biocare NobelParallel CC Implant',     'category' => 'implant',    'brand' => 'Nobel Biocare',      'country_of_origin' => 'Switzerland',   'is_premium' => true,  'warranty_years' => null],
            ['name' => 'Straumann Bone Level Tapered Implant',        'category' => 'implant',    'brand' => 'Straumann',          'country_of_origin' => 'Switzerland',   'is_premium' => true,  'warranty_years' => null],
            ['name' => 'Osstem TSIII Implant',                        'category' => 'implant',    'brand' => 'Osstem',             'country_of_origin' => 'South Korea',   'is_premium' => false, 'warranty_years' => null],
            ['name' => 'MegaGen AnyRidge Implant',                    'category' => 'implant',    'brand' => 'MegaGen',            'country_of_origin' => 'South Korea',   'is_premium' => false, 'warranty_years' => null],
            ['name' => 'BioHorizons Tapered Internal Implant',        'category' => 'implant',    'brand' => 'BioHorizons',        'country_of_origin' => 'United States', 'is_premium' => true,  'warranty_years' => null],
            ['name' => 'IPS e.max CAD Block',                         'category' => 'crown',      'brand' => 'Ivoclar Vivadent',   'country_of_origin' => 'Liechtenstein', 'is_premium' => true,  'warranty_years' => 5],
            ['name' => 'Katana Zirconia STML Block',                  'category' => 'crown',      'brand' => 'Kuraray Noritake',   'country_of_origin' => 'Japan',         'is_premium' => true,  'warranty_years' => 5],
            ['name' => 'VITA Mark II Feldspathic Porcelain Block',    'category' => 'crown',      'brand' => 'VITA Zahnfabrik',    'country_of_origin' => 'Germany',       'is_premium' => false, 'warranty_years' => 5],
            ['name' => 'Empress CAD Veneer Block',                    'category' => 'veneer',     'brand' => 'Ivoclar Vivadent',   'country_of_origin' => 'Liechtenstein', 'is_premium' => true,  'warranty_years' => 5],
            ['name' => 'Filtek Supreme Ultra Composite',              'category' => 'composite',  'brand' => '3M ESPE',            'country_of_origin' => 'United States', 'is_premium' => true,  'warranty_years' => null],
            ['name' => 'GC Initial Porcelain Layering Ceramic',       'category' => 'porcelain',  'brand' => 'GC Corporation',     'country_of_origin' => 'Japan',         'is_premium' => true,  'warranty_years' => null],
            ['name' => 'Opalescence PF 35% Whitening Gel',           'category' => 'whitening',  'brand' => 'Ultradent',          'country_of_origin' => 'United States', 'is_premium' => false, 'warranty_years' => null],
            ['name' => 'ZOOM! Advanced Power Whitening Gel',          'category' => 'whitening',  'brand' => 'Philips',            'country_of_origin' => 'United States', 'is_premium' => true,  'warranty_years' => null],
            ['name' => 'Nobel Biocare NobelProcera Zirconia Bridge',  'category' => 'bridge',     'brand' => 'Nobel Biocare',      'country_of_origin' => 'Sweden',        'is_premium' => true,  'warranty_years' => 5],
        ];

        foreach ($materials as $material) {
            Material::firstOrCreate(
                ['name' => $material['name'], 'clinic_id' => null],
                $material
            );
        }
    }

    private function seedSharedLegalBlocks(): void
    {
        foreach (LegalBlockFactory::BLOCKS as $block) {
            LegalBlock::firstOrCreate(
                ['code' => $block['code'], 'clinic_id' => null],
                [
                    'name' => $block['name'],
                    'content' => $block['content'],
                    'language' => 'en',
                    'jurisdiction' => $block['jurisdiction'],
                    'is_default' => $block['is_default'],
                ]
            );
        }
    }

    private function seedSharedAnimationClips(): void
    {
        $clips = [
            ['code' => 'ANIM-IMP-001', 'name' => 'Single Implant Placement Procedure',          'procedure_type' => 'implant',      'jaw' => 'both',  'duration_seconds' => 90,  'category_slug' => 'implants'],
            ['code' => 'ANIM-IMP-002', 'name' => 'All-on-4 Full Arch Restoration',              'procedure_type' => 'implant',      'jaw' => 'upper', 'duration_seconds' => 120, 'category_slug' => 'implants'],
            ['code' => 'ANIM-IMP-003', 'name' => 'Implant Osseointegration Process',            'procedure_type' => 'implant',      'jaw' => 'both',  'duration_seconds' => 60,  'category_slug' => 'implants'],
            ['code' => 'ANIM-CRN-001', 'name' => 'Crown Preparation and Fitting',               'procedure_type' => 'crown',        'jaw' => 'upper', 'duration_seconds' => 75,  'category_slug' => 'crowns-and-bridges'],
            ['code' => 'ANIM-VEN-001', 'name' => 'Porcelain Veneer Application',                'procedure_type' => 'veneer',       'jaw' => 'upper', 'duration_seconds' => 65,  'category_slug' => 'veneers'],
            ['code' => 'ANIM-EXT-001', 'name' => 'Simple Tooth Extraction',                     'procedure_type' => 'extraction',   'jaw' => 'lower', 'duration_seconds' => 45,  'category_slug' => 'oral-surgery'],
            ['code' => 'ANIM-EXT-002', 'name' => 'Surgical Wisdom Tooth Removal',               'procedure_type' => 'extraction',   'jaw' => 'lower', 'duration_seconds' => 90,  'category_slug' => 'oral-surgery'],
            ['code' => 'ANIM-RCT-001', 'name' => 'Root Canal Treatment Procedure',              'procedure_type' => 'root_canal',   'jaw' => 'upper', 'duration_seconds' => 80,  'category_slug' => 'endodontics'],
            ['code' => 'ANIM-WHT-001', 'name' => 'In-clinic Teeth Whitening Process',           'procedure_type' => 'whitening',    'jaw' => 'both',  'duration_seconds' => 50,  'category_slug' => 'teeth-whitening'],
            ['code' => 'ANIM-ORT-001', 'name' => 'Clear Aligner Teeth Movement',                'procedure_type' => 'orthodontics', 'jaw' => 'both',  'duration_seconds' => 60,  'category_slug' => 'orthodontics'],
            ['code' => 'ANIM-PER-001', 'name' => 'Periodontal Deep Cleaning (Scaling and Root Planing)', 'procedure_type' => 'periodontal', 'jaw' => 'both', 'duration_seconds' => 55, 'category_slug' => 'periodontics'],
        ];

        $categories = TreatmentCategory::whereNull('clinic_id')->get()->keyBy('slug');

        foreach ($clips as $clip) {
            $category = $categories->get($clip['category_slug']);

            AnimationClip::firstOrCreate(
                ['code' => $clip['code'], 'clinic_id' => null],
                [
                    'name' => $clip['name'],
                    'procedure_type' => $clip['procedure_type'],
                    'jaw' => $clip['jaw'],
                    'duration_seconds' => $clip['duration_seconds'],
                    'treatment_category_id' => $category?->id,
                    'sort_order' => 0,
                ]
            );
        }
    }

    // -----------------------------------------------------------------------
    // Demo clinic
    // -----------------------------------------------------------------------

    private function seedDemoClinic(): Clinic
    {
        return Clinic::firstOrCreate(
            ['slug' => 'esagio-demo-clinic'],
            [
                'name' => 'Esagio Demo Dental Clinic',
                'email' => 'hello@esagio-demo.co.uk',
                'phone' => '+44 20 7123 4567',
                'country' => 'GB',
                'timezone' => 'Europe/London',
                'currency_default' => 'GBP',
                'language_default' => 'en',
                'plan_tier' => 'professional',
                'is_active' => true,
            ]
        );
    }

    private function seedDemoAdmin(Clinic $clinic): void
    {
        User::firstOrCreate(
            ['email' => 'admin@esagio-demo.co.uk'],
            [
                'name' => 'Demo Admin',
                'password' => Hash::make('password'),
                'clinic_id' => $clinic->id,
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        User::firstOrCreate(
            ['email' => 'coordinator@esagio-demo.co.uk'],
            [
                'name' => 'Sarah Coordinator',
                'password' => Hash::make('password'),
                'clinic_id' => $clinic->id,
                'role' => 'coordinator',
                'email_verified_at' => now(),
            ]
        );
    }

    private function seedClinicBranding(Clinic $clinic): void
    {
        ClinicBranding::firstOrCreate(
            ['clinic_id' => $clinic->id],
            [
                'primary_colour' => '#0A2540',
                'accent_colour' => '#E8663D',
                'heading_font' => 'Fraunces',
                'body_font' => 'Inter',
                'footer_text' => 'Esagio Demo Dental Clinic. Regulated by the General Dental Council (GDC). All clinicians are GDC registered.',
                'social_links' => json_encode([
                    'instagram' => 'https://instagram.com/esagiodemo',
                    'facebook' => 'https://facebook.com/esagiodemo',
                    'website' => 'https://esagio-demo.co.uk',
                ]),
                'accreditations' => json_encode([
                    'GDC Registered',
                    'BDA Member',
                    'Invisalign Preferred Provider',
                    'Nobel Biocare Certified',
                    'Straumann Certified Partner',
                ]),
            ]
        );
    }

    private function seedTeamMembers(Clinic $clinic): void
    {
        $members = [
            [
                'first_name' => 'James',
                'last_name' => 'Hargreaves',
                'title' => 'Dr.',
                'role' => 'Implantologist',
                'specialisation' => 'Oral Implantology and Full Arch Rehabilitation',
                'bio' => 'Dr. James Hargreaves is a specialist implantologist with over 15 years of experience placing implants in the UK and across Europe. He holds a Diploma in Implant Dentistry from the Royal College of Surgeons of England and has completed over 3,000 implant cases.',
                'qualifications' => 'BDS (Hons) University of Leeds, MFDS RCS(Ed), Diploma in Implant Dentistry RCS(Eng)',
                'years_experience' => 15,
                'languages_spoken' => 'English',
                'sort_order' => 1,
            ],
            [
                'first_name' => 'Sophia',
                'last_name' => 'Castellani',
                'title' => 'Dr.',
                'role' => 'Cosmetic Dentist',
                'specialisation' => 'Aesthetic Dentistry and Smile Design',
                'bio' => 'Dr. Sophia Castellani trained in Rome and completed postgraduate studies in smile design at the Eastman Dental Institute, London. She specialises in porcelain veneers, composite bonding, and digital smile design.',
                'qualifications' => 'Laurea in Odontoiatria (University of Rome), MClinDent Aesthetic Dentistry (UCL)',
                'years_experience' => 12,
                'languages_spoken' => 'English, Italian',
                'sort_order' => 2,
            ],
            [
                'first_name' => 'Mehmet',
                'last_name' => 'Ozkan',
                'title' => 'Dr.',
                'role' => 'Oral Surgeon',
                'specialisation' => 'Implant Surgery, Bone Grafting, Wisdom Tooth Removal',
                'bio' => 'Dr. Mehmet Ozkan qualified from Istanbul University and completed surgical training in Germany. He has 18 years of experience in implant surgery and is a certified trainer for MegaGen and Osstem implant systems.',
                'qualifications' => 'DDS Istanbul University, Postgraduate Certificate in Oral Surgery (Berlin)',
                'years_experience' => 18,
                'languages_spoken' => 'English, Turkish, German',
                'sort_order' => 3,
            ],
            [
                'first_name' => 'Priya',
                'last_name' => 'Sharma',
                'title' => 'Dr.',
                'role' => 'Orthodontist',
                'specialisation' => 'Clear Aligners and Fixed Orthodontics',
                'bio' => 'Dr. Priya Sharma completed specialist orthodontic training at King\'s College London. She is a certified provider of Invisalign and ClearCorrect and has treated over 800 orthodontic cases.',
                'qualifications' => 'BDS University of Birmingham, MOrth RCS(Eng)',
                'years_experience' => 10,
                'languages_spoken' => 'English, Hindi',
                'sort_order' => 4,
            ],
        ];

        foreach ($members as $member) {
            ClinicTeamMember::firstOrCreate(
                [
                    'clinic_id' => $clinic->id,
                    'first_name' => $member['first_name'],
                    'last_name' => $member['last_name'],
                ],
                array_merge($member, ['clinic_id' => $clinic->id, 'is_active' => true])
            );
        }
    }

    private function seedCrmPipelines(Clinic $clinic): array
    {
        $pipelines = [];

        // Consultation Pipeline
        $consultation = CrmPipeline::firstOrCreate(
            ['clinic_id' => $clinic->id, 'name' => 'Consultation Pipeline'],
            ['description' => 'Manages new patient enquiries through to a booked consultation.', 'is_default' => true, 'sort_order' => 1]
        );
        $pipelines['consultation'] = $consultation;

        foreach (CrmPipelineStageFactory::CONSULTATION_STAGES as $stage) {
            CrmPipelineStage::firstOrCreate(
                ['crm_pipeline_id' => $consultation->id, 'name' => $stage['name']],
                [
                    'colour' => $stage['colour'],
                    'probability_percentage' => $stage['probability_percentage'],
                    'sort_order' => $stage['sort_order'],
                    'is_won' => $stage['is_won'],
                    'is_lost' => $stage['is_lost'],
                ]
            );
        }

        // Treatment Pipeline
        $treatment = CrmPipeline::firstOrCreate(
            ['clinic_id' => $clinic->id, 'name' => 'Treatment Pipeline'],
            ['description' => 'Tracks active treatment cases from plan acceptance to completion.', 'is_default' => false, 'sort_order' => 2]
        );
        $pipelines['treatment'] = $treatment;

        foreach (CrmPipelineStageFactory::TREATMENT_STAGES as $stage) {
            CrmPipelineStage::firstOrCreate(
                ['crm_pipeline_id' => $treatment->id, 'name' => $stage['name']],
                [
                    'colour' => $stage['colour'],
                    'probability_percentage' => $stage['probability_percentage'],
                    'sort_order' => $stage['sort_order'],
                    'is_won' => $stage['is_won'],
                    'is_lost' => $stage['is_lost'],
                ]
            );
        }

        return $pipelines;
    }

    private function seedPriceList(Clinic $clinic): void
    {
        $year = now()->year;

        $priceList = PriceList::firstOrCreate(
            ['clinic_id' => $clinic->id, 'name' => "Standard Price List {$year}"],
            [
                'currency' => 'GBP',
                'is_default' => true,
                'valid_from' => now()->startOfYear()->toDateString(),
                'valid_until' => now()->endOfYear()->toDateString(),
                'notes' => 'Prices inclusive of all laboratory fees. Finance options available.',
            ]
        );

        $prices = [
            'IMP-001' => ['price' => 2500.00, 'unit_label' => 'per_tooth',  'notes' => 'Includes implant fixture, abutment, and zirconia crown.'],
            'IMP-002' => ['price' => 8500.00, 'unit_label' => 'per_arch',   'notes' => 'Per arch. Includes four implants and provisional prosthesis.'],
            'IMP-003' => ['price' => 10500.00, 'unit_label' => 'per_arch',   'notes' => 'Per arch. Includes six implants and provisional prosthesis.'],
            'CRN-001' => ['price' => 850.00,  'unit_label' => 'per_tooth',  'notes' => 'Includes laboratory fees and two fitting appointments.'],
            'CRN-002' => ['price' => 950.00,  'unit_label' => 'per_tooth',  'notes' => 'Lithium disilicate. Best suited for anterior teeth.'],
            'VEN-001' => ['price' => 650.00,  'unit_label' => 'per_tooth',  'notes' => 'Includes smile design consultation and temporary veneers.'],
            'VEN-002' => ['price' => 250.00,  'unit_label' => 'per_tooth',  'notes' => 'Chair-side composite resin. Single appointment.'],
            'WHT-001' => ['price' => 350.00,  'unit_label' => 'fixed',      'notes' => 'Includes home maintenance kit.'],
            'ORT-001' => ['price' => 3200.00, 'unit_label' => 'fixed',      'notes' => 'Full treatment including retainers.'],
            'END-001' => ['price' => 550.00,  'unit_label' => 'per_tooth',  'notes' => 'Single-rooted tooth. Multi-rooted teeth charged separately.'],
            'SRG-001' => ['price' => 450.00,  'unit_label' => 'per_tooth',  'notes' => 'Surgical extraction under local anaesthetic.'],
            'SRG-002' => ['price' => 150.00,  'unit_label' => 'per_tooth',  'notes' => 'Simple erupted tooth.'],
            'PRD-001' => ['price' => 1200.00, 'unit_label' => 'per_arch',   'notes' => 'Custom acrylic full denture.'],
            'GEN-001' => ['price' => 120.00,  'unit_label' => 'per_tooth',  'notes' => 'Tooth-coloured composite resin.'],
        ];

        $templates = TreatmentTemplate::whereNull('clinic_id')
            ->whereIn('code', array_keys($prices))
            ->get()
            ->keyBy('code');

        foreach ($prices as $code => $pricing) {
            $template = $templates->get($code);

            PriceListItem::firstOrCreate(
                ['price_list_id' => $priceList->id, 'treatment_template_id' => $template?->id],
                [
                    'unit_price' => $pricing['price'],
                    'unit_label' => $pricing['unit_label'],
                    'notes' => $pricing['notes'],
                ]
            );
        }
    }

    private function seedPatientsWithActivity(Clinic $clinic): void
    {
        $user = User::where('clinic_id', $clinic->id)->first();
        $stages = CrmPipelineStage::whereHas(
            'pipeline',
            fn ($q) => $q->where('clinic_id', $clinic->id)->where('name', 'Consultation Pipeline')
        )->get()->keyBy('name');

        $patients = [
            [
                'first_name' => 'Oliver',
                'last_name' => 'Thompson',
                'email' => 'oliver.thompson12@gmail.com',
                'phone' => '+44 7911 123456',
                'status' => 'plan_sent',
                'source' => 'Google Ads',
                'deal_value' => 5200.00,
                'tags' => json_encode(['implants', 'cosmetic']),
                'notes' => 'Interested in implants for upper left quadrant and whitening.',
                'stage' => 'Plan Sent',
                'country_of_residence' => 'GB',
                'city' => 'London',
                'gender' => 'male',
                'date_of_birth' => '1981-04-14',
            ],
            [
                'first_name' => 'Ayse',
                'last_name' => 'Kaya',
                'email' => 'ayse.kaya88@hotmail.com',
                'phone' => '+90 532 111 2233',
                'status' => 'consulting',
                'source' => 'Instagram',
                'deal_value' => 9500.00,
                'tags' => json_encode(['vip', 'implants']),
                'notes' => 'Wants All-on-4 on upper arch. Flying from Istanbul.',
                'stage' => 'Consultation Booked',
                'country_of_residence' => 'TR',
                'city' => 'Istanbul',
                'gender' => 'female',
                'date_of_birth' => '1975-09-03',
            ],
            [
                'first_name' => 'James',
                'last_name' => 'Whitfield',
                'email' => 'james.whitfield@outlook.com',
                'phone' => '+44 7700 987654',
                'status' => 'in_treatment',
                'source' => 'referral',
                'deal_value' => 7800.00,
                'tags' => json_encode(['implants', 'full-rehab']),
                'notes' => 'Full mouth rehabilitation in progress. Implants placed. Awaiting healing.',
                'stage' => 'Won',
                'country_of_residence' => 'GB',
                'city' => 'Manchester',
                'gender' => 'male',
                'date_of_birth' => '1968-11-22',
            ],
            [
                'first_name' => 'Emily',
                'last_name' => 'Clarke',
                'email' => 'emily.clarke99@yahoo.co.uk',
                'phone' => '+44 7843 654321',
                'status' => 'lead',
                'source' => 'website',
                'deal_value' => 3900.00,
                'tags' => json_encode(['veneers', 'cosmetic']),
                'notes' => 'Submitted enquiry form about veneers. Not yet contacted.',
                'stage' => 'New Lead',
                'country_of_residence' => 'GB',
                'city' => 'Bristol',
                'gender' => 'female',
                'date_of_birth' => '1993-07-08',
            ],
            [
                'first_name' => 'Burak',
                'last_name' => 'Demir',
                'email' => 'burak.demir77@gmail.com',
                'phone' => '+90 533 444 5566',
                'status' => 'plan_accepted',
                'source' => 'Facebook',
                'deal_value' => 6500.00,
                'tags' => json_encode(['implants', 'vip']),
                'notes' => 'Plan accepted. Awaiting deposit to confirm dates.',
                'stage' => 'Won',
                'country_of_residence' => 'TR',
                'city' => 'Ankara',
                'gender' => 'male',
                'date_of_birth' => '1979-02-15',
            ],
            [
                'first_name' => 'Sophia',
                'last_name' => 'Martin',
                'email' => 'sophia.martin45@gmail.com',
                'phone' => '+44 7922 334455',
                'status' => 'completed',
                'source' => 'referral',
                'deal_value' => 5200.00,
                'tags' => json_encode(['veneers', 'whitening']),
                'notes' => 'Treatment completed. Eight veneers and whitening. Very happy with the result.',
                'stage' => 'Won',
                'country_of_residence' => 'GB',
                'city' => 'London',
                'gender' => 'female',
                'date_of_birth' => '1988-05-19',
            ],
            [
                'first_name' => 'Harry',
                'last_name' => 'Evans',
                'email' => 'harry.evans61@hotmail.co.uk',
                'phone' => '+44 7800 221144',
                'status' => 'cancelled',
                'source' => 'Google Ads',
                'deal_value' => 2500.00,
                'tags' => json_encode(['implants']),
                'notes' => 'Patient cancelled due to financial constraints. Mark for recall in 6 months.',
                'stage' => 'Lost',
                'country_of_residence' => 'GB',
                'city' => 'Leeds',
                'gender' => 'male',
                'date_of_birth' => '1972-08-30',
            ],
            [
                'first_name' => 'Zeynep',
                'last_name' => 'Arslan',
                'email' => 'zeynep.arslan90@gmail.com',
                'phone' => '+90 542 667 8899',
                'status' => 'treatment_scheduled',
                'source' => 'Instagram',
                'deal_value' => 12000.00,
                'tags' => json_encode(['vip', 'implants', 'cosmetic']),
                'notes' => 'All-on-6 both arches planned. Dates confirmed. Flying in from Izmir next month.',
                'stage' => 'Won',
                'country_of_residence' => 'TR',
                'city' => 'Izmir',
                'gender' => 'female',
                'date_of_birth' => '1982-12-01',
            ],
        ];

        foreach ($patients as $data) {
            $stageName = $data['stage'];
            unset($data['stage']);

            $stage = $stages->get($stageName);

            $patient = Patient::firstOrCreate(
                ['email' => $data['email'], 'clinic_id' => $clinic->id],
                array_merge($data, [
                    'clinic_id' => $clinic->id,
                    'reference_code' => 'PAT-'.strtoupper(Str::random(6)),
                    'assigned_to' => $user?->id,
                    'created_by' => $user?->id,
                    'pipeline_stage_id' => $stage?->id,
                    'preferred_language' => str_contains($data['email'], '.kaya') || str_contains($data['email'], '.demir') || str_contains($data['email'], '.arslan') ? 'tr' : 'en',
                    'last_contacted_at' => now()->subDays(rand(1, 30)),
                ])
            );

            // Seed some CRM activities for each patient
            $this->seedPatientActivities($patient, $clinic, $user);
            $this->seedPatientTasks($patient, $clinic, $user);
        }
    }

    private function seedPatientActivities(Patient $patient, Clinic $clinic, ?User $user): void
    {
        $activities = [
            ['type' => 'call_made',    'subject' => 'Initial enquiry call',              'occurred_at' => now()->subDays(rand(20, 40))],
            ['type' => 'email_sent',   'subject' => 'Sent welcome email with brochure',  'occurred_at' => now()->subDays(rand(10, 19))],
            ['type' => 'plan_sent',    'subject' => 'Full treatment plan sent via email', 'occurred_at' => now()->subDays(rand(3, 9))],
        ];

        foreach ($activities as $activity) {
            CrmActivity::firstOrCreate(
                ['patient_id' => $patient->id, 'type' => $activity['type'], 'subject' => $activity['subject']],
                [
                    'clinic_id' => $clinic->id,
                    'user_id' => $user?->id,
                    'description' => null,
                    'occurred_at' => $activity['occurred_at'],
                ]
            );
        }
    }

    private function seedPatientTasks(Patient $patient, Clinic $clinic, ?User $user): void
    {
        $tasks = [
            [
                'title' => 'Follow up on treatment plan',
                'priority' => 'high',
                'status' => 'open',
                'due_date' => now()->addDays(rand(1, 7))->toDateString(),
            ],
            [
                'title' => 'Confirm deposit payment received',
                'priority' => 'medium',
                'status' => 'open',
                'due_date' => now()->addDays(rand(7, 14))->toDateString(),
            ],
        ];

        foreach ($tasks as $task) {
            CrmTask::firstOrCreate(
                ['patient_id' => $patient->id, 'title' => $task['title']],
                array_merge($task, [
                    'clinic_id' => $clinic->id,
                    'assigned_to' => $user?->id,
                    'created_by' => $user?->id,
                ])
            );
        }
    }

    private function seedBeforeAfterCases(Clinic $clinic): void
    {
        $cases = [
            [
                'title' => 'Full Mouth Rehabilitation with Implants',
                'description' => 'Eight dental implants placed across both arches to support full fixed zirconia bridgework. Patient flew from the UK for staged treatment.',
                'patient_age_range' => '45-55',
                'patient_gender' => 'male',
                'treatment_duration_days' => 180,
                'is_featured' => true,
                'consent_obtained' => true,
                'tags' => 'implants,full-rehab,zirconia',
                'treatment_value' => 22000.00,
            ],
            [
                'title' => 'Smile Makeover with Porcelain Veneers',
                'description' => 'Eight porcelain veneers on upper anterior teeth combined with in-clinic whitening on the lower arch.',
                'patient_age_range' => '30-40',
                'patient_gender' => 'female',
                'treatment_duration_days' => 14,
                'is_featured' => true,
                'consent_obtained' => true,
                'tags' => 'veneers,cosmetic,whitening',
                'treatment_value' => 6200.00,
            ],
            [
                'title' => 'All-on-4 Upper Arch Restoration',
                'description' => 'Upper arch fully restored with the All-on-4 technique. Same-day provisional teeth placed; final zirconia prosthesis fitted at 6 months.',
                'patient_age_range' => '55-65',
                'patient_gender' => 'male',
                'treatment_duration_days' => 150,
                'is_featured' => true,
                'consent_obtained' => true,
                'tags' => 'implants,all-on-4',
                'treatment_value' => 9500.00,
            ],
            [
                'title' => 'Composite Smile Makeover',
                'description' => 'Ten direct composite veneers placed in a single appointment. No tooth preparation required.',
                'patient_age_range' => '28-38',
                'patient_gender' => 'female',
                'treatment_duration_days' => 1,
                'is_featured' => false,
                'consent_obtained' => true,
                'tags' => 'composite,veneers,cosmetic',
                'treatment_value' => 2500.00,
            ],
            [
                'title' => 'Clear Aligner Orthodontic Treatment',
                'description' => 'Moderate crowding on both arches corrected with 28 sets of clear aligners over 14 months.',
                'patient_age_range' => '25-35',
                'patient_gender' => 'female',
                'treatment_duration_days' => 420,
                'is_featured' => false,
                'consent_obtained' => true,
                'tags' => 'orthodontics,aligners',
                'treatment_value' => 3200.00,
            ],
        ];

        foreach ($cases as $case) {
            BeforeAfterCase::firstOrCreate(
                ['clinic_id' => $clinic->id, 'title' => $case['title']],
                $case + ['clinic_id' => $clinic->id]
            );
        }
    }

    private function seedClinicLegalBlocks(Clinic $clinic): void
    {
        // Clinic-specific copy of the general disclaimer and GDPR block
        $blocks = [
            [
                'code' => 'general_disclaimer',
                'name' => 'General Treatment Disclaimer',
                'content' => 'The information in this treatment plan is for informational purposes only and does not constitute a guarantee of outcome. Results may vary between patients. All treatment is subject to a full clinical assessment at Esagio Demo Dental Clinic.',
                'jurisdiction' => 'UK',
                'is_default' => true,
            ],
            [
                'code' => 'gdpr_consent',
                'name' => 'GDPR Data Processing Consent',
                'content' => 'Your personal and dental health data will be processed by Esagio Demo Dental Clinic for the purpose of providing dental care. Data may be shared with specialist laboratories where clinically necessary. Records are retained for a minimum of 10 years in line with GDC guidelines. You have the right to access or request deletion of your data.',
                'jurisdiction' => 'UK',
                'is_default' => true,
            ],
        ];

        foreach ($blocks as $block) {
            LegalBlock::firstOrCreate(
                ['clinic_id' => $clinic->id, 'code' => $block['code']],
                array_merge($block, ['clinic_id' => $clinic->id, 'language' => 'en'])
            );
        }
    }
}
