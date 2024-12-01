<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Status Update Notification</title>
</head>
<body>
<p>The request with ID {{$requestRecord->id}} has been {{$status}}.</p>
<p>Details:</p>
<ul>
    <li>Request ID: {{$requestRecord->id}}</li>
    <li>Status: {{$status}}</li>
    <li>Checked By: " . {{$checkedByEmail ?? 'N/A'}} . "</li>
    <li>Approved By: " . {{$approvedByEmail ?? 'N/A'}} . "</li>
</ul>
</body>
</html>
