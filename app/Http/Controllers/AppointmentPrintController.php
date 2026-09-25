<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Carbon\Carbon;
use Exception;
use FPDF;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;

require_once base_path('vendor/setasign/fpdf/fpdf.php');

class AppointmentPrintController extends Controller
{
    /**
     * Thermal print receipt for an appointment.
     */
    public function print(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'patient_name' => ['required', 'string'],
            'date' => ['required', 'string'],
            'time' => ['required', 'string'],
            'doctor' => ['required', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        try {
            $connector = new WindowsPrintConnector('POS-58');
            $printer = new Printer($connector);

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("HOSPITAL APPOINTMENT RECEIPT\n");
            $printer->text("-------------------------------\n");
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text('Patient: '.$validated['patient_name']."\n");
            $printer->text('Date: '.$validated['date']."\n");
            $printer->text('Time: '.$validated['time']."\n");
            $printer->text('Doctor: '.$validated['doctor']."\n");

            if (! empty($validated['notes'])) {
                $printer->text('Notes: '.$validated['notes']."\n");
            }

            $printer->text("-------------------------------\n");
            $printer->text("Thank you!\n");

            $printer->cut();
            $printer->close();

            return response()->json([
                'success' => true,
                'message' => 'Print job sent successfully.',
            ]);
        } catch (Exception $e) {
            Log::error('Thermal print failed: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Thermal print error: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Generate PDF receipt for an appointment.
     */
    public function appointmentPdf(int|string $id): Response
    {
        if (ob_get_length()) {
            ob_end_clean();
        }
        ob_start();

        try {
            $appointment = Appointment::with('doctor')->findOrFail($id);

            $doctorName = $appointment->doctor?->name ?? 'N/A';
            $patientName = $appointment->name ?? 'N/A';

            $date = 'N/A';
            if (! empty($appointment->created_at)) {
                $date = Carbon::parse($appointment->created_at)->format('d M Y');
            }

            $time = 'N/A';
            if (! empty($appointment->time)) {
                try {
                    $time = Carbon::parse($appointment->time)->format('h:i A');
                } catch (Exception) {
                    $time = (string) $appointment->time;
                }
            }

            $status = $appointment->status ?? 'Confirmed';
            $appointmentId = $appointment->id;
            $notes = $appointment->notes ?? 'N/A';
            $existingDiagnosis = $appointment->diagnosis ?? '';
            $existingMedicine = $appointment->medicine_suggestions ?? '';

            $pdf = new FPDF('P', 'mm', 'A4');
            $pdf->AddPage();
            $pdf->SetFillColor(255, 255, 255);
            $pdf->Rect(0, 0, 210, 297, 'F');
            $pdf->SetMargins(5, 5, 5);
            $pdf->SetAutoPageBreak(true, 10);

            $logoPath = public_path('images/medical-logo-png-897.png');
            if (file_exists($logoPath)) {
                $pdf->Image($logoPath, 20, 15, 25);
            }

            $pdf->SetY(15);
            $pdf->SetFont('Arial', 'B', 18);
            $pdf->SetTextColor(40, 40, 100);
            $pdf->Cell(0, 10, 'CITY HOSPITAL & MEDICAL CENTER', 0, 1, 'C');
            $pdf->SetFont('Arial', '', 10);
            $pdf->SetTextColor(100, 100, 100);
            $pdf->Cell(0, 5, '123 Healthcare Avenue, Wellness City', 0, 1, 'C');
            $pdf->Cell(0, 5, 'Phone: +92 300 1234567 | Mail: care@cityhospital.com', 0, 1, 'C');
            $pdf->Ln(6);

            $pdf->SetFont('Arial', 'I', 9);
            $pdf->SetTextColor(80, 80, 80);
            $pdf->Cell(0, 5, 'Receipt No: AP-'.str_pad((string) $appointmentId, 6, '0', STR_PAD_LEFT), 0, 1, 'R');
            $pdf->SetDrawColor(200, 200, 200);
            $pdf->Line(20, $pdf->GetY(), 190, $pdf->GetY());
            $pdf->Ln(3);

            $pdf->SetFont('Arial', 'B', 16);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 10, 'MEDICAL RECEIPT', 0, 1, 'C');
            $pdf->Ln(2);

            $pdf->SetFont('Arial', 'B', 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, 'PATIENT DETAILS', 0, 1, 'L');
            $pdf->SetDrawColor(150, 150, 150);
            $pdf->Line(20, $pdf->GetY(), 190, $pdf->GetY());
            $pdf->Ln(3);

            $usableWidth = 170;
            $colWidth = $usableWidth / 2;
            $leftX = $pdf->GetX();
            $rightX = $leftX + $colWidth;

            $this->drawDetailPair($pdf, 'Patient Name', (string) $patientName, $colWidth);
            $pdf->SetX($rightX);
            $this->drawDetailPair($pdf, 'Doctor', (string) $doctorName, $colWidth);
            $pdf->Ln(8);

            $pdf->SetX($leftX);
            $this->drawDetailPair($pdf, 'Date & Time', $date.' at '.$time, $colWidth);
            $pdf->SetX($rightX);
            $this->drawDetailPair($pdf, 'Status', (string) $status, $colWidth);
            $pdf->Ln(8);

            $pdf->SetX($leftX);
            $this->drawDetailPair($pdf, 'Notes', (string) $notes, $usableWidth);
            $pdf->Ln(5);

            $leftWidth = 55;
            $rightWidth = 115;
            $leftX = $pdf->GetX();
            $rightX = $leftX + $leftWidth + 8;
            $separatorX = $leftX + $leftWidth + 4;

            $pdf->SetDrawColor(150, 150, 150);
            $pdf->Line(20, $pdf->GetY(), 190, $pdf->GetY());
            $pdf->Ln(6);

            $startY = $pdf->GetY();

            $pdf->SetFont('Arial', 'B', 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY($leftX, $startY);
            $pdf->Cell($leftWidth, 8, 'DIAGNOSIS', 0, 1, 'L');

            $pdf->SetXY($rightX, $startY);
            $pdf->Cell($rightWidth, 8, 'MEDICINE SUGGESTIONS', 0, 1, 'L');

            $pdf->SetY($startY + 16);
            $pdf->SetDrawColor(150, 150, 150);
            $pdf->Line(20, $pdf->GetY(), 190, $pdf->GetY());
            $pdf->Ln(2);

            $pdf->SetFont('Arial', '', 11);
            $pdf->SetTextColor(80, 80, 80);
            $diagStartY = $pdf->GetY();
            $lineHeight = 8;
            $numLines = 12;

            if (! empty($existingDiagnosis)) {
                $pdf->SetXY($leftX, $diagStartY);
                $pdf->MultiCell($leftWidth, $lineHeight, $existingDiagnosis, 0, 'L');
            } else {
                for ($i = 0; $i < $numLines; $i++) {
                    $pdf->SetXY($leftX, $pdf->GetY());
                    $pdf->Cell($leftWidth, $lineHeight, '', 0, 1);
                }
            }
            $diagBottomY = $pdf->GetY();

            $pdf->SetY($diagStartY);
            if (! empty($existingMedicine)) {
                $pdf->SetXY($rightX, $diagStartY);
                $pdf->MultiCell($rightWidth, $lineHeight, $existingMedicine, 0, 'L');
            } else {
                for ($i = 0; $i < $numLines; $i++) {
                    $pdf->SetXY($rightX, $pdf->GetY());
                    $pdf->Cell($rightWidth, $lineHeight, '', 0, 1);
                }
            }
            $medBottomY = $pdf->GetY();

            $verticalTopY = $startY;
            $verticalBottomY = max($diagBottomY, $medBottomY) + 35;
            $pdf->SetDrawColor(150, 150, 150);
            $pdf->SetLineWidth(0.8);
            $pdf->Line($separatorX, $verticalTopY, $separatorX, $verticalBottomY);
            $pdf->SetLineWidth(0.2);

            $pdf->SetY(-49);
            $pdf->SetDrawColor(150, 150, 150);
            $pdf->Line(20, $pdf->GetY(), 190, $pdf->GetY());
            $pdf->Ln(4);

            $pdf->SetFont('Arial', 'B', 11);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 8, "Doctor's Signature", 0, 1, 'L');
            $pdf->SetDrawColor(0, 0, 0);
            $pdf->Line(20, $pdf->GetY(), 90, $pdf->GetY());
            $pdf->Ln(6);

            $pdf->SetFont('Arial', 'I', 9);
            $pdf->SetTextColor(20, 80, 20);
            $pdf->Cell(0, 5, '(Signature & Hospital Stamp)', 0, 1, 'L');

            $pdf->SetFont('Arial', 'I', 9);
            $pdf->SetTextColor(70, 70, 70);
            $pdf->Cell(0, 5, 'Thank you for choosing City Hospital. Wishing you good health!', 0, 1, 'C');

            $pdf->SetFont('Arial', '', 8);
            $pdf->SetTextColor(150, 150, 150);
            $pdf->Cell(0, 5, 'Generated on: '.now()->format('d M Y, h:i A'), 0, 0, 'C');

            ob_end_clean();

            return response($pdf->Output('S'))
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="appointment_'.$appointmentId.'.pdf"');
        } catch (Exception $e) {
            Log::error('PDF generation failed: '.$e->getMessage());

            return response('Unable to generate PDF. Error: '.$e->getMessage(), 500);
        }
    }

    /**
     * Helper to render a label-value pair on the PDF.
     */
    private function drawDetailPair(FPDF $pdf, string $label, string $value, float $width): void
    {
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetTextColor(50, 50, 50);
        $pdf->Cell($width * 0.35, 6, $label.':', 0, 0, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell($width * 0.65, 6, $value, 0, 0, 'L');
    }
}
