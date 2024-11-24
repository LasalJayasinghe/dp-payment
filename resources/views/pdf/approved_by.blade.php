<table border="1" cellpadding="5" width="100%" style="margin-top: 20px;">
    <tr>
        <td colspan="3" style="text-align: center;"><strong>Payment Approved By:</strong></td>
    </tr>
    <tr>
        <td style="text-align: center;"><strong>Authorized Signatory Name</strong></td>
        <td style="text-align: center;"><strong>Signature</strong></td>
        <td style="text-align: center;"><strong>Date</strong></td>
    </tr>
    <tr>
        <td style="text-align: center;">{{ $approvedBy->name ?? 'Not Given' }}</td>
        <td style="text-align: center;">
            @if(isset($approvedBy->signature))
            <img src="{{ Storage::url($approvedBy->signature) }}" width="100" />
            @else
                Not Provided
            @endif
        </td>
        <td style="text-align: center;">{{ $approvedDate ?? 'Not Given' }}</td>
    </tr>
    
    <!-- Add new row for Check No -->
    <tr>
        <td style="text-align: center;"><strong>Check No</strong></td>
        <td colspan="2" style="text-align: center;">{{ $approvedData->check_number ?? 'Not Given' }}</td>
    </tr>

    <!-- Add new row for Voucher No -->
    <tr>
        <td style="text-align: center;"><strong>Voucher No</strong></td>
        <td colspan="2" style="text-align: center;">{{ $approvedData->voucher_number ?? 'Not Given' }}</td>
    </tr>

    <!-- Add new row for Deposit Slip -->
    <tr>
        <td style="text-align: center;"><strong>Deposit Slip</strong></td>
        <td colspan="2" style="text-align: center;">
            @if(isset($approvedData->deposit_slip))
            <img src="{{ Storage::url($approvedData->deposit_slip) }}" width="100" />
            @else
                Not Provided
            @endif
        </td>
    </tr>
</table>
