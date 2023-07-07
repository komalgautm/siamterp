@php

$sql = DB::table('claims')->where('id',$claim_id)->first();
$details = DB::table('claim_detail')->where('claim_id',$sql->id)->get();

$billto = \App\ClientModel::where('id',$client_id)->first();
$shipto = \App\ShipToModel::where('id',$shipto_id)->first();
$currency = \App\CurrencyModel::where('id',$currency_id)->first();
$count_item = \App\InvoiceDetailModel::distinct('itf_id')->count('itf_id');
$count_box = 0;
@endphp
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $sql->claim_code }}</title>
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

        .lineH {
            line-height: 0.2;
        }

        .lineH2 {
            line-height: 1;
            padding-top: 30px
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
            background-color: #002060;
            padding: 2px 2px;
        }

        .hrclass1 {
            background-color: #002060;
        }
        tfoot td {
            border: none!important;
        }
    </style>
</head>


<body>

    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-bottom:4px; margin-left:1%;">
        <tr>
            <td valign="top" style="width:100px;"><img src="upload/logo.jpg" alt="" style="width:100px; height:60px;"></td>
            <td align="left" valign="top" style="padding-left:5px; width:500px;">
                <b class="fontsize20 colorblue" style="font-size:30px;">Siam Eats Co.,Ltd</b> <br />
                <span class="fontsize16 colorblue">16/18 Mu 11 Khlong Nueng, Khlong Luang Pathumthani 12120</span> <br />
                <span class="fontsize16 colorblue">Tax ID 395561000010</span>
            </td>
        </tr>
    </table>

    <hr class="hrclass" />
    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-bottom:4px; margin-left:5%;">
        <tr>
            <td align="center" style="font-size:30px;" class="colorblue"><b>Credit Note</b></td>
        </tr>
    </table>
    <hr class="hrclass" />


    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-bottom:4px; margin-left:5%;">
        <tr class="colorblue" style="font-size:18px;">
            <td style="width:100px;"><b>Credit Note#</b></td>
            <td style="width:20px;"><b>:</b></td>
            <td>{{ $sql->claim_code }}</td>

             <td style="width:100px;"><b>TT Ref.</b></td>
            <td style="width:20px;"><b>:</b></td>
            <td>{{ @$tt_ref }}</td>

          
        </tr>

        <tr class="colorblue" style="font-size:18px;">
             <td style="width:100px;"><b>@if($sql->status==1) Approval @elseif($sql->status==2) Rejection @endif Date</b></td>
            <td style="width:20px;"><b>:</b></td>
            <td>@if($sql->status==1||$sql->status==2){{ date('d-M-Y',strtotime($sql->updated_at)) }} @endif</td>

            <td style="width:100px;"><b>Invoice No.</b></td>
            <td style="width:20px;"><b>:</b></td>
            <td>{{ $code }}</td>
        </tr>

        <tr class="colorblue" style="font-size:18px;">
            <td colspan="3"></td>
            <td style="width:100px;"><b>AWB</b></td>
            <td style="width:20px;"><b>:</b></td>
            <td>{{ $awb_no }}</td>

            
        </tr>
    </table>

<hr class="hrclass1" />
    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-bottom:4px; margin-left:1%;">
        <tr class="colorblue" style="font-size:18px;">
            <td style="width:100px;">Bill To</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>

            <td style="width:100px;">Ship To</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
    </table>
    <hr class="hrclass1" />

    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-bottom:4px; margin-left:1%;">
        <tr class="colorblue" style="font-size:18px;">
            <td width="50%">
                <span>{{ @$billto->name }}&nbsp;</span><br>
                <span>
                    {!! @$billto->address !!}&nbsp;
                </span><br>
                <span>{{ @$billto->tax_number }}&nbsp;</span>
            </td>
            <td width="50%">
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
              
                
                <td align="center" class="line-R bgblue" style="font-size:18px; padding:2px 2px; width:250px;"><b>Item Detail</b></td>
                <td align="center" class="line-R bgblue" style="font-size:18px; padding:2px 2px; width:30px"><b>QTY</b></td>
                <td align="center" class="line-R bgblue" style="font-size:18px; padding:2px 2px; width:30px"><b>Unit</b></td>
                <td align="center" class="line-R bgblue" style="font-size:18px; padding:2px 2px; width:100px"><b>Unit  price<br/>{{ $currency->currency }}</b></td>
                <td align="center" class="line-R bgblue" style="font-size:18px; padding:2px 2px; width:100px"><b>Line  Total {{ $currency->currency }}</b></td>
            </tr>
        </thead>
        <!-- สิ้นสุดสัวนหัว -->
        <tbody>
            @foreach ($details as $key => $de)
            @php
                $itf = \App\ITFModel::where('id',$de->itf_id)->first();
                $unit = \App\UnitCountModel::where('id',$de->claim_unit)->first();
                $count_box =0;
            @endphp
                <tr class="list" style="font-size:18px;">
                    <td align="center">{{ $key+1 }}</td>
                
                  
                    <td align="left">{{ $itf->name }}</td>
                    <td align="center">{{ $de->claim_qty }}</td>
                    <td align="center">{{ $unit->name_en }}</td>
                    <td align="right">{{ $de->unit_price }}</td>
                    <td align="right">{{ floatval($de->line_total) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <table width="100%">
        <tfoot>
           
            <tr>
                <td  colspan="6" width="80%">&nbsp;</td>
            
                
                <td colspan="1" align="center" class="line-R bgblue" style="font-size:18px; padding:2px 2px;"><b>TOTAL {{ $currency->currency }}</b></td>
            </tr>
            <tr>
                  <td  colspan="6" align="left" class="bgwhite" style="font-size:18px; padding:2px 2px; ">&nbsp;</td>
              
                <td colspan="1" align="center" class="line-R bgwhite" style="font-size:18px; padding:2px 2px;"><b>{{number_format($sql->total_price,2)}}</b></td>
            </tr>
            <tr>
                  <td  colspan="6" align="left" class="bgwhite" style="font-size:18px; padding:2px 2px; ">&nbsp;</td>
              
                <td colspan="1" align="center" class="line-R bgwhite" style="font-size:18px; padding:2px 2px;"><b>{{number_format($ex_rate,2)}} THB/{{ $currency->currency }}</b></td>
            </tr>
           
        </tfoot>
    </table>
@if($sql->status==2)
<p>
<b>Rejection Remarks:</b> No Isssue
</p>
@endif
</body>

</html>