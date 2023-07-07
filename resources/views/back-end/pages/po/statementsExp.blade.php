<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>LRP Report</title>
    <style>
        @page {
            /* size: 25cm 30cm portrait; */
            margin: 20px 20px;
        }
        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: normal;
            src: url("fonts/THSarabunNew.ttf") format('TrueType');
        }

        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: bold;
            src: url("fonts/THSarabunNew Bold.ttf") format('TrueType');
        }

        @font-face {
            font-family: 'THSarabunNew';
            font-style: italic;
            font-weight: normal;
            src: url("fonts/THSarabunNew Italic.ttf") format('TrueType');
        }

        @font-face {
            font-family: 'THSarabunNew';
            font-style: italic;
            font-weight: bold;
            src: url("fonts/THSarabunNew BoldItalic.ttf") format('TrueType');
        }
    </style>
</head>
<body>
    <!--<h3 style="text-align: center">}LRP Report ({{date('d/m/Y')}})</h3>-->
    <h3 style="text-align: center">{{$vendor_name->name}}  ({{date('d/m/Y')}})</h3>
	<h5 style="text-align: center">Start Date: {{$from_date}}</h5>
	<h5 style="text-align: center">End Date: {{$to_date}}</h5>
	<h5 style="text-align: center">Starting Balance: {{$stating_balance->amount}}</h5>
    <table width="100%" border="1" cellpadding="0" cellspacing="0">
        <thead>
            <tr>

                <th>Date</th>
                <th>Transaction</th>
                <th>Number</th>
                <th>Ammount</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
			<?php
			$total = $stating_balance->amount;
			?>
            @foreach($items as $item)
			<?php
			if($item->txn_mode=='dr')
			{
				$total = $total-$item->amount;
			}
			else
			{
				$total = $total+$item->amount;
			}
			
			?>
           
            <tr>
				<?php
					$date = $item->txn_date;

					$createDate = new DateTime($date);

					$strip = $createDate->format('d-m-Y');
				?>
                <!--<td>{{$item->txn_date}}</td>-->
                <td>{{$strip}}</td>
                <td>{{$item->transaction}}</td>
                <td>{{$item->numbers}}</td>
				<td>@if($item->txn_mode=='dr') - @endif {{number_format($item->amount,2)}}</td>
                <!--<td>{{$item->amount}}</td>-->
                <td>{{$item->notes}}</td>
              
               
            </tr>
         @endforeach
            <tr >
            <td colspan="2"></td>
            <td > Total Amount:</td>
            <!--<td > <b>{{$items[count($items)-1]->total}}THB</b></td>-->
            <td> <b>{{number_format($total)}}  THB</b></td>
            <td> </td>
        </tr>
        </tbody>
    </table>
</body>
</html>