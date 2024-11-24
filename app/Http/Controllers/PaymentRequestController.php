<?php

namespace App\Http\Controllers;

use App\Models\User;
use TCPDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentRequestController extends Controller
{
    public function generatePdf(Request $request)
    {
        $requestId = $request->get('requestId');

        $loggedInUser = Auth::user();

        $userRole = $loggedInUser->role;

        if ($requestId) {
            $requestDetails = DB::table('requests')
                ->leftJoin('suppliers', 'requests.supplier_id', '=', 'suppliers.id')
                ->where('requests.id', $requestId)
                ->first();

            if ($requestDetails) {
                if ($requestDetails->status === 'rejected') {
                    return response()->json(['error' => 'The payment request has been rejected. PDF cannot be generated.'], 400);
                }

                if ($userRole === 'user' && $requestDetails->user_id !== $loggedInUser->id) {
                    return response()->json(['error' => 'You do not have permission to view this request.'], 403);
                }

                $user = User::where('id', $requestDetails->user_id)->first();

                $checkedByHtml = '';
                if ($requestDetails->checked_by) {
                    $checkedBy = User::where('id', $requestDetails->checked_by)->first();
                    $checkedByHtml = view('pdf.checked_by', ['checkedBy' => $checkedBy, 'checkedDate' => $requestDetails->checked_date])->render();
                }

                Log::info("requested data" , [$requestDetails]);
                // Fetch details for the approved_by user, if any
                $approvedByHtml = '';
                if ($requestDetails->approved_by) {
                    Log::info("des this work");
                    $approvedBy = User::where('id', $requestDetails->approved_by)->first();
                    $approvedByHtml = view('pdf.approved_by', ['approvedBy' => $approvedBy, 'approvedDate' => $requestDetails->approved_date])->render();
                }

                // Create a new PDF document
                $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

                // Set document information
                $pdf->SetCreator(PDF_CREATOR);
                $pdf->SetAuthor('Your Organization');
                $pdf->SetTitle('Payment Request Voucher');
                $pdf->SetSubject('Payment Request');
                $pdf->SetKeywords('Payment Request');

                // Add a page
                $pdf->AddPage();

                // Prepare data for the PDF
                $html = view('pdf.payment_request', [
                    'request' => $requestDetails,
                    'user' => $user,
                    'checkedByHtml' => $checkedByHtml,
                    'approvedByHtml' => $approvedByHtml,
                    'formattedAmount' => number_format($requestDetails->amount, 2),
                    'amountExceedsOneMillion' => $requestDetails->amount > 1000000 ? 'Yes' : 'No',
                ])->render();

                // Output the HTML content to the PDF
                $pdf->writeHTML($html, true, false, true, false, '');

                // Close and output PDF document
                $pdf->Output('payment_request_voucher.pdf', 'I');
            } else {
                return response()->json(['error' => 'Request not found.'], 404);
            }
        } else {
            return response()->json(['error' => 'Request ID not provided.'], 400);
        }
    }
}
