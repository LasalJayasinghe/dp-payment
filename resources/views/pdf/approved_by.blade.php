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
        <td style="text-align: center;">{{ $approvedBy->fname ?? 'Not Given' }}</td>
        <td style="text-align: center;">
            @if(isset($approvedBy->signature))
                <img src="{{ public_path($approvedBy->signature) }}" width="100" />
            @else
                Not Provided
            @endif
        </td>
        <td style="text-align: center;">{{ $approvedDate ?? 'Not Given' }}</td>
    </tr>
</table>
