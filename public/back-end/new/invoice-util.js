

async function set_invoice_note(invoice_id) {

	const res = await fetch(`/invoice/note/${invoice_id}`);
	const value = await res.text()


	const sw_res = await Swal.fire({
		title: "Set invoice note",
		input: 'textarea',
		inputValue: value,
		inputLabel: 'Note',
		inputPlaceholder: 'Type note here',
		inputAttributes: {
			'aria-label': 'Type note here'
		},
		showCancelButton: true
	});

	if (sw_res.dismiss){ return; }
	save_invoice_note(invoice_id, sw_res.value);
}

async function save_invoice_note(invoice_id, note)  {
	const encoded_note = encodeURI(note);
	const csrf = $('meta[name="csrf-token"]').attr('content');
	const response = await fetch(`/invoice/note/${invoice_id}`, {
	    method: "POST", 
	    headers: {
	      "Content-Type": "application/x-www-form-urlencoded",
	      "X-CSRF-TOKEN": csrf
	    },
	    body: `note=${encoded_note}`

	  });
	  return response.text();

}



