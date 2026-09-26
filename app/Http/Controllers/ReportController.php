<?php

namespace App\Http\Controllers;

use App\Models\PatientHistory;
use App\Services\PdfReportService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ReportController extends Controller
{
    public function __construct(
        private readonly PdfReportService $pdfService
    ) {}

    /**
     * Export all patients or search results to PDF.
     */
    public function patientsPdf(Request $request): Response
    {
        return $this->pdfService->exportPatientsPdf($request->query('search'));
    }

    /**
     * Export a single patient summary / history to PDF.
     */
    public function patientProfilePdf(int|string $id): Response
    {
        $patient = PatientHistory::findOrFail($id);

        return $this->pdfService->exportSinglePatientPdf($patient);
    }

    /**
     * Export appointments schedule to PDF.
     */
    public function appointmentsPdf(Request $request): Response
    {
        return $this->pdfService->exportAppointmentsPdf(
            $request->query('search'),
            $request->query('status')
        );
    }

    /**
     * Export doctors list to PDF.
     */
    public function doctorsPdf(): Response
    {
        return $this->pdfService->exportDoctorsPdf();
    }

    /**
     * Export staff members list to PDF.
     */
    public function staffPdf(): Response
    {
        return $this->pdfService->exportStaffPdf();
    }

    /**
     * Export daily expenses audit report to PDF.
     */
    public function expensesPdf(): Response
    {
        return $this->pdfService->exportExpensesPdf();
    }

    /**
     * Export hospital payments financial statement to PDF.
     */
    public function paymentsPdf(Request $request): Response
    {
        return $this->pdfService->exportPaymentsPdf(
            $request->query('start_date'),
            $request->query('end_date')
        );
    }
}
