<?php

namespace App\Jobs;

use App\Mail\StatusNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendStatusNotification implements ShouldQueue
{
    use Queueable;

    protected $requestRecord;
    protected $status;
    /**
     * Create a new job instance.
     */
    public function __construct($requestRecord, $status)
    {
        $this->requestRecord = $requestRecord;
        $this->status = $status;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $requestUserEmail = $this->requestRecord->checkedRef?->email ?? null;
        $checkedByEmail = $this->requestRecord->checkedRef?->email ?? null;
        $approvedByEmail = $this->requestRecord->approvedRef?->email ?? null;

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

        $emailContent = new StatusNotification($this->requestRecord, $this->status, $checkedByEmail, $approvedByEmail);
        try {
            Mail::to($requestUserEmail)->cc($ccEmails)->send($emailContent);
        } catch (\Exception $e) {
            Log::error('Caught exception: ' . $e->getMessage());
        }
    }
}
