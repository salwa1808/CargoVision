<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">

    <title>Global Supply Chain Risk Report</title>

    <style>

        body{

            font-family: DejaVu Sans;

            font-size:12px;

        }

        h2{

            text-align:center;

        }

        table{

            width:100%;

            border-collapse:collapse;

            margin-top:20px;

        }

        th,td{

            border:1px solid #000;

            padding:8px;

            text-align:left;

        }

        th{

            background:#eeeeee;

        }

    </style>

</head>

<body>

<h2>

GLOBAL SUPPLY CHAIN RISK REPORT

</h2>

<p>

Tanggal :
{{ date('d-m-Y') }}

</p>

<table>

<thead>

<tr>

<th>No</th>

<th>Country</th>

<th>Region</th>

<th>Total Score</th>

<th>Risk Level</th>

</tr>

</thead>

<tbody>

@foreach($riskScores as $index=>$risk)

<tr>

<td>{{ $index+1 }}</td>

<td>{{ $risk->country->name }}</td>

<td>{{ $risk->country->region }}</td>

<td>{{ $risk->total_score }}</td>

<td>{{ $risk->risk_level }}</td>

</tr>

@endforeach

</tbody>

</table>

</body>

</html>