@php
$detail = \App\QuotationDetailModel::where('quotation_id',$id)->get();
$billto = \App\ClientModel::where('id',$client_id)->first();
$shipto = \App\ShipToModel::where('id',$shipto_id)->first();
$currency = \App\CurrencyModel::where('id',$currency_id)->first();
$airport = \App\AirportModel::where('id',$airport_id)->first();
$count_item = \App\QuotationDetailModel::distinct('itf_id')->count('itf_id');
$count_box = 0;
@endphp
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $code }}</title>
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
        
        .line-T {
            border-top: solid #000 1px;
        }

        .line-B {
            border-bottom: solid #000 1px;
        }

        .line-L {
            border-left: solid #000 1px;
        }

        .line-R {
            border-right: solid #000 1px;
        }

        .line-B2 {
            border-bottom: #000 1px dotted;
        }

        .text-R {
            text-align: right;
        }

        body {
            margin: 0 auto;
            padding-top: 12px;
            color: #001028;
            background: #FFFFFF;
            font-size: 18px;
            font-family: 'THSarabunNew';
        }

        tr {
            line-height: 0.6;
        }

        td {
            vertical-align: middle;
        }

        tr.list {
            line-height: 0.2;
        }

        .fontsize20 {
            font-size: 20px;
        }

        .fontsize16 {
            font-size: 16px;
        }

        .colorblue {
            color: #003366;
        }

        .bgblue {
            background-color: #222b35;
            color: white;
        }

        .bgwhite {
            background-color: white;
            color: black;
        }

        /* Customer */
        #customers {
            /* font-family: "Trebuchet MS", Arial, Helvetica, sans-serif; */
            border-collapse: collapse;
            width: 100%;
        }

        #customers td,
        #customers th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        #customers tr:nth-child(even) {
            background-color: #d9e1f2;
        }

        #customers tr:hover {
            background-color: #ddd;
        }

        #customers th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #4CAF50;
            color: white;
        }

        .hrclass {
            background-color: #222b35;
            padding: 2px 2px;
        }

        .hrclass1 {
            background-color: #222b35;
        }
        tfoot td {
            border: none!important;
        }
    </style>
</head>


<body>

    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-bottom:5px;">
        <tr>
            <td valign="top" style="width:150px;"><img src="upload/logo.jpg" alt="" style="width:125px;"></td>
            <td align="left" valign="top" style="padding-left:10px; padding-right:10px; width:150px; line-height:0.8;">
                <b class="fontsize20 colorblue">Siam Eats Co.,Ltd</b> <br />
                <span class="fontsize16 colorblue">16/18 Mu 11</span> <br />
                <span class="fontsize16 colorblue">Khlong Nueng, Khlong Luang</span> <br />
                <span class="fontsize16 colorblue"Pathumthani 12120, Thailand</span> <br />
                <span class="fontsize16 colorblue">Tax ID 395561000010</span> <br />
            </td>
            <td align="left">
                <table width="100%">
                    <tbody style="font-size:16px;">
                        <tr>
                            <td align="center" colspan="6" class="bgblue" style="padding: 5px 5px; font-size: 20px;"><b>Quotation</b></td>
                        </tr>
                        <tr>
                            <td>Quotation</td>
                            <td>:</td>
                            <td>{{ $code }}</td>
                            <td>Ship Before</td>
                            <td>:</td>
                            <td>{{ date('d-M-Y',strtotime($ship_date)) }}</td>
                        </tr>
                        <tr>
                            <td>Date</td>
                            <td>:</td>
                            <td>{{ date('d-M-Y',strtotime($created)) }}</td>
                            <td>Net Weight</td>
                            <td>:</td>
                            <td>{{ $total_nw }}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>Airport</td>
                            <td>:</td>
                            <td>{{ $airport->airport_code }}</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>

    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-bottom:5px;">
        <tr>
            <td >
                <table width="100%">
                    <tbody style="font-size:16px;">
                        <tr>
                            <td align="center" colspan="5" class="bgblue" style="padding: 2px 2px; font-size: 18px;"><b>Bill To</b></td>
                        </tr>
                    </tbody>
                </table>
            </td>
            <td >
                <table width="100%">
                    <tbody style="font-size:16px;">
                        <tr>
                            <td align="center" colspan="5" class="bgblue" style="padding: 2px 2px; font-size: 18px;"><b>Ship To</b></td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr class="colorblue" style="font-size:16px;">
            <td width="100%">
                <span>{{ @$billto->name }}&nbsp;</span><br>
                <span>
                    {!! @$billto->address !!}&nbsp;
                </span><br>
                <span>{{ @$billto->tax_number }}&nbsp;</span>
            </td>
            <td width="100%">
                <span>{{ @$shipto->name }}&nbsp;</span><br>
                <span>
                    {!! @$shipto->address !!}&nbsp;
                </span><br>
                <span>{{ @$shipto->tax_number }}&nbsp;</span>
            </td>
        </tr>
    </table>

    <table id="customers" width="100%" border="0" cellpadding="0" cellspacing="0" style="">
        <!-- ส่วนหัว -->
        <thead>
            <tr>
                <td align="center" class="line-R bgblue" style="font-size:18px; padding:2px 2px; width:30px"><b>#</b></td>
                <td align="center" class="line-R bgblue" style="font-size:18px; padding:2px 2px; width:50px"> <b>N.W.<br/>(KG)</b></td>
                <td align="center" class="line-R bgblue" style="font-size:18px; padding:2px 2px; width:50px"><b>Box</b></td>
                <td align="center" class="line-R bgblue" style="font-size:18px; padding:2px 2px; width:250px;"><b>Item Detail</b></td>
                <td align="center" class="line-R bgblue" style="font-size:18px; padding:2px 2px; width:30px"><b>QTY</b></td>
                <td align="center" class="line-R bgblue" style="font-size:18px; padding:2px 2px; width:30px"><b>UNIT</b></td>
                <td align="center" class="line-R bgblue" style="font-size:18px; padding:2px 2px; width:100px"><b>UNIT PRICE<br/>{{ $currency->currency }}</b></td>
            </tr>
        </thead>
        <!-- สิ้นสุดสัวนหัว -->
        <tbody>
            @foreach ($detail as $key => $de)
            @php
                $itf = \App\ITFModel::where('id',$de->itf_id)->first();
                $unit = \App\UnitCountModel::where('id',$de->unitcount_id)->first();
                $count_box += $de->number_box;
            @endphp
                <tr class="list" style="font-size:18px;">
                    <td align="center">{{ $key+1 }}</td>
                    <td align="center">{{ $de->nw }}</td>
                    <td align="center">{{ $de->number_box }}</td>
                    <td align="left">{{ $itf->name }}</td>
                    <td align="center">{{ $de->qty }}</td>
                    <td align="center">{{ $unit->name_en }}</td>
                    <td align="right">{{ $de->unit_price }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>


</body>

</html>
