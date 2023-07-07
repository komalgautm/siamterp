@foreach($headings as $heading){{$heading}},@endforeach
@foreach($rows as $row) 
@foreach($headings as $heading)"{{$row->$heading}}",@endforeach
@endforeach
