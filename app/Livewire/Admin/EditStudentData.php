<?php
namespace App\Livewire\Admin;

use App\Models\GradeLevel;
use App\Models\StudentInformation;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;

class EditStudentData extends Component implements HasForms
{
    use InteractsWithForms;

    public $school_levels;
    public $level_name;
    public $is_permanent;
    public $is_special;
    public $has_pwd_id;
    public $is_senior_high;
    public $pwd_id_number;

    public $lrn, $lastname, $firstname, $middlename, $extension, $sex, $birthdate, $birthplace, $religion, $mother_tongue;
    public $is_indigenous;
    public $is_4ps;
    public $indigenous, $four_ps_id_number;

    public $building, $street, $barangay, $municipality, $province, $zipcode;
    public $permanent_building, $permanent_street, $permanent_barangay, $permanent_municipality, $permanent_province, $permanent_zipcode;

    public $father_lastname, $father_firstname, $father_middlename, $father_contact;
    public $mother_lastname, $mother_firstname, $mother_middlename, $mother_contact;
    public $guardian_lastname, $guardian_firstname, $guardian_middlename, $guardian_contact;

    public $special_need_a1;
    public $special_need_a2;
    public $is_cancer;
    public $blind;
    public $pwd_id;
    public $grade_level;

    public $semester;
    public $track;
    public $strand;
    public $classroom;

    public $student;

