<?php

namespace App\Http\Controllers;

use App\Models\ApprovedRequest;
use App\Models\Category;
use App\Models\Chat;
use App\Models\ChatStatus;
use App\Models\Files;
use App\Models\RejectedRequests;
use App\Models\Requests;
use App\Models\SubRequest;
use App\Models\Supplier;
use App\Models\SupplierAccount;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RequestController extends Controller
{
    public function history(Request $request)
    {
        $user = Auth::user();

        $statusFilter = $request->input('status');

        $query = SubRequest::query();
    
        if ($user->role == 'user') {
            $query->where('created_by', $user->id);
        } elseif ($user->role == 'highAccount' || $user->role == 'minAccount') {
            $query->where('checked_by', $user->id);
        } elseif ($user->role == 'manager') {
            $query->where('signed_by', $user->id);
        } elseif ($user->role == 'account') {
            $query->where('approved_by', $user->id);
        } elseif ($user->role == 'admin') {
        }
    
        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }
    
        $requests = $query->get();
    
        return view('requests.history', compact('requests'));
    }

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
                $requests = SubRequest::all();
                return view('admin.requests', compact('requests', 'heading', 'statusClasses'));

        } elseif ($user->can('low amount requests')){

            $requests = SubRequest::query()->where('status', SubRequest::STATUS_PENDING)
            ->where('amount', '<=', 5000000)
            ->get();
            return view('admin.requests', compact('requests', 'heading', 'statusClasses'));

        } elseif ($user->can('higher amount requests')){

            $requests = SubRequest::query()->where('status', SubRequest::STATUS_PENDING)
            ->where('amount', '>', 5000000)
            ->get();

            return view('admin.requests', compact('requests', 'heading', 'statusClasses'));

        } elseif ($user->can('waiting request')){

            $requests = SubRequest::query()->where('status', SubRequest::STATUS_CHECKED)->get();
            return view('admin.requests', compact('requests', 'heading', 'statusClasses'));

        } elseif ($user->can('approve request')){
            $requests = SubRequest::query()->where('status', SubRequest::STATUS_WAITING_FOR_SIGNATURE)->get();
            return view('admin.requests', compact('requests', 'heading', 'statusClasses'));

        }else {

            Log::info("works" , [$status]);
            $requests = SubRequest::query()->where('created_by', $user->id)
                ->where('status', $status)
                ->get();

                Log::info("works" , [$requests]);

        }

        return view('requests.show', compact('requests', 'heading', 'statusClasses'));
    }

    // public function  getHistoryRequests()
    // {
    //     $user = Auth::user();

    //     if($user->role == "minAccount" || $user->role == "highAccount")
    //     {
    //         $requests = SubRequest::query()->where('checked_by', $user->id)->get();

    //     }elseif($user->role == "manager")
    //     {
    //         $requests = SubRequest::query()->where('signed_by', $user->id)->get();

    //     }elseif($user->role == "account")
    //     {
    //         $requests = SubRequest::query()->where('approved_by', $user->id)->get();
    //     }elseif( $user->role == "admin")
    //     {
    //         $requests = SubRequest::query()->where('approved_by', $user->id)
    //         ->orWhere('signed_by', $user->id)
    //         ->orWhere('checked_by', $user->id)
    //         ->get();
    //     }

    //     foreach($requests as $request){
    //         $request->supplier_name = Supplier::query()->where('id' , $request->supplier_id)->pluck('supplier_name')->first();
    //     }

    //     return view('requests.history', compact('requests'));
    // }

    public function getAllUserRequests()
    {
        $user = Auth::user();
        $requests = SubRequest::query()->where('created_by' ,$user->id )->get();
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


    public function createRequest(Request $request)
    {
        if ($request->isMethod('post')) {
            if ($request->amount <  $request->pay_amount){
                return redirect()->intended(route('dashboard'), 301)->with('error', 'Pay amount cannot greater than amount');
            }
            try {
                DB::beginTransaction();
                $new_request = new Requests();
                $new_request->user_id = Auth::id();
                $new_request->account_id = $request->account;
                $new_request->amount = $request->amount;
                $new_request->status = 'pending';
                $new_request->subcategory = $request->subcategory;
                $new_request->supplier_id = $request->supplier;
                $new_request->due_date = Carbon::parse($request->due_date)->addDays(14);            ;
                $new_request->note = $request->note;
                $new_request->priority = $request->priority;
                $new_request->vender_invoice = $request->vender_invoice;
                $new_request->type = $request->type;
                $due_amount = ($request->amount - $request->pay_amount);
                $new_request->due_amount = $due_amount;

                if ($due_amount <= 0){
                    $new_request->is_payment_settled = true;
                }

                $new_request->indicator = $request->indicator;
                $new_request->payment_link = $request->payment_link;
                $new_request->saveOrFail();

                $sub_request = new SubRequest();
                $sub_request->account = $request->account;
                $sub_request->request = $new_request->id;
                $sub_request->amount =  $request->amount;
                $sub_request->due_amount = $due_amount;
                $sub_request->paid_amount = $request->pay_amount;
                $sub_request->subcategory = $request->subcategory;
                $sub_request->supplier_id = $request->supplier;
                $sub_request->due_date = $new_request->due_date;
                $sub_request->note = $request->note;
                $sub_request->priority = Str::upper($request->priority);
                $sub_request->vender_invoice = $request->vender_invoice;
                if ($due_amount <= 0){
                    $sub_request->type = Requests::FULL_PAYMENT;
                }
                $sub_request->indicator = $request->indicator;
                $sub_request->vender_invoice = $request->vender_invoice;
                $sub_request->payment_link = $request->payment_link;
                $sub_request->saveOrFail();

                $transaction = new Transaction();
                $transaction->request = $new_request->id;
                $transaction->sub_request = $sub_request->id;
                $transaction->amount = $request->pay_amount;
                if ($due_amount > 0){
                    $transaction->type = Transaction::ADVANCE_PAYMENT;
                }
                $transaction->status = Transaction::TRANSACTION_SUCCESS;
                $transaction->meta = json_encode($sub_request);
                $transaction->saveOrFail();

                if ($request->uploaded_files){
                    $uploadedFiles = $request->uploaded_files;
                    $filePaths = explode(',', rtrim($uploadedFiles, ','));

                    $validFiles = [];

                    // Loop through each file path and check if it exists
                    foreach ($filePaths as $filePath) {
                        $validFiles[] = $filePath; // Add to valid files array if it exists
                    }

                    // Save valid files to the database with the request ID
                    foreach ($validFiles as $validFile) {
                        Files::query()->create([
                            'request_id' => $new_request->id,
                            'sub_request' => $sub_request->id,
                            'file_path' => $validFile,
                        ]);
                    }
                }
                DB::commit();
                return redirect()->intended(route('dashboard'), 301)->with('success', 'Request added successfully!');
            }catch (\Exception $ex){
                Log::error("Request Create : ". $ex->getMessage());
                DB::rollBack();
                return redirect()->intended(route('dashboard'), 301)->with('error', 'Something went wrong!');
            }
        }

        $suppliers = Supplier::all();
        return view('requests.create' , compact('suppliers'));
    }

    public function settledRequest(Request $request, int $id):RedirectResponse | View
    {
        $latest_request = SubRequest::query()->findOrFail($id);
        if ($request->isMethod('POST')){
            if ($latest_request->due_amount < $request->pay_amount){
              return redirect()->intended(route('dashboard'), 301)->with('error', 'Pay amount cannot greater than amount!');
            }
            try {
                DB::beginTransaction();
                $sub_request = new SubRequest();
                $sub_request->account = $request->account;
                $sub_request->request = $latest_request->request;
                $sub_request->amount =  $latest_request->due_amount;
                $sub_request->due_amount = ($latest_request->due_amount - $request->pay_amount);
                $sub_request->paid_amount = $request->pay_amount;
                $sub_request->subcategory = $request->subcategory;
                $sub_request->supplier_id = $request->supplier;
                $sub_request->due_date = Carbon::parse($request->due_date)->addDays(14);
                $sub_request->note = $request->note;
                $sub_request->priority = Str::upper($request->priority);
                $sub_request->vender_invoice = $request->vender_invoice;
                if ($sub_request->due_amount <= 0){
                    $sub_request->type = Requests::FULL_PAYMENT;
                    $sub_request->requestRef->is_payment_settled = true;
                    $sub_request->requestRef->updateOrFail();
                }
                $sub_request->indicator = $request->indicator;
                $sub_request->vender_invoice = $request->vender_invoice;
                $sub_request->payment_link = $request->payment_link;
                $sub_request->saveOrFail();

                $latest_request->is_latest = false;
                $latest_request->updateOrFail();

                $latest_request->requestRef->due_amount = $sub_request->due_amount;
                $latest_request->requestRef->updateOrFail();

                $transaction = new Transaction();
                $transaction->request = $latest_request->request;
                $transaction->sub_request = $sub_request->id;
                $transaction->amount = $request->pay_amount;
                if ($sub_request->due_amount > 0){
                    $transaction->type = Transaction::ADVANCE_PAYMENT;
                }
                $transaction->status = Transaction::TRANSACTION_SUCCESS;
                $transaction->meta = json_encode($sub_request);
                $transaction->saveOrFail();

               if ($request->hasFile('uploaded_files')){
                   $uploadedFiles = $request->file('uploaded_files');
                   $filePaths = explode(',', rtrim($uploadedFiles, ','));

                   $validFiles = [];
                   foreach ($filePaths as $filePath) {
                       $validFiles[] = $filePath; // Add to valid files array if it exists
                   }

                   foreach ($validFiles as $validFile) {
                       Files::query()->create([
                           'request_id' => $latest_request->request,
                           'sub_request' => $sub_request->id,
                           'file_path' => $validFile,
                       ]);
                   }
               }
                DB::commit();
                return redirect()->intended(route('dashboard'), 301)->with('success', 'Request added successfully!');
            }catch (\Exception $ex){
                Log::error("Request Create : ". $ex->getMessage());
                DB::rollBack();
                return redirect()->intended(route('dashboard'), 301)->with('error', 'Something went wrong!');
            }
        }
        $suppliers = Supplier::all();
        return view('requests.update' , compact('suppliers', 'latest_request'));
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
        $request = SubRequest::query()->where('id' , $id)->first();
        $request->account_name = SupplierAccount::query()->where('supplier_id' , $request->supplier_id)->pluck('account_name')->first();
        $request->account_number = SupplierAccount::query()->where('supplier_id' , $request->supplier_id)->pluck('account_number')->first();
        $request->bank_name = SupplierAccount::query()->where('supplier_id' , $request->supplier_id)->pluck('bank_name')->first();

        return response()->json([
            'requestId' => $id,
            'category' => $request->category,
            'subcategory' => $request->subcategory,
            'supplier_name' => $request->supplierRef?->supplier_name,
            'amount' => number_format($request->amount, 2),
            'due_amount' => number_format($request->due_amount, 2),
            'total_paid' => number_format($request->paid_amount, 2),
            'status' => $request->status,
            'requested_date' => $request->created_at->format('Y-m-d'),
            'requested_by' => $request->createdByRef?->name,
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
        $subRequest = SubRequest::findOrFail($id);
        $requestData = Requests::where('id', $subRequest->request)->first();

        $requestData->category = $request->category;
        $requestData->save();

        return response()->json(['success' => true]);
    }

    public function updateStatus(Request $request)
    {
        Log::info("request" , [$request->all()]);
        $validated = $request->validate([
            'request_id' => 'required|integer',
            'status' => 'required|string',
            'checked_by' => 'nullable|integer',
            'approved_by' => 'nullable|integer',
            'rejected_by' => 'nullable|integer',
            'reject_message' => 'nullable|string',
        ]);

        $requestRecord = SubRequest::query()->find($validated['request_id']);
        if (!$requestRecord) {
            return response()->json(['success' => false, 'message' => 'Request not found']);
        }

        $requestRecord->status = $validated['status'];
        if ($validated['status'] === SubRequest::STATUS_CHECKED) {
            $requestRecord->checked_by = Auth::id();
            $requestRecord->checked_date = Carbon::now();
            $this->sendStatusChangeNotification($requestRecord, SubRequest::STATUS_CHECKED);

        } elseif ($validated['status'] === SubRequest::STATUS_WAITING_FOR_SIGNATURE) {
            $requestRecord->signed_by = Auth::id();

        }  elseif ($validated['status'] === SubRequest::STATUS_APPROVED) {
            $requestRecord->approved_by = Auth::id();
            $requestRecord->approved_date = Carbon::now();

        } elseif ($validated['status'] === SubRequest::STATUS_REJECTED) {
            try {
                DB::beginTransaction();

                $rejectedRequest = new RejectedRequests();
                $rejectedRequest->request_id = $request->request_id;
                $rejectedRequest->rejected_by = Auth::id();  
                $rejectedRequest->message = $request->reject_message;
                $rejectedRequest->save();

                $transaction = Transaction::where('sub_request', $request->request_id)->first();
                $transaction->status = Transaction::TRANSACTION_FAILED;
                $transaction->updated_by = Auth::id();
                $transaction->save();

                DB::commit();
            
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Transaction failed: ' . $e->getMessage());
                throw $e;
            }

            $this->sendStatusChangeNotification($requestRecord, SubRequest::STATUS_REJECTED);

        }
        $requestRecord->save();

        return response()->json(['success' => true]);
    }

    private function sendStatusChangeNotification($requestRecord, $status)
    {
        Log::info("Request data", [$requestRecord]);
        
        // Retrieve emails
        $requestUserEmail = User::where('id', $requestRecord->created_by)->pluck('email')->first();
        $checkedByEmail = $requestRecord->checked_by
            ? User::where('id', $requestRecord->checked_by)->pluck('email')->first()
            : null;
        $approvedByEmail = $requestRecord->approved_by
            ? User::where('id', $requestRecord->approved_by)->pluck('email')->first()
            : null;
    
        // CC emails
        $ccEmails = [];
        if ($checkedByEmail && $checkedByEmail !== $requestUserEmail) {
            $ccEmails[] = $checkedByEmail;
        }
        if ($approvedByEmail && $approvedByEmail !== $requestUserEmail && $approvedByEmail !== $checkedByEmail) {
            $ccEmails[] = $approvedByEmail;
        }
        Log::info("Request User Email: ", [$requestUserEmail]);
        Log::info("Checked By Email: ", [$checkedByEmail]);
        Log::info("Approved By Email: ", [$approvedByEmail]);
    
        // Email content
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
    
        // Send email
        $sendgrid = new \SendGrid(env('SENDGRID_API_KEY'));
        $emailMessage = new \SendGrid\Mail\Mail();
        $emailMessage->setFrom("info@vallibelone.com", "Vallibel One");
        $emailMessage->setSubject($emailSubject);
        $emailMessage->addTo($requestUserEmail);
    
        foreach ($ccEmails as $ccEmail) {
            $emailMessage->addCc($ccEmail);
        }
    
        $emailMessage->addContent("text/html", $emailContent);
    
        try {
            $response = $sendgrid->send($emailMessage);
            if ($response->statusCode() >= 200 && $response->statusCode() < 300) {
                Log::info("Successfully sent email: ", [$response->statusCode()]);
                return true;
            } else {
                Log::error("Failed to send email: ", [$response->statusCode(), $response->body()]);
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
        Log::info('get files' , [$requestId]);
        $files = Files::query()->where('sub_request', $requestId)->get();
        return response()->json($files);
    }

    public function approveRequest(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'requestId' => 'required|exists:sub_requests,id',
            'checkNumber' => 'nullable|string|max:255',
            'voucherNumber' => 'nullable|string|max:255',
            'depositSlip' => 'nullable|file|mimes:jpg,png,pdf|max:2048', // Ensure file is valid
        ]);

        Log::info("in");
        try {
            DB::beginTransaction();
            // Process file upload if present
            $depositSlipPath = null;
            if ($request->hasFile('depositSlip')) {
                $depositSlipPath = $request->file('depositSlip')->store('deposit_slips', 'public'); // Store in 'public/deposit_slips'
            }

            $sub_request = SubRequest::query()->where('id', $validated['requestId'])->firstOrFail();
            $entry = new ApprovedRequest();
            $entry->request_id = $sub_request->request;
            $entry->sub_request = $sub_request->id;
            $entry->check_number = $validated['checkNumber'];
            $entry->voucher_number = $validated['voucherNumber'];
            $entry->deposit_slip = $depositSlipPath;
            $entry->approved_at = now();
            $entry->approved_by = Auth::user()->id; // Assuming a logged-in user is approving the request
            $entry->save();
    
            $data = SubRequest::query()->findOrFail($validated['requestId']);
            $data->status = SubRequest::STATUS_APPROVED;
            $data->approved_by = Auth::user()->id;
            $data->approved_date = Carbon::now();
            $data->saveOrFail();
            DB::commit();

            $this->sendStatusChangeNotification($sub_request, SubRequest::STATUS_APPROVED);

    
            return response()->json(['success' => true, 'message' => 'Request approved successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error approving request: ", ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to approve request.']);
        }
    }


}
