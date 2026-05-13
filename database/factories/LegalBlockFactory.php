<?php

namespace Database\Factories;

use App\Models\LegalBlock;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LegalBlock>
 */
class LegalBlockFactory extends Factory
{
    public const BLOCKS = [
        [
            'code' => 'general_disclaimer',
            'name' => 'General Treatment Disclaimer',
            'content' => 'The information provided in this treatment plan is for informational purposes only and does not constitute a guarantee of treatment outcome. Results may vary between patients. All treatment is subject to a full clinical assessment and radiographic review. The clinic reserves the right to amend this plan following further clinical evaluation.',
            'jurisdiction' => 'UK',
            'is_default' => true,
        ],
        [
            'code' => 'implant_risks',
            'name' => 'Dental Implant Risk Disclosure',
            'content' => 'Dental implant surgery carries the following risks which you should be aware of before proceeding: infection at the implant site; nerve damage causing numbness or tingling; sinus complications (upper jaw implants); implant failure and the need for re-treatment; prolonged healing in patients with certain medical conditions including diabetes, osteoporosis, and a history of radiotherapy to the jaw. The overall success rate of dental implants is approximately 95% over 10 years. Smoking significantly increases the risk of implant failure.',
            'jurisdiction' => 'UK',
            'is_default' => false,
        ],
        [
            'code' => 'informed_consent',
            'name' => 'Informed Consent Statement',
            'content' => 'By accepting this treatment plan, you confirm that you have been given the opportunity to ask questions, that the proposed treatment has been explained to you in a language you understand, that you are aware of the risks, benefits, and alternatives to the proposed treatment, and that you consent to proceeding. You understand that you may withdraw consent at any time prior to treatment commencing.',
            'jurisdiction' => 'UK',
            'is_default' => true,
        ],
        [
            'code' => 'gdpr_consent',
            'name' => 'GDPR Data Processing Consent',
            'content' => 'Your personal data, including medical and dental health information, will be processed by this clinic for the purpose of providing dental care and treatment coordination. Your data may be shared with specialist laboratories and referring clinicians where clinically necessary. We will retain your records for a minimum of 10 years in accordance with NHS and GDC guidelines. You have the right to access, correct, or request deletion of your personal data. For full details, please refer to our Privacy Policy.',
            'jurisdiction' => 'UK',
            'is_default' => true,
        ],
        [
            'code' => 'photo_consent',
            'name' => 'Clinical Photography Consent',
            'content' => 'Clinical photographs may be taken before, during, and after treatment for the purposes of treatment planning, clinical records, quality assurance, and (with your separate written consent) educational or marketing use. Photographs used for marketing purposes will be anonymised unless you provide explicit consent for identifiable images to be used.',
            'jurisdiction' => 'UK',
            'is_default' => false,
        ],
        [
            'code' => 'payment_terms',
            'name' => 'Payment Terms and Deposit Policy',
            'content' => 'A non-refundable deposit of 20% of the total treatment fee is required to secure your appointment. The remaining balance is payable prior to or on the day of treatment commencement. Finance options are available subject to eligibility. In the event of cancellation with less than 48 hours notice, the deposit may be forfeited. The clinic reserves the right to pause treatment if payments are not maintained in accordance with the agreed schedule.',
            'jurisdiction' => 'UK',
            'is_default' => false,
        ],
        [
            'code' => 'guarantee_terms',
            'name' => 'Treatment Guarantee Terms',
            'content' => 'Guarantees are provided as a mark of our commitment to clinical excellence and are subject to the following conditions: the patient attends all recommended recall and review appointments; the patient maintains satisfactory oral hygiene as assessed at review; the patient refrains from habits known to damage restorations including nail biting, pen chewing, and grinding without a prescribed nightguard. Guarantees do not cover damage caused by trauma or accidental injury.',
            'jurisdiction' => 'UK',
            'is_default' => false,
        ],
        [
            'code' => 'veneer_risks',
            'name' => 'Veneer and Crown Risk Disclosure',
            'content' => 'Porcelain veneers and crowns are irreversible restorations requiring permanent removal of tooth structure. Associated risks include: post-operative sensitivity; pulp irritation or the need for subsequent root canal treatment; veneer or crown fracture; debonding; gum recession exposing margins over time. All restorations have a finite lifespan and will require replacement at some point in the future.',
            'jurisdiction' => 'UK',
            'is_default' => false,
        ],
    ];

    public function definition(): array
    {
        $block = $this->faker->randomElement(self::BLOCKS);

        return [
            'clinic_id' => null,
            'code' => $block['code'],
            'name' => $block['name'],
            'content' => $block['content'],
            'language' => 'en',
            'jurisdiction' => $block['jurisdiction'],
            'is_default' => $block['is_default'],
        ];
    }

    public function generalDisclaimer(): static
    {
        return $this->state([
            'code' => 'general_disclaimer',
            'name' => 'General Treatment Disclaimer',
            'is_default' => true,
        ]);
    }

    public function implantRisks(): static
    {
        return $this->state([
            'code' => 'implant_risks',
            'name' => 'Dental Implant Risk Disclosure',
        ]);
    }

    public function gdpr(): static
    {
        return $this->state([
            'code' => 'gdpr_consent',
            'name' => 'GDPR Data Processing Consent',
            'is_default' => true,
        ]);
    }
}
