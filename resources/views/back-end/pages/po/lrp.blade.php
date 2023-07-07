@php
    $items = \App\ItemModel::where('type','item')->get();
@endphp
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
    <h3 style="text-align: center">LRP Report ({{date('d/m/Y')}})</h3>
    <table width="100%" border="1" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th>Thai Name</th>
                <th>English Name</th>
                <th>Newest Purchase Price</th>
                <th>Unit</th>
                <th>Purchase Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            @php $import = \App\ImportModel::select('imports.*','pos.created as created_date','unit_count.name_th','unit_count.name_en')
            ->leftJoin('pos','imports.po','=','pos.id')
            ->leftJoin('unit_count','imports.unit_count','=','unit_count.id')
            ->where('item',$item->id)->where('type','item')->orderBy('imports.id','desc')->first(); 
            @endphp
            <tr>
                <td>{{$item->name_th}}</td>
                <td>{{$item->name_en}}</td>
                <td>{{@$import->price}}</td>
                <td>@if(@$import->name_th){{@$import->name_th.'/'.@$import->name_en}}@endif</td>
                <td>@if(@$import->created_date){{date('d/m/Y',strtotime($import->created_date))}}@endif</td>
            </tr>
         @endforeach
        </tbody>
    </table>
</body>
</html>