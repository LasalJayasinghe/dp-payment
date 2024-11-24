<h2>PAYMENT REQUEST VOUCHER</h2>
<table border="1" cellpadding="5" width="100%">
    <tr>
        <td colspan="2"><strong>Name of the unit</strong></td>
        <td colspan="6">{{ $request->subcategory ?? 'Not Given' }}</td>
    </tr>
    <tr>
        <td colspan="2"><strong>Payable to</strong></td>
        <td colspan="6">{{ $request->supplier_name ?? 'Not Given' }}</td>
    </tr>
    <tr>
        <td colspan="2"><strong>Address</strong></td>
        <td colspan="6">{{ $request->supplier_address ?? 'Not Given' }}</td>
    </tr>
    <tr>
        <td colspan="2"><strong>Payment Type</strong></td>
        <td colspan="6">{{ $request->type ?? 'Not Given' }}</td>
    </tr>
    {{-- <tr>
        <td colspan="2"><strong>Payment Type</strong></td>
        <td colspan="6">{{ $request->payment_type ?? 'Not Given' }}</td>
    </tr> --}}
    <tr>
        <td colspan="2"><strong>Vendor Invoice #</strong></td>
        <td colspan="6">{{ $request->vender_invoice ?? 'Not Given' }}</td>
    </tr>
    <tr>
        <td colspan="2"><strong>Date of Invoice</strong></td>
        <td colspan="6">{{ $request->created_at ?? 'Not Given' }}</td>
    </tr>
    <tr>
        <td colspan="2"><strong>Description</strong></td>
        <td colspan="6">{{ $request->note ?? 'Not Given' }}</td>
    </tr>
</table>

{!! $checkedByHtml !!}

<!-- Approved By Section -->
{!! $approvedByHtml !!}

