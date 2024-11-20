<tr>
    <td colspan="3"><strong>Payment Approved By:</strong></td>
</tr>
<tr>
    <td><strong>Authorized Signatory Name</strong></td>
    <td><strong>Signature</strong></td>
    <td><strong>Date</strong></td>
</tr>
<tr>
    <td style="text-align: center;">{{ $approvedBy->fname }}</td>
    <td style="text-align: center;"><img src="../{{ $approvedBy->signature }}" width="100" /></td>
    <td style="text-align: center;">{{ $approvedDate }}</td>
</tr>