    public function mount()
    {
        $id            = decrypt(request('id'));
        $this->student = StudentInformation::findOrFail($id);

        // Initialize all reactive properties
        $this->school_levels  = [];
        $this->level_name     = $this->student->level_name ?? null;
        $this->is_permanent   = false;
        $this->is_special     = false;
        $this->has_pwd_id     = false;
        $this->is_senior_high = false;
        $this->pwd_id_number  = null;

        $this->lrn               = $this->student->lrn ?? '';
        $this->lastname          = $this->student->lastname ?? '';
        $this->firstname         = $this->student->firstname ?? '';
        $this->middlename        = $this->student->middlename ?? '';
        $this->extension         = $this->student->extension ?? '';
        $this->sex               = $this->student->sex ?? '';
        $this->birthdate         = $this->student->birthdate ?? '';
        $this->birthplace        = $this->student->birthplace ?? '';
        $this->religion          = $this->student->religion ?? '';
        $this->mother_tongue     = $this->student->mother_tongue ?? '';
        $this->is_indigenous     = (bool) ($this->student->is_indigenous ?? false);
        $this->is_4ps            = (bool) ($this->student->is_4ps ?? false);
        $this->indigenous        = $this->student->indigenous ?? '';
        $this->four_ps_id_number = $this->student->four_ps_id_number ?? '';

        $this->building     = $this->student->building ?? '';
        $this->street       = $this->student->street ?? '';
        $this->barangay     = $this->student->barangay ?? '';
        $this->municipality = $this->student->municipality ?? '';
        $this->province     = $this->student->province ?? '';
        $this->zipcode      = $this->student->zipcode ?? '';

        $this->permanent_building     = $this->student->permanent_building ?? '';
        $this->permanent_street       = $this->student->permanent_street ?? '';
        $this->permanent_barangay     = $this->student->permanent_barangay ?? '';
        $this->permanent_municipality = $this->student->permanent_municipality ?? '';
        $this->permanent_province     = $this->student->permanent_province ?? '';
        $this->permanent_zipcode      = $this->student->permanent_zipcode ?? '';

        $this->father_lastname   = $this->student->father_lastname ?? '';
        $this->father_firstname  = $this->student->father_firstname ?? '';
        $this->father_middlename = $this->student->father_middlename ?? '';
        $this->father_contact    = $this->student->father_contact ?? '';

        $this->mother_lastname   = $this->student->mother_lastname ?? '';
        $this->mother_firstname  = $this->student->mother_firstname ?? '';
        $this->mother_middlename = $this->student->mother_middlename ?? '';
        $this->mother_contact    = $this->student->mother_contact ?? '';

        $this->guardian_lastname   = $this->student->guardian_lastname ?? '';
        $this->guardian_firstname  = $this->student->guardian_firstname ?? '';
        $this->guardian_middlename = $this->student->guardian_middlename ?? '';
        $this->guardian_contact    = $this->student->guardian_contact ?? '';

        $this->special_need_a1 = json_decode($this->student->special_need_a1 ?? '[]', true);
        $this->special_need_a2 = json_decode($this->student->special_need_a2 ?? '[]', true);
        $this->is_cancer       = $this->student->is_cancer ?? null;
        $this->blind           = $this->student->blind ?? null;
        $this->pwd_id          = $this->student->pwd_id ?? null;

        $this->grade_level = $this->student->grade_level_id ?? null;

        $this->semester  = $this->student->semester ?? null;
        $this->track     = $this->student->track ?? null;
        $this->strand    = $this->student->strand ?? null;
        $this->classroom = $this->student->classroom ?? null;
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Fieldset::make('PERSONAL INFORMATION')->schema([
                Grid::make(4)->schema([
                    TextInput::make('lrn')->label('LRN (12 Digits)')->maxLength(12)->required()->columnSpan(2),
                ]),
                Grid::make(4)->schema([
                    TextInput::make('lastname')->label('Last Name')->required(),
                    TextInput::make('firstname')->label('First Name')->required(),
                    TextInput::make('middlename')->label('Middle Name'),
                    TextInput::make('extension')->label('Extension Name (Jr., Sr., etc.)')->columnSpan(2),
                    Select::make('sex')->options([
                        'Male'   => 'Male',
                        'Female' => 'Female',
                    ])->required(),
                    DatePicker::make('birthdate')->label('Date of Birth')->required(),
                    TextInput::make('birthplace')->label('Place of Birth')->required()->columnSpan(2),
                    TextInput::make('religion')->label('Religion')->required(),
                    TextInput::make('mother_tongue')->label('Mother Tongue')->required(),
                    Checkbox::make('is_indigenous')->reactive()->label('Belonging to any Indigenous Peoples (IP) Community/Indigenous Cultural Community?')->columnSpan(4),
                    TextInput::make('indigenous')->label('Please specify')->hidden(fn() => ! $this->is_indigenous)->columnSpan(1),
                    Checkbox::make('is_4ps')->label('Is the family a beneficiary of 4Ps?')->columnSpan(4)->reactive(),
                    TextInput::make('four_ps_id_number')->label('4Ps ID Number')->hidden(fn() => ! $this->is_4ps)->columnSpan(1),

                ]),
            ])->columns(1),
            Fieldset::make('ADDRESS INFORMATION')->schema([
                Grid::make(4)->schema([
                    ViewField::make('rating')
                        ->view('filament.forms.current-address')->columnSpan(4),
                    TextInput::make('building')->label('House No./Lot/Bldg.'),
                    TextInput::make('street')->label('Street/Sitio'),
                    TextInput::make('barangay')->label('Barangay'),
                    TextInput::make('municipality')->label('Municipality'),
                    TextInput::make('province')->label('Province'),
                    TextInput::make('zipcode')->label('Zipcode'),
                ]),
                Checkbox::make('is_permanent')->reactive()->label('Is your permanent address the same as your current address?'),
                Grid::make(4)->schema([
                    ViewField::make('rating')
                        ->view('filament.forms.permanent-address')->columnSpan(4),
                    TextInput::make('permanent_building')->label('House No./Lot/Bldg.'),
                    TextInput::make('permanent_street')->label('Street/Sitio'),
                    TextInput::make('permanent_barangay')->label('Barangay'),
                    TextInput::make('permanent_municipality')->label('Municipality'),
                    TextInput::make('permanent_province')->label('Province'),
                    TextInput::make('permanent_zipcode')->label('Zipcode'),
                ])->hidden(fn() => $this->is_permanent),
            ])->columns(1),
            Fieldset::make('PARENT/GUARDIAN INFORMATION (Provide at least one: Father, Mother, Guardian)')->schema([
                Fieldset::make("Father's Information (Optional)")->schema([
                    TextInput::make('father_lastname')->label('Last Name')->required(),
                    TextInput::make('father_firstname')->label('First Name')->required(),
                    TextInput::make('father_middlename')->label('Middle Name')->required(),
                    TextInput::make('father_contact')->label('Contact Number')->required(),

                ])->columns(3),
                Fieldset::make("Mother's Information (Optional)")->schema([
                    TextInput::make('mother_lastname')->label('Last Name')->required(),
                    TextInput::make('mother_firstname')->label('First Name')->required(),
                    TextInput::make('mother_middlename')->label('Middle Name')->required(),
                    TextInput::make('mother_contact')->label('Contact Number')->required(),

                ])->columns(3),
                Fieldset::make("Guardian's Information (Required if no parent information is provided)")->schema([
                    TextInput::make('guardian_lastname')->label('Last Name')->required(),
                    TextInput::make('guardian_firstname')->label('First Name')->required(),
                    TextInput::make('guardian_middlename')->label('Middle Name')->required(),
                    TextInput::make('guardian_contact')->label('Contact Number')->required(),

                ])->columns(3),
            ])->columns(4),
            ViewField::make('rating')->view('filament.forms.special-need'),
            Checkbox::make('is_special')->reactive()->label('Learner is under Special Needs Education Program'),
            Fieldset::make('')->hidden(fn() => ! $this->is_special)->schema([

                Fieldset::make('')->schema([
                    CheckboxList::make('special_need_a1')->label('A1: Specific Conditions (Check all that apply)')
                        ->options([
                            'Attention Deficit Hyperactivity Disorder' => 'Attention Deficit Hyperactivity Disorder',
                            'Autism Spectrum Disorder'                 => 'Autism Spectrum Disorder',
                            'Cerebral Palsy'                           => 'Cerebral Palsy',
                            'Emotional-Behavior Disorder'              => 'Emotional-Behavior Disorder',
                            'Hearing Impairment'                       => 'Hearing Impairment',
                            'Intellectual Disability'                  => 'Intellectual Disability',
                            'Learning Disability'                      => 'Learning Disability',
                            'Multiple Disabilities'                    => 'Multiple Disabilities',
                            'Orthopedic/Physical Handicap'             => 'Orthopedic/Physical Handicap',
                            'Speech/Language Disorder'                 => 'Speech/Language Disorder',
                            'Special Health Problem/Chronic Disease'   => 'Special Health Problem/Chronic Disease',
                            'Visual Impairment'                        => 'Visual Impairment',
                        ])
                        ->columns(2)->columnSpan(2),
                    Radio::make('is_cancer')
                        ->label('')
                        ->options([
                            'Cancer'    => 'Cancer',
                            'No Cancer' => 'No Cancer',
                        ])
                        ->inline()
                        ->inlineLabel(false),
                    Radio::make('blind')
                        ->label('')
                        ->options([
                            'Blind'      => 'Blind',
                            'Low Vision' => 'Low Vision',
                        ])
                        ->inline()
                        ->inlineLabel(false),
                ])->columns(2),
                Fieldset::make('')->schema([
                    CheckboxList::make('special_need_a2')->label('A2: Functional Difficulties (Check all that apply)')
                        ->options([
                            'Difficulty in applying knowledge'                => 'Difficulty in applying knowledge',
                            'Difficulty in communicating'                     => 'Difficulty in communicating',
                            'Difficulty in displaying interpersonal behavior' => 'Difficulty in displaying interpersonal behavior',
                            'Difficulty in hearing'                           => 'Difficulty in hearing',
                            'Difficulty in mobility'                          => 'Difficulty in mobility',
                            'Difficulty in performing adaptive skills'        => 'Difficulty in performing adaptive skills',
                            'Difficulty in remembering'                       => 'Difficulty in remembering',
                            'Difficulty in seeing'                            => 'Difficulty in seeing',

                        ])
                        ->columns(2)->columnSpan(2),

                ])->columns(2),
                Checkbox::make('has_pwd_id')->reactive()->label('Learner has a PWD ID')->columnSpan(2),
                TextInput::make('pwd_id')->placeholder('PWD Number')->label('')->hidden(fn() => ! $this->has_pwd_id),
            ]),

            Fieldset::make('')->schema([
                //  ViewField::make('rating')
                //     ->view('filament.forms.school_level')->columnSpan(2),
                Select::make('grade_level')->label('Assign Grade Level')->required()->options(
                    GradeLevel::where('id', $this->grade_level)->pluck('name', 'id')
                )->columnSpan(2),
            ])->columns(2),
        ]);

    }

    public function updateForm()
    {
       

    // Update student record
    $this->student->update([
        'lrn'               => $data['lrn'] ?? $this->lrn,
        'lastname'          => $data['lastname'] ?? $this->lastname,
        'firstname'         => $data['firstname'] ?? $this->firstname,
        'middlename'        => $data['middlename'] ?? $this->middlename,
        'extension'         => $data['extension'] ?? $this->extension,
        'sex'               => $data['sex'] ?? $this->sex,
        'birthdate'         => $data['birthdate'] ?? $this->birthdate,
        'birthplace'        => $data['birthplace'] ?? $this->birthplace,
        'religion'          => $data['religion'] ?? $this->religion,
        'mother_tongue'     => $data['mother_tongue'] ?? $this->mother_tongue,
        'is_ips'     => $this->is_indigenous,
        'indigenous'        => $this->indigenous,
        'is_4ps'            => $this->is_4ps,
        'four_ps_id_number' => $this->four_ps_id_number,

        // Address info
        'building'          => $this->building,
        'street'            => $this->street,
        'barangay'          => $this->barangay,
        'municipality'      => $this->municipality,
        'province'          => $this->province,
        'zipcode'           => $this->zipcode,

        'permanent_building'     => $this->permanent_building,
        'permanent_street'       => $this->permanent_street,
        'permanent_barangay'     => $this->permanent_barangay,
        'permanent_municipality' => $this->permanent_municipality,
        'permanent_province'     => $this->permanent_province,
        'permanent_zipcode'      => $this->permanent_zipcode,

        // Parent/guardian info
        'father_lastname'   => $this->father_lastname,
        'father_firstname'  => $this->father_firstname,
        'father_middlename' => $this->father_middlename,
        'father_contact'    => $this->father_contact,

        'mother_lastname'   => $this->mother_lastname,
        'mother_firstname'  => $this->mother_firstname,
        'mother_middlename' => $this->mother_middlename,
        'mother_contact'    => $this->mother_contact,

        'guardian_lastname'   => $this->guardian_lastname,
        'guardian_firstname'  => $this->guardian_firstname,
        'guardian_middlename' => $this->guardian_middlename,
        'guardian_contact'    => $this->guardian_contact,

        // Special needs
        'is_special_needs'      => $this->is_special,
        'special_needs_a1' => json_encode($this->special_need_a1 ?? []),
        'special_needs_a2' => json_encode($this->special_need_a2 ?? []),
        'pwd_id_number'      => $this->pwd_id_number,
        // 'pwd_id'          => $this->pwd_id,

        // Education
        // 'grade_level_id' => $this->grade_level,
        // 'semester'       => $this->semester,
        // 'track'          => $this->track,
        // 'strand'         => $this->strand,
        // 'classroom'      => $this->classroom,
    ]);

    // Show success message
        sweetalert()->success('Student information updated successfully!');
        return redirect()->route('admin.enroll-student' );
    }

    public function render()
    {
        return view('livewire.admin.edit-student-data');
    }
}
