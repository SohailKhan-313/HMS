<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\Printer;
use App\Models\appointment;

require_once base_path('vendor/setasign/fpdf/fpdf.php');

class AppointmentPrintController extends Controller
{
    public function print(Request $request)
    {
        $appointment = $request->validate([
            'patient_name' => 'required|string',
            'date' => 'required|string',
            'time' => 'required|string',
            'doctor' => 'required|string',
            'notes' => 'nullable|string'
        ]);

        try {
            // --- Configure your printer connection ---
            // For USB printer on Windows (shared name):
            $connector = new WindowsPrintConnector("POS-58");
            // For network printer (IP and port):
            // $connector = new NetworkPrintConnector("192.168.1.100", 9100);
            // For Linux USB:
            // $connector = new FilePrintConnector("/dev/usb/lp0");

            $printer = new Printer($connector);

            // --- Build the receipt ---
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("YOUR CLINIC NAME\n");
            $printer->text("-------------------------------\n");
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("Patient: " . $appointment['patient_name'] . "\n");
            $printer->text("Date: " . $appointment['date'] . "\n");
            $printer->text("Time: " . $appointment['time'] . "\n");
            $printer->text("Doctor: " . $appointment['doctor'] . "\n");
            if (!empty($appointment['notes'])) {
                $printer->text("Notes: " . $appointment['notes'] . "\n");
            }
            $printer->text("-------------------------------\n");
            $printer->text("Thank you!\n");

            // Cut the paper
            $printer->cut();
            $printer->close();

            return response()->json(['success' => true, 'message' => 'Print job sent.']);
        } catch (\Exception $e) {
            \Log::error('Thermal print failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Print error: ' . $e->getMessage()], 500);
        }
    }
    public function appointmentPdf($id)
    {
        // Clean output buffers
        if (ob_get_length()) {
            ob_end_clean();
        }
        ob_start();

        try {
            $appointment = \App\Models\Appointment::with('doctor')->find($id);
            if (!$appointment) {
                abort(404);
            }

            // --- Resolve doctor name ---
            if ($appointment->relationLoaded('doctor') && $appointment->doctor) {
                $doctorName = $appointment->doctor->name ?? 'N/A';
            } elseif (is_string($appointment->doctor)) {
                $decoded = json_decode($appointment->doctor, true);
                $doctorName = is_array($decoded) ? ($decoded['name'] ?? 'N/A') : $appointment->doctor;
            } else {
                $doctorName = 'N/A';
            }

            // --- Resolve patient name ---
            $patientName = $appointment->patient_name
                ?? $appointment->name
                ?? $appointment->patient
                ?? 'N/A';

            // --- Format date ---
            $date = 'N/A';
            if (!empty($appointment->date)) {
                try {
                    $date = \Carbon\Carbon::parse($appointment->date)->format('d M Y');
                } catch (\Exception $e) {
                    $date = $appointment->date;
                }
            }

            // --- Format time ---
            $time = 'N/A';
            if (!empty($appointment->time)) {
                try {
                    $time = \Carbon\Carbon::parse($appointment->time)->format('h:i A');
                } catch (\Exception $e) {
                    $time = $appointment->time;
                }
            }

            $status = $appointment->status ?? 'Confirmed';
            $appointmentId = $appointment->id;
            $notes = $appointment->notes ?? 'N/A';

            // Optional pre-filled diagnosis / medicine
            $existingDiagnosis = $appointment->diagnosis ?? '';
            $existingMedicine = $appointment->medicine_suggestions ?? '';

      // --- PDF creation ---
$pdf = new \FPDF('P', 'mm', 'A4');
$pdf->AddPage();

// Set medical‑type background color (soft medical blue)
$pdf->SetFillColor(255, 255, 255); // RGB for #E8F4F8
$pdf->Rect(0, 0, 210, 297, 'F');  // Fill entire A4 page

$pdf->SetMargins(5, 5, 5);
$pdf->SetAutoPageBreak(true, 10);
            // ========== HEADER ==========
            // ========== HEADER WITH LOGO ==========
            // Logo on the left (adjust position and size as needed)
            $logoPath = public_path('images/medical-logo-png-897.png'); // Correct: no {{ }}, no asset() // Change to your logo path
            if (file_exists($logoPath)) {
                $pdf->Image($logoPath, 20, 15, 25); // X=20, Y=15, width=25mm (height auto)
            }

            // Hospital details (centered, with a slight right offset if logo is present)
            $pdf->SetY(15); // Align vertically with logo
            $pdf->SetFont('Arial', 'B', 18);
            $pdf->SetTextColor(40, 40, 100);
            $pdf->Cell(0, 10, 'CITY HOSPITAL & MEDICAL CENTER', 0, 1, 'C');
            $pdf->SetFont('Arial', '', 10);
            $pdf->SetTextColor(100, 100, 100);
            $pdf->Cell(0, 5, '123 Healthcare Avenue, Wellness City - 560001', 0, 1, 'C');
            $pdf->Cell(0, 5, 'Phone : +91 98765 43210 | Mail : care@cityhospital.com', 0, 1, 'C');
            $pdf->Ln(6); // Extra space after header

            // Receipt number
            $pdf->SetFont('Arial', 'I', 9);
            $pdf->SetTextColor(80, 80, 80);
            $pdf->Cell(0, 5, 'Receipt No: AP-' . str_pad($appointmentId, 6, '0', STR_PAD_LEFT), 0, 1, 'R');
            $pdf->SetDrawColor(200, 200, 200);
            $pdf->Line(20, $pdf->GetY(), 190, $pdf->GetY());
            $pdf->Ln(3);

            // Title
            $pdf->SetFont('Arial', 'B', 16);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 10, ' MEDICAL RECEIPT', 0, 1, 'C');
            $pdf->Ln(2);

           // ========== PATIENT DETAILS (rows / two columns) ==========
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(0, 8, 'PATIENT DETAILS', 0, 1, 'L');
$pdf->SetDrawColor(150, 150, 150);
$pdf->Line(20, $pdf->GetY(), 190, $pdf->GetY());
$pdf->Ln(3);

// Helper function to draw a label-value pair
function drawDetailPair($pdf, $label, $value, $width) {
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetTextColor(50, 50, 50);
    $pdf->Cell($width * 0.35, 6, $label . ':', 0, 0, 'L');
    $pdf->SetFont('Arial', '', 10);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell($width * 0.65, 6, $value, 0, 0, 'L');
}

$usableWidth = 190 - 20; // 170mm (right margin at 190? Actually left margin 20, right margin 20 -> usable 170)
$colWidth = $usableWidth / 2; // 85mm per column

$leftX = $pdf->GetX();
$rightX = $leftX + $colWidth;

// Row 1: Patient Name (left) | Doctor (right)
drawDetailPair($pdf, 'Patient Name', $patientName, $colWidth);
$pdf->SetX($rightX);
drawDetailPair($pdf, 'Doctor', $doctorName, $colWidth);
$pdf->Ln(8);

// Row 2: Date & Time (left) | Status (right)
$pdf->SetX($leftX);
drawDetailPair($pdf, 'Date & Time', $date . ' at ' . $time, $colWidth);
$pdf->SetX($rightX);
drawDetailPair($pdf, 'Status', $status, $colWidth);
$pdf->Ln(8);

// Row 3: Notes (full width)
$pdf->SetX($leftX);
drawDetailPair($pdf, 'Notes', $notes, $usableWidth);
$pdf->Ln(5);

            // ========== TWO COLUMNS ==========
            $leftWidth = 55;   // 1 part
            $rightWidth = 115; // 2 parts
            $leftX = $pdf->GetX();
            $rightX = $leftX + $leftWidth + 8;
            $separatorX = $leftX + $leftWidth + 4; // middle of gap

            // --- Horizontal line above columns ---
            $pdf->SetDrawColor(150, 150, 150);
            $pdf->Line(20, $pdf->GetY(), 190, $pdf->GetY());
            $pdf->Ln(6);

            // Save Y to align headings
            $startY = $pdf->GetY();

            // ----- LEFT HEADING -----
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY($leftX, $startY);
            $pdf->Cell($leftWidth, 8, 'DIAGNOSIS', 0, 1, 'L');

            // ----- RIGHT HEADING -----
            $pdf->SetXY($rightX, $startY);
            $pdf->Cell($rightWidth, 8, 'MEDICINE SUGGESTIONS', 0, 1, 'L');

            // Move below headings
            $pdf->SetY($startY + 16);
            $pdf->SetDrawColor(150, 150, 150);
            $pdf->Line(20, $pdf->GetY(), 190, $pdf->GetY());
            $pdf->Ln(2);

            // --- DIAGNOSIS content (6 blank lines, 8mm height each) ---
            $pdf->SetFont('Arial', '', 11);
            $pdf->SetTextColor(80, 80, 80);
            $diagStartY = $pdf->GetY();
            $lineHeight = 8; // mm per blank line
            $numLines = 12;   // ample writing space

            if (!empty($existingDiagnosis)) {
                $pdf->SetXY($leftX, $diagStartY);
                $pdf->MultiCell($leftWidth, $lineHeight, $existingDiagnosis, 0, 'L');
            } else {
                for ($i = 0; $i < $numLines; $i++) {
                    $pdf->SetXY($leftX, $pdf->GetY());
                    $pdf->Cell($leftWidth, $lineHeight, '', 0, 1);
                }
            }
            $diagBottomY = $pdf->GetY();

            // --- MEDICINE content (same number of lines) ---
            $pdf->SetY($diagStartY);
            if (!empty($existingMedicine)) {
                $pdf->SetXY($rightX, $diagStartY);
                $pdf->MultiCell($rightWidth, $lineHeight, $existingMedicine, 0, 'L');
            } else {
                for ($i = 0; $i < $numLines; $i++) {
                    $pdf->SetXY($rightX, $pdf->GetY());
                    $pdf->Cell($rightWidth, $lineHeight, '', 0, 1);
                }
            }
            $medBottomY = $pdf->GetY();

            // --- Draw BOLD VERTICAL LINE (spans from top to bottom of the taller column) ---
        // --- Draw BOLD VERTICAL LINE (spans from top to a lower point) ---
$verticalTopY = $startY;
// Increase bottom Y by 20mm (or any desired value, e.g., +30)
$verticalBottomY = max($diagBottomY, $medBottomY) + 35; // <-- added extra length
$pdf->SetDrawColor(150, 150, 150);
$pdf->SetLineWidth(0.8);
$pdf->Line($separatorX, $verticalTopY, $separatorX, $verticalBottomY);
$pdf->SetLineWidth(0.2);

            // Move below the taller column
            $pdf->SetY(max($diagBottomY, $medBottomY) + 8);

            // ========== DOCTOR SIGNATURE ==========
            // ... after the diagnosis/medicine columns ...

// ========== BOTTOM SECTION (zero margin) ==========
// Move to 30mm from bottom (adjust as needed; 30mm leaves room for printer margins)
$pdf->SetY(-49);

// Horizontal line above doctor's signature
$pdf->SetDrawColor(150, 150, 150);
$pdf->Line(20, $pdf->GetY(), 190, $pdf->GetY());
$pdf->Ln(4);

// Doctor's Signature
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(0, 8, 'Doctor\'s Signature', 0, 1, 'L');
$pdf->SetDrawColor(0, 0, 0);
$pdf->Line(20, $pdf->GetY(), 90, $pdf->GetY());
$pdf->Ln(6);

$pdf->SetFont('Arial', 'I', 9);
$pdf->SetTextColor(20, 80, 20);
$pdf->Cell(0, 5, '(Signature & Hospital Stamp)', 0, 1, 'L');

// Thank you message
$pdf->SetFont('Arial', 'I', 9);
$pdf->SetTextColor(70, 70, 70);
$pdf->Cell(0, 5, 'Thank you for choosing City Hospital. Wishing you good health!', 0, 1, 'C');

// Timestamp – place at very bottom with zero extra space
$pdf->SetFont('Arial', '', 8);
$pdf->SetTextColor(150, 150, 150);
$pdf->Cell(0, 5, 'Generated on: ' . now()->format('d M Y, h:i A'), 0, 0, 'C');

// No Ln() or extra spacing after this line – bottom margin is effectively zero.
            // ========== FOOTER (absolute bottom) ==========

            ob_end_clean();
            return response($pdf->Output('S'))
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="appointment_' . $appointmentId . '.pdf"');
        } catch (\Exception $e) {
            \Log::error('PDF generation failed: ' . $e->getMessage());
            return response('Unable to generate PDF. Error: ' . $e->getMessage(), 500);
        }
    }
}
