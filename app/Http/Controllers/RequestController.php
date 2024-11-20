<?php

namespace App\Http\Controllers;

use App\Models\ApprovedRequest;
use App\Models\Category;
use App\Models\Chat;
use App\Models\ChatStatus;
use App\Models\Files;
use App\Models\RejectedRequests;
use App\Models\Requests;
use App\Models\Supplier;
use App\Models\SupplierAccount;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class RequestController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $status = match($request->route()->getName()) {
            'requests.pending-check' => 'pending',
            'requests.pending-approval' => 'checked',
            'requests.waiting-signature' => 'waiting_for_signature',
            'requests.approved' => 'approved',
            'requests.rejected' => 'rejected',
            default => 'all', // fallback
        };

        $heading = $this->getHeading($status);
        $statusClasses = [
            'pending' => 'secondary',
            'checked' => 'info',
            'waiting_for_signature' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
        ];

        if ($user->can('low amount requests') && $user->can('higher amount requests')) {
                $requests = Requests::all();
                foreach($requests as $request){
                    $request->supplier_name = Supplier::where('id' , $request->supplier_id)->pluck('supplier_name')->first();
                }
                return view('admin.requests', compact('requests', 'heading', 'statusClasses'));

        } elseif ($user->can('low amount requests')){

            $requests = Requests::where('amount', '<=', 5000000)->get();
            foreach($requests as $request){
                $request->supplier_name = Supplier::where('id' , $request->supplier_id)->pluck('supplier_name')->first();
            }
            return view('admin.requests', compact('requests', 'heading', 'statusClasses'));

        } elseif ($user->can('higher amount requests')){
         
            $requests = Requests::where('amount', '>', 5000000)->get();
            foreach($requests as $request){
                $request->supplier_name = Supplier::where('id' , $request->supplier_id)->pluck('supplier_name')->first();
            }
            return view('admin.requests', compact('requests', 'heading', 'statusClasses'));

        } else {

            Log::info("works" , [$status]);
            $requests = Requests::where('user_id', $user->id)
                ->where('status', $status)
                ->get();

                Log::info("works" , [$requests]);

        }

        return view('requests.show', compact('requests', 'heading', 'statusClasses'));
    }

    public function getAllUserRequests()
    {
        $user = Auth::user();
        $requests = Requests::where('user_id' ,$user->id )->get();

        foreach($requests as $request)
        {
            $request->supplier_name = Supplier::where('id', $request->supplier_id)->pluck('supplier_name')->first();
            $request->checked = User::where('id' , $request->checked_by)->pluck('name')->first();
            $request->approved = User::where('id' , $request->approved_by)->pluck('name')->first();
        }

        return view('requests.allRequests', compact('requests'));
    }

    private function getHeading($status)
    {
        return match($status) {
            'pending' => 'Pending Check',
            'checked' => 'Pending Approval',
            'waiting_for_signature' => 'Waiting for Signature',
            'approved' => 'Approved Requests',
            'rejected' => 'Rejected Requests',
            default => 'All Requests',
        };
    }

    
    public function createRequest(REQUEST $request)
    {
        if ($request->isMethod('post')) {
            Log::info($request->all());
            $new_request = new Requests();
            $new_request->user_id = Auth::id();
            $new_request->account_id = $request->account;
            $new_request->amount = $request->amount;
            $new_request->status = 'pending';
            $new_request->subcategory = $request->subcategory;
            $new_request->supplier_id = $request->supplier;
            $new_request->due_date = $new_request->due_date = Carbon::now()->addDays(14);            ;
            $new_request->note = $request->note;
            $new_request->priority = $request->priority;
            $new_request->vender_invoice = $request->vender_invoice;
            $new_request->type = $request->type;
            $new_request->indicator = $request->indicator;
            $new_request->payment_link = $request->payment_link;
            $new_request->save();

            $uploadedFiles = $request->uploaded_files;
            $filePaths = explode(',', rtrim($uploadedFiles, ','));

            $validFiles = [];
        
            // Loop through each file path and check if it exists
            foreach ($filePaths as $filePath) {
                    $validFiles[] = $filePath; // Add to valid files array if it exists
            }
        
            // Save valid files to the database with the request ID
            foreach ($validFiles as $validFile) {
                Files::create([
                    'request_id' => $new_request->id,
                    'file_path' => $validFile,
                ]);
            }

            return redirect()->route('dashboard')->with('success', 'Request added successfully!');
        }

        $suppliers = Supplier::all();
        return view('requests.create' , compact('suppliers'));
    }

    public function uploadFiles(Request $request)
    {
        $file = $request->file('file');
        $path = $file->store('uploads', 'public'); // Adjust storage path as needed
        return response()->json(['filePath' => $path]);
    }

    public function deleteFile(Request $request)
    {
        $filePath = $request->input('filePath');

        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);

            return response()->json(['message' => 'File deleted successfully.'], 200);
        }

        return response()->json(['message' => 'File not found.'], 404);
    }


    public function getAccountsForSupplier($id)
    {
        $accounts = SupplierAccount::where('supplier_id', $id)->get();
        return response()->json([
            'accounts' => $accounts
        ]);
    }

    public function getAccountDetails($id)
    {
        $account = SupplierAccount::where('id', $id)->first();
        return response()->json([
            'bank_details' => [
                'account_name' => $account->account_name,
                'account_number' => $account->account_number,
                'bank_name' => $account->bank_name,
                'branch' => $account->branch
            ]
        ]);
    }


    public function getRequestDeatails($id)
    {
        $request = Requests::where('id' , $id)->first();
        $request->supplier_name = Supplier::where('id' , $request->supplier_id)->pluck('supplier_name')->first();
        $request->requested_user = User::where('id' , $request->user_id)->pluck('name')->first();
        $request->account_name = SupplierAccount::where('supplier_id' , $request->supplier_id)->pluck('account_name')->first();
        $request->account_number = SupplierAccount::where('supplier_id' , $request->supplier_id)->pluck('account_number')->first();
        $request->bank_name = SupplierAccount::where('supplier_id' , $request->supplier_id)->pluck('bank_name')->first();

        return response()->json([
            'requestId' => $id,
            'category' => $request->category,
            'subcategory' => $request->subcategory,
            'supplier_name' => $request->supplier_name,
            'amount' => number_format($request->amount, 2),
            'status' => $request->status,
            'requested_date' => $request->created_at->format('Y-m-d'),
            'requested_by' => $request->requested_user,
            'due_date' => $request->due_date,
            'payment_type' => $request->type,
            'account_name' => $request->account_name,
            'account_number' => $request->account_number,
            'bank_name' => $request->bank_name,
            'note' => $request->note,
            'document_link' => $request->document_link,
        ]);

    }

    public function updateRequest(Request $request, $id)
    {
        $requestData = Requests::findOrFail($id);
    
        $requestData->category = $request->category;
        $requestData->save();
    
        return response()->json(['success' => true]);
    }
    
    public function updateStatus(Request $request)
    {
        $validated = $request->validate([
            'request_id' => 'required|integer',
            'status' => 'required|string',
            'checked_by' => 'nullable|integer',
            'approved_by' => 'nullable|integer',
            'rejected_by' => 'nullable|integer',
            'reject_message' => 'nullable|string',
        ]);
    
        $requestRecord = Requests::find($validated['request_id']);
        if (!$requestRecord) {
            return response()->json(['success' => false, 'message' => 'Request not found']);
        }
    
        $requestRecord->status = $validated['status'];
        if ($validated['status'] === 'checked') {
            $requestRecord->checked_by = Auth::id();
            $requestRecord->checked_date = Carbon::now();
            $this->sendStatusChangeNotification($requestRecord, 'checked');

        } elseif ($validated['status'] === 'waiting_for_signature') {
            $requestRecord->signed_by = Auth::id();

        }  elseif ($validated['status'] === 'approved') {
            $requestRecord->approved_by = Auth::id();
            $requestRecord->approved_date = Carbon::now();
            $this->sendStatusChangeNotification($requestRecord, 'approved');

        } elseif ($validated['status'] === 'rejected') {
            $rejectedRequest = new RejectedRequests();
            $rejectedRequest->request_id = $request->request_id;
            $rejectedRequest->rejected_by= Auth::id();
            $rejectedRequest->message = $request->reject_message;
            $rejectedRequest->save();
            $this->sendStatusChangeNotification($requestRecord, 'rejected');

        }
        $requestRecord->save();
    
        return response()->json(['success' => true]);
    }

    private function sendStatusChangeNotification($requestRecord, $status)
    {
        Log::info("request data" , [ $requestRecord]);
        $requestUserEmail = User::where('id' , $requestRecord->user_id)->pluck('email')->first();
        $checkedByEmail = $requestRecord->checked_by 
        ? User::where('id', $requestRecord->checked_by)->pluck('email')->first() 
        : null;
    
    $approvedByEmail = $requestRecord->approved_by 
        ? User::where('id', $requestRecord->approved_by)->pluck('email')->first() 
        : null;

        $emailSubject = "Request #{$requestRecord->id} Status Updated: " . ucfirst($status);
        $emailContent = "
            <html>
            <head>
                <title>Status Update Notification</title>
            </head>
            <body>
                <p>The request with ID {$requestRecord->id} has been {$status}.</p>
                <p>Details:</p>
                <ul>
                    <li>Request ID: {$requestRecord->id}</li>
                    <li>Status: {$status}</li>
                    <li>Checked By: " . ($checkedByEmail ?? 'N/A') . "</li>
                    <li>Approved By: " . ($approvedByEmail ?? 'N/A') . "</li>
                </ul>
            </body>
            </html>";

        $sendgrid = new \SendGrid(env('SENDGRID_API_KEY'));

        $emailMessage = new \SendGrid\Mail\Mail();
        $emailMessage->setFrom("info@yourdomain.com", "Your App");
        $emailMessage->setSubject($emailSubject);
        $emailMessage->addTo($requestUserEmail); // Send to request user
        if ($checkedByEmail) {
            $emailMessage->addCc($checkedByEmail);
        }
        if ($approvedByEmail) {
            $emailMessage->addCc($approvedByEmail);
        }
        $emailMessage->addContent("text/html", $emailContent);

        try {
            $response = $sendgrid->send($emailMessage);
            if ($response->statusCode() >= 200 && $response->statusCode() < 300) {
                return true; // Email sent successfully
            } else {
                // Log errors for debugging
                Log::error("Failed to send email: " . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Caught exception: ' . $e->getMessage());
            return false;
        }
    }


    public function getChatStatus($id)
    {
        $chatStatus = ChatStatus::where('request_id', $id)->first();

        if ($chatStatus) {
            return response()->json(['chat' => $chatStatus->status]);
        }

        return response()->json(['chat' => 0]);
    }

    public function enableChat($id)
    {
        $chatStatus = ChatStatus::where('request_id', $id)->first();
        if ($chatStatus) {
            $chatStatus->update(['status' => true]);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    public function sendChatMessage(Request $request, $id)
    {
        $user = Auth::user();

        $message = new Chat();
        $message->request_id = $id;
        $message->sender_id = Auth::id();
        $message->message = $request->message;
        $message->save();

        return response()->json([
            'success' => true,
            'userName' => $user->name
        ]);
    
    }

    public function getMessages($request_id)
    {
        $messages = Chat::where('request_id', $request_id)
                        ->with('sender') 
                        ->orderBy('created_at', 'asc')
                        ->get();

        return response()->json(['success' => true, 'messages' => $messages]);
    }

    public function getFiles($requestId)
    {
        $files = Files::where('request_id', $requestId)->get();
        return response()->json($files);
    }
    
    public function approveRequest(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'requestId' => 'required|exists:requests,id',
            'checkNumber' => 'nullable|string|max:255',
            'voucherNumber' => 'nullable|string|max:255',
            'depositSlip' => 'nullable|file|mimes:jpg,png,pdf|max:2048', // Ensure file is valid
        ]);
    
        try {
            // Process file upload if present
            $depositSlipPath = null;
            if ($request->hasFile('depositSlip')) {
                $depositSlipPath = $request->file('depositSlip')->store('deposit_slips', 'public'); // Store in 'public/deposit_slips'
            }
    
            $entry = new ApprovedRequest();
            $entry->request_id = $validated['requestId'];
            $entry->check_number = $validated['checkNumber'];
            $entry->voucher_number = $validated['voucherNumber'];
            $entry->deposit_slip = $depositSlipPath;
            $entry->approved_at = now();
            $entry->approved_by = Auth::user()->id; // Assuming a logged-in user is approving the request
            $entry->save();
    
            $data = Requests::findOrFail($validated['requestId']);
            $data->status = "approved";
            $data->save();
    
            Log::info("Approval Data", [$request->all()]);
    
            return response()->json(['success' => true, 'message' => 'Request approved successfully.']);
        } catch (\Exception $e) {
            Log::error("Error approving request: ", ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to approve request.']);
        }
    }
    

}
