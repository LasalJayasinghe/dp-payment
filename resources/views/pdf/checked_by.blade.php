<table border="1" cellpadding="5" width="100%" style="margin-top: 20px;">
    <tr>
        <td colspan="3" style="text-align: center;"><strong>Payment Checked By:</strong></td>
    </tr>
    <tr>
        <td style="text-align: center;"><strong>Authorized Signatory Name</strong></td>
        <td style="text-align: center;"><strong>Signature</strong></td>
        <td style="text-align: center;"><strong>Date</strong></td>
    </tr>
    <tr>
        <td style="text-align: center;">{{ $checkedBy->name ?? 'Not Given' }}</td>
        <td style="text-align: center;">
            @if(isset($checkedBy->signature))
                <img  src="{{ Storage::url($checkedBy->signature) }}"width="100" />
            @else
                Not Provided
            @endif
        </td>
        <td style="text-align: center;">{{ $checkedDate ?? 'Not Given' }}</td>
    </tr>
</table>
