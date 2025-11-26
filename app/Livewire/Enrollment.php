<?php
namespace App\Livewire;

use App\Models\GradeLevel;
use App\Models\StudentInformation;
use App\Models\User;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Enrollment extends Component implements HasForms
{
    public $school_levels;
    public $level_name;
    public $is_permanent   = false;
    public $is_special     = false;
    public $has_pwd_id     = false;
    public $is_senior_high = false;
    public $pwd_id_number;
    use InteractsWithForms;

    public $lrn, $lastname, $firstname, $middlename, $extension, $sex, $birthdate, $birthplace, $religion, $mother_tongue, $is_indigenous = false, $is_4ps = false;
    public $building, $street, $barangay, $municipality, $province, $zipcode;
    public $permanent_building, $permanent_street, $permanent_barangay, $permanent_municipality, $permanent_province, $permanent_zipcode;
    public $father_lastname, $father_firstname, $father_middlename, $father_contact, $mother_lastname, $mother_firstname, $mother_middlename, $mother_contact;
    public $guardian_lastname, $guardian_firstname, $guardian_middlename, $guardian_contact;

    public $special_need_a1 = [];
    public $special_need_a2 = [];
    public $is_cancer, $blind;
    public $pwd_id;
    public $grade_level;

    public $indigenous, $four_ps_id_number;

    public $semester, $track, $strand;
    public $classroom;

    public function updatedSchoolLevels()
    {
        if ($this->school_levels == 'Senior High School') {
            $this->is_senior_high = true;
        } else {
            $this->is_senior_high = false;
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
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
                        TextInput::make('municipality')->label('Municipality/City'),
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
                        TextInput::make('permanent_municipality')->label('Municipality/City'),
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
                // Fieldset::make('')->hidden(fn() => $this->level_name != 'Senior High School')->schema([
                //        ViewField::make('rating')->columnSpan(3)
                //     ->view('filament.forms.senior-high'),
                //     Select::make('semester')->options([
                //         'First Semester' => 'First Semester',
                //         'Second Semester' => 'Second Semester',
                //     ]),
                //     Select::make('track')->options([
                //         'Academic' => 'Academic',
                //         'Technical-Vocational-Livelihood(TVL)' => 'Technical-Vocational-Livelihood(TVL)',
                //         'Sport' => 'Sport',
                //         'Arts and Design' => 'Arts and Design',
                //     ]),
                //     Select::make('strand')->options([
                //         'ABM' => 'ABM',
                //         'GAS' => 'GAS',
                //         'HUMMS' => 'HUMMS',
                //         'STEM' => 'STEM',
                //     ])

                // ])->columns(3),
                Fieldset::make('')->schema([
                    //  ViewField::make('rating')
                    //     ->view('filament.forms.school_level')->columnSpan(2),
                    Select::make('grade_level')->label('Assign Grade Level')->required()->options(
                        GradeLevel::where('department', $this->level_name)->pluck('name', 'id')
                    )->columnSpan(2),
                ])->columns(2),
            ]);
    }

    public function mount()
    {
        $this->school_levels = GradeLevel::all()
            ->groupBy('department')
            ->map(function ($levels, $department) {
                // Extract grade numbers
                $grades = $levels->map(function ($level) {
                    preg_match('/\d+/', $level->name, $matches);
                    return (int) ($matches[0] ?? 0);
                })->sort();

                $minGrade = $grades->first();
                $maxGrade = $grades->last();

                return [
                    'name'        => $department,
                    'description' => "Grade {$minGrade} - Grade {$maxGrade}",
                ];
            })
            ->values();
    }



    public function enroll()
    {


        sleep(2);
        $this->validate([
            'lrn'           => 'required|digits:12',
            'lastname'      => 'required',
            'firstname'     => 'required',
            'sex'           => 'required',
            'birthdate'     => 'required',
            'birthplace'    => 'required',
            'religion'      => 'required',
            'mother_tongue' => 'required',
            'grade_level'   => 'required',
        ]);
        // $user = User::create([
        //     'name'     => $this->firstname . ' ' . $this->lastname,
        //     'email'    => strtolower($this->firstname . '' . $this->lastname) . '@bnhs.edu.ph',
        //     'password' => Hash::make($this->lrn),
        //     'role'     => 'student',

        // ]);

        $student = StudentInformation::create([
            'lrn'                    => $this->lrn,
            'firstname'              => $this->firstname,
            'middlename'             => $this->middlename,
            'lastname'               => $this->lastname,
            'extension'              => $this->extension,
            'sex'                    => $this->sex,
            'birthdate'              => $this->birthdate,
            'birthplace'             => $this->birthplace,
            'religion'               => $this->religion,
            'mother_tongue'          => $this->mother_tongue,
            'is_ips'                 => $this->is_indigenous,
            'indigenous'             => $this->indigenous,
            'is_4ps'                 => $this->is_4ps,
            'four_ps_id_number'      => $this->four_ps_id_number,
            'building'               => $this->building,
            'street'                 => $this->street,
            'barangay'               => $this->barangay,
            'municipality'           => $this->municipality,
            'province'               => $this->province,
            'zipcode'                => $this->zipcode,
            'is_permanent_address'   => $this->is_permanent,
            'permanent_building'     => $this->permanent_building,
            'permanent_street'       => $this->permanent_street,
            'permanent_barangay'     => $this->permanent_barangay,
            'permanent_municipality' => $this->permanent_municipality,
            'permanent_province'     => $this->permanent_province,
            'permanent_zipcode'      => $this->permanent_zipcode,
            'father_lastname'        => $this->father_lastname,
            'father_firstname'       => $this->father_firstname,
            'father_middlename'      => $this->father_middlename,
            'father_contact'         => $this->father_contact,
            'mother_lastname'        => $this->mother_lastname,
            'mother_firstname'       => $this->mother_firstname,
            'mother_middlename'      => $this->mother_middlename,
            'mother_contact'         => $this->mother_contact,
            'guardian_lastname'      => $this->guardian_lastname,
            'guardian_firstname'     => $this->guardian_firstname,
            'guardian_middlename'    => $this->guardian_middlename,
            'guardian_contact'       => $this->guardian_contact,
            'is_special_needs'       => $this->is_special,
            'special_needs_a1'       => json_encode($this->special_need_a1),
            'special_needs_a2'       => json_encode($this->special_need_a2),
            'pwd_id_number'          => $this->pwd_id_number,

            'grade_level_id'         => $this->grade_level,
        ]);
        sweetalert()->success('Student has been enlisted successfully!');
        return auth()->user()->role == 'admin' ? redirect()->route('admin.enrollment') : redirect()->route('encoder.dashboard');

    }
    public function render()
    {
        return view('livewire.enrollment');
    }
}
