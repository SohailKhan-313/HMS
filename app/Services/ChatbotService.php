<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Expense;
use App\Models\PatientHistory;
use App\Models\Staff;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ChatbotService
{
    /**
     * Process an incoming message and return an AI/database response.
     *
     * @return array{reply: string, suggestions: array<int, string>}
     */
    public function respond(string $message): array
    {
        $cleanMsg = trim($message);
        $lowerMsg = strtolower($cleanMsg);

        // 1. Check if external Gemini API is configured
        $geminiKey = config('services.gemini.key') ?? env('GEMINI_API_KEY');
        if (! empty($geminiKey)) {
            $geminiResponse = $this->callGeminiApi($cleanMsg, $geminiKey);
            if ($geminiResponse) {
                return $geminiResponse;
            }
        }

        // 2. Intelligent Local Record & Medical Triage Engine
        return $this->processLocalEngine($cleanMsg, $lowerMsg);
    }

    /**
     * Process message using the internal database records and clinical triage knowledgebase.
     *
     * @return array{reply: string, suggestions: array<int, string>}
     */
    protected function processLocalEngine(string $cleanMsg, string $lowerMsg): array
    {
        // Greetings
        if ($this->matchesAny($lowerMsg, ['hello', 'hi', 'hey', 'salam', 'assalam', 'aoa', 'good morning', 'good afternoon', 'good evening'])) {
            $today = Carbon::today()->toDateString();
            $docCount = Doctor::count();
            $todayApts = Appointment::whereDate('created_at', $today)->count();

            $reply = "👋 **Hello! Welcome to the Hospital Management System (HMS) AI Assistant.**\n\n".
                     "I can assist you with:\n".
                     "• 🏥 **Hospital Records**: Doctors roster, consultation fees, appointments, and patient records.\n".
                     "• 🩺 **Medical & Symptom Suggestions**: Clinical guidance, home triage, and matching with the right specialist.\n".
                     "• 📊 **Hospital Analytics**: Today's appointment schedules and facility metrics.\n\n".
                     "Currently, we have **{$docCount} specialists** on roster and **{$todayApts} appointments** scheduled today.\n\n".
                     '*How can I assist you today?*';

            return [
                'reply' => $reply,
                'suggestions' => ['Doctors & Fees', 'Today Appointments', 'Check Symptoms', 'Patient Dues'],
            ];
        }

        // Doctors & Specialists Query
        if ($this->matchesAny($lowerMsg, ['doctor', 'doctors', 'specialist', 'specialists', 'physician', 'pmdc', 'duty', 'fee', 'fees'])) {
            return $this->handleDoctorsQuery($lowerMsg);
        }

        // Appointments Query
        if ($this->matchesAny($lowerMsg, ['appointment', 'appointments', 'booking', 'schedule', 'cancel appointment', 'pending appointment'])) {
            return $this->handleAppointmentsQuery($cleanMsg, $lowerMsg);
        }

        // Patients & Records Query
        if ($this->matchesAny($lowerMsg, ['patient', 'patients', 'dues', 'due', 'wallet', 'history', 'mrn', 'cnic'])) {
            return $this->handlePatientsQuery($cleanMsg, $lowerMsg);
        }

        // Expenses & Hospital Financials
        if ($this->matchesAny($lowerMsg, ['expense', 'expenses', 'expenditure', 'payroll', 'salary', 'staff', 'cost'])) {
            return $this->handleHospitalOperationsQuery($lowerMsg);
        }

        // Hospital Overview / General Info
        if ($this->matchesAny($lowerMsg, ['overview', 'summary', 'about', 'hospital', 'facilities', 'contact', 'phone', 'whatsapp', 'email', 'timing', 'time'])) {
            return $this->handleHospitalOverview();
        }

        // Medical Symptoms and Clinical Suggestions Triage
        $medicalTriage = $this->handleMedicalTriage($lowerMsg);
        if ($medicalTriage !== null) {
            return $medicalTriage;
        }

        // Fallback response with helpful guide
        return [
            'reply' => "I can help you with hospital records and clinical triage:\n\n".
                       "• **Search Doctors**: Try *'Show available doctors'* or *'What is Dr. Farhan fee?'*\n".
                       "• **Appointments**: Try *'Appointments today'* or *'Search appointment for [Patient Name]'*\n".
                       "• **Medical Advice**: Describe symptoms like *'I have toothache and fever'* or *'Chest pain and breathlessness'*\n".
                       "• **Patient Records**: Try *'Check patient dues'* or *'Patient history for [Name]'*",
            'suggestions' => ['Available Doctors', 'Today Appointments', 'Find Doctor by Symptom', 'Hospital Overview'],
        ];
    }

    /**
     * Handle queries relating to doctors, consultation fees, and rosters.
     *
     * @return array{reply: string, suggestions: array<int, string>}
     */
    protected function handleDoctorsQuery(string $lowerMsg): array
    {
        $doctors = Doctor::query()->get();

        if ($doctors->isEmpty()) {
            return [
                'reply' => "⚠️ There are currently no doctors registered in the hospital database.\n\nPlease visit the **Doctors** section to add medical staff.",
                'suggestions' => ['Register Doctor', 'Hospital Overview'],
            ];
        }

        // Check if user is asking for a specific specialty
        $specialties = $doctors->pluck('speciality')->filter()->unique();
        foreach ($specialties as $spec) {
            if (Str::contains($lowerMsg, strtolower($spec))) {
                $matched = $doctors->filter(fn ($d) => strtolower($d->speciality) === strtolower($spec));
                $reply = "🩺 **Specialists in {$spec}:**\n\n";
                foreach ($matched as $doc) {
                    $reply .= "• **{$doc->name}**\n";
                    $reply .= "  - **Specialty**: {$doc->speciality}\n";
                    $reply .= '  - **Consultation Fee**: Rs. '.number_format($doc->fee, 0)."\n";
                    if ($doc->pmdc) {
                        $reply .= "  - **PMDC Reg**: {$doc->pmdc}\n";
                    }
                    if ($doc->duty_schedule) {
                        $reply .= "  - **Duty Hours**: {$doc->duty_schedule}\n";
                    }
                    $reply .= "  - **Contact**: {$doc->phone}\n\n";
                }

                return [
                    'reply' => $reply,
                    'suggestions' => ['Book Appointment', 'All Doctors', 'Check Symptoms'],
                ];
            }
        }

        // Check if user asked for a specific doctor name
        foreach ($doctors as $doc) {
            $nameParts = explode(' ', strtolower($doc->name));
            foreach ($nameParts as $part) {
                if (strlen($part) > 2 && Str::contains($lowerMsg, $part) && ! in_array($part, ['dr.', 'dr', 'doctor'])) {
                    $reply = "👨‍⚕️ **Doctor Profile: {$doc->name}**\n\n".
                             '• **Specialty**: '.($doc->speciality ?: 'General Physician')."\n".
                             '• **Consultation Fee**: Rs. '.number_format($doc->fee, 0)."\n".
                             '• **PMDC Registration**: '.($doc->pmdc ?: 'Registered')."\n".
                             "• **Phone**: {$doc->phone}\n".
                             "• **Email**: {$doc->email}\n";
                    if ($doc->duty_schedule) {
                        $reply .= "• **Duty Schedule**: {$doc->duty_schedule}\n";
                    }

                    return [
                        'reply' => $reply,
                        'suggestions' => ["Book with {$doc->name}", 'All Doctors List', 'Today Appointments'],
                    ];
                }
            }
        }

        // General doctors list
        $reply = '👨‍⚕️ **Hospital Medical Specialists Roster ('.$doctors->count()." Doctors):**\n\n";
        foreach ($doctors->take(6) as $doc) {
            $reply .= "• **{$doc->name}** — *{$doc->speciality}*\n";
            $reply .= '  Fee: **Rs. '.number_format($doc->fee, 0)."** | Phone: {$doc->phone}\n";
        }

        if ($doctors->count() > 6) {
            $reply .= "\n*...and ".($doctors->count() - 6).' more doctors available in directory.*';
        }

        return [
            'reply' => $reply,
            'suggestions' => ['Book Appointment', 'Print Doctors Roster (PDF)', 'Check Symptoms'],
        ];
    }

    /**
     * Handle appointments lookup and schedule queries.
     *
     * @return array{reply: string, suggestions: array<int, string>}
     */
    protected function handleAppointmentsQuery(string $cleanMsg, string $lowerMsg): array
    {
        $today = Carbon::today()->toDateString();

        if (Str::contains($lowerMsg, ['today', 'pending', 'cancel', 'schedule'])) {
            $totalToday = Appointment::whereDate('created_at', $today)->count();
            $pending = Appointment::whereDate('created_at', $today)->where('status', 'Pending')->count();
            $completed = Appointment::whereDate('created_at', $today)->where('status', 'Completed')->count();
            $cancelled = Appointment::whereDate('created_at', $today)->where('status', 'Cancelled')->count();

            $reply = "📅 **Today's Appointment Schedule (".Carbon::today()->format('d M Y')."):**\n\n".
                     "• **Total Bookings Today**: {$totalToday}\n".
                     "• **Pending Consultations**: {$pending}\n".
                     "• **Completed**: {$completed}\n".
                     "• **Cancelled**: {$cancelled}\n\n";

            $recentToday = Appointment::whereDate('created_at', $today)->with('doctor')->take(4)->get();
            if ($recentToday->isNotEmpty()) {
                $reply .= "**Recent Queue Today:**\n";
                foreach ($recentToday as $apt) {
                    $docName = $apt->doctor ? $apt->doctor->name : 'Unassigned';
                    $reply .= "• `[{$apt->time}]` **{$apt->name}** with {$docName} — *{$apt->status}*\n";
                }
            }

            return [
                'reply' => $reply,
                'suggestions' => ['Print Appointments (PDF)', 'Doctors Roster', 'New Appointment'],
            ];
        }

        // Search appointment by patient name or phone
        preg_match('/(?:for|patient|search|find)\s+([a-zA-Z0-9\s]+)/i', $cleanMsg, $matches);
        $searchTerm = trim($matches[1] ?? '');

        if (! empty($searchTerm) && strlen($searchTerm) >= 3) {
            $results = Appointment::query()
                ->where('name', 'like', "%{$searchTerm}%")
                ->orWhere('phone', 'like', "%{$searchTerm}%")
                ->with('doctor')
                ->latest()
                ->take(3)
                ->get();

            if ($results->isNotEmpty()) {
                $reply = "🔍 **Found Appointment(s) for '{$searchTerm}':**\n\n";
                foreach ($results as $apt) {
                    $docName = $apt->doctor ? $apt->doctor->name : 'General Staff';
                    $reply .= "• **#APT-{$apt->id}**: {$apt->name} ({$apt->phone})\n".
                             "  Doctor: **{$docName}** | Time: {$apt->time}\n".
                             "  Status: **{$apt->status}** | Date: {$apt->created_at->format('d M Y')}\n\n";
                }

                return [
                    'reply' => $reply,
                    'suggestions' => ['Print Appointment Receipt', 'Today Appointments', 'Doctors Roster'],
                ];
            }
        }

        return [
            'reply' => "📅 **Appointment Assistance:**\n\n".
                       "You can ask me:\n".
                       "• *'Appointments today'*\n".
                       "• *'How many pending appointments?'*\n".
                       "• *'Search appointment for [Patient Name]'*\n".
                       '• Or click below to manage the appointment register.',
            'suggestions' => ['Today Appointments', 'Print Appointments (PDF)', 'Available Doctors'],
        ];
    }

    /**
     * Handle patient history, dues, and wallet lookups.
     *
     * @return array{reply: string, suggestions: array<int, string>}
     */
    protected function handlePatientsQuery(string $cleanMsg, string $lowerMsg): array
    {
        $totalPatients = PatientHistory::count();
        $totalDue = PatientHistory::sum('due_amount');
        $totalWallet = PatientHistory::sum('wallet_amount');

        if (Str::contains($lowerMsg, ['due', 'dues', 'debt', 'owing', 'balance', 'wallet'])) {
            $duePatients = PatientHistory::where('due_amount', '>', 0)->orderByDesc('due_amount')->take(5)->get();

            $reply = "💳 **Patient Financial Overview:**\n\n".
                     '• **Total Outstanding Dues**: Rs. '.number_format($totalDue, 2)."\n".
                     '• **Patient Wallet Credits**: Rs. '.number_format($totalWallet, 2)."\n\n";

            if ($duePatients->isNotEmpty()) {
                $reply .= "**Top Patients with Outstanding Dues:**\n";
                foreach ($duePatients as $p) {
                    $reply .= "• **{$p->name}** ({$p->phone}) — **Rs. ".number_format($p->due_amount, 2)."**\n";
                }
            }

            return [
                'reply' => $reply,
                'suggestions' => ['Print Patients Directory (PDF)', 'Today Appointments', 'Doctors Roster'],
            ];
        }

        // Search patient by name or phone/CNIC
        preg_match('/(?:for|patient|search|find)\s+([a-zA-Z0-9\s-]+)/i', $cleanMsg, $matches);
        $search = trim($matches[1] ?? '');

        if (! empty($search) && strlen($search) >= 3) {
            $patient = PatientHistory::query()
                ->where('name', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")
                ->orWhere('cnic', 'like', "%{$search}%")
                ->first();

            if ($patient) {
                $reply = "👤 **Patient Record Found:**\n\n".
                         "• **Name**: {$patient->name}\n".
                         '• **Age**: '.($patient->age ?: 'N/A')." | **Phone**: {$patient->phone}\n".
                         '• **CNIC**: '.($patient->cnic ?: 'N/A')."\n".
                         '• **Outstanding Due**: Rs. '.number_format($patient->due_amount, 2)."\n".
                         '• **Wallet Credit**: Rs. '.number_format($patient->wallet_amount, 2)."\n".
                         '• **Registered On**: '.($patient->created_at ? $patient->created_at->format('d M Y') : 'N/A')."\n";

                return [
                    'reply' => $reply,
                    'suggestions' => ['Patient Appointments', 'Print Patient PDF', 'All Patients'],
                ];
            }
        }

        $reply = "👥 **Hospital Patient Records:**\n\n".
                 "• **Total Registered Patients**: {$totalPatients}\n".
                 '• **Total Dues**: Rs. '.number_format($totalDue, 2)."\n".
                 '• **Total Wallet Balance**: Rs. '.number_format($totalWallet, 2)."\n\n".
                 "You can search any patient by asking: *'Search patient [Name or Phone]'*.";

        return [
            'reply' => $reply,
            'suggestions' => ['Check Patient Dues', 'Print Patients (PDF)', 'New Appointment'],
        ];
    }

    /**
     * Handle staff payroll and hospital expenses.
     *
     * @return array{reply: string, suggestions: array<int, string>}
     */
    protected function handleHospitalOperationsQuery(string $lowerMsg): array
    {
        $totalStaff = Staff::count();
        $totalPayroll = Staff::sum('salary');
        $totalExpenses = Expense::sum('amount');
        $recentExpenses = Expense::latest()->take(4)->get();

        $reply = "💼 **Hospital Operational & Financial Summary:**\n\n".
                 "• **Hospital Staff**: {$totalStaff} Active Personnel\n".
                 '• **Monthly Staff Payroll**: Rs. '.number_format($totalPayroll, 2)."\n".
                 '• **Recorded Daily Expenses**: Rs. '.number_format($totalExpenses, 2)."\n\n";

        if ($recentExpenses->isNotEmpty()) {
            $reply .= "**Recent Expenditures:**\n";
            foreach ($recentExpenses as $exp) {
                $reply .= "• `{$exp->date}` **{$exp->name}** ({$exp->catagory}): Rs. ".number_format($exp->amount, 2)."\n";
            }
        }

        return [
            'reply' => $reply,
            'suggestions' => ['Expenses Audit (PDF)', 'Staff Directory (PDF)', 'Doctors Roster'],
        ];
    }

    /**
     * Handle general hospital overview and contact info.
     *
     * @return array{reply: string, suggestions: array<int, string>}
     */
    protected function handleHospitalOverview(): array
    {
        $docs = Doctor::count();
        $staff = Staff::count();
        $patients = PatientHistory::count();

        $reply = "🏥 **Hospital Overview & Reception Desk:**\n\n".
                 "• **Registered Specialists**: {$docs} Doctors\n".
                 "• **Support & Clinical Staff**: {$staff} Members\n".
                 "• **Patients Served**: {$patients} Registered\n".
                 "• **Emergency / OPD Timing**: 24/7 Emergency & OPD Desk\n".
                 "• **Direct WhatsApp Support**: `03470232059`\n".
                 "• **Administrator Email**: `skpattan850911@gmail.com`\n\n".
                 '*Our medical staff is available for consultations, walk-in OPD, and inpatient care.*';

        return [
            'reply' => $reply,
            'suggestions' => ['Doctors Roster', 'Today Appointments', 'Check Symptoms'],
        ];
    }

    /**
     * Clinical symptom triage knowledge base.
     * Maps symptoms to medical departments, first-aid precautions, and matches available doctors.
     *
     * @return array{reply: string, suggestions: array<int, string>}|null
     */
    protected function handleMedicalTriage(string $lowerMsg): ?array
    {
        $clinicalMap = [
            'cardiology' => [
                'keywords' => ['chest pain', 'heart', 'palpitation', 'palpitations', 'high bp', 'blood pressure', 'angina', 'shortness of breath'],
                'specialty' => 'Cardiologist',
                'department' => 'Cardiology',
                'severity' => 'High',
                'home_advice' => 'Sit down immediately in a comfortable upright position. Loosen tight clothing. Rest quietly, avoid physical exertion, and do not drive yourself.',
                'emergency_alert' => '⚠️ If chest pain radiates to your left arm, jaw, or is accompanied by cold sweat, seek **EMERGENCY IMMEDIATE CARE**.',
            ],
            'dermatology' => [
                'keywords' => ['skin', 'rash', 'itching', 'itch', 'acne', 'eczema', 'allergy', 'pimples', 'hair fall', 'hair loss', 'skin burn'],
                'specialty' => 'Dermatologist',
                'department' => 'Dermatology & Skin Care',
                'severity' => 'Moderate',
                'home_advice' => 'Keep the affected skin clean and dry. Avoid scratching or applying harsh scented soaps. A cold compress or mild hypoallergenic moisturizer may soothe irritation.',
                'emergency_alert' => '⚠️ If the rash is spreading rapidly or accompanied by facial swelling or difficulty breathing, visit Emergency immediately.',
            ],
            'dental' => [
                'keywords' => ['tooth', 'teeth', 'toothache', 'dental', 'gums', 'bleeding gum', 'cavity', 'jaw pain'],
                'specialty' => 'Dentist',
                'department' => 'Dental Surgery & Orthodontics',
                'severity' => 'Moderate',
                'home_advice' => 'Rinse gently with warm salt water. Apply a cold compress to the outside of your cheek for pain and swelling. Avoid very hot, cold, or sugary foods.',
                'emergency_alert' => '⚠️ If there is severe facial swelling or difficulty swallowing, prompt dental/surgical drainage is required.',
            ],
            'pediatrics' => [
                'keywords' => ['child', 'baby', 'infant', 'toddler', 'kid', 'pediatric'],
                'specialty' => 'Pediatrician',
                'department' => 'Pediatrics (Child Health)',
                'severity' => 'Moderate',
                'home_advice' => 'Keep the child hydrated with water, oral rehydration salts (ORS), or breastmilk. Monitor temperature with a digital thermometer and ensure light clothing.',
                'emergency_alert' => '⚠️ Seek immediate pediatric attention if the child is lethargic, refuses fluids, has a high fever (>102°F), or shows rapid breathing.',
            ],
            'gynecology' => [
                'keywords' => ['pregnancy', 'pregnant', 'menstrual', 'period', 'periods', 'gynecology', 'pcos', 'pelvic pain', 'ovary'],
                'specialty' => 'Gynecologist',
                'department' => 'Obstetrics & Gynecology',
                'severity' => 'Moderate',
                'home_advice' => 'Stay rested, maintain good hydration, and use a warm heating pad for abdominal cramping. Track your cycle symptoms accurately.',
                'emergency_alert' => '⚠️ Severe unilateral pelvic pain or heavy bleeding requires immediate emergency medical evaluation.',
            ],
            'neurology' => [
                'keywords' => ['headache', 'migraine', 'dizziness', 'dizzy', 'seizure', 'fainting', 'numbness', 'tremor'],
                'specialty' => 'Neurologist',
                'department' => 'Neurology',
                'severity' => 'Moderate to High',
                'home_advice' => 'Rest in a quiet, dark room. Drink plenty of water and avoid bright screens or loud sounds.',
                'emergency_alert' => '⚠️ Sudden "thunderclap" headache, facial drooping, slurred speech, or weakness in one arm indicates potential stroke—CALL EMERGENCY.',
            ],
            'orthopedics' => [
                'keywords' => ['bone', 'joint', 'knee', 'back pain', 'fracture', 'sprain', 'arthritis', 'shoulder pain', 'spine'],
                'specialty' => 'Orthopedic',
                'department' => 'Orthopedics & Joint Care',
                'severity' => 'Moderate',
                'home_advice' => 'Follow the R.I.C.E. protocol: Rest, Ice (15 mins), Compress with an elastic bandage, and Elevate the affected limb.',
                'emergency_alert' => '⚠️ Inability to bear any weight, visible bone deformity, or severe numbness requires immediate X-ray and orthopedic review.',
            ],
            'gastroenterology' => [
                'keywords' => ['stomach', 'abdomen', 'abdominal pain', 'nausea', 'vomit', 'vomiting', 'diarrhea', 'constipation', 'acidity', 'gas', 'indigestion'],
                'specialty' => 'Gastroenterologist',
                'department' => 'Gastroenterology & Internal Medicine',
                'severity' => 'Moderate',
                'home_advice' => 'Eat a bland diet (bananas, rice, applesauce, toast). Drink electrolyte fluids or ORS in small sips. Avoid oily, spicy, and dairy products.',
                'emergency_alert' => '⚠️ Persistent vomiting, blood in stool, or rigid intense stomach pain warrants urgent hospital assessment.',
            ],
            'respiratory' => [
                'keywords' => ['fever', 'cough', 'flu', 'cold', 'sore throat', 'runny nose', 'phlegm', 'asthma', 'breathing', 'sneezing'],
                'specialty' => 'General Physician',
                'department' => 'Internal Medicine & Pulmonology',
                'severity' => 'Moderate',
                'home_advice' => 'Drink warm fluids, honey with warm water, and perform steam inhalation. Rest adequately and monitor your oxygen and temperature levels.',
                'emergency_alert' => '⚠️ If you experience blue lips, oxygen saturation dropping below 94%, or severe gasping, seek emergency care immediately.',
            ],
        ];

        foreach ($clinicalMap as $category => $info) {
            if ($this->matchesAny($lowerMsg, $info['keywords'])) {
                // Find matching doctors in hospital database
                $matchingDoctors = Doctor::query()
                    ->where('speciality', 'like', "%{$info['specialty']}%")
                    ->orWhere('speciality', 'like', "%{$info['department']}%")
                    ->get();

                // If not found by exact specialty, fallback to general physician or first available
                if ($matchingDoctors->isEmpty()) {
                    $matchingDoctors = Doctor::query()
                        ->where('speciality', 'like', '%Physician%')
                        ->orWhere('speciality', 'like', '%General%')
                        ->take(2)
                        ->get();
                }

                $reply = "🩺 **Clinical Triage & Health Guidance**\n\n".
                         "**Suggested Department**: `{$info['department']}`\n".
                         "**Primary Specialist**: `{$info['specialty']}`\n\n".
                         "**Home & Immediate Care Recommendations:**\n".
                         "• {$info['home_advice']}\n\n";

                if (! empty($info['emergency_alert'])) {
                    $reply .= "{$info['emergency_alert']}\n\n";
                }

                $reply .= "🏥 **Recommended Doctors at Our Hospital:**\n";
                if ($matchingDoctors->isNotEmpty()) {
                    foreach ($matchingDoctors->take(3) as $doc) {
                        $reply .= "• **{$doc->name}** ({$doc->speciality})\n".
                                 '  Consultation Fee: **Rs. '.number_format($doc->fee, 0)."** | Phone: {$doc->phone}\n";
                    }
                    $reply .= "\n*Would you like to book an appointment with one of our specialists?*";
                } else {
                    $reply .= "• General Medical OPD is open 24/7 for evaluation and referral.\n";
                }

                $reply .= "\n\n_Disclaimer: AI triage is for informational guidance and does not replace in-person clinical diagnosis by a licensed physician._";

                return [
                    'reply' => $reply,
                    'suggestions' => ['Book Appointment', 'Doctors Roster', 'Today Appointments', 'Emergency Contact'],
                ];
            }
        }

        return null;
    }

    /**
     * Optional Gemini API integration with live hospital database context injection.
     *
     * @return array{reply: string, suggestions: array<int, string>}|null
     */
    protected function callGeminiApi(string $message, string $apiKey): ?array
    {
        try {
            $doctors = Doctor::take(10)->get(['name', 'speciality', 'fee', 'phone'])->toJson();
            $today = Carbon::today()->toDateString();
            $todayApts = Appointment::whereDate('created_at', $today)->count();
            $patientCount = PatientHistory::count();

            $systemPrompt = "You are the AI Medical Assistant for 'Hospital Management System (HMS)'. ".
                            'You provide accurate hospital information and helpful clinical medical guidance / symptom triage. '.
                            "Live Hospital Data:\n".
                            "- Available Doctors: {$doctors}\n".
                            "- Today's Appointments: {$todayApts}\n".
                            "- Total Registered Patients: {$patientCount}\n".
                            "- Contact WhatsApp: 03470232059, Email: skpattan850911@gmail.com\n".
                            'When answering medical questions, always provide safe preliminary care suggestions, recommend the matching doctor from the list with their fee, and include an emergency caveat.';

            $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}";

            $response = Http::timeout(8)->post($url, [
                'contents' => [
                    [
                        'role' => 'user',
                        'parts' => [
                            ['text' => "{$systemPrompt}\n\nUser Question: {$message}"],
                        ],
                    ],
                ],
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $replyText = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
                if ($replyText) {
                    return [
                        'reply' => $replyText,
                        'suggestions' => ['Doctors & Fees', 'Today Appointments', 'Check Symptoms', 'Book Appointment'],
                    ];
                }
            }
        } catch (\Throwable) {
            // Silently fall back to internal engine
        }

        return null;
    }

    /**
     * Helper to test if lower string matches any keyword.
     *
     * @param  array<int, string>  $keywords
     */
    protected function matchesAny(string $haystack, array $keywords): bool
    {
        foreach ($keywords as $kw) {
            if (Str::contains($haystack, strtolower($kw))) {
                return true;
            }
        }

        return false;
    }
}
