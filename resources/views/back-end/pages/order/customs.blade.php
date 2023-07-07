@php
$detail = \App\OrderDetailModel::where('order_id',$id)->get();
$billto = \App\ClientModel::where('id',$client_id)->first();
$shipto = \App\ShipToModel::where('id',$shipto_id)->first();
$currency = \App\CurrencyModel::where('id',$currency_id)->first();
$count_item = \App\OrderDetailModel::distinct('itf_id')->count('itf_id');
$count_box = 0;

 $total_fob= floatval(preg_replace("/[^-0-9\.]/","",$total_fob));
 $total_freight= floatval(preg_replace("/[^-0-9\.]/","",$total_freight)); 
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
            <td align="center" style="font-size:30px;" class="colorblue"><b>Packing List / Invoice</b></td>
        </tr>
    </table>
    <hr class="hrclass" />


    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-bottom:4px; margin-left:5%;">
        <tr class="colorblue" style="font-size:18px;">
            <td style="width:100px;"><b>Order #</b></td>
            <td style="width:20px;"><b>:</b></td>
            <td>{{ $code }}</td>

            <td style="width:100px;"><b>TT Ref</b></td>
            <td style="width:20px;"><b>:</b></td>
            <td>{{ @$tt_ref }}</td>
        </tr>

        <tr class="colorblue" style="font-size:18px;">
            <td style="width:100px;"><b>Date</b></td>
            <td style="width:20px;"><b>:</b></td>
            <td>{{ date('d-M-Y',strtotime($created)) }}</td>

            <td style="width:100px;"><b>Ship Date</b></td>
            <td style="width:20px;"><b>:</b></td>
            <td>{{ date('d-M-Y',strtotime($ship_date)) }}</td>
        </tr>

        <tr class="colorblue" style="font-size:18px;">
            <td style="width:100px;"><b>AWB</b></td>
            <td style="width:20px;"><b>:</b></td>
            <td>{{ $awb_no }}</td>

            <td style="width:100px;"><b>Delivery By</b></td>
            <td style="width:20px;"><b>:</b></td>
            <td>{{ $freight_detail }}</td>
        </tr>
    </table>


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
                <td align="center" class="line-R bgblue" style="font-size:18px; padding:2px 2px; width:200px"> <b>Item Detail</b></td>
                <td align="center" class="line-R bgblue" style="font-size:18px; padding:2px 2px; width:50px"><b>QTY</b></td>
                <td align="center" class="line-R bgblue" style="font-size:18px; padding:2px 2px; width:30px"><b>UNIT</b></td>
                <td align="center" class="line-R bgblue" style="font-size:18px; padding:2px 2px; width:40px"><b>BOX</b></td>
                <td align="center" class="line-R bgblue" style="font-size:18px; padding:2px 2px; width:50px"><b>THB FOB</b></td>
            </tr>
        </thead>
        <!-- สิ้นสุดสัวนหัว -->
        <tbody>
            @foreach ($detail as $key => $de)
            @php
                $itf = \App\ITFModel::where('id',$de->itf_id)->first();
                $itf_detail = \App\ITFdetailModel::where(['itf'=>$itf->id,'type'=>'ean'])->sum('ean_qty');
                $unit = \App\UnitCountModel::where('id',$de->unitcount_id)->first();
                $count_box += $de->number_box;

                  
            @endphp
                <tr style="font-size:16px; line-height: 0.1;">
                    <td align="center">{{ $key+1 }}</td>
                    <td align="left">{{ $itf->name }}</td>
                    <td align="center">{{ $de->nw }}</td>
                    <td align="center">KG</td>
                    <td align="right">{{ $de->number_box }} Box</td>
                    <td align="right">THB {{ number_format($de->fob,2) }}</td>
                </tr>
            @endforeach


        </tbody>
    </table>
    <table width="100%">
        <tfoot>
            <tr style="line-height: 0.4;">
                <td align="left" class="bgwhite" style="font-size:18px; padding:2px 2px;">Total</td>
                <td align="left" class="bgwhite" style="font-size:18px; padding:2px 2px;">:</td>
                <td align="left" class="bgwhite" style="font-size:18px; padding:2px 2px;">{{ number_format($count_box,0) }} Boxes / {{ number_format($key+1,0) }} Item</td>
                <td align="left" class="bgwhite" style="font-size:18px; padding:2px 2px;">Total Packages</td>
                <td align="left" class="bgwhite" style="font-size:18px; padding:2px 2px;">:</td>
                <td align="right" class="bgwhite" style="font-size:18px; padding:2px 2px;">{{ number_format($total_package,0) }}</td>
                <td colspan="2" align="center" class="line-R bgblue" style="font-size:18px; padding:2px 2px;"><b>TOTAL THB</b></td>
            </tr>
            <tr style="line-height: 0.4;">
                <td align="left" class="bgwhite" style="font-size:18px; padding:2px 2px;">Total Net Weight</td>
                <td align="left" class="bgwhite" style="font-size:18px; padding:2px 2px;">:</td>
                <td align="left" class="bgwhite" style="font-size:18px; padding:2px 2px;">{{ number_format($total_nw,2) }} Kg</td>
                <td align="left" class="bgwhite" style="font-size:18px; padding:2px 2px;">THB FOB</td>
                <td align="left" class="bgwhite" style="font-size:18px; padding:2px 2px;">:</td>
                <td align="right" class="bgwhite" style="font-size:18px; padding:2px 2px;">THB {{ number_format($total_fob,2) }}</td>
                <td colspan="2" align="right" class="line-R bgwhite" style="font-size:18px; padding:2px 2px;"><b>{{ number_format(((float)$total_fob)+((float)$total_freight),2) }}</b></td>
            </tr>
            <tr style="line-height: 0.4;">
                <td align="left" class="bgwhite" style="font-size:18px; padding:2px 2px;">Total Gross Weight</td>
                <td align="left" class="bgwhite" style="font-size:18px; padding:2px 2px;">:</td>
                <td align="left" class="bgwhite" style="font-size:18px; padding:2px 2px;">{{ number_format($total_gw,2) }} Kg</td>
                <td align="left" class="bgwhite" style="font-size:18px; padding:2px 2px;">Air Freight</td>
                <td align="left" class="bgwhite" style="font-size:18px; padding:2px 2px;">:</td>
                <td align="right" class="bgwhite" style="font-size:18px; padding:2px 2px;">THB {{ number_format((float)$total_freight,2) }}</td>
                @if($currency->id != 1)
                <td colspan="2" align="center" class="line-R bgblue" style="font-size:18px; padding:2px 2px;"><b>TOTAL {{ $currency->currency }}</b></td>
                @endif
            </tr>
            <tr style="line-height: 0.4;">
                <td align="left" class="bgwhite" style="font-size:18px; padding:2px 2px;">Total CBM</td>
                <td align="left" class="bgwhite" style="font-size:18px; padding:2px 2px;">:</td>
                <td align="left" class="bgwhite" style="font-size:18px; padding:2px 2px;">{{ number_format($total_cbm,4) }}</td>
                <td align="left" class="bgwhite" style="font-size:18px; padding:2px 2px;">Exchange Rate</td>
                <td align="left" class="bgwhite" style="font-size:18px; padding:2px 2px;">:</td>
                <td align="right" class="bgwhite" style="font-size:18px; padding:2px 2px;">THB {{ number_format($ex_rate,4) }}</td>
                @if($currency->id != 1)
                <td colspan="2" align="right" class="line-R bgwhite" style="font-size:18px; padding:2px 2px;"><b>{{ number_format(((float)$total_fob+(float)$total_freight)/$ex_rate,2) }}</b></td>
                @endif
            </tr>
        </tfoot>
    </table>


</body>

</html>